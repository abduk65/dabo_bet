<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\DailySales;
use App\Http\Requests\StoreDailySalesRequest;
use App\Http\Requests\UpdateDailySalesRequest;
use App\Models\Product;

class DailySalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $commissions = Commission::all();
        return view("dailySales.create", compact('products', 'commissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailySalesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailySales $dailySales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailySales $dailySales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDailySalesRequest $request, DailySales $dailySales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailySales $dailySales)
    {
        //
    }
}
