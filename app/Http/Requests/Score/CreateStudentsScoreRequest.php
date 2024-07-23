<?php

namespace App\Http\Requests\Score;

use App\Enums\SubjectMinScoreEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateStudentsScoreRequest extends FormRequest
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
        $max = request()->type_id == 1 ? SubjectMinScoreEnum::Middle->value: SubjectMinScoreEnum::Final->value;
        return [
            'grade_id' => ['required', 'exists:sub_grades,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'year' => ['required', 'exists:years,name'],

            'students' => 'required|array',
            'student_id.*' => 'required|string',
            'written.*' => "required|numeric|min:0|max:$max",
            'oral.*' => "required|numeric|min:0|max:$max",
            'attendance.*' => "required|numeric|min:0|max:$max",
            'activity.*' => "required|numeric|min:0|max:$max",
            'homework.*' => "required|numeric|min:0|max:$max",
            'evaluation.*' => "required|numeric|min:0|max:$max",
            'total.*' => "required|numeric|min:0|max:$max",
            'type_id' => 'required|string',


        ];
    }
}
