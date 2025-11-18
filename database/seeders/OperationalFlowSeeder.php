<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;
use App\Models\Brand;
use App\Models\MaterialType;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeComponent;
use App\Models\ProductionOrder;
use App\Models\ProductionMaterialConsumption;
use App\Models\ProductionOutput;
use App\Models\FinishedGoodsInventory;
use App\Models\Dispatch;
use App\Models\DispatchItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;

class OperationalFlowSeeder extends Seeder
{
    private $mainBranch;
    private $branchA;
    private $branchB;
    private $manager;
    private $supervisor;
    private $kg;
    private $piece;
    private $gram;
    private $liter;

    public function run(): void
    {
        // Load references
        $this->mainBranch = Branch::where('type', 'main')->first();
        $this->branchA = Branch::where('type', 'sub')->where('name', 'LIKE', '%Piassa%')->first();
        $this->branchB = Branch::where('type', 'sub')->where('name', 'LIKE', '%Bole%')->first();

        $this->manager = User::where('role', 'manager')->first();
        $this->supervisor = User::where('role', 'supervisor')->first();

        $this->kg = Unit::where('unit_abbreviation', 'kg')->first();
        $this->piece = Unit::where('unit_abbreviation', 'pcs')->first();
        $this->gram = Unit::where('unit_abbreviation', 'g')->first();
        $this->liter = Unit::where('unit_abbreviation', 'L')->first();

        // Create inventory and recipes first
        $this->createInventoryItems();
        $whiteBreadRecipe = $this->createWhiteBreadRecipe();
        $brownBreadRecipe = $this->createBrownBreadRecipe();

        // Purchase raw materials (3 days ago)
        $this->purchaseRawMaterials(Carbon::now()->subDays(3));

        // Operational Flow
        // Day 1: 2 days ago (Nov 16)
        $day1 = Carbon::now()->subDays(2);
        $this->runDailyOperation($day1, $whiteBreadRecipe, 500, 250, 150);

        // Day 2: Yesterday (Nov 17)
        $day2 = Carbon::now()->subDay();
        $this->runDailyOperation($day2, $whiteBreadRecipe, 600, 300, 200);
        $this->runDailyOperation($day2, $brownBreadRecipe, 400, 200, 150);

        // Day 3: Today (Nov 18) - morning production only, no sales yet
        $day3 = Carbon::now();
        $this->createProductionOrder($day3, $whiteBreadRecipe, 700);
        $this->createProductionOrder($day3, $brownBreadRecipe, 500);
    }

    private function createInventoryItems()
    {
        $allPurposeFlour = MaterialType::where('type_code', 'FLOUR_ALLPURPOSE')->first();
        $wholeWheatFlour = MaterialType::where('type_code', 'FLOUR_WHOLEWHEAT')->first();
        $yeast = MaterialType::where('type_code', 'YEAST_BAKERS')->first();
        $salt = MaterialType::where('type_code', 'SALT_TABLE')->first();
        $sugar = MaterialType::where('type_code', 'SUGAR_WHITE')->first();
        $oil = MaterialType::where('type_code', 'OIL_VEGETABLE')->first();

        $mama = Brand::where('brand_name', 'Mama')->first();
        $momona = Brand::where('brand_name', 'Momona')->first();
        $local = Brand::where('brand_name', 'Local')->first();

        // Create or update inventory items with initial stock
        InventoryItem::updateOrCreate(
            ['sku_code' => 'INV-FLOUR-MAMA-AP'],
            [
                'material_type_id' => $allPurposeFlour->id,
                'brand_id' => $mama->id,
                'unit_of_measure_id' => $this->kg->id,
                'current_stock_quantity' => 500,
                'reorder_level' => 100,
            ]
        );

        InventoryItem::updateOrCreate(
            ['sku_code' => 'INV-FLOUR-MAMA-WW'],
            [
                'material_type_id' => $wholeWheatFlour->id,
                'brand_id' => $mama->id,
                'unit_of_measure_id' => $this->kg->id,
                'current_stock_quantity' => 300,
                'reorder_level' => 50,
            ]
        );

        InventoryItem::updateOrCreate(
            ['sku_code' => 'INV-YEAST-MOMONA'],
            [
                'material_type_id' => $yeast->id,
                'brand_id' => $momona->id,
                'unit_of_measure_id' => $this->kg->id,
                'current_stock_quantity' => 50,
                'reorder_level' => 10,
            ]
        );

        InventoryItem::updateOrCreate(
            ['sku_code' => 'INV-SALT-LOCAL'],
            [
                'material_type_id' => $salt->id,
                'brand_id' => $local->id,
                'unit_of_measure_id' => $this->kg->id,
                'current_stock_quantity' => 100,
                'reorder_level' => 20,
            ]
        );

        InventoryItem::updateOrCreate(
            ['sku_code' => 'INV-SUGAR-LOCAL'],
            [
                'material_type_id' => $sugar->id,
                'brand_id' => $local->id,
                'unit_of_measure_id' => $this->kg->id,
                'current_stock_quantity' => 80,
                'reorder_level' => 20,
            ]
        );

        InventoryItem::updateOrCreate(
            ['sku_code' => 'INV-OIL-LOCAL'],
            [
                'material_type_id' => $oil->id,
                'brand_id' => $local->id,
                'unit_of_measure_id' => $this->liter->id,
                'current_stock_quantity' => 40,
                'reorder_level' => 10,
            ]
        );
    }

