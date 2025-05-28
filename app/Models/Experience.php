<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable =[
        'jobtitle',
        'organization',
        'current',
         'user_id',
       
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
