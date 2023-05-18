<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APK extends Model
{
    protected $table = 'apk_management';
    protected $fillable = ['version','apk_file','apk_path','remark'];

    // public function country_data()
    // {
    //     return $this->belongsTo(Country::class, 'country');
    // }
    // public function district()
    // {
    //     return $this->hasMany(District::class, 'state');
    // }
}
