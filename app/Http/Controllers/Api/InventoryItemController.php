<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of inventory items
     */
    public function index(Request $request): JsonResponse
    {
        $query = InventoryItem::with(['materialType', 'brand', 'unit'])
            ->active();

        // Filter by material type
        if ($request->has('material_type_id')) {
            $query->where('material_type_id', $request->material_type_id);
        }

        // Filter by brand
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter low stock items
        if ($request->boolean('low_stock')) {
            $query->where('current_stock_quantity', '<=', 'reorder_level');
        }

        $items = $query->orderBy('sku_code')->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'material_type_id' => 'required|exists:material_types,id',
            'brand_id' => 'required|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'reorder_level' => 'nullable|numeric|min:0',
            'storage_location' => 'nullable|string|max:100',
        ]);

        $validated['created_by_user_id'] = auth()->id();

        $item = InventoryItem::create($validated);
        $item->load(['materialType', 'brand', 'unit']);

        return response()->json([
            'success' => true,
            'message' => 'Inventory item created successfully',
            'data' => $item,
        ], 201);
    }

    /**
     * Display the specified inventory item
     */
    public function show(InventoryItem $inventoryItem): JsonResponse
    {
        $inventoryItem->load(['materialType', 'brand', 'unit', 'purchasePrices']);

        return response()->json([
            'success' => true,
            'data' => $inventoryItem,
        ]);
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, InventoryItem $inventoryItem): JsonResponse
    {
        $validated = $request->validate([
            'reorder_level' => 'sometimes|numeric|min:0',
            'storage_location' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $inventoryItem->update($validated);
        $inventoryItem->load(['materialType', 'brand', 'unit']);

        return response()->json([
            'success' => true,
            'message' => 'Inventory item updated successfully',
            'data' => $inventoryItem,
        ]);
    }

    /**
     * Get price history for an inventory item
     */
    public function priceHistory(InventoryItem $inventoryItem): JsonResponse
    {
        $priceHistory = $inventoryItem->purchasePrices()
            ->with('recordedBy')
            ->orderBy('effective_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $priceHistory,
        ]);
    }

    /**
     * Get current stock level
     */
    public function stockLevel(InventoryItem $inventoryItem): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'sku_code' => $inventoryItem->sku_code,
                'current_stock' => $inventoryItem->current_stock_quantity,
                'reorder_level' => $inventoryItem->reorder_level,
                'needs_reorder' => $inventoryItem->needsReorder(),
                'unit' => $inventoryItem->unit->unit_abbreviation,
            ],
        ]);
    }

    /**
     * Get low stock items
     */
    public function lowStock(): JsonResponse
    {
        $items = InventoryItem::with(['materialType', 'brand', 'unit'])
            ->active()
            ->whereColumn('current_stock_quantity', '<=', 'reorder_level')
            ->orderBy('current_stock_quantity')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}
