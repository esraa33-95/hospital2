<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentTranslation extends Model
{
   public $timestamps = false;

   protected $fillable = ['name'];


    public function department()
{
    return $this->belongsTo(Department::class);
}

}


