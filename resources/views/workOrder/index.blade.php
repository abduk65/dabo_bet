@extends('layouts.app')

@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Work Order</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('workOrder.create') }}"
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
                                        <th class="text-center">Using Recipe</th>
                                        <th class="text-center">Output Count</th>
                                        <th class="text-center">StandardBatchVariety Name</th>
                                        <th class="text-center">StandardBatchVariety Expected Output</th>
                                        <th class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workOrders as $workOrder)
                                        <tr class="">
                                            <td class="">
                                                {{ $workOrder->id }}
                                            </td>

                                            <td class="text-center">
                                                {{ $workOrder->standardBatchVariety->recipe->product->name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $workOrder->standardBatchVariety->recipe->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $workOrder->output_count }}
                                            </td>
                                            <td class="text-center">
                                                {{ $workOrder->standardBatchVariety->name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $workOrder->standardBatchVariety->single_factor_expected_output * $workOrder->variety_factor }}
                                            </td>

                                            <td class="text-center">
                                                {{ $workOrder->updated_at }}
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
