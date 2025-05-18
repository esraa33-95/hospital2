<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable =[
        'file_path',
        'file_type',
         'user_id',
    ];

    public function users()
      {
        return $this->belongsTo(User::class);
      }

      
}
