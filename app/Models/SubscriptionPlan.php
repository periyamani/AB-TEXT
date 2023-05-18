<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plan';
    protected $fillable = ['name','duration','amount'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
