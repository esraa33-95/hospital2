<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiseaseTranslation extends Model
{
      public $timestamps = false;

    protected $fillable = ['name'];


 public function disease()
{
    return $this->belongsTo(Disease::class);
}
}
