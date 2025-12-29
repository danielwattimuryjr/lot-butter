<?php

namespace App\Http\Requests\BillOfMaterial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $productId = $this->route('product')->id;

        return [
            'component_id' => [
                'required',
                'exists:components,id',
                Rule::unique('bills_of_materials', 'component_id')
                    ->where('product_id', $productId)
            ],
            'quantity' => 'required|decimal:1,4|min:0',
        ];
    }
}
