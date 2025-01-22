<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\StandardBatchVariety;
use App\Http\Requests\StoreStandardBatchVarietyRequest;
use App\Http\Requests\UpdateStandardBatchVarietyRequest;
use Illuminate\Http\Request;

class StandardBatchVarietyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $standardBatchVariety = StandardBatchVariety::with('recipe', 'recipe.product')->get();
        if ($request->wantsJson()) {
            return response()->json($standardBatchVariety);
        }
        return view("standardBatchVariety.index", compact("standardBatchVariety"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $recipes = Recipe::all();
        return view("standardBatchVariety.create", compact("recipes"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStandardBatchVarietyRequest $request)
    {
        $validated = $request->only('name', 'recipe_id', 'single_factor_expected_output');
        StandardBatchVariety::create($validated);
        if($request->wantsJson()){
            return response()->json($validated);
        }
        return redirect()->back()->with("success", "message");
    }

    /**
     * Display the specified resource.
     */
    public function show(StandardBatchVariety $standardBatchVariety)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StandardBatchVariety $standardBatchVariety)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStandardBatchVarietyRequest $request, StandardBatchVariety $standardBatchVariety)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StandardBatchVariety $standardBatchVariety)
    {
        //
    }
}
