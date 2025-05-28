<?php

namespace App\Transformers\Admin;

use App\Models\Disease;
use League\Fractal\TransformerAbstract;

class DiseaseTransform extends TransformerAbstract
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
    public function transform(Disease $disease)
    {
        return [
               'id' => $disease->id,
              'name_en' => $disease->translate('en')->name,
              'name_ar' => $disease->translate('ar')->name,
        ];
    }
}
