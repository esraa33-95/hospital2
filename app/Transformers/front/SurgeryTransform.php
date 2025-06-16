<?php

namespace App\Transformers\front;

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
             'id' => $surgery->id,
              'name_en' => $surgery->translate('en')->name,
              'name_ar' => $surgery->translate('ar')->name,
            
        ];
    }
}
