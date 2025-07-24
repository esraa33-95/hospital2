<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
       protected $fillable=[
        'user_id',
        'total_price',
         'pending_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class );
    }


     public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
