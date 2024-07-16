@extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="inventory_create" action="{{ route('workOrder.store') }}" method="POST" class="mt-3 ml-15 mr-15">
                    @csrf


                    <div class="form-group">
                        <label for="standardBatchVariety">standardBatchVariety:</label>
                        <select id="standardBatchVariety" name="standard_batch_variety_id" class="form-control" required>
                            <option value="">Select standardBatchVariety</option>
                            @foreach ($standardBatchVarietys as $standardBatchVariety)
                                <option value="{{ $standardBatchVariety->id }}">{{ $standardBatchVariety->name }} |
                                    {{ $standardBatchVariety->recipe->product->name }} using
                                    {{ $standardBatchVariety->recipe->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="variety_factor">variety_factor:</label>
                            <input type="number" id="variety_factor" name="variety_factor" class="form-control"
                                step="0.01" required>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="custom_unit_checkbox">
                            <label class="form-check-label" for="custom_unit_checkbox">Custom Factor</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="output_count">output_count:</label>
                        <input type="number" id="output_count" name="output_count" class="form-control" required>
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
