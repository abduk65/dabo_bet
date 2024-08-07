<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CommissionRecipient;
use App\Http\Requests\StoreCommissionRecipientRequest;
use App\Http\Requests\UpdateCommissionRecipientRequest;

class CommissionRecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissionRecipients = CommissionRecipient::all();
        return view("commissionRecipient.index", compact("commissionRecipients"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        return view("commissionRecipient.create", compact("branches"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommissionRecipientRequest $request)
    {
        CommissionRecipient::create(
            $request->only(
                'name',
                'branch_id'
            )
        );

        return redirect()->back()->with('success', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(CommissionRecipient $commissionRecipient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommissionRecipient $commissionRecipient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommissionRecipientRequest $request, CommissionRecipient $commissionRecipient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommissionRecipient $commissionRecipient)
    {
        //
    }
}
