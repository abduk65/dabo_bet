@extends('layouts.app')

@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Unit</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('standardBatchVariety.create') }}"
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
                                        <th class="text-center">Recipe Name</th>
                                        <th class="text-center">Recipe for Product</th>
                                        <th class="text-center">Expected Output</th>
                                        <th class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($standardBatchVariety as $sBV)
                                        <tr class="">
                                            <td class="">
                                                {{ $sBV->id }}
                                            </td>

                                            <td class="text-center">
                                                {{ $sBV->recipe->name }} | {{ $sBV->name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $sBV->recipe->product->name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $sBV->single_factor_expected_output }}
                                            </td>

                                            <td class="text-center">
                                                {{ $sBV->updated_at }}
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
