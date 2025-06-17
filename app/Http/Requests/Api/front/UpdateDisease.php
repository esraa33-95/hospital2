<?php

namespace App\Http\Requests\Api\front;

use App\Models\DiseaseTranslation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDisease extends FormRequest
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
      $id = $this->disease; 

    return [
         'disease_id'=>'nullable|exists:diseases,id',
    ];
    }
}
