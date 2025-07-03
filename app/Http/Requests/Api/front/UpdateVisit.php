<?php

namespace App\Http\Requests\Api\front;

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
        $user = auth()->user();

        return [
            'user_id'=>$user,
            'visit_id'=>'nullable|exists:visits,id',
            'price'=>'nullable|decimal:2',
            'active'=>'nullable|boolean',
        ];
    }
}
