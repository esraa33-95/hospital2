<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Allergy extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable=[];
 
    public function users()
{
    return $this->belongsToMany(User::class,'allergy_user');
}

 public function translates()
{
    return $this->hasMany(SurgeryTranslation::class);
}

}
