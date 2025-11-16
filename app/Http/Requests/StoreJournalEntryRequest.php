<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalEntryRequest extends FormRequest
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
            'entry_date' => 'required|date',
            'entry_type' => 'required|in:purchase,production,dispatch,sale,payment,expense,depreciation,adjustment,opening_balance',
            'description' => 'required|string|max:500',
            'reference_type' => 'nullable|string|max:50',
            'reference_id' => 'nullable|integer',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit_amount' => 'required|numeric|min:0',
            'lines.*.credit_amount' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'entry_date.required' => 'Entry date is required',
            'entry_type.required' => 'Entry type is required',
            'entry_type.in' => 'Invalid entry type',
            'description.required' => 'Description is required',
            'lines.required' => 'At least two lines are required for a journal entry',
            'lines.min' => 'A journal entry must have at least two lines (debit and credit)',
            'lines.*.account_id.required' => 'Account is required for each line',
            'lines.*.account_id.exists' => 'Selected account does not exist',
            'lines.*.debit_amount.required' => 'Debit amount is required',
            'lines.*.credit_amount.required' => 'Credit amount is required',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $lines = $this->input('lines', []);

            // Validate each line has either debit or credit (not both or neither)
            foreach ($lines as $index => $line) {
                $debit = (float) ($line['debit_amount'] ?? 0);
                $credit = (float) ($line['credit_amount'] ?? 0);

                if (($debit > 0 && $credit > 0) || ($debit == 0 && $credit == 0)) {
                    $validator->errors()->add(
                        "lines.{$index}",
                        'Each line must have either a debit or credit amount, not both or neither'
                    );
                }
            }

            // Validate debits equal credits
            $totalDebits = 0;
            $totalCredits = 0;

            foreach ($lines as $line) {
                $totalDebits += (float) ($line['debit_amount'] ?? 0);
                $totalCredits += (float) ($line['credit_amount'] ?? 0);
            }

            if (abs($totalDebits - $totalCredits) > 0.01) {
                $validator->errors()->add('lines', sprintf(
                    'Journal entry is not balanced. Debits: %.2f, Credits: %.2f',
                    $totalDebits,
                    $totalCredits
                ));
            }
        });
    }
}
