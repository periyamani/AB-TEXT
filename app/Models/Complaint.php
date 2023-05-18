<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $fillable = ['category','remark','dealer_id','open_date','close_date','active','status','updated_by','created_by','close_remark'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
