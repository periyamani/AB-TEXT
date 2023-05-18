<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\VehicleDetail;
use App\Models\EcuDetail;
use File;
use Illuminate\Http\Request;

class VehicleDetailController extends BaseController
{
    protected $vcidetail = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VehicleDetail $vcidetail)
    {
        $this->middleware('auth:api');
        $this->vcidetail = $vcidetail;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $vehicledetail = $this->vcidetail->where('vehicle_details.status','1')
        ->join('vehicle_type','vehicle_type.id','=','vehicle_details.vehicle_type_id')
        ->select('vehicle_details.*','vehicle_type.name as typename','vehicle_type.id as typeid')
        ->latest()->paginate(10);
        if(count($vehicledetail) > 0){
            foreach($vehicledetail as $key => $val){
                $functionData = [];
                if($vehicledetail[$key]['ecu_id']){
                $functionData = EcuDetail::where('status','1')->whereIn('id',json_decode($vehicledetail[$key]['ecu_id']))->get(['name','id']);
                }
                $vehicledetail[$key]['ecu_name'] = $functionData;  
            }
        }
        return $this->sendResponse($vehicledetail, 'Vehicle Detail list');
    }

    public function getVehicleDetail()
    {
        // dd('jhdfkjd');
        $vehicledetail = $this->vcidetail->where('vehicle_details.status','1')
        ->join('vehicle_type','vehicle_type.id','=','vehicle_details.vehicle_type_id')
        ->select('vehicle_details.*','vehicle_type.name as typename','vehicle_type.id as typeid')
        ->latest()->get();
        if(count($vehicledetail) > 0){
            foreach($vehicledetail as $key => $val){
                $functionData = EcuDetail::whereIn('id',json_decode($vehicledetail[$key]['ecu_id']))->get(['name','id']);
                $vehicledetail[$key]['ecu_name'] = $functionData;  
            }
        }
        return $this->sendResponse($vehicledetail, 'Vehicle Detail list');
    }

    public function getSingleVehicleDetail(Request $request)
    {
        // dd('jhdfkjd');
        $vehicledetail = $this->vcidetail->where('vehicle_details.status','1')
        ->where('vehicle_details.id',$request->vehicle_id)
        ->join('vehicle_type','vehicle_type.id','=','vehicle_details.vehicle_type_id')
        ->select('vehicle_details.*','vehicle_type.name as typename','vehicle_type.id as typeid')
        ->latest()->get();
        if(count($vehicledetail) > 0){
            foreach($vehicledetail as $key => $val){
                $functionData = EcuDetail::whereIn('id',json_decode($vehicledetail[$key]['ecu_id']))->get(['name','id']);
                $vehicledetail[$key]['ecu_name'] = $functionData;  
            }
        }
        return $this->sendResponse($vehicledetail, 'Vehicle Detail list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $vehicledetail = $this->vcidetail->where('status','1')->get();

        return $this->sendResponse($vehicledetail, 'Vehicle Detail list');
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
        $getData = VehicleDetail::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($request->all(), 'Already exist');
        }else{
            // dd($request);
            $onimage = $request->file('image');
            $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
            $destinationPath = public_path('/images/vehicle_image');
            $onimage->move($destinationPath,$onimage_name);
            $getData = new VehicleDetail;
            $getData->name = $request['name'];
            $getData->ecu_id = json_encode($request['ecu_id']);
            $getData->model = $request['model'];
            $getData->vehicle_type_id = $request['vehicle_type_id'];
            $getData->vin = $request['vin'];
            $getData->image = $onimage_name; 
            $getData->image_path = '/images/vehicle_image/'.$onimage_name; 
            $getData->created_by = auth()->user()->id;
            $getData->save();
    
            return $this->sendResponse($getData, 'Vehicle Detail Created Successfully');
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
        // dd($request->all());
        // $getData = $this->vcidetail->findOrFail($id);
        $onimage_name = null;
        if(isset($request->image) && $request->hasFile('image')){
         $onimage = $request->file('image');
         $oldImage = VehicleDetail::find($id);
         if($oldImage->image){
             File::delete('images/vehicle_image/'.$oldImage->image);
         }
         $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
         $destinationPath = public_path('/images/vehicle_image');
         $onimage->move($destinationPath,$onimage_name);
        }
        // $onimage = $request->file('image');
        // $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
        // $destinationPath = public_path('/images/vehicle_image');
        // $onimage->move($destinationPath,$onimage_name);
        $getData = VehicleDetail::find($id);
        $getData->name = $request['name'];
        $getData->ecu_id = $request['ecu_id'];
        $getData->model = $request['model'];
        $getData->vehicle_type_id = $request['vehicle_type_id'];
        if($onimage_name){
            $getData->image = $onimage_name;
            $getData->image_path = '/images/vehicle_image/'.$onimage_name; 
        }
        $getData->vin = $request['vin'];
        $getData->created_by = auth()->user()->id;
        $getData->save();

        return $this->sendResponse($getData, 'Vehicle Detail has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = VehicleDetail::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getVehicleDetailData(Request $request,$id)
    // {
    //     $getData = VehicleDetail::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Vehicle Detail List');
        
    // }
    public function getUserVehicleDetails(Request $request,$id)
    {
        $getData = VehicleDetail::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Vehicle Detail List');
        
    }
    public function destroy($id)
    {

        $getData = VehicleDetail::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Vehicle Detail has been Deleted');
    }
}
