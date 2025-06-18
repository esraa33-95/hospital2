<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AddressTranslation extends Model 
{

public $timestamps = false;

 protected $fillable=[
        
        'street_name',
        'building_number',
        'floor_number',
        'landmark',
     ];



 public function address()
{
    return $this->belongsTo(Address::class);
}

}
