<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\IoData;
use Illuminate\Http\Request;
use App\Imports\IoDataImport;
use App\Models\EcuDetail;
use App\Models\VehicleDetail;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class IoDataController extends BaseController
{
    protected $iodata = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IoData $iodata)
    {
        $this->middleware('auth:api');
        $this->iodata = $iodata;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $iodatas = $this->iodata->where('io_control_data.status','1')
        ->latest()->paginate(10);
        if(count($iodatas) > 0){
            foreach($iodatas as $key => $val){
                $ecuData = [];
                $vehicleData = [];
                if($iodatas[$key]['ecu_id']){
                    $ecuData = EcuDetail::where('status','1')->whereIn('id',json_decode($iodatas[$key]['ecu_id']))->get(['name','id']);
                }
                if($iodatas[$key]['vehicle_mapping_id']){
                    $vehicleData = VehicleDetail::where('status','1')->whereIn('id',json_decode($iodatas[$key]['vehicle_mapping_id']))->get(['name','id']);
                }
                $iodatas[$key]['ecu_name'] = $ecuData;
                $iodatas[$key]['vehicle_name'] = $vehicleData;  
            }
        }

        return $this->sendResponse($iodatas, 'I/O Control Data list');
    }

    public function getIoDataDetails(Request $request)
    {
        $allData = [];
        $idData = [];
        if(count(json_decode($request->ecu_id)) > 0){
            foreach(json_decode($request->ecu_id) as $ecu){
                $iodatas = $this->iodata->where('io_control_data.status','1')
                ->whereJsonContains('io_control_data.vehicle_mapping_id',json_decode($request->vehicle_id))
                ->whereJsonContains('io_control_data.ecu_id',json_decode($ecu))
                ->latest()->get();
                if(count($iodatas)>0){
                    foreach($iodatas as $live){
                    array_push($idData,$live['id']); 
                    }
                }
            }
            $getLive = array_unique($idData);
            $allData = $this->iodata->where('io_control_data.status','1')
            ->whereIn('id',$getLive)
            ->latest()->get();
        }
        // if(count($iodatas) > 0){
        //     foreach($iodatas as $key => $val){
        //         $ecuData = [];
        //         $vehicleData = [];
        //         if($iodatas[$key]['ecu_id']){
        //             $ecuData = EcuDetail::whereIn('id',json_decode($iodatas[$key]['ecu_id']))->get(['name','id']);
        //         }
        //         if($iodatas[$key]['vehicle_mapping_id']){
        //             $vehicleData = VehicleDetail::whereIn('id',json_decode($iodatas[$key]['vehicle_mapping_id']))->get(['name','id']);
        //         }
        //         $iodatas[$key]['ecu_name'] = $ecuData;
        //         $iodatas[$key]['vehicle_name'] = $vehicleData;  
        //     }
        // }

        return $this->sendResponse($allData, 'I/O Control Data list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $iodatas = $this->iodata->where('status','1')->get();

        return $this->sendResponse($iodatas, 'I/O Control Data list');
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
        $getData = IoData::where('did',$request->get('did'))->where('status','1')->get();
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

            $getData = $this->iodata->create([
                'did' => $request->get('did'),
                'name' => json_encode($langName),
                'vehicle_mapping_id' => json_encode($vehicleId),
                'ecu_id' => json_encode($ecuId),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'I/O Control Data Created Successfully');
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

        $getData = IoData::find($id);;
        $getData->name = json_encode($langName);
        $getData->did = $request['did'];
        $getData->vehicle_mapping_id = json_encode($vehicleId);
        $getData->ecu_id = json_encode($ecuId);
        $getData->created_by = auth()->user()->id;
        $getData->save();
        return $this->sendResponse($getData, 'I/O Control Data has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = IoData::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getIoDataData(Request $request,$id)
    // {
    //     $getData = IoData::where('active','1')->get();
    //     return $this->sendResponse($getData, 'I/O Control Data List');
        
    // }
    public function getUserIoDatas(Request $request,$id)
    {
        $getData = IoData::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'I/O Control Data List');
        
    }
    public function destroy($id)
    {

        $getData = IoData::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'I/O Control Data has been Deleted');
    }
    public function import(Request $request)
    {
        // dd($request->all());
        try {
        $file = Excel::import(new IoDataImport,request()->file('csv'));
        return $this->sendResponse($file, 'I/O Data has been Imported');
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
            $livedatas = $this->iodata->where('io_control_data.status','1')->latest()->get();
            
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
                // dd($key);
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
            //   dd($key);
                $eachData['SNO'] = $x;
                $eachData['DID'] = $livedatas[$key]['did'];
                $eachData['Name'] = $livedatas[$key]['name'];
                $eachData['Vehicle Details'] = $vname;
                $eachData['ECU'] = $ename;
                // dd($eachData);
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
