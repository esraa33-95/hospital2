<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
   
    public const DEPOSIT = 1;
    public const WITHDRAW = 2;
  

     protected $fillable=[
        'wallet_id',
        'amount',
        'status'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

}
