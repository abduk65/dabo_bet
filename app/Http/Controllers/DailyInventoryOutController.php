<?php

namespace App\Http\Controllers;

use App\Models\DailyInventoryOut;
use App\Http\Requests\StoreDailyInventoryOutRequest;
use App\Http\Requests\UpdateDailyInventoryOutRequest;
use App\Models\InventoryItem;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class DailyInventoryOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $daily =  DailyInventoryOut::with('inventoryItem', 'inventoryItem.brand', 'unit', 'user', 'receiver_user')->get();
        if($request->wantsJson()){
            return response()->json($daily);
        }
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
        $daily->user_id = auth()->user()->id || 0;
//        TODO FIX THIS REMOVE THE USER ID HARDCODE MEDEREGUN
        $daily->receiver_user_id = $request->receiver_user_id;
        $daily->save();
        if($request->wantsJson())
        {
            return response()->json($daily);
        }

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
