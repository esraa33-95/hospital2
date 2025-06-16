<?php

namespace App\Transformers\front;

use App\Models\Blood;
use League\Fractal\TransformerAbstract;

class BloodTransform extends TransformerAbstract
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
   public function transform(Blood $blood)
    {
        return [
             'id' => $blood->id,
              'name_en' => $blood->translate('en')->name,
              'name_ar' => $blood->translate('ar')->name,
        ];
    }
}
