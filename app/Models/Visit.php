<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Visit extends Model implements TranslatableContract
{
    use Translatable;

   public $translatedAttributes = ['visit_type'];

    protected $fillable=['min_price', 'max_price'];

   public function doctors()
{
    return $this->belongsToMany(User::class,'visit_doctors')
                 ->withPivot('active','price')->withTimestamps();
}

    public function translates()
{
    return $this->hasMany(VisitTranslation::class);
}

  public function orders()
{
    return $this->hasMany(Order::class);
}


}
