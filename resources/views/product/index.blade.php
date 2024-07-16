@extends('layouts.app')

@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">Product</h4>
                        </div>
                        <div class="">
                            <a href="{{ route('product.create') }}"
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
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr class="">
                                            <td class="">
                                                {{ $product->id }}
                                            </td>

                                            <td class="text-center">
                                                {{ $product->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->unit_price }}
                                            </td>

                                            <td class="text-center">
                                                {{ $product->updated_at }}
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
