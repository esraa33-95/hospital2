<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Department extends Model implements TranslatableContract
{
    use Translatable;
    
     public $translatedAttributes = ['name'];

    protected $fillable = [];

      public function users()
      {
        return $this->hasMany(User::class);
      }

       public function translates()
{
    return $this->hasMany(DepartmentTranslation::class);
}

}
