<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable=[
        'doctor_id',
        'shift_id',
        'patient_id',
        'status',

      ];

      public function doctors()
      {
        return $this->belongsTo(Doctor::class);
      }

      public function patients()
      {
        return $this->belongsTo(Patient::class);
      }

      public function shifts()
      {
        return $this->belongsTo(Shift::class);
      }
}
