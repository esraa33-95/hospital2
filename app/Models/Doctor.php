<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable=[
        'name',
        'email',
        'phone',
        'specialization',
        'department_id',
        'image',
  
      ];
}
