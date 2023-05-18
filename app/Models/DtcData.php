<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DtcData extends Model
{
    protected $table = 'dtc_data';
    protected $fillable = ['name','description','dtc_code','troubleshoot','vehicle_mapping_id','ecu_id','active','created_by'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
