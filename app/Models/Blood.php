<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Blood extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable=[ ];
 
    public function users()
{
    return $this->belongsToMany(User::class,'blood_user');
}

 public function translates()
{
    return $this->hasMany(BloodTranslation::class);
}
}
