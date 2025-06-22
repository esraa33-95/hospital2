<?php

namespace App\Http\Requests\Api\front;

use App\Models\AddressTranslation;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddress extends FormRequest
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
            'city_id'=>'required|exists:cities,id',
            'country_id'=>'required|exists:countries,id',
            'area_id'=>'required|exists:areas,id',

            'lat' => 'required|decimal:1|between:-90,90',
            'lng' => 'required|decimal:1|between:-180,180',

            'building_number_en'=> ['required','string','max:15',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('building_number', $value)->where('locale', 'en')  
                    ->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],

         'building_number_ar' => ['required','string','max:15',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('building_number', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],

        'floor_number_en'=>['required','string','max:15',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('floor_number', $value)->where('locale', 'en')  
                    ->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
       'floor_number_ar' => ['required','string','max:15',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('floor_number', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],


         'landmark_en'=>['nullable','string','max:100',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('landmark', $value)->where('locale', 'en')  
                    ->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'landmark_ar' => ['nullable','string','max:100',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('landmark', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],

        'street_name_en' => ['required','string','max:100',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('street_name', $value)->where('locale', 'en')  
                    ->exists()) 
                {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],

        'street_name_ar' => ['required','string','max:100',
            function ($attribute, $value, $error) {
                if (AddressTranslation::where('street_name', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],
   
        ];
    }
}
