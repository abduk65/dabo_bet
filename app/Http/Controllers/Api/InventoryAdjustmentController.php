<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryAdjustment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryAdjustmentController extends Controller
{
    /**
     * Display a listing of inventory adjustments
     */
    public function index(Request $request): JsonResponse
    {
        $query = InventoryAdjustment::with([
            'inventoryItem.materialType',
            'inventoryItem.brand',
            'createdBy',
            'approvedBy'
        ]);

        // Filter by status
        if ($request->has('status')) {
            $query->whereNull('approved_by_user_id');
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        $adjustments = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $adjustments,
        ]);
    }

    /**
     * Store a newly created inventory adjustment
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'operation' => 'required|in:addition,subtraction',
            'quantity' => 'required|numeric|min:0.001',
            'reason' => 'required|string',
        ]);

        $validated['created_by_user_id'] = auth()->id();

        $adjustment = InventoryAdjustment::create($validated);
        $adjustment->load(['inventoryItem.materialType', 'inventoryItem.brand', 'createdBy']);

        return response()->json([
            'success' => true,
            'message' => 'Inventory adjustment created successfully',
            'data' => $adjustment,
        ], 201);
    }

    /**
     * Display the specified inventory adjustment
     */
    public function show(InventoryAdjustment $inventoryAdjustment): JsonResponse
    {
        $inventoryAdjustment->load([
            'inventoryItem.materialType',
            'inventoryItem.brand',
            'createdBy',
            'approvedBy'
        ]);

        return response()->json([
            'success' => true,
            'data' => $inventoryAdjustment,
        ]);
    }

    /**
     * Approve inventory adjustment
     */
    public function approve(InventoryAdjustment $inventoryAdjustment): JsonResponse
    {
        if ($inventoryAdjustment->approved_by_user_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Adjustment has already been approved',
            ], 422);
        }

        $inventoryAdjustment->approve(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Inventory adjustment approved successfully',
            'data' => $inventoryAdjustment->fresh(['inventoryItem', 'approvedBy']),
        ]);
    }

    /**
     * Get pending adjustments
     */
    public function pending(): JsonResponse
    {
        $adjustments = InventoryAdjustment::with([
            'inventoryItem.materialType',
            'inventoryItem.brand',
            'createdBy'
        ])
            ->whereNull('approved_by_user_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $adjustments,
        ]);
    }
}
