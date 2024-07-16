@extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="inventory_create" action="{{ route('standardBatchVariety.store') }}" method="POST"
                    class="mt-3 ml-15 mr-15">
                    @csrf

                    <div class="form-group">
                        <label for="standardBatchVariety_name">name:</label>
                        <input type="text" id="standardBatchVariety_name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="recipe">recipe:</label>
                        <select id="recipe" name="recipe_id" class="form-control" required>
                            <option value="">Select recipe</option>
                            @foreach ($recipes as $recipe)
                                <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Price -->
                    <div class="form-group">
                        <label for="single_factor_expected_output">Single Factor Expected Output:</label>
                        <input type="number" id="single_factor_expected_output" name="single_factor_expected_output"
                            class="form-control" step="0.01" required>
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
