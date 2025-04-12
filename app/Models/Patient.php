<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
 use SoftDeletes;
    protected $fillable=[
        'name',
        'email',
        'phone',
        'image',
      ];

      public function booking()
      {
        return $this->hasMany(Booking::class);
      }

      
}
