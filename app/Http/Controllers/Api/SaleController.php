<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request): JsonResponse
    {
        $query = Sale::with(['customer', 'branch', 'createdBy']);

        // Filter by branch
        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by customer
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->status($request->status);
        }

        // Filter by payment type
        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter outstanding
        if ($request->boolean('outstanding_only')) {
            $query->outstanding();
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('sale_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('sale_date', '<=', $request->to_date);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'payment_type' => 'required|in:cash,credit,bank_transfer',
            'amount_paid' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date|after:sale_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'branch_id' => $validated['branch_id'],
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'payment_type' => $validated['payment_type'],
                'tax_amount' => 0, // Can be calculated based on business rules
                'amount_paid' => $validated['amount_paid'] ?? 0,
                'due_date' => $validated['due_date'] ?? null,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'created_by_user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'unit_cost' => $item['unit_cost'],
                ]);
            }

            $sale->calculateTotals();
            $sale->load(['items.product', 'customer', 'branch']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully',
                'data' => $sale,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale): JsonResponse
    {
        $sale->load([
            'customer',
            'branch',
            'items.product',
            'payments',
            'createdBy'
        ]);

        $sale->total_profit = $sale->totalProfit();
        $sale->profit_margin = $sale->profitMargin();

        return response()->json([
            'success' => true,
            'data' => $sale,
        ]);
    }

    /**
     * Complete sale
     */
    public function complete(Sale $sale): JsonResponse
    {
        if ($sale->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft sales can be completed',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $sale->complete(auth()->id());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'data' => $sale->fresh(['items', 'customer']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel sale
     */
    public function cancel(Sale $sale): JsonResponse
    {
        if ($sale->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft sales can be cancelled',
            ], 422);
        }

        $sale->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Sale cancelled successfully',
        ]);
    }

    /**
     * Get outstanding sales
     */
    public function outstanding(): JsonResponse
    {
        $sales = Sale::with(['customer', 'branch'])
            ->outstanding()
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }

    /**
     * Get overdue sales
     */
    public function overdue(): JsonResponse
    {
        $sales = Sale::with(['customer', 'branch'])
            ->overdue()
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }
}
