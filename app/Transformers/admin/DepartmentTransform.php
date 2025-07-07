<?php

namespace App\Transformers\admin;

use App\Models\Department;
use League\Fractal\TransformerAbstract;

class DepartmentTransform extends TransformerAbstract
{
   
  
    public function transform( Department $department):array
    {
        return [
              'id' => $department->id,
              'name_en' => $department->translate('en')->name,
              'name_ar' => $department->translate('ar')->name,
              //'created_at'=>$department->created_at->toDateTimeString(),
        ];
    }
}
