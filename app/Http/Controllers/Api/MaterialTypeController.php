<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MaterialTypeController extends Controller
{
    /**
     * Display a listing of material types
     */
    public function index(): JsonResponse
    {
        $materialTypes = MaterialType::with('unit')
            ->active()
            ->orderBy('type_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $materialTypes,
        ]);
    }

    /**
     * Store a newly created material type
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type_code' => 'required|string|max:20|unique:material_types',
            'type_name' => 'required|string|max:100',
            'type_name_am' => 'nullable|string|max:100',
            'category' => 'required|in:flour,ersho,bread_improver,salt,sugar,oil,cake_component,packaging,other',
            'default_unit_id' => 'required|exists:units,id',
            'description' => 'nullable|string',
        ]);

        $materialType = MaterialType::create($validated);
        $materialType->load('unit');

        return response()->json([
            'success' => true,
            'message' => 'Material type created successfully',
            'data' => $materialType,
        ], 201);
    }

    /**
     * Display the specified material type
     */
    public function show(MaterialType $materialType): JsonResponse
    {
        $materialType->load(['unit', 'inventoryItems.brand']);

        return response()->json([
            'success' => true,
            'data' => $materialType,
        ]);
    }

    /**
     * Update the specified material type
     */
    public function update(Request $request, MaterialType $materialType): JsonResponse
    {
        $validated = $request->validate([
            'type_code' => 'sometimes|string|max:20|unique:material_types,type_code,' . $materialType->id,
            'type_name' => 'sometimes|string|max:100',
            'type_name_am' => 'nullable|string|max:100',
            'category' => 'sometimes|in:flour,ersho,bread_improver,salt,sugar,oil,cake_component,packaging,other',
            'default_unit_id' => 'sometimes|exists:units,id',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $materialType->update($validated);
        $materialType->load('unit');

        return response()->json([
            'success' => true,
            'message' => 'Material type updated successfully',
            'data' => $materialType,
        ]);
    }

    /**
     * Remove the specified material type
     */
    public function destroy(MaterialType $materialType): JsonResponse
    {
        // Soft delete by setting is_active to false
        $materialType->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Material type deactivated successfully',
        ]);
    }

    /**
     * Get material types by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $materialTypes = MaterialType::with('unit')
            ->active()
            ->where('category', $category)
            ->orderBy('type_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $materialTypes,
        ]);
    }
}
