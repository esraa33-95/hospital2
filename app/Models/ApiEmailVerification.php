<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiEmailVerification extends Model
{
    protected $table = 'api_email_verification';
    protected $fillable=[
        'user_id',
         'evcode'
        ];
   
        public function users()
        {
            return $this->belongsTo(User::class);
        }

}
