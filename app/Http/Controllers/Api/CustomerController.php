<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPricing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request): JsonResponse
    {
        $query = Customer::with('branch')->active();

        // Filter by type
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        $customers = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:walk_in,commission_recipient,branch',
            'branch_id' => 'nullable|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'tin_number' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_period_days' => 'nullable|integer|min:0',
        ]);

        $customer = Customer::create($validated);
        $customer->load('branch');

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer,
        ], 201);
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer): JsonResponse
    {
        $customer->load(['branch', 'customPricing.product', 'sales', 'payments']);
        $customer->outstanding_balance = $customer->outstandingBalance();

        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'type' => 'sometimes|in:walk_in,commission_recipient,branch',
            'branch_id' => 'nullable|exists:branches,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'tin_number' => 'nullable|string|max:50',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_period_days' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ]);
    }

    /**
     * Get customers by type
     */
    public function byType(string $type): JsonResponse
    {
        $customers = Customer::with('branch')
            ->active()
            ->ofType($type)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Get commission recipients
     */
    public function commissionRecipients(): JsonResponse
    {
        $customers = Customer::active()
            ->commissionRecipients()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Get customer pricing
     */
    public function pricing(Customer $customer): JsonResponse
    {
        $pricing = $customer->customPricing()
            ->with(['product', 'createdBy'])
            ->orderBy('product_id')
            ->orderBy('effective_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pricing,
        ]);
    }

    /**
     * Set customer pricing
     */
    public function setPricing(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price_per_unit' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['created_by_user_id'] = auth()->id();

        $pricing = CustomerPricing::create($validated);
        $pricing->load(['product', 'createdBy']);

        return response()->json([
            'success' => true,
            'message' => 'Customer pricing set successfully',
            'data' => $pricing,
        ], 201);
    }

    /**
     * Get customer outstanding balance
     */
    public function outstandingBalance(Customer $customer): JsonResponse
    {
        $balance = $customer->outstandingBalance();
        $isOverLimit = $customer->isOverCreditLimit();

        return response()->json([
            'success' => true,
            'data' => [
                'customer_code' => $customer->customer_code,
                'customer_name' => $customer->name,
                'outstanding_balance' => $balance,
                'credit_limit' => $customer->credit_limit,
                'is_over_limit' => $isOverLimit,
                'available_credit' => max(0, $customer->credit_limit - $balance),
            ],
        ]);
    }
}
