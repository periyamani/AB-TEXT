<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\DtcData;
use App\Models\EcuDetail;
use App\Models\VehicleDetail;
use Illuminate\Http\Request;
use App\Imports\DtcDataImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class DtcDataController extends BaseController
{
    protected $dtcdata = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DtcData $dtcdata)
    {
        $this->middleware('auth:api');
        $this->dtcdata = $dtcdata;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $dtcdatas = $this->dtcdata->where('dtc_data.status','1')
        ->latest()->paginate(10);
        if(count($dtcdatas) > 0){
            foreach($dtcdatas as $key => $val){
                $ecuData = [];
                $vehicleData = [];
                if($dtcdatas[$key]['ecu_id']){
                    $ecuData = EcuDetail::where('status','1')->whereIn('id',json_decode($dtcdatas[$key]['ecu_id']))->get(['name','id']);
                }
                if($dtcdatas[$key]['vehicle_mapping_id']){
                    $vehicleData = VehicleDetail::where('status','1')->whereIn('id',json_decode($dtcdatas[$key]['vehicle_mapping_id']))->get(['name','id']);
                }
                $dtcdatas[$key]['ecu_name'] = $ecuData;
                $dtcdatas[$key]['vehicle_name'] = $vehicleData;  
            }
        }

        return $this->sendResponse($dtcdatas, 'Dtc Data list');
    }

    public function getDtcDataDetails(Request $request)
    {
        $allData = [];
        $idData = [];
        if(count(json_decode($request->ecu_id)) > 0){
            foreach(json_decode($request->ecu_id) as $ecu){
                $dtcdatas = $this->dtcdata->where('dtc_data.status','1')
                ->whereJsonContains('dtc_data.vehicle_mapping_id',json_decode($request->vehicle_id))
                ->whereJsonContains('dtc_data.ecu_id',json_decode($ecu))
                ->latest()->get();
                if(count($dtcdatas)>0){
                    foreach($dtcdatas as $live){
                    array_push($idData,$live['id']); 
                    }
                }
            }
            $getLive = array_unique($idData);
            $allData = $this->dtcdata->where('dtc_data.status','1')
            ->whereIn('id',$getLive)
            ->latest()->get();
        }

        return $this->sendResponse($allData, 'Dtc Data list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $dtcdatas = $this->dtcdata->where('status','1')->get();

        return $this->sendResponse($dtcdatas, 'Dtc Data list');
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
        $getData = DtcData::where('dtc_code',$request->get('dtc_code'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request);
            $langName = [];
            $descName = [];
            $ecuId = [];
            $vehicleId = [];
            $lang = DB::table('language')->where('status',1)->get();
            foreach($request->get('name') as $key=>$nameData){
                // dd($lang[0]->name);
                $data1['name'] = $lang[$key]->name;
                $data1['id'] = $lang[$key]->id;
                $data1['value'] = $nameData;
                array_push($langName,$data1); 
            }
            foreach($request->get('description') as $key=>$nameData){
                // dd($lang[0]->name);
                $data1['name'] = $lang[$key]->name;
                $data1['id'] = $lang[$key]->id;
                $data1['value'] = $nameData;
                array_push($descName,$data1); 
            }
            foreach($request->get('ecu_id') as $ecu){
                array_push($ecuId,(int)$ecu); 
            }
            foreach($request->get('vehicle_mapping_id') as $vehicle){
                array_push($vehicleId,(int)$vehicle); 
            }
            $getData = $this->dtcdata->create([
                'dtc_code' => $request->get('dtc_code'),
                'name' => json_encode($langName),
                'description' => json_encode($descName),
                'troubleshoot' => $request->get('troubleshoot'),
                'vehicle_mapping_id' => json_encode($vehicleId),
                'ecu_id' => json_encode($ecuId),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Dtc Data Created Successfully');
        }
        
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
        
        $getData = $this->dtcdata->findOrFail($id);

        $getData->update($request->all());

        $langName = [];
        $descName = [];
        $ecuId = [];
        $vehicleId = [];
        $lang = DB::table('language')->where('status',1)->get();
        foreach($request->get('name') as $key=>$nameData){
            // dd($lang[0]->name);
            $data1['name'] = $lang[$key]->name;
            $data1['id'] = $lang[$key]->id;
            $data1['value'] = $nameData;
            array_push($langName,$data1); 
        }
        foreach($request->get('description') as $key=>$nameData){
            // dd($lang[0]->name);
            $data1['name'] = $lang[$key]->name;
            $data1['id'] = $lang[$key]->id;
            $data1['value'] = $nameData;
            array_push($descName,$data1); 
        }
        foreach($request->get('ecu_id') as $ecu){
            array_push($ecuId,(int)$ecu); 
        }
        foreach($request->get('vehicle_mapping_id') as $vehicle){
            array_push($vehicleId,(int)$vehicle); 
        }
            $getData = DtcData::find($id);;
            $getData->name = json_encode($langName);
            $getData->dtc_code = $request['dtc_code'];
            $getData->description = json_encode($descName);
            $getData->troubleshoot = $request['troubleshoot'];
            $getData->vehicle_mapping_id = json_encode($vehicleId);
            $getData->ecu_id = json_encode($ecuId);
            $getData->created_by = auth()->user()->id;
            $getData->save();

        return $this->sendResponse($getData, 'Dtc Data has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = DtcData::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getDtcDataData(Request $request,$id)
    // {
    //     $getData = DtcData::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Dtc Data List');
        
    // }
    public function getUserDtcDatas(Request $request,$id)
    {
        $getData = DtcData::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Dtc Data List');
        
    }
    public function destroy($id)
    {

        $getData = DtcData::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Dtc Data has been Deleted');
    }
    public function import(Request $request)
    {
        // dd($request->all());
        try {
        $file = Excel::import(new DtcDataImport,request()->file('csv'));
        return $this->sendResponse($file, 'Dtc Data has been Imported');
        }
        catch(\Exception $e) {
            return $this->sendResponse('error', $e->getMessage());

        }
    }

    public function export(Request $request)
    {
        // dd($request->all());
        try {
            $totalData = [];
            $livedatas = $this->dtcdata->where('dtc_data.status','1')
        ->latest()->get();
        if(count($livedatas) > 0){
            $x = 1;
            foreach($livedatas as $key => $val){
                $eachData= [];
                $ecuData = [];
                $vname = '';
                $ename = '';
                $vehicleData = [];
                if($livedatas[$key]['ecu_id']){
                    $ecuData = EcuDetail::whereIn('id',json_decode($livedatas[$key]['ecu_id']))->get(['name','id']);
                }
                if($livedatas[$key]['vehicle_mapping_id']){
                    $vehicleData = VehicleDetail::whereIn('id',json_decode($livedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
                }
                if(count($vehicleData)>0){
                    foreach($vehicleData as $key1 => $vdata){
                        if($key1 == 0){
                            if($vdata['name']){
                                $vname =  $vdata['name'];
                            }
                        }else{
                            if($vdata['name']){
                                $vname =  $vname.' - '.$vdata['name'];
                            }
                        } 
                    }
                }
                if(count($ecuData)>0){
                    foreach($ecuData as $key2 => $vdata){
                        if($key2 == 0){
                            if($vdata['name']){
                                $ename =  $vdata['name'];
                            }
                        }else{
                            if($vdata['name']){
                                $ename =  $ename.' - '.$vdata['name'];
                            }
                        } 
                    }
                }
                // dd($vname);
                $livedatas[$key]['ecu_name'] = $ename;
                $livedatas[$key]['vehicle_name'] = $vname; 
                $eachData['SNO'] = $x;
                $eachData['DTC Code'] = $livedatas[$key]['dtc_code'];
                $eachData['Name'] = $livedatas[$key]['name'];
                $eachData['Description'] = $livedatas[$key]['description'];
                $eachData['Troubleshoot'] = $livedatas[$key]['troubleshoot'];
                $eachData['Vehicle Details'] = $vname;
                $eachData['ECU'] = $ename;
                array_push($totalData,$eachData);
                $x++;
            }
        }
        return $this->sendResponse($totalData, 'Live Data list');
        }        
        catch(\Exception $e) {
            return $this->sendResponse('error', $e->getMessage());

        }
    }
}
