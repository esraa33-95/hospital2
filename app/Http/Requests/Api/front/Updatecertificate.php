<?php

namespace App\Http\Requests\Api\front;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CertificateTranslation;

class Updatecertificate extends FormRequest
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
   public function rules()
{
    $id = $this->Certificate; 

    return [
        'name_en' => [ 'nullable',
            function ($attribute, $value, $error) use ($id) {
                $en = CertificateTranslation::where('name', $value)
                    ->where('locale', 'en')
                    ->where('certificate_id', '!=', $id)
                    ->exists();

                if ($en) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'name_ar' => [ 'nullable',
            function ($attribute, $value, $error) use ($id) {
                $ar = CertificateTranslation::where('name', $value)
                    ->where('locale', 'ar')
                    ->where('certificate_id', '!=', $id)
                    ->exists();

                if ($ar) {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],
      
     'image' => 'nullable|mimes:pdf|max:5120',   
    ];
}

}