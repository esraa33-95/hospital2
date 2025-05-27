<?php

namespace App\Http\Requests\Api\admin;

use App\Models\Experience;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExperience extends FormRequest
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
            'jobtitle' => [ 'nullable',
            function ($attribute, $value, $error)  {
                $exists = Experience::where('jobtitle', $value)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.jobtitle.unique'));
                }
            }
        ],

        'organization' => [ 'nullable',
            function ($attribute, $value, $error)  {
                $exists = Experience::where('organization', $value)
                    ->exists();

                if ($exists) {
                    $error(__('validation.custom.organization.unique'));
                }
            }
        ],
           
             'current'=>'nullable|boolean',
              'uuid' => 'nullable|uuid|exists:users,uuid',
           
        ];
    }
}
