<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Otp extends Model
{
    protected $fillable = [
        'user_id', 
        'otp',
        'expires_at',
        'usage',
        
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
 public static function generateCode()
    {
      if(App::environment('production'))
      {
        return mt_rand(1000,9999);
      }
      else{
        return "1234";
      }
    }  
}

