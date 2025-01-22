<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Unit;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\RecipeInventoryItem;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $recipes = Recipe::with('inventoryItems', 'product', 'standardBatchVariety', 'unit')->get();
        if($request->wantsJson())
        {
            return response()->json($recipes);
        }
        return view("recipe.index", compact("recipes"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: FIX THE BLADE FILE TO BE REFACTORED TO USE COMPONENTS TO CREATE THE NEW RECIPE DROPDOWN EVERYTIME OR ATLEAST CLEANER JQUERY CODE METEQEM AND IF WE CAN SEPARATE ARGO DATA TRANSPORT MAREG

        // TODO KE SHOW RECIPE PAGE  MIMETAWN CREATE REQUEST PRE POPULATE IT WITH THAT SPECIFIC PRODUCT. JUST FOR CONVENIENCE
        $unit = Unit::all();
        $products = Product::all();
        $inventoryItems = InventoryItem::all();

        return view("recipe.create", compact("products", "inventoryItems", "unit"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeRequest $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'inventory_item_id.*' => 'required|exists:inventory_items,id',
            'quantity.*' => 'required|integer|min:1',
        ]);
        //TODO: CLEAN UP THIS JUMBLED MESS OF A RELATIONSHIP
        $recipe = Recipe::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'instruction' => $request->instruction,
        ]);

        foreach ($request->inventory_item_id as $index => $inventoryItemId) {
            $recipe->inventoryItems()->attach($inventoryItemId, [
                'quantity' => $request->quantity[$index],
                'unit_id' => $request->unit_id[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Recipe created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        // $recipeII = RecipeInventoryItem::find($recipe->id);
        $recipe = Recipe::with('inventoryItems')->findOrFail($recipe->id);
        return view("recipe.show", compact("recipe"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
