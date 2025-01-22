<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CashCollected;
use App\Http\Requests\StoreCashCollectedRequest;
use App\Http\Requests\UpdateCashCollectedRequest;
use Illuminate\Http\Request;

class CashCollectedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cashCollected = CashCollected::with('branch')->get();
        if ($request->wantsJson()) {
            return response()->json($cashCollected);
        }
        return view("cashCollected.index", compact("cashCollected"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        return view("cashCollected.create", compact("branches"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCashCollectedRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CashCollected $cashCollected)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashCollected $cashCollected)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCashCollectedRequest $request, CashCollected $cashCollected)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashCollected $cashCollected)
    {
        //
    }
}
