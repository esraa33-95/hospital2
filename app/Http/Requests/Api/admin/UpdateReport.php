<?php

namespace App\Http\Requests\Api\admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReport extends FormRequest
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
             'report_name'=>'nullable|string|max:255',
             'symptoms'=>'nullable|string',
            'traitment'=>'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
