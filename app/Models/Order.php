<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $fillable=[
        'address_id',
        'is_current'
       
     ];

    public function address()
{
    return $this->belongsTo(Address::class);
}
}
