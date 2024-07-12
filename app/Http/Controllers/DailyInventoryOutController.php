<?php

namespace App\Http\Controllers;

use App\Models\DailyInventoryOut;
use App\Http\Requests\StoreDailyInventoryOutRequest;
use App\Http\Requests\UpdateDailyInventoryOutRequest;
use App\Models\InventoryItem;
use App\Models\Unit;
use App\Models\User;

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
        $inventoryItems = InventoryItem::all();
        $users = User::all();
        $units = Unit::all();

        return view('dailyInventoryOut.create', compact('inventoryItems', 'users', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyInventoryOutRequest $request)
    {
        $daily = new DailyInventoryOut();
        $daily->inventory_item_id = $request->inventory_item;
        $daily->quantity = $request->quantity;
        $daily->unit_id = $request->unit;
        $daily->user_id = auth()->user()->id;
        $daily->receiver_user_id = $request->receiver_user;
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
