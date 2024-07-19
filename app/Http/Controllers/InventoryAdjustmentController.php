<?php

namespace App\Http\Controllers;

use App\Models\InventoryAdjustment;
use App\Models\InventoryItem;
use App\Http\Requests\StoreInventoryAdjustmentRequest;
use App\Http\Requests\UpdateInventoryAdjustmentRequest;
use App\Models\Unit;

class InventoryAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventoryAdjustments = InventoryAdjustment::all();
        return view("inventoryAdjustment.index", compact("inventoryAdjustments"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventoryItems = InventoryItem::all();
        $units = Unit::all();
        return view("inventoryAdjustment.create", compact("inventoryItems", "units"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryAdjustmentRequest $request)
    {
        InventoryAdjustment::create($request->only('quantity', 'inventory_item_id', 'unit_id', 'remark', 'operation'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryAdjustment $inventoryAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryAdjustment $inventoryAdjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryAdjustmentRequest $request, InventoryAdjustment $inventoryAdjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryAdjustment $inventoryAdjustment)
    {
        //
    }
}
