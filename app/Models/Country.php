<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Country extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

     protected $fillable=[
     ];

public function address()
{
    return $this->hasMany(Address::class);
}

public function cities()
{
    return $this->hasMany(City::class);
}

  public function translates()
{
    return $this->hasMany(CountryTranslation::class);
}

     
}
