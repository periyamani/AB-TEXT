<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\EcuFlash;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ECUFlashController extends BaseController
{
    protected $ecuflash = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EcuFlash $ecuflash)
    {
        $this->middleware('auth:api');
        $this->ecuflash = $ecuflash;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $ecuflashs = $this->ecuflash->latest()->paginate(10);

        return $this->sendResponse($ecuflashs, 'Ecu Flash list');
    }

    public function getEcuFlashDetails(Request $request)
    {
        // dd('jhdfkjd');
        $ecuflashs = $this->ecuflash->where('dtc_data.status','1')
        ->whereJsonContains('dtc_data.vehicle_mapping_id',json_decode($request->vehicle_id))
        ->whereJsonContains('dtc_data.ecu_id',json_decode($request->ecu_id))
        ->latest()->get();

        return $this->sendResponse($ecuflashs, 'Ecu Flash list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $ecuflashs = $this->ecuflash->get();

        return $this->sendResponse($ecuflashs, 'Ecu Flash list');
    }


    /**
     * Store a newly created resource in storage.
     *
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // dd($request->all());
            $getData = $this->ecuflash->create([
                'dealer_email' => $request->get('dealer_email'),
                'model_name' => $request->get('model_name'),
                'varient_name' => $request->get('varient_name'),
                'vci_serial' => $request->get('vci_serial'),
                'vin' => $request->get('vin'),
                'cal_id_old' => $request->get('cal_id_old'),
                'cal_id_new' => $request->get('cal_id_new'),
                'cvn_old' => $request->get('cvn_old'),
                'cvn_new' => $request->get('cvn_new'),
                'odometer' => $request->get('odometer'),
                'gps' => $request->get('gps'),
                'duration' => $request->get('duration'),
                'status' => $request->get('status'),
            ]);
    
            return $this->sendResponse($getData, 'Ecu Flash Created Successfully');
        
    }

    /**
     * Update the resource in storage
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        
        $getData = $this->ecuflash->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Ecu Flash has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = EcuFlash::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getEcuFlashData(Request $request,$id)
    // {
    //     $getData = EcuFlash::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Ecu Flash List');
        
    // }
    public function getUserEcuFlashs(Request $request,$id)
    {
        $getData = EcuFlash::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Ecu Flash List');
        
    }
    public function destroy($id)
    {

        $getData = EcuFlash::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Ecu Flash has been Deleted');
    }

    public function export(Request $request)
    {
        // dd($request->all());
        try {
            $totalData = [];
            $livedatas = $this->ecuflash->latest()->get();
        if(count($livedatas) > 0){
            $x = 1;
            foreach($livedatas as $key => $val){
                $eachData['SNO'] = $x;
                $eachData['Dealer Email'] = $livedatas[$key]['dealer_email'];
                $eachData['Model Name'] = $livedatas[$key]['model_name'];
                $eachData['Varient Name'] = $livedatas[$key]['varient_name'];
                $eachData['VCI Serial'] = $livedatas[$key]['vci_serial'];
                $eachData['VIN'] = $livedatas[$key]['vin'];
                $eachData['Cal Id Old'] = $livedatas[$key]['cal_id_old'];
                $eachData['Cal Id New'] = $livedatas[$key]['cal_id_new'];
                $eachData['CVN Old'] = $livedatas[$key]['cvn_old'];
                $eachData['CVN New'] = $livedatas[$key]['cvn_new'];
                $eachData['Odometer'] = $livedatas[$key]['odometer'];
                $eachData['GPS'] = $livedatas[$key]['gps'];
                $eachData['Duration'] = $livedatas[$key]['duration'];
                $eachData['Status'] = $livedatas[$key]['status'];
                array_push($totalData,$eachData);
                $x++;
            }
        }
        return $this->sendResponse($totalData, 'Ecu Flash list');
        }        
        catch(\Exception $e) {
            return $this->sendResponse('error', $e->getMessage());

        }
    }
}
