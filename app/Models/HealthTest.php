<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTest extends Model
{
    protected $table = 'healthtest_data';
    protected $fillable = ['field','customer_details','vehicle_details','dealer_id','dealer_comments','status','active'];
}
