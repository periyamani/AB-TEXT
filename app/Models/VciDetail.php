<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VciDetail extends Model
{
    protected $table = 'vci_details';
    protected $fillable = ['name','vci_number','user_id','created_by','sold_status','active','updated_by'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
