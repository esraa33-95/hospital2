<?php

namespace App\Http\Requests\Api\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDepartment extends FormRequest
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
        'name_en' => [ 'required', 'string', 'max:255',
            Rule::unique('department_translations', 'name')->where('locale', 'en'),
        ],
        'name_ar' => [ 'required','string','max:255',
            Rule::unique('department_translations', 'name')->where('locale', 'ar'),
        ],
     
    ];

   
}
}
