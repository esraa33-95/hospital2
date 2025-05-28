<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllergyTranslation extends Model
{
     public $timestamps = false;

    protected $fillable = ['name'];


 public function allergy()
{
    return $this->belongsTo(Allergy::class);
}
}
