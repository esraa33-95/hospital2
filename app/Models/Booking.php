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
}
