<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\VehicleFunction;
use Illuminate\Http\Request;

class VehicleFunctionController extends BaseController
{
    protected $vehiclefunction = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VehicleFunction $vehiclefunction)
    {
        $this->middleware('auth:api');
        $this->vehiclefunction = $vehiclefunction;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $vehiclefunctions = $this->vehiclefunction->where('status','1')->latest()->paginate(10);

        return $this->sendResponse($vehiclefunctions, 'Vehicle Function list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $vehiclefunctions = $this->vehiclefunction->where('status','1')->get();

        return $this->sendResponse($vehiclefunctions, 'Vehicle Function list');
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
        $getData = VehicleFunction::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request);
            $getData = $this->vehiclefunction->create([
                'name' => $request->get('name'),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Vehicle Function Created Successfully');
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
        
        $getData = $this->vehiclefunction->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Vehicle Function has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = VehicleFunction::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getVehicleFunctionData(Request $request,$id)
    {
        $getData = VehicleFunction::where('active','1')->get();
        return $this->sendResponse($getData, 'Vehicle Function List');
        
    }
    public function destroy($id)
    {

        $getData = VehicleFunction::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Vehicle Function has been Deleted');
    }
}
