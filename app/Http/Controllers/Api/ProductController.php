<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['unit', 'prices'])->active();

        // Filter by type
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        $products = $query->orderBy('name')->get();

        // Add current price to each product
        $products->each(function ($product) {
            $product->current_price = $product->currentPrice();
        });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'name_am' => 'nullable|string|max:100',
            'type' => 'required|in:Bread,Cake,Others',
            'description' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'shelf_life_days' => 'nullable|integer|min:1',
        ]);

        $product = Product::create($validated);
        $product->load('unit');

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['unit', 'recipes.components', 'prices']);
        $product->current_price = $product->currentPrice();
        $product->active_recipe = $product->activeRecipe();

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'name_am' => 'nullable|string|max:100',
            'type' => 'sometimes|in:Bread,Cake,Others',
            'description' => 'nullable|string',
            'unit_id' => 'sometimes|exists:units,id',
            'shelf_life_days' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $product->update($validated);
        $product->load('unit');

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    /**
     * Get products by type
     */
    public function byType(string $type): JsonResponse
    {
        $products = Product::with(['unit'])
            ->active()
            ->ofType($type)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Set product price
     */
    public function setPrice(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'price_per_unit' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
        ]);

        $validated['product_id'] = $product->id;
        $validated['recorded_by_user_id'] = auth()->id();

        $price = $product->prices()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product price set successfully',
            'data' => $price,
        ], 201);
    }

    /**
     * Get price history
     */
    public function priceHistory(Product $product): JsonResponse
    {
        $prices = $product->prices()
            ->with('recordedBy')
            ->orderBy('effective_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prices,
        ]);
    }
}
