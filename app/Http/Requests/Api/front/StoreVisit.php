<?php

namespace App\Http\Requests\Api\front;

use App\Models\Visit;
use Illuminate\Foundation\Http\FormRequest;

class StoreVisit extends FormRequest
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
           
            'visit_id' => ['required', 'exists:visits,id'],
            
            'price'=>['required','decimal:2',
            function ($attribute, $value, $error) 
            {
                 $visit = $this->input('visit_id');
                 
                if ($visit) 
                {
                    $visit = Visit::find($visit);

                    if ($visit) 
                    {
                        if ($value < $visit->min_price || $value > $visit->max_price) {
                            $error(__('validation.custom.price.required'));
                        }
                    }
                }
            }

        ],
    ];
    }
}
