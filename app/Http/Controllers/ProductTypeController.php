<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productTypes = ProductType::all();
        if($request->wantsJson()){
            return response()->json($productTypes);
        }
        return view(
            'productType.index',
            ['productTypes' => $productTypes]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductTypeRequest $request)
    {
        $productType = new ProductType;
        $productType->create($request->only('name'));
        if($request->wantsJson()){
            return response()->json($productType);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductType $productType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductTypeRequest $request, ProductType $productType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        //
    }
}
