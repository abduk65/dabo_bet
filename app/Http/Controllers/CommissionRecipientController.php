<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CommissionRecipient;
use App\Http\Requests\StoreCommissionRecipientRequest;
use App\Http\Requests\UpdateCommissionRecipientRequest;

use Illuminate\Http\Request;

class CommissionRecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $commissionRecipients = CommissionRecipient::with('branch')->get();
        if ($request->wantsJson()) {
            return response()->json($commissionRecipients);
        }
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
        if ($request->wantsJson()) {
            return response()->json(["success" => true, "commissionRecipient" => CommissionRecipient::all()]);
        }
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
        $commissionRecipient->update(
            $request->only([
                'name',
                'branch_id'
            ])
        );

        if ($request->wantsJson()) {
            return response()->json([
                "success" => true,
                "commissionRecipient" => $commissionRecipient->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Commission recipient updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommissionRecipient $commissionRecipient)
    {
        $commissionRecipient->delete();

        if (request()->wantsJson()) {
            return response()->json([
                "success" => true,
                "message" => "Commission recipient deleted successfully"
            ]);
        }

        return redirect()->back()->with('success', 'Commission recipient deleted successfully');
    }
}
