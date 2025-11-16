<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeComponent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Display a listing of recipes
     */
    public function index(Request $request): JsonResponse
    {
        $query = Recipe::with(['product', 'unit', 'components.inventoryItem']);

        // Filter by product
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter active only
        if ($request->boolean('active_only')) {
            $query->active();
        }

        $recipes = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $recipes,
        ]);
    }

    /**
     * Store a newly created recipe
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'recipe_name' => 'required|string|max:100',
            'batch_size' => 'required|numeric|min:0.01',
            'unit_id' => 'required|exists:units,id',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'instructions' => 'nullable|string',
            'components' => 'required|array|min:1',
            'components.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'components.*.quantity_required' => 'required|numeric|min:0.001',
            'components.*.unit_id' => 'required|exists:units,id',
            'components.*.sequence_order' => 'nullable|integer',
            'components.*.is_substitutable' => 'nullable|boolean',
            'components.*.notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $recipe = Recipe::create([
                'product_id' => $validated['product_id'],
                'recipe_name' => $validated['recipe_name'],
                'batch_size' => $validated['batch_size'],
                'unit_id' => $validated['unit_id'],
                'effective_date' => $validated['effective_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'instructions' => $validated['instructions'] ?? null,
                'is_active' => true,
                'created_by_user_id' => auth()->id(),
            ]);

            foreach ($validated['components'] as $index => $component) {
                RecipeComponent::create([
                    'recipe_id' => $recipe->id,
                    'inventory_item_id' => $component['inventory_item_id'],
                    'quantity_required' => $component['quantity_required'],
                    'unit_id' => $component['unit_id'],
                    'sequence_order' => $component['sequence_order'] ?? ($index + 1),
                    'is_substitutable' => $component['is_substitutable'] ?? true,
                    'notes' => $component['notes'] ?? null,
                ]);
            }

            $recipe->calculateCostPerBatch();
            $recipe->load(['product', 'components.inventoryItem.materialType', 'components.inventoryItem.brand']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Recipe created successfully',
                'data' => $recipe,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create recipe: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified recipe
     */
    public function show(Recipe $recipe): JsonResponse
    {
        $recipe->load([
            'product',
            'unit',
            'components.inventoryItem.materialType',
            'components.inventoryItem.brand',
            'components.unit',
            'createdBy'
        ]);

        return response()->json([
            'success' => true,
            'data' => $recipe,
        ]);
    }

    /**
     * Update the specified recipe
     */
    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        $validated = $request->validate([
            'recipe_name' => 'sometimes|string|max:100',
            'batch_size' => 'sometimes|numeric|min:0.01',
            'instructions' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $recipe->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Recipe updated successfully',
            'data' => $recipe,
        ]);
    }

    /**
     * Get recipe components
     */
    public function components(Recipe $recipe): JsonResponse
    {
        $components = $recipe->components()
            ->with(['inventoryItem.materialType', 'inventoryItem.brand', 'unit'])
            ->orderBy('sequence_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $components,
        ]);
    }

    /**
     * Calculate recipe cost
     */
    public function calculateCost(Recipe $recipe): JsonResponse
    {
        $recipe->calculateCostPerBatch();

        $breakdown = [];
        foreach ($recipe->components as $component) {
            $breakdown[] = [
                'material' => $component->inventoryItem->materialType->type_name,
                'brand' => $component->inventoryItem->brand->brand_name,
                'quantity' => $component->quantity_required,
                'unit' => $component->unit->unit_abbreviation,
                'cost' => $component->currentCost(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'recipe_code' => $recipe->recipe_code,
                'batch_size' => $recipe->batch_size,
                'total_cost' => $recipe->cost_per_batch,
                'cost_per_unit' => $recipe->cost_per_batch / $recipe->batch_size,
                'breakdown' => $breakdown,
            ],
        ]);
    }

    /**
     * Activate a recipe (deactivate others for same product)
     */
    public function activate(Recipe $recipe): JsonResponse
    {
        // Deactivate other recipes for the same product
        Recipe::where('product_id', $recipe->product_id)
            ->where('id', '!=', $recipe->id)
            ->update(['is_active' => false]);

        $recipe->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Recipe activated successfully',
            'data' => $recipe,
        ]);
    }
}
