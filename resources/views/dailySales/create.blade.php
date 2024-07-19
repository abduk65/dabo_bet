@extends('layouts.app')

@section('content')
    @include('layouts.upper')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">


                <div class="container">
                    <h1>Daily Sales</h1>
                    <table id="salesTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                                <th>Adari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <select name="product_id" id="">
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity" placeholder="Quantity"></td>
                                    <td><input type="number" name="adari" placeholder="Adari"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button id="addRow">Add Row</button>
                    <button id="submit" type="submit">Submitw</button>
                </div>

                <script>
                    $(document).ready(function() {
                        $('#salesTable').DataTable();

                        $('#salesTable tbody').on('input', 'input[name="quantity"], input[name="price"]', function(event) {
                            console.log(event);

                            var $row = $(this).closest('tr');
                            var quantity = $row.find('input[name="quantity"]').val();
                            var price = $row.find('input[name="price"]').val();
                            var total = quantity * price;
                            $row.find('input[name="total"]').val(total);
                        });
                    });

                    $('#submit').on('click', function(event) {
                        console.log(event);
                    })
                </script>


            </div>
        </div>
    </div>
@endsection
