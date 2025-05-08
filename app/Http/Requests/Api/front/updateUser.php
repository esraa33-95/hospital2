<?php

namespace App\Http\Requests\Api\front;

use App\Enum\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
         
                'name'=>'nullable|string|max:255',
                'email' => 'nullable|email',
                'mobile' => 'nullable','regex:/^01[0125][0-9]{8}$/',
                'image'=>'nullable|mimes:png,jpg,jpeg|max:2048',

                'department_id' => [
                     Rule::requiredIf(function () {
                     return request('user_type') == UserType::Doctor->value;
                   }),'nullable','exists:departments,id'],

                 'user_type' => ['nullable', 'integer', Rule::in(array_column(UserType::cases(), 'value'))],       
                
                ];
    }
}
