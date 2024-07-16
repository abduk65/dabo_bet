<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\StandardBatchVariety;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\UpdateWorkOrderRequest;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workOrders = WorkOrder::all();
        return view("workOrder.index", compact("workOrders"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $standardBatchVarietys = StandardBatchVariety::all();
        return view("workOrder.create", compact("standardBatchVarietys"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOrderRequest $request)
    {
        WorkOrder::create($request->only('output_count', 'variety_factor', 'standard_batch_variety_id'));
        return redirect()->back()->with('success', '');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
    }
}
