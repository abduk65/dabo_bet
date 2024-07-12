@extends('layouts.app')
@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Inventory List</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('inventory.create') }}"
                                class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3">
                                <i class="btn-inner">

                                </i>
                                <span>New INVENTORY RECORD</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th class="text-center">INVENTORY ITEM</th>
                                        <th class="text-center">BRAND</th>
                                        <th class="text-center">QUANTITY</th>
                                        <th class="text-center">UNIT</th>
                                        <th class="text-center">PRICE</th>
                                        <th class="text-center">TOTAL PRICE</th>
                                        <th class="text-center">BATCH NUMBER</th>
                                        <th class="text-center">RECORD USER</th>
                                        <th class="text-center">RECORD DATE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventoryItems as $inventory)
                                        <tr class="">
                                            <td class="">
                                                {{ $inventory->id }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->item_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->brand->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->quantity }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->unit->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->total_price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->batch_number }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->user->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventory->updated_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                <a href="./admin.html" type="button" class="btn btn-primary">Save</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
