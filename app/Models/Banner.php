<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use Translatable;

    public $translatedAttributes = ['image', 'description'];

    protected $fillable = ['position','user_id'];

       public function users()
{
    return $this->belongsTo(User::class);
}

 public function translates()
{
    return $this->hasMany(BannerTranslation::class);
}


}
