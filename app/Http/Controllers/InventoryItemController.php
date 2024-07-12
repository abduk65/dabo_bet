<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Http\Requests\StoreInventoryItemRequest;
use App\Http\Requests\UpdateInventoryItemRequest;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InventoryItemController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', InventoryItem::class);
        $inventoryItems = InventoryItem::all(); // Assuming you want to retrieve all items

        // Pass the inventory items to the view
        return view('inventory.index', ['inventoryItems' => $inventoryItems]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('create', InventoryItem::class);

        $brands = Brand::all();
        $units = Unit::all();

        return view('inventory.create', ['units' => $units, 'brands' => $brands]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryItemRequest $request)
    {
        Gate::authorize('create', InventoryItem::class);

        $previousLastBatch = 1;
        $pre = InventoryItem::where('brand_id', $request->brand)->orderBy('batch_number', 'asc')->pluck('batch_number');

        $previousLastBatch = $pre->last();
        // dd($previousInventoryBrand);
        $previousLastBatch += 1;
        // Store the inventory input in the database
        $inventoryInput = new InventoryItem();
        $inventoryInput->item_name = $request->item_name;
        $inventoryInput->unit_id =  $request->unit;
        $inventoryInput->brand_id =  $request->brand;
        $inventoryInput->quantity = $request->quantity;
        $inventoryInput->user_id = auth()->id();
        $inventoryInput->batch_number = $previousLastBatch;
        $inventoryInput->price = $request->price;
        $inventoryInput->total_price = $request->price * $request->quantity;
        $inventoryInput->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Inventory input added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryItem $inventoryItem)
    {
        Gate::authorize('view', InventoryItem::class);

        return 'shown';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        Gate::authorize('view', InventoryItem::class);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventoryItem)
    {
        Gate::authorize('update', InventoryItem::class);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        Gate::authorize('delete', InventoryItem::class);
    }
}
