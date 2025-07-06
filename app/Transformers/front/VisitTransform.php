<?php

namespace App\Transformers\front;

use App\Models\Visit;
use League\Fractal\TransformerAbstract;

class VisitTransform extends TransformerAbstract
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
     public function transform(Visit $visit):array
    {
       return [
              'id' => $visit->id,
              'visit_type_en' => $visit->translate('en')->visit_type,
              'visit_type_ar' => $visit->translate('ar')->visit_type,
              'min_price'=>$visit->min_price,
              'max_price'=>$visit->max_price,
              //'price'=>$visit->pivot->price,
             //'active'=>$visit->pivot->active,

             
               
        ];
    }
}
