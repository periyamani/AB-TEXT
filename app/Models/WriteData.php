<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WriteData extends Model
{
    protected $table = 'write_data';
    protected $fillable = ['name','did','hint','security','conversion','range','default_value','vehicle_mapping_id','method','created_by','active','ecu_id'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
