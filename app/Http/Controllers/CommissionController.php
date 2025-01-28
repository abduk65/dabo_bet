<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Http\Requests\StoreCommissionRequest;
use App\Http\Requests\UpdateCommissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $commissions = Commission::with('commissionRecipient', 'product')->get();
        if ($request->wantsJson()) {
            return response()->json(
                $commissions
            );
        }
        return view('commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommissionRequest $request)
    {
        $commission = Commission::create($request->all());
        if ($request->wantsJson()) {
            return response()->json($commission);
        }
        return redirect()->route('commissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommissionRequest $request, Commission $commission)
    {
        $commission->update(
            $request->only([
                'commission_recipient_id',
                'product_id',
                'discount_amount'
            ])
        );

        if ($request->wantsJson()) {
            return response()->json([
                "success" => true,
                "commission" => $commission->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Commission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commission $commission)
    {
        $commission->delete();

        if (request()->wantsJson()) {
            return response()->json([
                "success" => true,
                "message" => "Commission deleted successfully"
            ]);
        }

        return redirect()->back()->with('success', 'Commission deleted successfully');
    }
}
