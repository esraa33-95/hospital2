<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Experience extends Model implements TranslatableContract
{
     use Translatable;

public $translatedAttributes = [ 'jobtitle','organization'];

    protected $fillable=[ 'current','user_id'];

      public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function translates()
{
    return $this->hasMany(ExperienceTranslation::class);
}

}
