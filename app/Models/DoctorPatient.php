<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorPatient extends Model
{
    protected $fillable=[
        'doctor_id',
        'patient_id',
      ];
}
