<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable=[
        
        'doctor_id',
        'start_time',
        'end_time',
        'day', 
      ];

      public function doctors()
      {
        return $this->belongsTo(Doctor::class);
      }

      public function booking()
      {
        return $this->hasMany(Booking::class);
      }
}
