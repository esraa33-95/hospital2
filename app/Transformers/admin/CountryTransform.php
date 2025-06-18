<?php

namespace App\Transformers\admin;

use App\Models\Country;
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
            'id' => $country->id,
              'name_en' => $country->translate('en')->name,
              'name_ar' => $country->translate('ar')->name,
        ];
    }
}
