<?php

namespace App\Transformers\front;

use App\Models\Address;
use Illuminate\Support\Facades\App;
use League\Fractal\TransformerAbstract;

class AddressTransform extends TransformerAbstract
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
    public function transform(Address $address):array
    {
    $locale = App::getLocale(); 

    return [
        'id' => $address->id,

        'country' => ($address->area && $address->area->city && $address->area->city->country)
            ? $address->area->city->country->translate($locale)->name
             : null,

        'city' => ($address->area && $address->area->city) 
                 ? $address->area->city->translate($locale)->name : null,
        'area' =>( $address->area ) ? $address->area->translate($locale)->name : null,
     
        'street_name' => $address->translate($locale)->street_name,
        'building_number' => $address->translate($locale)->building_number,
        'floor_number' => $address->translate($locale)->floor_number,
        'landmark' => $address->translate($locale)->landmark,

            
        ];
    }
}
