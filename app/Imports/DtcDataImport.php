<?php

namespace App\Imports;

use App\Models\DtcData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DtcDataImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DtcData([
            'did'     => $row['did'],
            'description'    => $row['description'], 
            'troubleshoot'     => $row['troubleshoot'],
            'vehicle_mapping_id'    => $row['vehicle_mapping_id'], 
            'ecu_id' => $row['ecu_id'], 
            'created_by' => auth()->user()->id,
        ]);
    }
}
