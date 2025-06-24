<?php

namespace App\Http\Requests\Api\admin;

use App\Models\BannerTranslation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBanner extends FormRequest
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
          $id = $this->banner; 

        return [
            'description_en' => ['nullable',
            function ($attribute, $value, $error) use ($id) {
                $exists = BannerTranslation::where('description', $value)
                    ->where('locale', 'en')
                    ->where('banner_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.description_en.unique'));
                }
            }
        ],

        'description_ar' => ['nullable',
           function ($attribute, $value, $error) use ($id) {
                $exists = BannerTranslation::where('description', $value)
                    ->where('locale', 'ar')
                    ->where('banner_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.description_ar.unique'));
                }
            }
        ],

          'image_ar' => ['nullable','mimes:png,jpg,jpeg','max:2048'],
            'image_en'=>['nullable','mimes:png,jpg,jpeg','max:2048'],
           'position' => 'nullable|in:doctor,patient',
        ];
    }
}
