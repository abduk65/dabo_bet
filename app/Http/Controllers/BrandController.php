<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::all();
        if ($request->wantsJson()) {
            $brands = Brand::with('productType')->get();
            return response()->json($brands);
        }
        return view('brands.index', ['brands' => $brands]);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productType = ProductType::all();
        return view('brands.create', compact('productType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        //        Gate::authorize('create', Brand::class);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->product_type_id = $request->product_type;
        $brand->save();
        if ($request->wantsJson()) {
            return response()->json($brand);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        try {
            // Log the update attempt
            Log::info('Updating brand', [
                'brand_id' => $brand->id,
                'data' => $request->all()
            ]);

            // Map the product_type to product_type_id
            $updateData = [
                'name' => $request->name,
                'product_type_id' => $request->product_type
            ];

            $brand->fill($updateData);
            $brand->save();

            // Load the relationship for API response
            $updatedBrand = $brand->fresh()->load('productType');

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Brand updated successfully',
                    'data' => $updatedBrand
                ], 200);
            }

            return redirect()->back()->with('success', 'Brand updated successfully');

        } catch (\Exception $e) {
            Log::error('Brand update failed', [
                'brand_id' => $brand->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to update brand',
                    'errors' => [$e->getMessage()]
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update brand. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return response()->json(['message' => 'Brand deleted Successfully']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
