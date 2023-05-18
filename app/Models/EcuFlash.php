<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcuFlash extends Model
{
    protected $table = 'ecu_flash_report';
    protected $fillable = ['dealer_email','model_name','varient_name','vci_serial','vin',
    'cal_id_old','cal_id_new','cvn_old','cvn_new','odometer','gps','duration','status',
    'created_by'];
}
