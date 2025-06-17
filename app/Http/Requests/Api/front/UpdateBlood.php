<?php

namespace App\Http\Requests\Api\front;

use App\Models\BloodTranslation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlood extends FormRequest
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
            'blood_id'=>'nullable|exists:bloods,id',
        ];
    }
}
