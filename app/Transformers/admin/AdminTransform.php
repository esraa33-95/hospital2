<?php

namespace App\Transformers\admin;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class AdminTransform extends TransformerAbstract
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
             'email' => $user->email,
             'password'=>$user->password,
        ];
    }
}
