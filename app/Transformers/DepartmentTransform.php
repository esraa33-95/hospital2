<?php

namespace App\Transformers;

use App\Models\Department;
use League\Fractal\TransformerAbstract;

class DepartmentTransform extends TransformerAbstract
{
   
  
    public function transform( Department $department):array
    {
        return [
              'id' => $department->id,
              'name' => $department->name,
        ];
    }
}
