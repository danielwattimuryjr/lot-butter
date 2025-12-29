<?php

namespace App\Http\Requests\Procurement;

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
            && Auth::user()->team->name === 'Procurement';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'component_id' => 'sometimes|exists:components,id',
            'description' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|numeric|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
            'date' => 'sometimes|date',
            'supplier' => 'nullable|string|max:255',
        ];
    }
}
