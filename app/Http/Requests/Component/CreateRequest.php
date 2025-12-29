<?php

namespace App\Http\Requests\Component;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check()
            && Auth::user()->hasRole('employee')
            && Auth::user()->team->name === 'Production';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|max:10|unique:components,code',
            'safety_stock' => 'required|min:0',
            'name' => 'required|max:50',
            'weight' => 'required|numeric|min:0',
            'unit' => 'required|max:10',
            'category' => 'required|max:50',
        ];
    }
}
