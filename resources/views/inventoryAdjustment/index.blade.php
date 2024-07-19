@extends('layouts.app')

@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">inventoryAdjustment</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('inventoryAdjustment.create') }}"
                                class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3">
                                <i class="btn-inner">
                                </i>
                                <span>New Record</span>
                            </a>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th class="text-center">Adjusted Item</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Operation</th>
                                        <th class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventoryAdjustments as $inventoryAdjustment)
                                        <tr class="">
                                            <td class="">
                                                {{ $inventoryAdjustment->id }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventoryAdjustment->inventoryItem->item_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventoryAdjustment->quantity }}
                                            </td>
                                            <td class="text-center">
                                                {{ $inventoryAdjustment->operation }}
                                            </td>

                                            <td class="text-center">
                                                {{ $inventoryAdjustment->updated_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
