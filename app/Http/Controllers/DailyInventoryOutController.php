<?php

namespace App\Http\Controllers;

use App\Models\DailyInventoryOut;
use App\Http\Requests\StoreDailyInventoryOutRequest;
use App\Http\Requests\UpdateDailyInventoryOutRequest;
use App\Models\InventoryItem;

class DailyInventoryOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $daily =  DailyInventoryOut::all();
        return view('dailyInventoryOut.index', compact('daily'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventoryItem = InventoryItem::all();
        return view('dailyInventoryOut.create', compact('inventoryItem'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyInventoryOutRequest $request)
    {
        $daily = new DailyInventoryOut();
        $daily->inventory_items_id = $request->inventory_item_id;
        $daily->quantity = $request->quantity;
        $daily->user_id = 1;
        $daily->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Inventory input added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyInventoryOut $dailyInventoryOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyInventoryOut $dailyInventoryOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailyInventoryOutRequest $request, DailyInventoryOut $dailyInventoryOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyInventoryOut $dailyInventoryOut)
    {
        //
    }
}
