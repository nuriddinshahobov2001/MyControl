<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditDebitRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'client_id' => 'required',
            'author_id' => 'required',
            'store_id' => 'required',
            'summa' => 'required|integer',
            'description' => 'nullable',
            'type' => 'in:credit,debit'
        ];
    }
}
