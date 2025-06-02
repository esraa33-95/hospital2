<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceTranslation extends Model
{
    public $timestamps = false;

    protected $fillable=[
        'jobtitle',
        'organization',
        
    ];


 public function experience()
{
    return $this->belongsTo(Experience::class);
}





}
