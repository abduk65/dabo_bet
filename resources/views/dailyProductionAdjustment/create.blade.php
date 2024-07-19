@extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="inventory_create" action="{{ route('dailyProductionAdjustment.store') }}" method="POST"
                    class="mt-3 ml-15 mr-15">
                    @csrf

                    <div class="form-group">
                        <label for="quantity">quantity:</label>
                        <input type="number" step="0.01" id="quantity" name="quantity" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="units">units:</label>
                        <select id="units" name="unit_id" class="form-control" required>
                            <option value="">Select Item</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <!-- products (Dropdown) -->
                    <div class="form-group">
                        <label for="products">products:</label>
                        <select id="products" name="product_id" class="form-control" required>
                            <option value="">Select Item</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="adjustment_type">Adjustment Type:</label>
                        <select type="text" id="adjustment_type" name="type" class="form-control" required>
                            <option value="stale">stale</option>
                            <option value="damaged">damaged</option>
                            <option value="worker_error">worker_error</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="remark">remark:</label>
                        <input type="text" id="remark" name="remark" class="form-control">
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
