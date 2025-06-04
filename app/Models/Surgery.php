<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Surgery extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable=[
        'user_id',
       
    ];
   
public function users()
{
    return $this->belongsTo(User::class);
}

   public function translates()
{
    return $this->hasMany(SurgeryTranslation::class);
}


}
