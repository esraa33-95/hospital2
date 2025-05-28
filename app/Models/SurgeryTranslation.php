<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurgeryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];


 public function surgery()
{
    return $this->belongsTo(Surgery::class);
}



   
}
