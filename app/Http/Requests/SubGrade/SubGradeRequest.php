<?php

namespace App\Http\Requests\SubGrade;

use Illuminate\Foundation\Http\FormRequest;

class SubGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'level' => ['nullable', 'string'],
            'grade_id' => ['required', 'numeric', 'exists:grades,id'],
            'location' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'year' => ['required'],
        ];
    }
}
