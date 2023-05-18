<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineData extends Model
{
    protected $table = 'routine_control_data';
    protected $fillable = ['name','did','vehicle_mapping_id','ecu_id','created_by','active'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
