<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'recipe_name' => 'required|string|max:100',
            'batch_size' => 'required|numeric|min:0.01|max:99999',
            'unit_id' => 'required|exists:units,id',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'instructions' => 'nullable|string|max:5000',
            'components' => 'required|array|min:1',
            'components.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'components.*.quantity_required' => 'required|numeric|min:0.001|max:99999',
            'components.*.unit_id' => 'required|exists:units,id',
            'components.*.sequence_order' => 'nullable|integer|min:1',
            'components.*.is_substitutable' => 'nullable|boolean',
            'components.*.notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required',
            'product_id.exists' => 'Selected product does not exist',
            'recipe_name.required' => 'Recipe name is required',
            'batch_size.required' => 'Batch size is required',
            'batch_size.min' => 'Batch size must be greater than 0',
            'effective_date.required' => 'Effective date is required',
            'expiry_date.after' => 'Expiry date must be after effective date',
            'components.required' => 'At least one component is required',
            'components.min' => 'Recipe must have at least one component',
            'components.*.inventory_item_id.required' => 'Component item is required',
            'components.*.inventory_item_id.exists' => 'Selected component does not exist',
            'components.*.quantity_required.required' => 'Component quantity is required',
            'components.*.quantity_required.min' => 'Component quantity must be greater than 0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'components.*.inventory_item_id' => 'component',
            'components.*.quantity_required' => 'quantity',
            'components.*.unit_id' => 'unit',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check for duplicate components
            $components = $this->input('components', []);
            $itemIds = array_column($components, 'inventory_item_id');
            $duplicates = array_diff_assoc($itemIds, array_unique($itemIds));

            if (count($duplicates) > 0) {
                $validator->errors()->add('components', 'Duplicate components are not allowed');
            }
        });
    }
}
