<?php

namespace App\Http\Requests\Comelec;

use Illuminate\Foundation\Http\FormRequest;

class DeleteComelecCandidateRequest extends FormRequest
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
            'candidate_id' => 'required|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'candidate_id.required' => 'Candidate is required.',
        ];
    }
}
