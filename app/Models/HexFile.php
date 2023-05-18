<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HexFile extends Model
{
    protected $table = 'hex_file';
    protected $fillable = ['vehicle_id','ecu_id','name','hex_file','remark','file_status','file_size','file_path','status','created_by','updated_by','approved_by','approver_remark'];
}