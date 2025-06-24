<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Banner extends Model implements TranslatableContract,HasMedia
{
    use Translatable;
    use InteractsWithMedia;
  
    public $translatedAttributes = ['description'];

    protected $fillable = [  
        'position',
    ];

     public function translates()
{
    return $this->hasMany(BannerTranslation::class);
}

}
