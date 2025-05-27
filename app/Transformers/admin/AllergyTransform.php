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
            'allergy_type'=>$allergy->allergy_type,
            'user_id'=>$allergy->user_id,
        ];
    }
}
