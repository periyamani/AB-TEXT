<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcuDetail extends Model
{
    protected $table = 'ecu_details';
    protected $fillable = ['name','serial_no','oem','created_by','ecu_functionality'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
