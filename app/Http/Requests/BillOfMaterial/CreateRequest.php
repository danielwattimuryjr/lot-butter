<?php

namespace App\Http\Requests\BillOfMaterial;

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
            'components' => 'required|array|min:1',
            'components.*.component_id' => 'required|exists:components,id',
            'components.*.quantity' => 'required|numeric|min:0',
            'components.*.level' => 'required|in:0,1,2',
        ];
    }
}
