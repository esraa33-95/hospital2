<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Banner extends Model implements TranslatableContract
{
    use Translatable;
  
    public $translatedAttributes = ['description','image'];

    protected $fillable = [  
        'position',
    ];

     public function translates()
{
    return $this->hasMany(BannerTranslation::class);
}

}
