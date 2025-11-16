<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dispatch;
use App\Models\DispatchItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    /**
     * Display a listing of dispatches
     */
    public function index(Request $request): JsonResponse
    {
        $query = Dispatch::with(['fromBranch', 'toBranch', 'dispatchedBy', 'receivedBy']);

        // Filter by status
        if ($request->has('status')) {
            $query->status($request->status);
        }

        // Filter pending
        if ($request->boolean('pending_only')) {
            $query->pending();
        }

        // Filter by date
        if ($request->has('from_date')) {
            $query->whereDate('dispatch_date', '>=', $request->from_date);
        }

        $dispatches = $query->orderBy('dispatch_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $dispatches,
        ]);
    }

    /**
     * Store a newly created dispatch
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'dispatch_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_dispatched' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $dispatch = Dispatch::create([
                'from_branch_id' => $validated['from_branch_id'],
                'to_branch_id' => $validated['to_branch_id'],
                'dispatch_date' => $validated['dispatch_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
            ]);

            foreach ($validated['items'] as $item) {
                DispatchItem::create([
                    'dispatch_id' => $dispatch->id,
                    'product_id' => $item['product_id'],
                    'quantity_dispatched' => $item['quantity_dispatched'],
                    'unit_cost' => $item['unit_cost'],
                ]);
            }

            $dispatch->load(['items.product', 'fromBranch', 'toBranch']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dispatch created successfully',
                'data' => $dispatch,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create dispatch: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified dispatch
     */
    public function show(Dispatch $dispatch): JsonResponse
    {
        $dispatch->load([
            'fromBranch',
            'toBranch',
            'items.product',
            'dispatchedBy',
            'receivedBy'
        ]);

        return response()->json([
            'success' => true,
            'data' => $dispatch,
        ]);
    }

    /**
     * Mark dispatch as dispatched
     */
    public function markDispatched(Dispatch $dispatch): JsonResponse
    {
        if ($dispatch->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft dispatches can be marked as dispatched',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $dispatch->markAsDispatched(auth()->id());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dispatch marked as dispatched successfully',
                'data' => $dispatch->fresh(['items', 'dispatchedBy']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to dispatch: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Receive dispatch
     */
    public function receive(Request $request, Dispatch $dispatch): JsonResponse
    {
        if ($dispatch->status !== 'dispatched') {
            return response()->json([
                'success' => false,
                'message' => 'Only dispatched items can be received',
            ], 422);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:dispatch_items,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.damage_quantity' => 'nullable|numeric|min:0',
            'items.*.damage_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['items'] as $itemData) {
                $item = DispatchItem::find($itemData['id']);
                $item->recordReceipt(
                    $itemData['quantity_received'],
                    $itemData['damage_quantity'] ?? 0,
                    $itemData['damage_notes'] ?? null
                );
            }

            $dispatch->markAsReceived(auth()->id());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dispatch received successfully',
                'data' => $dispatch->fresh(['items', 'receivedBy']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to receive dispatch: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get pending dispatches
     */
    public function pending(): JsonResponse
    {
        $dispatches = Dispatch::with(['fromBranch', 'toBranch', 'items.product'])
            ->pending()
            ->orderBy('dispatch_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $dispatches,
        ]);
    }
}
