<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    /**
     * Display a listing of brands
     */
    public function index(): JsonResponse
    {
        $brands = Brand::active()
            ->orderBy('brand_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $brands,
        ]);
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:100|unique:brands',
            'manufacturer' => 'nullable|string|max:100',
            'country_of_origin' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $brand = Brand::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => $brand,
        ], 201);
    }

    /**
     * Display the specified brand
     */
    public function show(Brand $brand): JsonResponse
    {
        $brand->load('inventoryItems.materialType');

        return response()->json([
            'success' => true,
            'data' => $brand,
        ]);
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, Brand $brand): JsonResponse
    {
        $validated = $request->validate([
            'brand_name' => 'sometimes|string|max:100|unique:brands,brand_name,' . $brand->id,
            'manufacturer' => 'nullable|string|max:100',
            'country_of_origin' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $brand->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully',
            'data' => $brand,
        ]);
    }

    /**
     * Remove the specified brand
     */
    public function destroy(Brand $brand): JsonResponse
    {
        // Soft delete by setting is_active to false
        $brand->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Brand deactivated successfully',
        ]);
    }
}
