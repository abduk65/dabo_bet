<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization will be handled by policies
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'supplier_name' => 'required|string|max:100',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.001|max:99999',
            'items.*.unit_price' => 'required|numeric|min:0|max:999999',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'supplier_name.required' => 'Supplier name is required',
            'order_date.required' => 'Order date is required',
            'expected_delivery_date.after_or_equal' => 'Delivery date must be on or after order date',
            'items.required' => 'At least one item is required',
            'items.min' => 'At least one item is required',
            'items.*.inventory_item_id.required' => 'Item is required',
            'items.*.inventory_item_id.exists' => 'Selected item does not exist',
            'items.*.quantity.required' => 'Quantity is required',
            'items.*.quantity.min' => 'Quantity must be greater than 0',
            'items.*.unit_price.required' => 'Unit price is required',
            'items.*.unit_price.min' => 'Unit price must be 0 or greater',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'items.*.inventory_item_id' => 'inventory item',
            'items.*.quantity' => 'quantity',
            'items.*.unit_price' => 'unit price',
        ];
    }
}
