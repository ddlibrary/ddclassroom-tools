<?php

namespace App\Http\Requests\Result;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResultRequest extends FormRequest
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
            'name' => ['required', 'string',  $this->id ? 'unique:results,name,'.$this->id : 'unique:results,name'],
            'from' => ['required', 'numeric', 'min:0', 'max:100'],
            'to' => ['required', 'numeric', 'min:0', 'max:100'],
            'result' => ['required', Rule::in(['کامیاب', 'ناکام'])],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
