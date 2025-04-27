<?php

namespace App\Http\Requests\Api\front\User;

use Illuminate\Foundation\Http\FormRequest;

class updateUser extends FormRequest
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
         
                'name'=>'nullable|string|min:3,max:255',
                'email' => 'nullable|email',
                'mobile' => 'nullable', 'regex:/^01[0125][0-9]{8}$/',
                'image'=>'nullable|mimes:png,jpg,jpeg',
                
                ];
    
    }
}
