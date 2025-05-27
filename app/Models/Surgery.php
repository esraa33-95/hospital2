<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    protected $fillable =[
        'surgery_type',
        'user_id',
    ];

public function users()
{
    return $this->belongsTo(User::class);
}



}
