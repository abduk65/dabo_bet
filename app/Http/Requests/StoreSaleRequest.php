<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date|before_or_equal:today',
            'payment_type' => 'required|in:cash,credit,bank_transfer',
            'amount_paid' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|required_if:payment_type,credit|date|after:sale_date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01|max:99999',
            'items.*.unit_price' => 'required|numeric|min:0|max:999999',
            'items.*.unit_cost' => 'required|numeric|min:0|max:999999',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'branch_id.required' => 'Branch is required',
            'customer_id.required' => 'Customer is required',
            'sale_date.required' => 'Sale date is required',
            'sale_date.before_or_equal' => 'Sale date cannot be in the future',
            'payment_type.required' => 'Payment type is required',
            'payment_type.in' => 'Invalid payment type',
            'due_date.required_if' => 'Due date is required for credit sales',
            'due_date.after' => 'Due date must be after sale date',
            'items.required' => 'At least one item is required',
            'items.min' => 'At least one item is required',
            'items.*.product_id.required' => 'Product is required',
            'items.*.product_id.exists' => 'Selected product does not exist',
            'items.*.quantity.required' => 'Quantity is required',
            'items.*.quantity.min' => 'Quantity must be greater than 0',
            'items.*.unit_price.required' => 'Unit price is required',
            'items.*.unit_cost.required' => 'Unit cost is required',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if customer is over credit limit for credit sales
            if ($this->input('payment_type') === 'credit') {
                $customer = \App\Models\Customer::find($this->input('customer_id'));

                if ($customer && $customer->isOverCreditLimit()) {
                    $validator->errors()->add('customer_id',
                        'Customer has exceeded their credit limit. Outstanding balance: ' .
                        number_format($customer->outstandingBalance(), 2) . ' ETB'
                    );
                }
            }

            // Validate amount paid is not more than total for cash sales
            if ($this->input('payment_type') === 'cash' && $this->has('amount_paid')) {
                $items = $this->input('items', []);
                $total = 0;
                foreach ($items as $item) {
                    $total += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                }

                if ($this->input('amount_paid') > $total) {
                    $validator->errors()->add('amount_paid', 'Amount paid cannot exceed total sale amount');
                }
            }
        });
    }
}
