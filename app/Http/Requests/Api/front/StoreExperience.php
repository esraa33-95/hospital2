<?php

namespace App\Http\Requests\Api\front;

use App\Models\CertificateTranslation;
use App\Models\Experience;
use App\Models\ExperienceTranslation;
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

            'jobtitle_en' => ['required',
            function ($attribute, $value, $error) {
                if (ExperienceTranslation::where('jobtitle', $value)->where('locale', 'en')  
                    ->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],

        'jobtitle_ar' => ['required',
            function ($attribute, $value, $error) {
                if (ExperienceTranslation::where('jobtitle', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],

           'organization_en' => ['required',
            function ($attribute, $value, $error) {
                if (ExperienceTranslation::where('organization', $value)->where('locale', 'en')  
                    ->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],

        'organization_ar' => ['required',
            function ($attribute, $value, $error) {
                if (ExperienceTranslation::where('organization', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],  
            'current'=>['required','boolean'],   
        ];
    }
}
