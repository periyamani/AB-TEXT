<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleFunction extends Model
{
    protected $table = 'functionality_data';
    protected $fillable = ['name'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
