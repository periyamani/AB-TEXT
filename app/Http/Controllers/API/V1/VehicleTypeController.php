<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends BaseController
{
    protected $vehicletype = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VehicleType $vehicletype)
    {
        // $this->middleware('auth:api');
        $this->vehicletype = $vehicletype;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $vehicletypes = $this->vehicletype->where('status','1')->latest()->paginate(10);

        return $this->sendResponse($vehicletypes, 'Vehicle Type list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $vehicletypes = $this->vehicletype->where('status','1')->get();

        return $this->sendResponse($vehicletypes, 'Vehicle Type list');
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
        // dd($request->get('name'));
        $getData = VehicleType::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd(auth()->user());
            $getData = $this->vehicletype->create([
                'name' => $request->get('name'),
                // 'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Vehicle Type Created Successfully');
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
        
        $getData = $this->vehicletype->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Vehicle Type has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = VehicleType::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getVehicleTypeData(Request $request,$id)
    {
        $getData = VehicleType::where('active','1')->get();
        return $this->sendResponse($getData, 'Vehicle Type List');
        
    }
    public function destroy($id)
    {

        $getData = VehicleType::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Vehicle Type has been Deleted');
    }
}
