<?php

namespace App\Http\Requests\Api\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperience extends FormRequest
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
            'jobtitle'=>'required|string|max:255',
            'organization'=>'required|string|max:255',
            'current'=>'required|boolean',
            'uuid' => 'required|uuid|exists:users,uuid',
            
        ];
    }
}
