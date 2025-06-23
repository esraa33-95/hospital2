<?php

namespace App\Transformers\front;

use App\Models\Area;
use League\Fractal\TransformerAbstract;

class AreaTransform extends TransformerAbstract
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
    public function transform(Area $area):array
    {
        return [
             'id'=>$area->id,
            'name_ar'=>$area->translate('ar')->name,
            'name_en'=>$area->translate('en')->name,
        ];
    }
}
