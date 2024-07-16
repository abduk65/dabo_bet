@extends('layouts.app')

@section('content')
    @include('layouts.upper')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap">
                        <div class="header-title">
                            <h4 class="card-title mb-0">{{ $recipe->name }} for {{ $recipe->product->name }}<br></h4>
                        </div>
                        <div class="">
                            <a href="{{ route('recipe.create') }}"
                                class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3">
                                <i class="btn-inner">
                                </i>
                                <span>New RECIPE</span>
                            </a>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">quantity</th>
                                        <th class="text-center">Unit </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recipe->inventoryItems as $inventoryItem)
                                        <tr class="">
                                            <td class="">
                                                {{ $inventoryItem->id }}
                                            </td>

                                            <td class="text-center">
                                                {{ $inventoryItem->item_name }}
                                            </td>

                                            <td class="text-center">
                                                {{ $inventoryItem->recipe[0]->pivot->quantity }}
                                            </td>

                                            <td class="text-center">
                                                {{ $inventoryItem->recipeUnit[0]->name }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
