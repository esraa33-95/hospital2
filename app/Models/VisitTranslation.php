<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitTranslation extends Model
{
    public $timestamps = false;

    protected $fillable=['visit_type'];


 public function visit()
{
    return $this->belongsTo(Visit::class);
}
}
