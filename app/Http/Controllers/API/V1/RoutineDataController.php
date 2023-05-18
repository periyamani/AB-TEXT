<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\RoutineData;
use Illuminate\Http\Request;
use App\Imports\RoutineDataImport;
use App\Models\EcuDetail;
use App\Models\VehicleDetail;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class RoutineDataController extends BaseController
{
    protected $routinedata = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoutineData $routinedata)
    {
        $this->middleware('auth:api');
        $this->routinedata = $routinedata;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $routinedatas = $this->routinedata->where('routine_control_data.status','1')
        ->latest()->paginate(10);
        if(count($routinedatas) > 0){
            foreach($routinedatas as $key => $val){
                $ecuData = [];
                $vehicleData = [];
                if($routinedatas[$key]['ecu_id']){
                    $ecuData = EcuDetail::where('status','1')->whereIn('id',json_decode($routinedatas[$key]['ecu_id']))->get(['name','id']);
                }
                if($routinedatas[$key]['vehicle_mapping_id']){
                    $vehicleData = VehicleDetail::where('status','1')->whereIn('id',json_decode($routinedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
                }
                $routinedatas[$key]['ecu_name'] = $ecuData;
                $routinedatas[$key]['vehicle_name'] = $vehicleData;  
            }
        }

        return $this->sendResponse($routinedatas, 'Routine Control Data list');
    }

    public function getRoutineDataDetails(Request $request)
    {
        $allData = [];
        $idData = [];
        if(count(json_decode($request->ecu_id)) > 0){
            foreach(json_decode($request->ecu_id) as $ecu){
                $routinedatas = $this->routinedata->where('routine_control_data.status','1')
        ->whereJsonContains('routine_control_data.vehicle_mapping_id',json_decode($request->vehicle_id))
        ->whereJsonContains('routine_control_data.ecu_id',json_decode($ecu))
        ->latest()->get();
                if(count($routinedatas)>0){
                    foreach($routinedatas as $live){
                    array_push($idData,$live['id']); 
                    }
                }
            }
            $getLive = array_unique($idData);
            $allData = $this->routinedata->where('routine_control_data.status','1')
            ->whereIn('id',$getLive)
            ->latest()->get();
        }
        // if(count($routinedatas) > 0){
        //     foreach($routinedatas as $key => $val){
        //         $ecuData = [];
        //         $vehicleData = [];
        //         if($routinedatas[$key]['ecu_id']){
        //             $ecuData = EcuDetail::whereIn('id',json_decode($routinedatas[$key]['ecu_id']))->get(['name','id']);
        //         }
        //         if($routinedatas[$key]['vehicle_mapping_id']){
        //             $vehicleData = VehicleDetail::whereIn('id',json_decode($routinedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
        //         }
        //         $routinedatas[$key]['ecu_name'] = $ecuData;
        //         $routinedatas[$key]['vehicle_name'] = $vehicleData;  
        //     }
        // }
        return $this->sendResponse($allData, 'Routine Control Data list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $routinedatas = $this->routinedata->where('status','1')->get();

        return $this->sendResponse($routinedatas, 'Routine Control Data list');
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
        $getData = RoutineData::where('did',$request->get('did'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
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
            $getData = $this->routinedata->create([
                'did' => $request->get('did'),
                'name' => json_encode($langName),
                'vehicle_mapping_id' => json_encode($request->get('vehicle_mapping_id')),
                'ecu_id' => json_encode($request->get('ecu_id')),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Routine Control Data Created Successfully');
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
        $getData = RoutineData::find($id);;
        $getData->name = json_encode($langName);
        $getData->did = $request['did'];
        $getData->vehicle_mapping_id = json_encode($vehicleId);
        $getData->ecu_id = json_encode($ecuId);
        $getData->created_by = auth()->user()->id;
        $getData->save();

        return $this->sendResponse($getData, 'Routine Control Data has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = RoutineData::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getRoutineDataData(Request $request,$id)
    // {
    //     $getData = RoutineData::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Routine Control Data List');
        
    // }
    public function getUserRoutineDatas(Request $request,$id)
    {
        $getData = RoutineData::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Routine Control Data List');
        
    }
    public function destroy($id)
    {

        $getData = RoutineData::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Routine Control Data has been Deleted');
    }
    public function import(Request $request)
    {
        // dd($request->all());
        try {
        $file = Excel::import(new RoutineDataImport,request()->file('csv'));
        return $this->sendResponse($file, 'Routine Data has been Imported');
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
            $livedatas = $this->routinedata->where('routine_control_data.status','1')
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
                $eachData['DID'] = $livedatas[$key]['did'];
                $eachData['Name'] = $livedatas[$key]['name'];
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
