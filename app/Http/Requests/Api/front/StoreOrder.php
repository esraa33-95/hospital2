<?php

namespace App\Http\Requests\Api\front;

use Illuminate\Foundation\Http\FormRequest;
use App\Enum\OrderEnum;

class StoreOrder extends FormRequest
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
           'doctor_id' => 'required|exists:users,id',
            'visit_id'=>'required|exists:visits,id',
            'date'=>'required|date_format:Y-m-d',              
            'time'=>'required|date_format:H:i',
             'price'=>['required','decimal:2'], 
        ];
    }
}
