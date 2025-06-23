<?php

namespace App\Http\Requests\Api\admin;

use App\Models\BannerTranslation;
use Illuminate\Foundation\Http\FormRequest;

class StoreBanner extends FormRequest
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
            'description_en' => ['required',
            function ($attribute, $value, $error) {
                if (BannerTranslation::where('description', $value)->where('locale', 'en')->exists()) 
                {
                    $error(__('validation.custom.description_en.unique'));
                }
            }
        ],

        'description_ar' => ['required',
            function ($attribute, $value, $error) {
                if (BannerTranslation::where('description', $value)->where('locale', 'ar')->exists())
                 {
                    $error(__('validation.custom.description_ar.unique'));
                }
            }
        ],



          'image_ar' => ['required','mimes:png,jpg,jpeg','max:2048',
           function ($attribute, $value, $fail) {
                if ($value && $value->isValid()) {

                    $image = $value->getClientOriginalName();
                    if (BannerTranslation::where('image', $image)->where('locale', 'ar')->exists()) {
                        $fail(__('validation.custom.image_ar.unique'));
                    }
                }
            }
        ],
            'image_en'=>['required','mimes:png,jpg,jpeg','max:2048',
          function ($attribute, $value, $fail) {
            
                if ($value && $value->isValid()) {
                    $image = $value->getClientOriginalName();
                    if (BannerTranslation::where('image', $image)->where('locale', 'en')->exists()) {
                        $fail(__('validation.custom.image_en.unique'));
                    }
                }
            }
        ],
           'position' => 'required|in:doctor,patient',
        ];
    }
}
