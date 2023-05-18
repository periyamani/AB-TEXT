<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\LiveData;
use App\Models\EcuDetail;
use App\Models\VehicleDetail;
use Illuminate\Http\Request;
use App\Imports\LiveDataImport;
use App\Exports\LiveDataExport;
use Storage;
use File;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class LiveDataController extends BaseController
{
    protected $livedata = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LiveData $livedata)
    {
        $this->middleware('auth:api');
        $this->livedata = $livedata;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
         // whereJsonContains('live_data.ecu_id',1)
        $livedatas = $this->livedata->where('live_data.status','1')
        ->latest()->paginate(10);
        if(count($livedatas) > 0){
            foreach($livedatas as $key => $val){
                $ecuData = [];
                $vehicleData = [];
                if($livedatas[$key]['ecu_id']){
                    $ecuData = EcuDetail::where('status','1')->whereIn('id',json_decode($livedatas[$key]['ecu_id']))->get(['name','id']);
                }
                if($livedatas[$key]['vehicle_mapping_id']){
                    $vehicleData = VehicleDetail::where('status','1')->whereIn('id',json_decode($livedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
                }
                $livedatas[$key]['ecu_name'] = $ecuData;
                $livedatas[$key]['vehicle_name'] = $vehicleData;  
            }
        }
        return $this->sendResponse($livedatas, 'Live Data list');
    }

    public function getLiveDataDetails(Request $request)
    {
        // dd(json_decode($request->ecu_id));
        $allData = [];
        $idData = [];
        if(count(json_decode($request->ecu_id)) > 0){
            foreach(json_decode($request->ecu_id) as $ecu){
                $livedatas = $this->livedata->where('live_data.status','1')->where('live_data.active','Enabled')
                ->whereJsonContains('live_data.vehicle_mapping_id',json_decode($request->vehicle_id))
                ->whereJsonContains('live_data.ecu_id',json_decode($ecu))
                ->latest()->get();
                if(count($livedatas)>0){
                    foreach($livedatas as $live){
                    array_push($idData,$live['id']); 
                    }
                }
            }
            $getLive = array_unique($idData);
            $allData = $this->livedata->where('live_data.status','1')
            ->whereIn('id',$getLive)
            ->latest()->get();
        }
        
        // if(count($livedatas) > 0){
        //     foreach($livedatas as $key => $val){
        //         $ecuData = [];
        //         $vehicleData = [];
        //         if($livedatas[$key]['ecu_id']){
        //             $ecuData = EcuDetail::whereIn('id',json_decode($livedatas[$key]['ecu_id']))->get(['name','id']);
        //         }
        //         if($livedatas[$key]['vehicle_mapping_id']){
        //             $vehicleData = VehicleDetail::whereIn('id',json_decode($livedatas[$key]['vehicle_mapping_id']))->get(['name','id']);
        //         }
        //         $livedatas[$key]['ecu_name'] = $ecuData;
        //         $livedatas[$key]['vehicle_name'] = $vehicleData;  
        //     }
        // }

        return $this->sendResponse($allData, 'Live Data list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $livedatas = $this->livedata->where('status','1')->get();

        return $this->sendResponse($livedatas, 'Live Data list');
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
        $getData = LiveData::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            
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
        $onimage_name = null;
        if(isset($request->icon) && $request->hasFile('icon')){
            $onimage = $request->file('icon');
            $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
            $destinationPath = public_path('images/icon_image');
            $onimage->move($destinationPath,$onimage_name);
        }
            $getData = new LiveData;
            $getData->name = json_encode($langName);
            $getData->did = $request['did'];
            $getData->datasize = $request['datasize'];
            $getData->unit = $request['unit'];
            $getData->conversion_formula = $request['conversion_formula'];
            $getData->conversion_method = $request['conversion_method'];
            $getData->range_from = $request['range_from'];
            $getData->range_to = $request['range_to'];
            $getData->category = $request['category'];
            $getData->vehicle_mapping_id = json_encode($vehicleId);
            $getData->ecu_id = json_encode($ecuId);
            if($onimage_name){
                $getData->icon = $onimage_name; 
                $getData->icon_path = 'images/icon_image/'.$onimage_name; 
            }
            $getData->created_by = auth()->user()->id;
            $getData->save();
    
            return $this->sendResponse($getData, 'Live Data Created Successfully');
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
        // dd($request->get('ecu_id'));
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
        $onimage_name = null;
        if(isset($request->icon) && $request->hasFile('icon')){
         $onimage = $request->file('icon');
         $oldImage = LiveData::find($id);
         if($oldImage->icon){
             File::delete('images/icon_image/'.$oldImage->icon);
         }
         $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
         $destinationPath = public_path('images/icon_image');
         $onimage->move($destinationPath,$onimage_name);
        }
            $getData = LiveData::find($id);;
            $getData->name = json_encode($langName);
            $getData->did = $request['did'];
            $getData->datasize = $request['datasize'];
            $getData->unit = $request['unit'];
            $getData->conversion_formula = $request['conversion_formula'];
            $getData->conversion_method = $request['conversion_method'];
            $getData->range_from = $request['range_from'];
            $getData->range_to = $request['range_to'];
            $getData->category = $request['category'];
            $getData->vehicle_mapping_id = json_encode($vehicleId);
            $getData->ecu_id = json_encode($ecuId);
            if($onimage_name){
                $getData->icon = $onimage_name;
                $getData->icon_path = 'images/icon_image/'.$onimage_name; 
            }
            $getData->created_by = auth()->user()->id;
            $getData->save();

        return $this->sendResponse($getData, 'Live Data has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = LiveData::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getLiveDataData(Request $request,$id)
    // {
    //     $getData = LiveData::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Live Data List');
        
    // }
    public function getUserLiveDatas(Request $request,$id)
    {
        $getData = LiveData::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Live Data List');
        
    }
    public function destroy($id)
    {

        $getData = LiveData::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Live Data has been Deleted');
    }
    public function imageDelete($id)
    {
        $oldImage = LiveData::find($id);
        if($oldImage->icon){
            File::delete('images/icon_image/'.$oldImage->icon);
        }
        $getData = LiveData::find($id);
        $getData->icon = null;
        $getData->icon_path = null;
        $getData->save();
        return $this->sendResponse($getData, 'Live Data Icon Deleted');
    }
    public function enableData($id,$dataName)
    {
        
        $getData = LiveData::find($id);
        $getData->active = $dataName.'d';
        $getData->save();
        return $this->sendResponse($getData, 'Live Data has been '.$dataName.'d');
    }
    public function import(Request $request)
    {
        // dd($request->all());
        try {
        $file = Excel::import(new LiveDataImport,request()->file('csv'));
        return $this->sendResponse($file, 'Live Data has been Imported');
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
            $livedatas = $this->livedata->where('live_data.status','1')
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
                $eachData['Datasize'] = $livedatas[$key]['datasize'];
                $eachData['Unit'] = $livedatas[$key]['unit'];
                $eachData['Conversion Method'] = $livedatas[$key]['conversion_method'];
                $eachData['Conversion Formula'] = $livedatas[$key]['conversion_formula'];
                $eachData['Range'] = $livedatas[$key]['range_from'].'-'.$livedatas[$key]['range_to'];
                $eachData['Category'] = $livedatas[$key]['category'];
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
