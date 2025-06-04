<?php

namespace App\Transformers\front;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class userTransform extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user):array
    {
        return [
             'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'image' => $user->getFirstMediaUrl('image') ?: asset('storage/default.png'),
            'department_name' => ($user->user_type == 2 && $user->department) ? $user->department->name : null,
            'user_type' => $user->user_type,

           'certificates' =>$user->user_type == 2 ? $user->certificate->map(function ($certificate) {
                return [
                    'name_ar' => $certificate->translate('ar')->name,
                    'name_en' => $certificate->translate('en')->name,
                    
                ];
            }): null,

            'experiences' =>$user->user_type == 2 ? $user->experiences->map(function ($experience) {
                return [
                    'jobtitle_ar' => $experience->translate('ar')->jobtitle,
                    'jobtitle_en' => $experience->translate('en')->jobtitle,
                    'organization_ar' => $experience->translate('ar')->organization,
                    'organization_en' => $experience->translate('en')->organization,
                    
                ];
            }): null,

             'surgeries' =>$user->user_type == 3? $user->surgeries->map(function ($surgery) {
                return [
                    'name_ar' => $surgery->translate('ar')->name,
                    'name_en' => $surgery->translate('en')->name,
                    
                ];
            }): null,
        

           

        ];


    }
}
