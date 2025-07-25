<?php

namespace App\Http\Requests\Api\front;

use App\Models\AllergyTranslation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAllergy extends FormRequest
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
            $id = $this->allergy; 

    return [
        'name_en' => [ 'nullable',
            function ($attribute, $value, $error) use ($id) {
                $exists = AllergyTranslation::where('name', $value)
                    ->where('locale', 'en')
                    ->where('allergy_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'name_ar' => [ 'nullable',
            function ($attribute, $value, $error) use ($id) {
                $exists = AllergyTranslation::where('name', $value)
                    ->where('locale', 'ar')
                    ->where('allergy_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_ar.unique' ));
                }
            }
        ],
    ];

    
    }
}
