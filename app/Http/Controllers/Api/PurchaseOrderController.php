<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders
     */
    public function index(Request $request): JsonResponse
    {
        $query = PurchaseOrder::with(['supplier', 'createdBy', 'items.inventoryItem.materialType']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('order_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('order_date', '<=', $request->to_date);
        }

        $orders = $query->orderBy('order_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Store a newly created purchase order
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:100',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $order = PurchaseOrder::create([
                'supplier_name' => $validated['supplier_name'],
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
                'created_by_user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'inventory_item_id' => $item['inventory_item_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $order->calculateTotal();
            $order->load(['items.inventoryItem.materialType', 'createdBy']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order created successfully',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified purchase order
     */
    public function show(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->load([
            'items.inventoryItem.materialType',
            'items.inventoryItem.brand',
            'createdBy',
            'approvedBy',
            'receivedBy'
        ]);

        return response()->json([
            'success' => true,
            'data' => $purchaseOrder,
        ]);
    }

    /**
     * Update the specified purchase order (only if draft)
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft purchase orders can be updated',
            ], 422);
        }

        $validated = $request->validate([
            'supplier_name' => 'sometimes|string|max:100',
            'order_date' => 'sometimes|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
        ]);

        $purchaseOrder->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Purchase order updated successfully',
            'data' => $purchaseOrder,
        ]);
    }

    /**
     * Submit purchase order for approval
     */
    public function submit(PurchaseOrder $purchaseOrder): JsonResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft purchase orders can be submitted',
            ], 422);
        }

        $purchaseOrder->submit(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Purchase order submitted for approval',
            'data' => $purchaseOrder,
        ]);
    }

    /**
     * Approve purchase order
     */
    public function approve(PurchaseOrder $purchaseOrder): JsonResponse
    {
        if ($purchaseOrder->status !== 'submitted') {
            return response()->json([
                'success' => false,
                'message' => 'Only submitted purchase orders can be approved',
            ], 422);
        }

        $purchaseOrder->approve(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Purchase order approved successfully',
            'data' => $purchaseOrder,
        ]);
    }

    /**
     * Receive purchase order
     */
    public function receive(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        if ($purchaseOrder->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved purchase orders can be received',
            ], 422);
        }

        $validated = $request->validate([
            'received_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['items'] as $itemData) {
                $item = PurchaseOrderItem::find($itemData['id']);
                $item->update([
                    'quantity_received' => $itemData['quantity_received'],
                ]);
            }

            $purchaseOrder->receive(auth()->id(), $validated['received_date']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase order received successfully',
                'data' => $purchaseOrder->fresh(['items.inventoryItem']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to receive purchase order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel purchase order
     */
    public function cancel(PurchaseOrder $purchaseOrder): JsonResponse
    {
        if (!in_array($purchaseOrder->status, ['draft', 'submitted'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel purchase order in current status',
            ], 422);
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Purchase order cancelled successfully',
        ]);
    }
}
