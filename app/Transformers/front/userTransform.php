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
           'certificate_name'=>($user->user_type == 2 && $user->certificate) ? $user->certificate->name: null,
        ];
    }
}
