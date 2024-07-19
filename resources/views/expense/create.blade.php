@extends('layouts.app')

@section('content')
    @include('layouts.upper')


    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <form id="inventory_create" action="{{ route('expense.store') }}" method="POST" class="mt-3 ml-15 mr-15">
                    @csrf

                    <div class="form-group">
                        <label for="expense_amount">Amount:</label>
                        <input type="number" id="expense_amount" name="amount" class="form-control" step="0.01"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="expense_description">Description: optional</label>
                        <input type="text" id="expense_description" name="description" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="user">Expense for User: optional</label>
                        <select id="user" name="user_id" class="form-control" required>
                            <option value="">Select For which user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="branch">Branch</label>
                        <select id="branch" name="branch_id" class="form-control" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="expense_type">Expense Type:</label>
                        <select type="text" id="expense_type" name="type" class="form-control" required>
                            <option value="expected">expected</option>
                            <option value="unexpected">unexpected </option>
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
@endsection
