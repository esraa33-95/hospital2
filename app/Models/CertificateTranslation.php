<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

 public function certificate()
{
    return $this->belongsTo(Certificate::class);
}



}
