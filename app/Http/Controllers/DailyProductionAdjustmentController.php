<?php

namespace App\Http\Controllers;

use App\Models\DailyProductionAdjustment;
use App\Models\Product;
use App\Models\Unit;
use App\Http\Requests\StoreDailyProductionAdjustmentRequest;
use App\Http\Requests\UpdateDailyProductionAdjustmentRequest;

class DailyProductionAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dailyProductionAdjustments = DailyProductionAdjustment::all();
        return view("dailyProductionAdjustment.index", compact("dailyProductionAdjustments"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $units = Unit::all();

        return view("dailyProductionAdjustment.create", compact("products", "units"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyProductionAdjustmentRequest $request)
    {
        DailyProductionAdjustment::create($request->only('quantity', 'product_id', 'unit_id', 'remark', 'type'));
        return redirect()->back()->with('success', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyProductionAdjustment $dailyProductionAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyProductionAdjustment $dailyProductionAdjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailyProductionAdjustmentRequest $request, DailyProductionAdjustment $dailyProductionAdjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyProductionAdjustment $dailyProductionAdjustment)
    {
        //
    }
}
