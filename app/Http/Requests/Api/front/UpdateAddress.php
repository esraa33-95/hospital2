<?php

namespace App\Http\Requests\Api\front;

use App\Models\AddressTranslation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddress extends FormRequest
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
           $id = $this->address; 

    return [
        'street_name_en' => [ 'nullable','string','max:100',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('street_name', $value)
                    ->where('locale', 'en')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'street_name_ar' => [ 'nullable','string','max:100',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('street_name', $value)
                    ->where('locale', 'ar')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_ar.unique' ));
                }
            }
        ],

'building_number_en' => [ 'nullable','string','max:15',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('building_number', $value)
                    ->where('locale', 'en')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
'building_number_ar' => [ 'nullable','string','max:15',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('building_number', $value)
                    ->where('locale', 'ar')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_ar.unique' ));
                }
            }
        ],

'floor_number_en' => [ 'nullable','string','max:15',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('floor_number', $value)
                    ->where('locale', 'en')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
'floor_number_ar' => [ 'nullable','string','max:15',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('floor_number', $value)
                    ->where('locale', 'ar')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_ar.unique' ));
                }
            }
        ],



'landmark_en' => [ 'nullable','string','max:100',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('landmark', $value)
                    ->where('locale', 'en')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'landmark_ar' => [ 'nullable','string','max:100',
            function ($attribute, $value, $error) use ($id) {
                $exists = AddressTranslation::where('landmark', $value)
                    ->where('locale', 'ar')
                    ->where('address_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_ar.unique' ));
                }
            }
        ],

            'city_id'=>'nullable|exists:cities,id',
            'country_id'=>'nullable|exists:countries,id',
            'area_id'=>'nullable|exists:areas,id',
            'lng'=>'nullable|numeric',
            'lat'=>'nullable|numeric',

    ];


    }
}
