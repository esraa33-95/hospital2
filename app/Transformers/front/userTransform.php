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

           

        //     'certificates' => ($user->user_type == 2 && $user->certificate) ? $user->certificate->map(function ($certificate) {
        //        return [
        //         'name_ar' => $certificate->translate('ar')->name,
        //         'name_en' => $certificate->translate('en')->name,
        //           ];
        //    }) : null,

        //      'experiences' => ($user->user_type == 2 && $user->experiences) ? $user->experiences->map(function ($experience) {
        //        return [
        //         'name_ar' => $experience->translate('ar')->name,
        //         'name_en' => $experience->translate('en')->name,
        //           ];
        //    }) : null,
        

//  'diseases' => ($user->user_type == 3 && $user->diseases) ? $user->diseases->map(function ($disease) {
//                return [
//           'name_ar' => $disease->translate('ar')->name,
//           'name_en' => $disease->translate('en')->name,
//                   ];
//            }) : null,

//   'allergy' => ($user->user_type == 3 && $user->allergies) ? $user->allergies->map(function ($allergy) {
//                 return [
       
//          'name_ar' => $allergy->translate('ar')->name,
//         'name_en' => $allergy->translate('en')->name,
//             ];
//            }) : null,


//    'surgeries' => ($user->user_type == 3 && $user->surgeries) ? $user->surgeries->map(function ($surgery) {
//                return [
       
//         'name_ar' => $surgery->translate('ar')->name,
//          'name_en' => $surgery->translate('en')->name,
//                   ];
//            }) : null,         
           


        //    'blood' => ($user->user_type == 3 && $user->blood) ? $user->blood->map(function ($blood) {
        //        return [
       
        // 'name_ar' => $blood->translate('ar')->name,
        // 'name_en' => $blood->translate('en')->name,
        //           ];
        //    }) : null, 


        ];


    }
}
