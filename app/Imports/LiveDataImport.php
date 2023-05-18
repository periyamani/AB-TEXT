<?php

namespace App\Imports;

use App\Models\LiveData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LiveDataImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LiveData([
            'did'     => $row['did'],
            'name'    => $row['name'], 
            'datasize'     => $row['datasize'],
            'unit'    => $row['unit'], 
            'conversion'     => $row['conversion'],
            'range_from'    => $row['range_from'], 
            'range_to'    => $row['range_to'], 
            'category'    => $row['category'], 
            'vehicle_mapping_id'    => $row['vehicle_mapping_id'], 
            'ecu_id'    => $row['ecu_id'], 
            'created_by' => auth()->user()->id,
        ]);
    }
}
