<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Address extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = [ 'street_name','building_number', 'floor_number','landmark'];

      protected $fillable=[
        'user_id',
        'country_id',
        'city_id',
        'area_id',
        'lat',
        'lng',
        'is_main'
       
     ];

     public function user()
{
    return $this->belongsTo(User::class);
}

public function country()
{
    return $this->belongsTo(Country::class);
}
public function city()
{
    return $this->belongsTo(City::class);
}

public function area()
{
    return $this->belongsTo(Area::class);
}

 public function translates()
{
    return $this->hasMany(AddressTranslation::class);
}

    public function order()
{
    return $this->hasMany(Order::class);
}

}
