<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaTranslation extends Model
{
    public $timestamps = false;
     
     protected $fillable=[
        'name',
        
    ];

 public function area()
{
    return $this->belongsTo(Area::class);
}



}
