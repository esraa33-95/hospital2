<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPassword extends FormRequest
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
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed|different:current_password',
        ];
    }


    // public function messages(): array
    // {
    //     return [
    //         'current_password.required' => 'الرجاء إدخال كلمة المرور الحالية',
    //         'current_password.min' => 'كلمة المرور الحالية يجب أن تكون على الأقل 6 أحرف',
    //         'new_password.required' => 'الرجاء إدخال كلمة المرور الجديدة',
    //         'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون على الأقل 6 أحرف',
    //         'new_password.different' => 'كلمة المرور الجديدة يجب أن تكون مختلفة عن الحالية',
    //     ];
    // }
}
