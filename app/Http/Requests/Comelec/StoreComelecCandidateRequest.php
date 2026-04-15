<?php

namespace App\Http\Requests\Comelec;

use Illuminate\Foundation\Http\FormRequest;

class StoreComelecCandidateRequest extends FormRequest
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
            'position_name' => 'required|string',
            'full_name' => 'required|string|max:255',
            'course' => 'required|string',
            'year' => 'required|string',
            'political_party' => 'nullable|string|max:100',
            'platform_agenda' => 'nullable|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'position_name.required' => 'Please select a position.',
            'full_name.required' => 'Full name is required.',
            'course.required' => 'Course is required.',
            'year.required' => 'Year level is required.',
        ];
    }
}
