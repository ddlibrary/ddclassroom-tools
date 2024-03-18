<?php

namespace App\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;

class CreateMultipleStudentsScoreRequest extends FormRequest
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
            'grade_id' => ['required', 'exists:sub_grades,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'year' => ['required', 'exists:years,name'],
            'file' => ['required', 'mimes:xlsx, csv, xls'],
        ];
    }
}
