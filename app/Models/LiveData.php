<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveData extends Model
{
    protected $table = 'live_data';
    protected $fillable = ['name','did','datasize','unit','conversion','conversion_method','range_from','range_to','category','vehicle_mapping_id','ecu_id','created_by'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
