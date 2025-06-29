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
            'area_id'=>'required|exists:areas,id',

            'lat' => 'required|decimal:1|between:-90,90',
            'lng' => 'required|decimal:1|between:-180,180',

         'building_number_en'=> ['required','string','max:15'],

         'building_number_ar' => ['required','string','max:15'],

        'floor_number_en'=>['required','string','max:15' ],
        'floor_number_ar' => ['required','string','max:15'],


         'landmark_en'=>['nullable','string','max:100'],
        'landmark_ar' => ['nullable','string','max:100'],

        'street_name_en' => ['required','string','max:100' ],

        'street_name_ar' => ['required','string','max:100' ],
        
    
   
        ];
    }
}
