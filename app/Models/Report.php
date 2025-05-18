<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable =[
        'report_name',
        'symptoms',
        'traitment',
        'user_id',
        'doctor_name',
    ];

    public function users()
      {
        return $this->belongsTo(User::class);
      }
}
