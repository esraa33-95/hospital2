<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerTranslation extends Model
{
     public $timestamps = false;

    protected $fillable = [
        'description'   
    ];

 public function banner()
{
    return $this->belongsTo(Banner::class);
}




}
