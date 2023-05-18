<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\HealthTest;
use Illuminate\Http\Request;
use PDF;

class HealthTestController extends BaseController
{
    protected $healthtest = '';


    public function __construct(HealthTest $healthtest)
    {
        // $this->middleware('auth:api');
        $this->healthtest = $healthtest;
    }

    public function addData(Request $request)
    {
// dd($request->dealer_details['dealer_id']);
        // $getData = $this->healthtest->create([
        //     'field' => json_encode($request->get('field')),
        //     'customer_details' => json_encode($request->get('customer_details')),
        //     'vehicle_details' => json_encode($request->get('vehicle_details')),
        //     'dealer_id' => $request->dealer_details['dealer_id'],
        //     'dealer_comments' => $request->dealer_details['dealer_comments'],
        //     'delivery_date' => $request->dealer_details['delivery_date'],
        //     // 'created_by' => auth()->user()->id,
        // ]);
        $getData = HealthTest::where('healthtest_data.id','1')
        ->join('users','users.id','=','healthtest_data.dealer_id')
        ->select('healthtest_data.*','users.name as dealer_name','users.mobile_no as mobile','users.address1 as address1','users.city as city')
        ->first();
        $data =$getData;
        // dd($data);
        $pdf = PDF::loadView('pdf', compact('data'))->save('assets/reports.pdf');
        return $pdf->download('pdf_file.pdf');
        // return $this->sendResponse($getData, 'Health Test Structure list');
    }

   
   
    
}
