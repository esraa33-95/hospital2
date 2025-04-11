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
}
