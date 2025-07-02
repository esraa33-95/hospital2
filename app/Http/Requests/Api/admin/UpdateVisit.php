<?php

namespace App\Http\Requests\Api\admin;

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

    return [
        'visit_type_en' => [ 'nullable','string','max:255' ],
        'visit_type_ar' => [ 'nullable','string','max:255' ],

        'min_price' => ['nullable','decimal:2','min:0'],
        'max_price' => ['nullable','decimal:2','gt:min_price'],

    ];
    
    }
}
