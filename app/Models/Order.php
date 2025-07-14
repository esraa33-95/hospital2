<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\OrderEnum;

class Order extends Model
{
  
public const STATUS = 'status';

    public const WAITING = 1;
    public const ACCEPTED = 2;
    public const REJECTED = 3;

    protected $fillable =[
        'user_id',
        'doctor_id',
        'visit_id',
        'date',
        'time',
        'status',  
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    
   
    
}
