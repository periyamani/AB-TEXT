<?php

namespace App\Imports;

use App\Models\RoutineData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoutineDataImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RoutineData([
            'did'     => $row['did'],
            'name'    => $row['name'],
            'vehicle_mapping_id'    => $row['vehicle_mapping_id'], 
            'ecu_id' => $row['ecu_id'], 
            'created_by' => auth()->user()->id,
        ]);
    }
}
