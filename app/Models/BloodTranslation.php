<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodTranslation extends Model
{
     public $timestamps = false;

    protected $fillable = ['name'];


 public function blood()
{
    return $this->belongsTo(Blood::class);
}

}
