<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Http\Requests\StoreInventoryItemRequest;
use App\Http\Requests\UpdateInventoryItemRequest;
use Illuminate\Support\Facades\Gate;

class InventoryItemController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventoryItems = InventoryItem::all(); // Assuming you want to retrieve all items
        $info = "info";
        // Pass the inventory items to the view
        return view('inventory.index', ['name' => 'James', 'inventoryItems' => $inventoryItems]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryItemRequest $request)
    {

        // Gate::authorize('create', $request);
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        // Store the inventory input in the database
        $inventoryInput = new InventoryItem();
        $inventoryInput->item_name = $request->item_name;
        $inventoryInput->unit = $request->unit;
        $inventoryInput->quantity = $request->quantity;
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
        return 'shown';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $inventoryItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventoryItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem)
    {
        //
    }
}
