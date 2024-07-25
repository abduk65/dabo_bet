{{-- @extends('layouts.app')

@section('content')
    @include('layouts.upper')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <style>
        <style>.tag-pill {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            cursor: pointer;
        }

        .tag-input-container {
            position: absolute;
            display: none;
            background-color: white;
            border: 1px solid #ddd;
            padding: 10px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .tag-input-container select {
            width: 200px;
        }
    </style>

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="container">
                    <h1>Daily Sales</h1>
                    <table id="dailySalesTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                                <th>Adari</th>
                                <th>CSV Commission</th>
                                <th>COMMISSION RECIPIENT</th>
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
                                    <td><input type="number" name="commission_values" placeholder="CSV COMMISSION"></td>
                                    <td class="product-tags"></td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button id="add-row">Add Row</button>
                    <button id="logData" type="submit">Submit</button>

                    <div class="tag-input-container">
                        <select multiple class="form-select"></select>
                        <button id="add-tag" class="btn btn-sm btn-primary mt-2">Add Tag</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            const commissions = ["com1", "com2", "com3"];

            function createTagInput() {
                const tagInputContainer = $('.tag-input-container');
                tagInputContainer.find('select').html(commissions.map(c => `<option value="${c}">${c}</option>`)
                    .join(''));
                return tagInputContainer;
            }

            function addTagToCell(cell, tag) {
                const tagPill = $(`<span class="tag-pill">${tag}</span>`);
                cell.append(tagPill);
            }

            // $(document).on('click', '.product-tags', function() {
            //     // if (!$(this).find('.tag-input').length) {
            //     const tagInput = createTagInput();
            //     $(this).append(tagInput);
            //     tagInput.find('select').focus();
            //     // }
            // });
            $(document).on('click', '.product-tags', function(event) {
                event.stopPropagation();
                const cell = $(this);
                const offset = cell.offset();
                const tagInputContainer = createTagInput();
                tagInputContainer.css({
                    top: offset.top + cell.outerHeight(),
                    left: offset.left
                }).show();
                tagInputContainer.data('cell', cell);
            });

            $('#add-tag').click(function() {
                const tagInputContainer = $('.tag-input-container');
                const selectedTags = tagInputContainer.find('select').val();
                const cell = tagInputContainer.data('cell');
                selectedTags.forEach(tag => {
                    if (cell.find(`.tag-pill:contains(${tag})`).length === 0) {
                        addTagToCell(cell, tag);
                    }
                });
                tagInputContainer.hide();
            });
            $(document).click(function() {
                $('.tag-input-container').hide();
            });
            // $(document).on('change', '.tag-input select', function() {
            //     const selectedTags = $(this).val().join(', ');
            //     $(this).parent().parent().text(selectedTags);
            // });

            $('#logData').click(function() {
                $('#dailySalesTable tbody tr').each(function() {
                    var row = $(this);
                    var productId = row.find('select[name="product_id"]').val();
                    var quantity = row.find('input[name="quantity"]').val();
                    var adari = row.find('input[name="adari"]').val();
                    var tags = row.find('select[class="form-select"]').val();

                    console.log({
                        product_id: productId,
                        quantity: quantity,
                        adari: adari,
                        tags: tags
                    });
                });
            });

            $('#add-row').click(function() {
                $('#dailySalesTable tbody').append(`
                    <tr>
                        <td><input type="text" class="product-name form-control"></td>
                        <td><input type="number" class="product-quantity form-control"></td>
                        <td><input type="number" class="product-price form-control"></td>
                        <td class="product-total"></td>
                        <td class="product-tags"></td>
                    </tr>
                `);
            });
        });
    </script>
@endsection --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Input Table with Tags</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('js/tag/js/tag-it.js') }}" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css"
        href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
    <link href="{{ asset('js/tag/css/jquery.tagit.css') }}" rel="stylesheet" type="text/css">
    <style>
        .tag-pill {
            display: inline-block;
            padding: 5px 10px;
            margin: 2px;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            cursor: pointer;
        }

        .tag-input-container {
            position: absolute;
            display: none;
            background-color: white;
            border: 1px solid #ddd;
            padding: 10px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .tag-input-container select {
            width: 200px;
        }
    </style>
</head>

<body>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#myTags").tagit({
                availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"],
            });

        });
    </script>

    {{-- <ul id="myTags"> --}}
    <input class="mt-29" id="myTags" type="text" name="" id="" />
    <!-- Existing list items will be pre-added to the tags -->
    {{-- <li>Tag1</li>
        <li>Tag2</li>
    </ul> --}}

    {{-- <table id="inventory-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Tags</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" class="product-name form-control"></td>
                <td><input type="number" class="product-quantity form-control"></td>
                <td><input type="number" class="product-price form-control"></td>
                <td class="product-total"></td>
                <td class="product-tags"></td>
            </tr>
        </tbody>
    </table>
    <button id="add-row" class="btn btn-primary">Add Row</button>
    <button id="calculate-total" class="btn btn-secondary">Calculate Total</button>

    <div class="tag-input-container">
        <select multiple class="form-select"></select>
        <button id="add-tag" class="btn btn-sm btn-primary mt-2">Add Tag</button>
    </div>

    <script>
        $(document).ready(function() {
            const commissions = ["Commission1", "Commission2",
                "Commission3"
            ]; // Replace this with an AJAX call to fetch from database

            function createTagInput() {
                const tagInputContainer = $('.tag-input-container');
                tagInputContainer.find('select').html(commissions.map(c => `<option value="${c}">${c}</option>`)
                    .join(''));
                return tagInputContainer;
            }

            function addTagToCell(cell, tag) {
                const tagPill = $(`<span class="tag-pill">${tag}</span>`);
                cell.append(tagPill);
            }

            $('#add-row').click(function() {
                $('#inventory-table tbody').append(`
                    <tr>
                        <td><input type="text" class="product-name form-control"></td>
                        <td><input type="number" class="product-quantity form-control"></td>
                        <td><input type="number" class="product-price form-control"></td>
                        <td class="product-total"></td>
                        <td class="product-tags"></td>
                    </tr>
                `);
            });

            $('#calculate-total').click(function() {
                $('#inventory-table tbody tr').each(function() {
                    var quantity = $(this).find('.product-quantity').val();
                    var price = $(this).find('.product-price').val();
                    var total = quantity * price;
                    $(this).find('.product-total').text(total.toFixed(2));
                });
            });

            $(document).on('click', '.product-tags', function(event) {
                event.stopPropagation();
                const cell = $(this);
                const offset = cell.offset();
                const tagInputContainer = createTagInput();
                tagInputContainer.css({
                    top: offset.top + cell.outerHeight(),
                    left: offset.left
                }).show();
                tagInputContainer.data('cell', cell);
            });

            $('#add-tag').click(function() {
                const tagInputContainer = $('.tag-input-container');
                const selectedTags = tagInputContainer.find('select').val();
                const cell = tagInputContainer.data('cell');
                selectedTags.forEach(tag => {
                    if (cell.find(`.tag-pill:contains(${tag})`).length === 0) {
                        addTagToCell(cell, tag);
                    }
                });
                tagInputContainer.hide();
            });

            $(document).click(function() {
                $('.tag-input-container').hide();
            });
        });
    </script> --}}
</body>

</html>
