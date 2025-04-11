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

      public function patient()
      {
        return $this->belongsToMany(Patient::class);
      }
}
