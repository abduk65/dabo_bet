@extends('layouts.app')
@section('content')
    @include('layouts.upper')
    <div class="card-body">
        <div class="form">
            <form id="daily_inventory_out_create" action="{{ route('dailyInventoryOut.store') }}" method="POST"
                class="mt-3 text-center">
                @csrf
                <div>
                    <label for="product_id">Product:</label>
                    <select name="inventory_item_id" class="form-control product_id" id="product_id" required>
                        <option value="">Select a product</option>
                        @foreach ($inventoryItem as $data)
                            <option value="{{ $data->id }}">{{ $data->item_name }}</option>
                        @endforeach
                    </select>
                </div>

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
