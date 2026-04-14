<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteFingerprintStudentRequest extends FormRequest
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
            'user_id.required' => 'A user must be selected to delete.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()->route('view.finger-print', $this->fingerprintQueryParams())
                ->withErrors($validator)
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
