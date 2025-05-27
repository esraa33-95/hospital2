<?php

namespace App\Transformers\admin;

use App\Models\Surgery;
use League\Fractal\TransformerAbstract;

class SurgeryTransform extends TransformerAbstract
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
    public function transform(Surgery $surgery):array
    {
        return [
            'surgery_type'=>$surgery->surgery_type,
            'user_id'=>$surgery->user_id,
            
        ];
    }
}
