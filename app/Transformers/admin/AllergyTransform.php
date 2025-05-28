<?php

namespace App\Transformers\admin;

use App\Models\Allergy;
use League\Fractal\TransformerAbstract;

class AllergyTransform extends TransformerAbstract
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
    public function transform(Allergy $allergy):array
    {
        return [
            'id' => $allergy->id,
              'name_en' => $allergy->translate('en')->name,
              'name_ar' => $allergy->translate('ar')->name,
        ];
    }
}
