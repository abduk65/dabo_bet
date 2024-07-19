@extends('layouts.app')

@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">dailyProductionAdjustment</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('dailyProductionAdjustment.create') }}"
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
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center">quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">type</th>
                                        <th class="text-center">Remark</th>
                                        <th class="text-center">date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyProductionAdjustments as $dailyProductionAdjustment)
                                        <tr class="">
                                            <td class="">
                                                {{ $dailyProductionAdjustment->id }}
                                            </td>

                                            <td class="text-center">
                                                {{ $dailyProductionAdjustment->product->name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $dailyProductionAdjustment->quantity }}
                                            </td>

                                            <td class="text-center">
                                                {{ $dailyProductionAdjustment->unit->name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $dailyProductionAdjustment->type }}
                                            </td>
                                            <td class="text-center">
                                                {{ $dailyProductionAdjustment->remark }}
                                            </td>

                                            <td class="text-center">
                                                {{ $dailyProductionAdjustment->updated_at }}
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
