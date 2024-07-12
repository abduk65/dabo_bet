@extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="inventory_create" action="{{ route('dailyInventoryOut.store') }}" method="POST"
                    class="mt-3 ml-15 mr-15">
                    @csrf
                    {{-- <!-- Item Name -->
                    <div class="form-group">
                        <label for="item_name">Item Name:</label>
                        <input type="text" id="item_name" name="item_name" class="form-control" required>
                    </div> --}}

                    <!-- Brands (Dropdown) -->
                    <div class="form-group">
                        <label for="inventory_item">Inventory Item:</label>
                        <select id="inventory_item" name="inventory_item" class="form-control" required>
                            <option value="">Select Inventory Item</option>
                            @foreach ($inventoryItems as $inventoryItem)
                                <option value="{{ $inventoryItem->id }}">{{ $inventoryItem->brand->name }}||||
                                    {{ $inventoryItem->batch_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="receiver_user">Received By</label>
                        <select id="receiver_user" name="receiver_user" class="form-control" required>
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" required>
                    </div>

                    <!-- Unit (Dropdown with Checkbox) -->
                    <div class="form-group">
                        <label for="unit">Unit:</label>
                        <select id="unit" name="unit" class="form-control" required>
                            <option value="default">Select Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="custom_unit_checkbox">
                            <label class="form-check-label" for="custom_unit_checkbox">Custom Unit</label>
                        </div>
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
