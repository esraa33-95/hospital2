<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'user_id', 
        'rate',
    ]; 

    public function users()
    {
      return $this->belongsTo(User::class);
    }
}
