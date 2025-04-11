<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
 
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
