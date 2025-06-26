<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Area extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable =[
        'city_id',
];

public function address()
{
    return $this->hasMany(Address::class);
}

public function city()
{
  return $this->belongsTo(City::class);
}

  public function translates()
{
    return $this->hasMany(AreaTranslation::class);
}

}
