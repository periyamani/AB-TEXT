<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTestStructure extends Model
{
    protected $table = 'health_test_structure';
    protected $fillable = ['name','vin_number','field','active','status'];
}
