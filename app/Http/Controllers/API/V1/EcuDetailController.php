<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\EcuDetail;
use App\Models\VehicleFunction;
use Illuminate\Http\Request;

class EcuDetailController extends BaseController
{
    protected $vcidetail = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EcuDetail $vcidetail)
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
        $ecudetail = $this->vcidetail->where('ecu_details.status','1')->latest()->paginate(10);
        if(count($ecudetail) > 0){
            foreach($ecudetail as $key => $val){
                $ecuData = [];
                if($ecudetail[$key]['ecu_functionality']){
                    $ecuData = VehicleFunction::where('status','1')->whereIn('id',json_decode($ecudetail[$key]['ecu_functionality']))->get(['name','id']);
                }
                $ecudetail[$key]['function_name'] = $ecuData;
            }
        }
        return $this->sendResponse($ecudetail, 'Ecu Detail list');
    }

    public function getEcidetail(Request $request)
    {
        // dd($request->ecu_id);
        $ecudetail = $this->vcidetail->where('ecu_details.status','1')->where('id',$request->ecu_id)->latest()->get();
        return $this->sendResponse($ecudetail, 'Ecu Detail list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $ecudetail = $this->vcidetail->where('status','1')->get();

        return $this->sendResponse($ecudetail, 'Ecu Detail list');
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
        $getData = EcuDetail::where('serial_no',$request->get('serial_no'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request);
            $getData = $this->vcidetail->create([
                'name' => $request->get('name'),
                'serial_no' => $request->get('serial_no'),
                'oem' => $request->get('oem'),
                'ecu_functionality' => json_encode($request->get('ecu_functionality')),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Ecu Detail Created Successfully');
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
        
        $getData = $this->vcidetail->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Ecu Detail has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = EcuDetail::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getEcuDetailData(Request $request,$id)
    // {
    //     $getData = EcuDetail::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Ecu Detail List');
        
    // }
    public function getUserEcuDetails(Request $request,$id)
    {
        $getData = EcuDetail::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Ecu Detail List');
        
    }
    public function destroy($id)
    {

        $getData = EcuDetail::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Ecu Detail has been Deleted');
    }
}
