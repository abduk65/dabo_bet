@extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="inventory_create" action="{{ route('brand.store') }}" method="POST" class="mt-3 ml-15 mr-15">
                    @csrf
                    <div class="form-group">
                        <label for="product_type">Brand Item:</label>
                        <select id="product_type" name="product_type" class="form-control" required>
                            <option value="">Select Inventory Item</option>
                            @foreach ($productType as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="name">name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>


                    <!-- Created Date (Automatically filled) -->
                    <div class="form-group">
                        <label for="created_date">Created Date:</label>
                        <input type="text" id="created_date" name="created_date" class="form-control"
                            value="{{ now()->format('Y-m-d') }}" readonly>
                    </div>

                    <!-- Modified Date (Automatically filled) -->
                    <div class="form-group">
                        <label for="modified_date">Modified Date:</label>
                        <input type="text" id="modified_date" name="modified_date" class="form-control"
                            value="{{ now()->format('Y-m-d') }}" readonly>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>

            </div>
        </div>
    </div>


    <script>
        // JavaScript to toggle custom unit selection based on checkbox
        document.getElementById('custom_unit_checkbox').addEventListener('change', function() {
            var unitSelect = document.getElementById('unit');
            if (this.checked) {
                unitSelect.removeAttribute('required');
            } else {
                unitSelect.setAttribute('required', 'required');
            }
        });
    </script>

    <style>
        .form label {
            display: block;
            padding-top: 15px;
        }
    </style>
@endsection
