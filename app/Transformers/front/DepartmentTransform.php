<?php

namespace App\Transformers\front;

use App\Models\Department;
use League\Fractal\TransformerAbstract;

class DepartmentTransform extends TransformerAbstract
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
    public function transform(Department $department):array
    {
        return [
             'id' => $department->id,
              'name_en' => $department->translate('en')->name,
              'name_ar' => $department->translate('ar')->name,
              'created_at'=>$department->created_at->toDateTimeString(),
        ];
    }
}
