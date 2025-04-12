<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
  use SoftDeletes;
    protected $fillable=[
        'name',
        'email',
        'phone',
        'specialization',
        'department_id',
        'image',
  
      ];

      public function department()
      {
        return $this->belongsTo(Department::class);
      }

      public function shifts()
      {
        return $this->hasMany(Shift::class);
      }

      public function booking()
      {
        return $this->hasMany(Booking::class);
      }

     
}
