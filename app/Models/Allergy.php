<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $fillable =[
        'allergy_type',
         'user_id',
    ];

    public function users()
{
    return $this->belongsTo(User::class);
}

}