    private function createWhiteBreadRecipe()
    {
        $whiteBread = Product::where('product_name', 'White Bread')->first();

        $recipe = Recipe::create([
            'recipe_code' => 'RCP-WHT-BREAD-001',
            'product_id' => $whiteBread->id,
            'recipe_name' => 'Standard White Bread',
            'version_number' => '1.0',
            'expected_yield_quantity' => 100,
            'yield_unit_id' => $this->piece->id,
            'is_active' => true,
            'effective_date' => Carbon::now()->subMonths(6),
            'created_by_user_id' => $this->manager->id,
        ]);

        $flour = InventoryItem::where('sku_code', 'INV-FLOUR-MAMA-AP')->first();
        $yeast = InventoryItem::where('sku_code', 'INV-YEAST-MOMONA')->first();
        $salt = InventoryItem::where('sku_code', 'INV-SALT-LOCAL')->first();
        $sugar = InventoryItem::where('sku_code', 'INV-SUGAR-LOCAL')->first();
        $oil = InventoryItem::where('sku_code', 'INV-OIL-LOCAL')->first();

        // Recipe components for 100 pieces
        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $flour->id,
            'quantity_required' => 25, // 25kg flour for 100 pieces
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $yeast->id,
            'quantity_required' => 0.5, // 500g yeast
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $salt->id,
            'quantity_required' => 0.4, // 400g salt
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $sugar->id,
            'quantity_required' => 1.5, // 1.5kg sugar
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $oil->id,
            'quantity_required' => 1.0, // 1L oil
            'unit_id' => $this->liter->id,
        ]);

        return $recipe;
    }

    private function createBrownBreadRecipe()
    {
        $brownBread = Product::where('product_name', 'Brown Bread')->first();

        $recipe = Recipe::create([
            'recipe_code' => 'RCP-BRN-BREAD-001',
            'product_id' => $brownBread->id,
            'recipe_name' => 'Whole Wheat Brown Bread',
            'version_number' => '1.0',
            'expected_yield_quantity' => 100,
            'yield_unit_id' => $this->piece->id,
            'is_active' => true,
            'effective_date' => Carbon::now()->subMonths(6),
            'created_by_user_id' => $this->manager->id,
        ]);

        $wholeWheatFlour = InventoryItem::where('sku_code', 'INV-FLOUR-MAMA-WW')->first();
        $yeast = InventoryItem::where('sku_code', 'INV-YEAST-MOMONA')->first();
        $salt = InventoryItem::where('sku_code', 'INV-SALT-LOCAL')->first();
        $sugar = InventoryItem::where('sku_code', 'INV-SUGAR-LOCAL')->first();
        $oil = InventoryItem::where('sku_code', 'INV-OIL-LOCAL')->first();

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $wholeWheatFlour->id,
            'quantity_required' => 28, // 28kg whole wheat flour
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $yeast->id,
            'quantity_required' => 0.6,
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $salt->id,
            'quantity_required' => 0.5,
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $sugar->id,
            'quantity_required' => 2.0,
            'unit_id' => $this->kg->id,
        ]);

        RecipeComponent::create([
            'recipe_id' => $recipe->id,
            'inventory_item_id' => $oil->id,
            'quantity_required' => 1.2,
            'unit_id' => $this->liter->id,
        ]);

        return $recipe;
    }

    private function purchaseRawMaterials($date)
    {
        $po = PurchaseOrder::create([
            'po_number' => 'PO-' . $date->format('Ymd') . '-001',
            'supplier_name' => 'Mama Flour Distributors',
            'order_date' => $date,
            'expected_delivery_date' => $date,
            'status' => 'received',
            'created_by_user_id' => $this->manager->id,
            'approved_by_user_id' => $this->manager->id,
            'received_by_user_id' => $this->supervisor->id,
            'received_at' => $date->copy()->addHours(2),
        ]);

        $flour = InventoryItem::where('sku_code', 'INV-FLOUR-MAMA-AP')->first();
        $wholeWheat = InventoryItem::where('sku_code', 'INV-FLOUR-MAMA-WW')->first();
        $yeast = InventoryItem::where('sku_code', 'INV-YEAST-MOMONA')->first();

        // Purchase 500kg all-purpose flour
        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'inventory_item_id' => $flour->id,
            'quantity_ordered' => 500,
            'unit_id' => $this->kg->id,
            'unit_price' => 45.00,
            'total_price' => 22500.00,
        ]);

        // Purchase 300kg whole wheat flour
        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'inventory_item_id' => $wholeWheat->id,
            'quantity_ordered' => 300,
            'unit_id' => $this->kg->id,
            'unit_price' => 52.00,
            'total_price' => 15600.00,
        ]);

        // Purchase 50kg yeast
        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'inventory_item_id' => $yeast->id,
            'quantity_ordered' => 50,
            'unit_id' => $this->kg->id,
            'unit_price' => 380.00,
            'total_price' => 19000.00,
        ]);

        // Create inventory transactions for receipt
        InventoryTransaction::create([
            'transaction_type' => 'purchase',
            'inventory_item_id' => $flour->id,
            'quantity' => 500,
            'unit_id' => $this->kg->id,
            'unit_cost' => 45.00,
            'transaction_date' => $date->copy()->addHours(2),
            'reference_type' => 'PurchaseOrder',
            'reference_id' => $po->id,
            'branch_id' => $this->mainBranch->id,
            'created_by_user_id' => $this->supervisor->id,
        ]);

        InventoryTransaction::create([
            'transaction_type' => 'purchase',
            'inventory_item_id' => $wholeWheat->id,
            'quantity' => 300,
            'unit_id' => $this->kg->id,
            'unit_cost' => 52.00,
            'transaction_date' => $date->copy()->addHours(2),
            'reference_type' => 'PurchaseOrder',
            'reference_id' => $po->id,
            'branch_id' => $this->mainBranch->id,
            'created_by_user_id' => $this->supervisor->id,
        ]);

        InventoryTransaction::create([
            'transaction_type' => 'purchase',
            'inventory_item_id' => $yeast->id,
            'quantity' => 50,
            'unit_id' => $this->kg->id,
            'unit_cost' => 380.00,
            'transaction_date' => $date->copy()->addHours(2),
            'reference_type' => 'PurchaseOrder',
            'reference_id' => $po->id,
            'branch_id' => $this->mainBranch->id,
            'created_by_user_id' => $this->supervisor->id,
        ]);
    }

    private function runDailyOperation($date, $recipe, $plannedQty, $toBranchA, $toBranchB)
    {
        // Create and complete production order
        $productionOrder = $this->createCompletedProduction($date, $recipe, $plannedQty);

        // Dispatch to branches
        $this->dispatchToBranches($date, $recipe->product, $productionOrder, $toBranchA, $toBranchB);

        // Record sales at all branches
        $mainSold = $plannedQty - $toBranchA - $toBranchB - rand(20, 40); // Some leftovers
        $this->recordSales($date, $this->mainBranch, $recipe->product, $mainSold);
        $this->recordSales($date, $this->branchA, $recipe->product, $toBranchA - rand(10, 20));
        $this->recordSales($date, $this->branchB, $recipe->product, $toBranchB - rand(10, 20));
    }

    private function createProductionOrder($date, $recipe, $plannedQty)
    {
        $orderNumber = 'WO-' . $date->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        return ProductionOrder::create([
            'work_order_number' => $orderNumber,
            'recipe_id' => $recipe->id,
            'production_date' => $date,
            'shift' => 'morning',
            'planned_quantity' => $plannedQty,
            'scaling_factor' => $plannedQty / $recipe->expected_yield_quantity,
            'status' => 'planned',
            'created_by_user_id' => $this->manager->id,
        ]);
    }

    private function createCompletedProduction($date, $recipe, $plannedQty)
    {
        $productionOrder = $this->createProductionOrder($date, $recipe, $plannedQty);

        // Start production
        $productionOrder->update([
            'status' => 'in_progress',
            'started_by_user_id' => $this->supervisor->id,
            'started_at' => $date->copy()->setTime(6, 0),
        ]);

        // Record material consumption
        $scalingFactor = $plannedQty / $recipe->expected_yield_quantity;
        foreach ($recipe->recipeComponents as $component) {
            $plannedConsumption = $component->quantity_required * $scalingFactor;
            $actualConsumption = $plannedConsumption * (1 + (rand(-5, 5) / 100)); // ±5% variance

            ProductionMaterialConsumption::create([
                'production_order_id' => $productionOrder->id,
                'recipe_component_id' => $component->id,
                'inventory_item_id' => $component->inventory_item_id,
                'planned_quantity' => $plannedConsumption,
                'actual_quantity_used' => $actualConsumption,
                'variance_quantity' => $actualConsumption - $plannedConsumption,
                'variance_reason' => $actualConsumption > $plannedConsumption ? 'spillage' : null,
                'unit_cost' => 45.00, // Simplified
                'total_cost' => $actualConsumption * 45.00,
                'is_substitution' => false,
                'recorded_by_user_id' => $this->supervisor->id,
            ]);

            // Record inventory consumption
            InventoryTransaction::create([
                'transaction_type' => 'production_consumption',
                'inventory_item_id' => $component->inventory_item_id,
                'quantity' => -$actualConsumption,
                'unit_id' => $component->unit_id,
                'unit_cost' => 45.00,
                'transaction_date' => $date->copy()->setTime(8, 0),
                'reference_type' => 'ProductionOrder',
                'reference_id' => $productionOrder->id,
                'branch_id' => $this->mainBranch->id,
                'created_by_user_id' => $this->supervisor->id,
            ]);
        }

        // Record output (slightly less than planned due to waste)
        $actualOutput = $plannedQty - rand(5, 15); // 1-3% waste

        ProductionOutput::create([
            'production_order_id' => $productionOrder->id,
            'quantity_produced' => $actualOutput,
            'unit_id' => $recipe->yield_unit_id,
            'quality_grade' => 'standard',
            'recorded_by_user_id' => $this->supervisor->id,
        ]);

        // Add to finished goods inventory
        FinishedGoodsInventory::create([
            'product_id' => $recipe->product_id,
            'branch_id' => $this->mainBranch->id,
            'transaction_type' => 'production',
            'quantity' => $actualOutput,
            'transaction_date' => $date->copy()->setTime(10, 0),
            'reference_type' => 'ProductionOrder',
            'reference_id' => $productionOrder->id,
            'unit_cost' => 15.00, // Simplified cost
            'created_by_user_id' => $this->supervisor->id,
        ]);

        // Complete production
        $productionOrder->update([
            'status' => 'completed',
            'actual_quantity_produced' => $actualOutput,
            'production_cost_total' => $actualOutput * 15.00,
            'completed_by_user_id' => $this->supervisor->id,
            'completed_at' => $date->copy()->setTime(10, 30),
        ]);

        return $productionOrder;
    }

    private function dispatchToBranches($date, $product, $productionOrder, $toBranchA, $toBranchB)
    {
        // Dispatch to Branch A
        $dispatchA = Dispatch::create([
            'dispatch_number' => 'DSP-' . $date->format('Ymd') . '-A-' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT),
            'from_branch_id' => $this->mainBranch->id,
            'to_branch_id' => $this->branchA->id,
            'dispatch_date' => $date,
            'status' => 'received',
            'dispatched_at' => $date->copy()->setTime(11, 0),
            'received_at' => $date->copy()->setTime(12, 0),
            'dispatched_by_user_id' => $this->supervisor->id,
            'received_by_user_id' => $this->supervisor->id,
        ]);

        DispatchItem::create([
            'dispatch_id' => $dispatchA->id,
            'product_id' => $product->id,
            'quantity_dispatched' => $toBranchA,
        ]);

        // Deduct from main branch
        FinishedGoodsInventory::create([
            'product_id' => $product->id,
            'branch_id' => $this->mainBranch->id,
            'transaction_type' => 'dispatch',
            'quantity' => -$toBranchA,
            'transaction_date' => $date->copy()->setTime(11, 0),
            'reference_type' => 'Dispatch',
            'reference_id' => $dispatchA->id,
            'unit_cost' => 15.00,
            'created_by_user_id' => $this->supervisor->id,
        ]);

        // Add to Branch A
        FinishedGoodsInventory::create([
            'product_id' => $product->id,
            'branch_id' => $this->branchA->id,
            'transaction_type' => 'dispatch',
            'quantity' => $toBranchA,
            'transaction_date' => $date->copy()->setTime(12, 0),
            'reference_type' => 'Dispatch',
            'reference_id' => $dispatchA->id,
            'unit_cost' => 15.00,
            'created_by_user_id' => $this->supervisor->id,
        ]);

        // Dispatch to Branch B
        $dispatchB = Dispatch::create([
            'dispatch_number' => 'DSP-' . $date->format('Ymd') . '-B-' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT),
            'from_branch_id' => $this->mainBranch->id,
            'to_branch_id' => $this->branchB->id,
            'dispatch_date' => $date,
            'status' => 'received',
            'dispatched_at' => $date->copy()->setTime(11, 30),
            'received_at' => $date->copy()->setTime(12, 30),
            'dispatched_by_user_id' => $this->supervisor->id,
            'received_by_user_id' => $this->supervisor->id,
        ]);

        DispatchItem::create([
            'dispatch_id' => $dispatchB->id,
            'product_id' => $product->id,
            'quantity_dispatched' => $toBranchB,
        ]);

        // Deduct from main branch
        FinishedGoodsInventory::create([
            'product_id' => $product->id,
            'branch_id' => $this->mainBranch->id,
            'transaction_type' => 'dispatch',
            'quantity' => -$toBranchB,
            'transaction_date' => $date->copy()->setTime(11, 30),
            'reference_type' => 'Dispatch',
            'reference_id' => $dispatchB->id,
            'unit_cost' => 15.00,
            'created_by_user_id' => $this->supervisor->id,
        ]);

        // Add to Branch B
        FinishedGoodsInventory::create([
            'product_id' => $product->id,
            'branch_id' => $this->branchB->id,
            'transaction_type' => 'dispatch',
            'quantity' => $toBranchB,
            'transaction_date' => $date->copy()->setTime(12, 30),
            'reference_type' => 'Dispatch',
            'reference_id' => $dispatchB->id,
            'unit_cost' => 15.00,
            'created_by_user_id' => $this->supervisor->id,
        ]);
    }

    private function recordSales($date, $branch, $product, $quantity)
    {
        if ($quantity <= 0) return;

        $customer = Customer::where('customer_type', 'walk-in')->first();
        $cashier = User::where('role', 'employee')->where('branch_id', $branch->id)->first()
                   ?? $this->supervisor;

        $unitPrice = 25.00; // 25 ETB per piece
        $subtotal = $quantity * $unitPrice;

        $sale = Sale::create([
            'sale_number' => 'SALE-' . $date->format('Ymd') . '-' . $branch->id . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'sale_date' => $date,
            'payment_type' => 'cash',
            'subtotal_amount' => $subtotal,
            'tax_amount' => 0,
            'total_amount' => $subtotal,
            'amount_paid' => $subtotal,
            'balance_due' => 0,
            'status' => 'completed',
            'created_by_user_id' => $cashier->id,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'tax_amount' => 0,
            'total_price' => $subtotal,
            'cost_of_goods' => $quantity * 15.00,
            'profit_margin' => $unitPrice - 15.00,
        ]);

        // Deduct from finished goods
        FinishedGoodsInventory::create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'transaction_type' => 'sales',
            'quantity' => -$quantity,
            'transaction_date' => $date->copy()->setTime(rand(14, 20), rand(0, 59)),
            'reference_type' => 'Sale',
            'reference_id' => $sale->id,
            'unit_cost' => 15.00,
            'created_by_user_id' => $cashier->id,
        ]);
    }
}
