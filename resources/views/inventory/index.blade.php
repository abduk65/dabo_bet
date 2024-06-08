@extends('layouts.app')
@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Role & Permission</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('inventory.create') }}"
                                class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3">
                                <i class="btn-inner">

                                </i>
                                <span>New INVENTORY RECORD</span>
                            </a>
                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Add new permission</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="email" class="form-label">permission title</label>
                                                <input type="email" class="form-control" id="email"
                                                    aria-describedby="email" placeholder="Permission Title">
                                            </div>
                                            <div class="text-start">
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-dismiss="modal">Save</button>
                                                <button type="button" class="btn btn-danger">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3"
                                data-bs-toggle="modal" data-bs-target="#staticBackdrop-1">
                                <i class="btn-inner">

                                </i>
                                <span>New Role</span>
                            </a>
                            <div class="modal fade" id="staticBackdrop-1" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Add new role</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="email" class="form-label">role title</label>
                                                <input type="email" class="form-control" id="email"
                                                    aria-describedby="email" placeholder="Role Title">
                                            </div>
                                            <div>
                                                <span>status</span>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="exampleRadios"
                                                        id="exampleRadios2" value="option2">
                                                    <label class="form-check-label" for="exampleRadios2">
                                                        yes
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="exampleRadios"
                                                        id="exampleRadios2" value="option2">
                                                    <label class="form-check-label" for="exampleRadios2">
                                                        no
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-start mt-2">
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-dismiss="modal">Save</button>
                                                <button type="button" class="btn btn-danger">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        {{-- <th class="text-center">ADMIN
                                            <div style="float:right;">
                                                <a class="btn btn-sm btn-icon text-primary flex-end"
                                                    data-bs-toggle="tooltip" title="Edit User" href="#">
                                                    <span class="btn-inner">

                                                    </span>
                                                </a>
                                                <a class="btn btn-sm btn-icon text-danger" data-bs-toggle="tooltip"
                                                    title="Delete User" href="#">
                                                    <span class="btn-inner">

                                                    </span>
                                                </a>
                                            </div>
                                        </th> --}}
                                        <th class="text-center">INVENTORY ITEM

                                        </th>

                                        <th class="text-center">PRICE

                                        </th>

                                        <th class="text-center">QUANTITY

                                        </th>

                                        <th class="text-center">UNIT

                                        </th>

                                        <th class="text-center">TOTAL PRICE

                                        </th>
                                        <th class="text-center">RECORD DATE

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr class="">
                                        <td class="">Role
                                            <div style="float:right;">
                                                <a class="btn btn-sm btn-icon text-primary flex-end"
                                                    data-bs-toggle="tooltip" title="Edit User" href="#">
                                                    <span class="btn-inner">

                                                    </span>
                                                </a>
                                                <a class="btn btn-sm btn-icon text-danger " data-bs-toggle="tooltip"
                                                    title="Delete User" href="#">
                                                    <span class="btn-inner">

                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                    </tr> --}}
                                    @foreach ($inventoryItems as $data)
                                        <tr class="">
                                            <td class="">
                                                {{ $data->id }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->item_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->quantity }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->unit }}
                                            </td>
                                            <td class="text-center">
                                                {{ $data->total_price }}
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
