<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Banner extends Model implements TranslatableContract
{
    use Translatable;
   

    public $translatedAttributes = ['description'];

    protected $fillable = [
        'image_right',
        'image_left',
        'position',
        'direction',
    ];

     public function translates()
{
    return $this->hasMany(BannerTranslation::class);
}

}
