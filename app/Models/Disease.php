<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
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
    return $this->hasMany(DiseaseTranslation::class);
}

}
