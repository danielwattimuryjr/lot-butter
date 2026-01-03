<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'name' => ['required', 'string'],
            'phone_number' => ['nullable', 'string', 'digits:12'],
            'nip' => ['required', 'string', 'digits:11', Rule::unique('employees', 'nip')->ignore($employeeId)],
            'team_id' => ['required', 'exists:teams,id'],
        ];
    }
}
