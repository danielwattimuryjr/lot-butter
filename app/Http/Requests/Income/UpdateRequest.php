<?php

namespace App\Http\Requests\Income;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check()
            && Auth::user()->hasRole('employee')
            && Auth::user()->team->name === 'Finance';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'nullable|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'date_received' => 'required|date',
        ];
    }
}
