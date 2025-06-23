<?php

namespace App\Transformers\front;

use App\Models\Country;
use Illuminate\Support\Arr;
use League\Fractal\TransformerAbstract;

class CountryTransform extends TransformerAbstract
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
    public function transform(Country $country):array
    {
        return [
            'id'=>$country->id,
            'name_ar'=>$country->translate('ar')->name,
            'name_en'=>$country->translate('en')->name,
        ];
    }
}
