<?php

namespace App\Imports;

use App\Models\WriteData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WriteDataImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new WriteData([
            'did'     => $row['did'],
            'name'    => $row['name'], 
            'security'     => $row['security'],
            'hint'    => $row['hint'], 
            'conversion'     => $row['conversion'],
            'range'    => $row['range'], 
            'default_value'    => $row['default_value'], 
            'vehicle_mapping_id'    => $row['vehicle_mapping_id'], 
            'method'    => $row['method'], 
            'ecu_id' => $row['ecu_id'], 
            'created_by' => auth()->user()->id,
        ]);
    }
}
