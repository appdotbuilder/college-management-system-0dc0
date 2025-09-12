<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasAdminAccess();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('student')->id,
            'password' => 'nullable|string|min:8',
            'nric_no' => 'required|string|max:20|unique:users,nric_no,' . $this->route('student')->id,
            'program_id' => 'required|exists:programs,id',
            'intake_date' => 'required|date',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered to another student.',
            'nric_no.required' => 'NRIC number is required.',
            'nric_no.unique' => 'This NRIC number is already registered to another student.',
            'program_id.required' => 'Please select a program.',
            'program_id.exists' => 'The selected program is invalid.',
            'intake_date.required' => 'Intake date is required.',
        ];
    }
}