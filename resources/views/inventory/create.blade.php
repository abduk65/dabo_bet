@extends('layouts.app')
@section('content')
    @include('layouts.upper')
    <div class="card-body">
        <div class="form">
            <form id="inventory_create" action="{{ route('inventory.store') }}" method="POST" class="mt-3 text-center">
                @csrf
                <label for="item_name">Item Name
                    <input type="text" name="item_name" class="form-control" id="item_name">
                </label>
                <label for="unit">Unit
                    <input type="text" name="unit" class="form-control" id="unit">
                </label>
                <label for="price">Price
                    <input type="number" name="price" class="form-control" id="price">
                </label>
                <label for="quantity"> Quantity
                    <input type="number" name="quantity" class="form-control" id="quantity">
                </label>

                <input type="submit" value="Save" class="btn btn-primary">
            </form>
        </div>
    </div>
    {{-- <style>
        .form label {
            display: block;
            padding-top: 15px;
        }
    </style> --}}
@endsection
