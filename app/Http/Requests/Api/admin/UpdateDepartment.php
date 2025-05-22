<?php

namespace App\Http\Requests\Api\admin;

use App\Models\DepartmentTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartment extends FormRequest
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
    $id = $this->department; 

    return [
        'name_en' => [ 'nullable',
            function ($attribute, $value, $error) use ($id) {
                $exists = DepartmentTranslation::where('name', $value)
                    ->where('locale', 'en')
                    ->where('department_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_en.unique'));
                }
            }
        ],
        'name_ar' => [ 'nullable',
            function ($attribute, $value, $error) use ($id) {
                $exists = DepartmentTranslation::where('name', $value)
                    ->where('locale', 'ar')
                    ->where('department_id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.name_ar.unique'));
                }
            }
        ],
    ];
}

}
