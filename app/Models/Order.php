<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =[
        'user_id',
        'doctor_id',
        'visit_id',
        'date',
        'time',
        'status',  
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
