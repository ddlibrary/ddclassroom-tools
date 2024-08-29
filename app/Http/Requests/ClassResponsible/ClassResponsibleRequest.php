<?php

namespace App\Http\Requests\ClassResponsible;

use Illuminate\Foundation\Http\FormRequest;

class ClassResponsibleRequest extends FormRequest
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
            'sub_grade_id' => ['required', 'numeric', 'exists:sub_grades,id'],
            'teacher_id' => ['required', 'numeric', 'exists:users,id'],
            'year' => ['required'],
        ];
    }
}
