<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFingerprintStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|string',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:191',
            'password' => 'nullable|min:6',
            'role' => 'required|in:student',
            'student_id' => 'required|string|max:50',
            'course' => 'required|string|max:191',
            'year_level' => 'required|string|max:50',
            'list_student_id' => 'nullable|string|max:255',
            'list_course' => 'nullable|string|max:255',
            'list_year_level' => 'nullable|string|max:50',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'middle_name.required' => 'Middle name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'We need an email address.',
            'email.email' => 'That email address does not look valid.',
            'password.min' => 'Password must be at least 6 characters.',
            'role.in' => 'Invalid role.',
            'student_id.required' => 'Student ID is required.',
            'course.required' => 'Course is required.',
            'year_level.required' => 'Year level is required.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()->route('view.finger-print', $this->fingerprintQueryParams())
                ->withErrors($validator)
                ->withInput()
                ->with('show_edit_modal', true)
        );
    }

    /**
     * @return array<string, string>
     */
    public function fingerprintQueryParams(): array
    {
        return array_filter([
            'student_id' => $this->input('list_student_id'),
            'course' => $this->input('list_course'),
            'year_level' => $this->input('list_year_level'),
        ], fn ($v) => $v !== null && $v !== '');
    }
}
