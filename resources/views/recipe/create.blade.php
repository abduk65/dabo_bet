@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Recipe</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('recipe.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="product_id">Product:</label>
                <select class="form-control" id="product_id" name="product_id">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="name">Recipe Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>

            <div class="form-group">
                <label for="instruction">Instructions:</label>
                <textarea class="form-control" id="instruction" name="instruction">{{ old('instruction') }}</textarea>
            </div>

            <h2>Ingredients</h2>
            <div id="ingredients">
                <div class="form-group ingredient">
                    <label for="inventory_item_id[]">Inventory Item:</label>
                    <select class="form-control" name="inventory_item_id[]" required>
                        @foreach ($inventoryItems as $item)
                            <option value="{{ $item->id }}">{{ $item->brand->name }} batch {{ $item->batch_number }}
                            </option>
                        @endforeach
                    </select>

                    <label for="quantity[]">Quantity Required:</label>
                    <input type="number" class="form-control" name="quantity[]" required>
                    <label for="unit_id[]">Quantity Unit:</label>

                    <select class="form-control" name="unit_id[]" required>
                        @foreach ($unit as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="button" id="add-ingredient" class="btn btn-primary">Add Ingredient</button>
            <button type="submit" class="btn btn-success">Save Recipe</button>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-ingredient').addEventListener('click', function() {
                let ingredientsDiv = document.getElementById('ingredients');
                let newIngredientDiv = document.createElement('div');
                newIngredientDiv.classList.add('form-group', 'ingredient');
                newIngredientDiv.innerHTML = `
                 <label for="inventory_item_id[]">Inventory Item:</label>
                    <select class="form-control" name="inventory_item_id[]" required>
                        @foreach ($inventoryItems as $item)
                            <option value="{{ $item->id }}">{{ $item->brand->name }} batch {{ $item->batch_number }}
                            </option>
                        @endforeach
                    </select>

    <label for="quantity[]">Quantity Required:</label>
    <input type="number" class="form-control" name="quantity[]" required>
    <label for="unit_id[]">Quantity Unit:</label>

                    <select class="form-control" name="unit_id[]" required>
                        @foreach ($unit as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
    <button type="button" class="btn btn-danger remove-ingredient">Remove</button>
    `;
                ingredientsDiv.appendChild(newIngredientDiv);
            });

            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-ingredient')) {
                    e.target.closest('.ingredient').remove();
                }
            });
        });
    </script>
@endsection
{{-- @extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="recipe_create" action="{{ route('recipe.store') }}" method="POST" class="mt-3 ml-15 mr-15">
                    @csrf

                    <div class="form-group">
                        <label for="recipe_name">name:</label>
                        <input type="text" id="recipe_name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="product">Product Item:</label>
                        <select id="product" name="product_id" class="form-control" required>
                            <option value="">Select Product Item</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
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
@endsection --}}
