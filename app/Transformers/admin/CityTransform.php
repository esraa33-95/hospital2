<?php

namespace App\Transformers\admin;

use App\Models\City;
use League\Fractal\TransformerAbstract;

class CityTransform extends TransformerAbstract
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
    public function transform(City $city):array
    {
        return [
            'id'=>$city->id,
            'name_ar'=>$city->translate('ar')->name,
            'name_en'=>$city->translate('en')->name,
        ];
    }
}
