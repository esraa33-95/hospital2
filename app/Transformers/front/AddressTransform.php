<?php

namespace App\Transformers\front;

use App\Models\Address;
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
    return [
            'id'=>$address->id,

             'country' => ( $address->countries ) ? $address->countries->map(function ($country) {
               return [
                'name_ar' => $country->translate('ar')->name,
                'name_en' => $country->translate('en')->name,
                  ];
           }) : null,

    'city' => ( $address->cities ) ?  $address->cities->map(function ($city) {
               return [
                'name_ar' => $city->translate('ar')->name,
                'name_en' => $city->translate('en')->name,
                  ];
           }) : null,

'area' => ( $address->areas ) ?  $address->areas->map(function ($area) {
               return [
                'name_ar' => $area->translate('ar')->name,
                'name_en' => $area->translate('en')->name,
                  ];
           }) : null,
     
            'street_name_ar'=>$address->translate('ar')->street_name,
            'street_name_en'=>$address->translate('en')->street_name,

            'building_number_ar'=>$address->translate('ar')->building_number,
             'building_number_en'=>$address->translate('en')->building_number,

            'floor_number_ar'=>$address->translate('ar')->floor_number,
             'floor_number_en'=>$address->translate('en')->floor_number,

            'landmark_ar'=>$address->translate('ar')->landmark,
             'landmark_en'=>$address->translate('en')->landmark,

            'lng'=>$address->lng,
            'lat'=>$address->lat,
            
        ];
    }
}
