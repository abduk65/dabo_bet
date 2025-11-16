<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MaterialTypeController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\InventoryItemController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\InventoryAdjustmentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\ProductionOrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DispatchController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\JournalEntryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api
| Authentication via Laravel Sanctum
|
*/

// ============================================================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================================================

Route::post('/login', [AuthController::class, 'login']);

// ============================================================================
// PROTECTED ROUTES (Authentication Required)
// ============================================================================

Route::middleware('auth:sanctum')->group(function () {

    // ========================================================================
    // AUTHENTICATION & USER MANAGEMENT
    // ========================================================================

    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // ========================================================================
    // PHASE 1: INVENTORY MANAGEMENT
    // ========================================================================

    Route::prefix('inventory')->group(function () {

        // Material Types
        Route::apiResource('material-types', MaterialTypeController::class);
        Route::get('material-types/category/{category}', [MaterialTypeController::class, 'byCategory']);

        // Brands
        Route::apiResource('brands', BrandController::class);

        // Inventory Items
        Route::apiResource('items', InventoryItemController::class);
        Route::get('items/{inventoryItem}/price-history', [InventoryItemController::class, 'priceHistory']);
        Route::get('items/{inventoryItem}/stock-level', [InventoryItemController::class, 'stockLevel']);
        Route::get('low-stock', [InventoryItemController::class, 'lowStock']);

        // Purchase Orders
        Route::apiResource('purchase-orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{purchaseOrder}/submit', [PurchaseOrderController::class, 'submit']);
        Route::post('purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve']);
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive']);
        Route::post('purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel']);

        // Inventory Adjustments
        Route::apiResource('adjustments', InventoryAdjustmentController::class);
        Route::post('adjustments/{inventoryAdjustment}/approve', [InventoryAdjustmentController::class, 'approve']);
        Route::get('adjustments-pending', [InventoryAdjustmentController::class, 'pending']);
    });

    // ========================================================================
    // PHASE 2: PRODUCTION MANAGEMENT
    // ========================================================================

    Route::prefix('production')->group(function () {

        // Products
        Route::apiResource('products', ProductController::class);
        Route::get('products/type/{type}', [ProductController::class, 'byType']);
        Route::post('products/{product}/set-price', [ProductController::class, 'setPrice']);
        Route::get('products/{product}/price-history', [ProductController::class, 'priceHistory']);

        // Recipes
        Route::apiResource('recipes', RecipeController::class);
        Route::get('recipes/{recipe}/components', [RecipeController::class, 'components']);
        Route::post('recipes/{recipe}/calculate-cost', [RecipeController::class, 'calculateCost']);
        Route::post('recipes/{recipe}/activate', [RecipeController::class, 'activate']);

        // Production Orders
        Route::apiResource('orders', ProductionOrderController::class);
        Route::post('orders/{productionOrder}/start', [ProductionOrderController::class, 'start']);
        Route::post('orders/{productionOrder}/record-consumption', [ProductionOrderController::class, 'recordConsumption']);
        Route::post('orders/{productionOrder}/record-output', [ProductionOrderController::class, 'recordOutput']);
        Route::post('orders/{productionOrder}/complete', [ProductionOrderController::class, 'complete']);
        Route::get('orders/{productionOrder}/consumption', [ProductionOrderController::class, 'consumption']);
        Route::get('orders/{productionOrder}/output', [ProductionOrderController::class, 'output']);
    });

    // ========================================================================
    // PHASE 3: SALES & DISTRIBUTION
    // ========================================================================

    // Customers
    Route::apiResource('customers', CustomerController::class);
    Route::get('customers/type/{type}', [CustomerController::class, 'byType']);
    Route::get('customers-commission-recipients', [CustomerController::class, 'commissionRecipients']);
    Route::get('customers/{customer}/pricing', [CustomerController::class, 'pricing']);
    Route::post('customers/{customer}/set-pricing', [CustomerController::class, 'setPricing']);
    Route::get('customers/{customer}/outstanding-balance', [CustomerController::class, 'outstandingBalance']);

    // Dispatches
    Route::apiResource('dispatches', DispatchController::class);
    Route::post('dispatches/{dispatch}/mark-dispatched', [DispatchController::class, 'markDispatched']);
    Route::post('dispatches/{dispatch}/receive', [DispatchController::class, 'receive']);
    Route::get('dispatches-pending', [DispatchController::class, 'pending']);

    // Sales
    Route::apiResource('sales', SaleController::class);
    Route::post('sales/{sale}/complete', [SaleController::class, 'complete']);
    Route::post('sales/{sale}/cancel', [SaleController::class, 'cancel']);
    Route::get('sales-outstanding', [SaleController::class, 'outstanding']);
    Route::get('sales-overdue', [SaleController::class, 'overdue']);

    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::get('payments-advance', [PaymentController::class, 'advance']);
    Route::get('payments/customer/{customerId}', [PaymentController::class, 'byCustomer']);

    // ========================================================================
    // PHASE 4: FINANCIAL MANAGEMENT
    // ========================================================================

    // Accounts
    Route::get('accounts', [AccountController::class, 'index']);
    Route::get('accounts/{account}', [AccountController::class, 'show']);
    Route::get('accounts/{account}/balance', [AccountController::class, 'balance']);
    Route::get('accounts/type/{type}', [AccountController::class, 'byType']);
    Route::get('chart-of-accounts', [AccountController::class, 'chartOfAccounts']);
    Route::get('trial-balance', [AccountController::class, 'trialBalance']);

    // Journal Entries
    Route::apiResource('journal-entries', JournalEntryController::class);
    Route::post('journal-entries/{journalEntry}/post', [JournalEntryController::class, 'post']);
    Route::post('journal-entries/{journalEntry}/reverse', [JournalEntryController::class, 'reverse']);
    Route::get('journal-entries-drafts', [JournalEntryController::class, 'drafts']);
    Route::get('journal-entries/type/{type}', [JournalEntryController::class, 'byType']);

    // ========================================================================
    // REFERENCE DATA (All Phases)
    // ========================================================================

    Route::get('units', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Unit::orderBy('unit_name')->get(),
        ]);
    });

    Route::get('branches', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Branch::where('is_active', true)->orderBy('name')->get(),
        ]);
    });
});
