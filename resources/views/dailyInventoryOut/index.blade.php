@extends('layouts.app')
@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Daily Inventory Out</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('dailyInventoryOut.create') }}"
                                class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3">
                                <i class="btn-inner">
                                </i>
                                <span>New Record</span>
                            </a>

                            <a href="#" class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3"
                                data-bs-toggle="modal" data-bs-target="#staticBackdrop-1">
                                <i class="btn-inner">

                                </i>
                                <span>New Role</span>
                            </a>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th class="text-center">INVENTORY ITEM</th>
                                        <th class="text-center">GIVEN TO </th>
                                        <th class="text-center">QUANTITY </th>
                                        <th class="text-center">UNIT</th>
                                        <th class="text-center">GIVEN BY</th>
                                        <th class="text-center">GIVEN ON</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daily as $data)
                                        <tr class="">
                                            <td class="">
                                                {{ $data->id }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->inventoryItem->brand->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->receiver_user->name ?? 'anonymous' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->quantity }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->unit->name ?? 'default' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->user->name ?? 'default' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->updated_at }}
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
