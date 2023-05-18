<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleDetail extends Model
{
    protected $table = 'vehicle_details';
    protected $fillable = ['name','vehicle_type_id','model','image','ecu_id','vin','created_at','image_path'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
