<?php

namespace App\Http\Requests\Api\front;

use App\Models\VisitTranslation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVisit extends FormRequest
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
      $id = $this->visit;

        return [
           'visit_type_en' => [ 'nullable','string',
            function ($attribute, $value, $error) use ($id) {
                $exists = VisitTranslation::where('visit_type', $value)
                    ->where('locale', 'en')
                    ->where('visit_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.visit_type_en.unique'));
                }
            }
        ],
           
        'visit_type_ar' => [ 'nullable','string',
            function ($attribute, $value, $error) use ($id) {
                $exists = VisitTranslation::where('visit_type', $value)
                    ->where('locale', 'ar')
                    ->where('visit_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.visit_type_en.unique'));
                }
            }
        ],
            
            'min_price'=>'nullable|decimal:2',
            'max_price'=>'nullable|decimal:2|gt:min_price',
            
        ];
          

    }
}
