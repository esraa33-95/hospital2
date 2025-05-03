<?php

namespace App\Http\Requests\Api\admin;

use App\Enum\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class Updatebyname extends FormRequest
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
           'user_type' => ['required', 'integer', Rule::in(array_column(UserType::cases(), 'value'))],
            'email' => 'required|email|exists:users,email',
            'name' => 'required|string|max:255',
        ];
    }
}
