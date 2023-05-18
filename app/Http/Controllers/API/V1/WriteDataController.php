<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\WriteData;
use Illuminate\Http\Request;
use App\Imports\WriteDataImport;
use App\Models\EcuDetail;
use App\Models\VehicleDetail;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class WriteDataController extends BaseController
{
    protected $writedata = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WriteData $writedata)
    {
        $this->middleware('auth:api');
        $this->writedata = $writedata;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $writedatas = $this->writedata->where('write_data.status','1')
        ->latest()->paginate(10);
        if(count($writedatas) > 0){
            foreach($writedatas as $key => $val){
                $ecuData = [];
                $vehicleData = [];
                if($writedatas[$key]['ecu_id']){
                    $ecuData = EcuDetail::where('status','1')->whereIn('id',json_decode($writedatas[$key]['ecu_id']))->get(['name','id']);
                }
                if($writedatas[$key]['vehicle_mapping_id']){
                    $vehicleData = VehicleDetail::where('status','1')->whereIn('id',json_decode($writedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
                }
                $writedatas[$key]['ecu_name'] = $ecuData;
                $writedatas[$key]['vehicle_name'] = $vehicleData;  
            }
        }

        return $this->sendResponse($writedatas, 'Write Data list');
    }

    public function getWriteDataDetails(Request $request)
    {
        $allData = [];
        $idData = [];
        if(count(json_decode($request->ecu_id)) > 0){
            foreach(json_decode($request->ecu_id) as $ecu){
                $writedatas = $this->writedata->where('write_data.status','1')
        ->whereJsonContains('write_data.vehicle_mapping_id',json_decode($request->vehicle_id))
        ->whereJsonContains('write_data.ecu_id',json_decode($ecu))
        ->latest()->get();
                if(count($writedatas)>0){
                    foreach($writedatas as $live){
                    array_push($idData,$live['id']); 
                    }
                }
            }
            $getLive = array_unique($idData);
            $allData = $this->writedata->where('write_data.status','1')
            ->whereIn('id',$getLive)
            ->latest()->get();
        }
        // if(count($writedatas) > 0){
        //     foreach($writedatas as $key => $val){
        //         $ecuData = [];
        //         $vehicleData = [];
        //         if($writedatas[$key]['ecu_id']){
        //             $ecuData = EcuDetail::whereIn('id',json_decode($writedatas[$key]['ecu_id']))->get(['name','id']);
        //         }
        //         if($writedatas[$key]['vehicle_mapping_id']){
        //             $vehicleData = VehicleDetail::whereIn('id',json_decode($writedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
        //         }
        //         $writedatas[$key]['ecu_name'] = $ecuData;
        //         $writedatas[$key]['vehicle_name'] = $vehicleData;  
        //     }
        // }

        return $this->sendResponse($allData, 'Write Data list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $writedatas = $this->writedata->where('status','1')->get();

        return $this->sendResponse($writedatas, 'Write Data list');
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
        $getData = WriteData::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request->all());
            $langName = [];
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
            foreach($request->get('ecu_id') as $ecu){
                array_push($ecuId,(int)$ecu); 
            }
            foreach($request->get('vehicle_mapping_id') as $vehicle){
                array_push($vehicleId,(int)$vehicle); 
            }
            $getData = $this->writedata->create([
                'name' => json_encode($langName),
                'did' => $request->get('did'),
                'hint' => $request->get('hint'),
                'security' => $request->get('security'),
                'conversion' => $request->get('conversion'),
                'range' => $request->get('range'),
                'default_value' => $request->get('default_value'),
                'method' => $request->get('method'),
                'vehicle_mapping_id' => json_encode($vehicleId),
                'ecu_id' =>  json_encode($ecuId),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Write Data Created Successfully');
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
        $ecuId = [];
        $vehicleId = [];
        $langName = [];
        $lang = DB::table('language')->where('status',1)->get();
        foreach($request->get('name') as $key=>$nameData){
            // dd($lang[0]->name);
            $data1['name'] = $lang[$key]->name;
            $data1['id'] = $lang[$key]->id;
            $data1['value'] = $nameData;
            array_push($langName,$data1); 
        }
        foreach($request->get('ecu_id') as $ecu){
            array_push($ecuId,(int)$ecu); 
        }
        foreach($request->get('vehicle_mapping_id') as $vehicle){
            array_push($vehicleId,(int)$vehicle); 
        }
            $getData = WriteData::find($id);;
            $getData->name = json_encode($langName);
            $getData->did = $request['did'];
            $getData->hint = $request['hint'];
            $getData->security = $request['security'];
            $getData->conversion = $request['conversion'];
            $getData->range = $request['range'];
            $getData->default_value = $request['default_value'];
            $getData->method = $request['method'];
            $getData->vehicle_mapping_id = json_encode($vehicleId);
            $getData->ecu_id = json_encode($ecuId);
            $getData->created_by = auth()->user()->id;
            $getData->save();

        return $this->sendResponse($getData, 'Write Data has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = WriteData::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getWriteDataData(Request $request,$id)
    // {
    //     $getData = WriteData::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Write Data List');
        
    // }
    public function getUserWriteDatas(Request $request,$id)
    {
        $getData = WriteData::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Write Data List');
        
    }
    public function destroy($id)
    {

        $getData = WriteData::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Write Data has been Deleted');
    }
    public function import(Request $request)
    {
        // dd($request->all());
        try {
        $file = Excel::import(new WriteDataImport,request()->file('csv'));
        return $this->sendResponse($file, 'Write Data has been Imported');
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
            $livedatas = $this->writedata->where('write_data.status','1')
        ->latest()->get();
        // dd($livedatas);
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
                    foreach($vehicleData as $key => $vdata){
                        if($key == 0){
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
                    foreach($ecuData as $key => $vdata){
                        if($key == 0){
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
                $eachData['DID'] = $livedatas[$key]['did'];
                $eachData['Name'] = $livedatas[$key]['name'];
                $eachData['Security'] = $livedatas[$key]['security'];
                $eachData['Hint'] = $livedatas[$key]['hint'];
                $eachData['Conversion'] = $livedatas[$key]['conversion'];
                $eachData['Range'] = $livedatas[$key]['range'];
                $eachData['Default value'] = $livedatas[$key]['default_value'];
                $eachData['Method'] = $livedatas[$key]['method'];
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
