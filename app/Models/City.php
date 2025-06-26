<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class City extends Model implements TranslatableContract
{
     use Translatable;

public $translatedAttributes = [ 'name'];

     protected $fillable=[
        'country_id',
     ];

     public function address()
{
    return $this->hasMany(Address::class);
}

public function country()
{
    return $this->belongsTo(Country::class);
}

public function areas()
{
    return $this->hasMany(Area::class);
}

  public function translates()
{
    return $this->hasMany(CityTranslation::class);
}


}
