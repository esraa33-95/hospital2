<?php

namespace App\Http\Requests\Api\admin;

use App\Models\SurgeryTranslation;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurgery extends FormRequest
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
            'name_en' => ['required',
            function ($attribute, $value, $error) {
                if (SurgeryTranslation::where('name', $value)->where('locale', 'en')->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'name_ar' => ['required',
            function ($attribute, $value, $error) {
                if (SurgeryTranslation::where('name', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],
        ];
              
    }
}
