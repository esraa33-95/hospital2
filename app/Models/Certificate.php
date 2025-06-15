<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Certificate extends Model implements TranslatableContract,HasMedia
{
    use Translatable;
    use InteractsWithMedia;

    public $translatedAttributes = ['name'];

    protected $fillable=['user_id'];
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function translates()
{
    return $this->hasMany(CertificateTranslation::class);
}



    
}
