<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\APK;
use App\Models\EcuDetail;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Validator;

class APKController extends BaseController
{
    protected $apk = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(APK $apk)
    {
        $this->middleware('auth:api');
        $this->apk = $apk;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $vehicledetail = $this->apk->where('apk_management.status','1')
        ->join('users as technician','technician.id','=','apk_management.created_by')
        ->leftjoin('users as approver','approver.id','=','apk_management.approved_by')
        ->select('apk_management.*','technician.name as technician_name','approver.name as approver_name')
        ->latest()->paginate(10);
        return $this->sendResponse($vehicledetail, 'APK list');
    }

    public function getApk()
    {
        // dd('jhdfkjd');
        $vehicledetail = $this->apk->where('status','1')
        ->latest()->first();
        return $this->sendResponse($vehicledetail, 'APK list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $vehicledetail = $this->apk->where('status','1')->get();

        return $this->sendResponse($vehicledetail, 'APK list');
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
        
        $getData = APK::where('version',$request->get('version'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($request->all(), 'Already exist');
        }else{
            // dd($request);
            $onimage = $request->file('apk_file');
            $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
            $destinationPath = public_path('/images/apk_file');
            $onimage->move($destinationPath,$onimage_name);
            $getData = new APK;
            $getData->version = $request['version'];
            $getData->apk_file = $onimage_name; 
            $getData->apk_path = '/images/apk_file/'.$onimage_name; 
            $getData->created_by = auth()->user()->id;
            $getData->remark = $request['remark'];
            $getData->save();
    
            return $this->sendResponse($getData, 'APK Created Successfully');
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
        // $getData = $this->apk->findOrFail($id);
        $onimage_name = null;
        if(isset($request->image) && $request->hasFile('apk_file')){
         $onimage = $request->file('apk_file');
         $oldImage = APK::find($id);
         if($oldImage->apk_file){
             File::delete('images/apk_file/'.$oldImage->image);
         }
         $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
         $destinationPath = public_path('/images/apk_file');
         $onimage->move($destinationPath,$onimage_name);
        }
        $getData = APK::find($id);
        $getData->version = $request['version'];
        $getData->remark = $request['remark'];
        if($onimage_name){
            $getData->apk_file = $onimage_name;
            $getData->apk_path = '/images/apk_file/'.$onimage_name; 
        }
        $getData->created_by = auth()->user()->id;
        $getData->save();

        return $this->sendResponse($getData, 'APK has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = APK::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getAPKData(Request $request,$id)
    // {
    //     $getData = APK::where('active','1')->get();
    //     return $this->sendResponse($getData, 'APK List');
        
    // }
    public function getUserAPKs(Request $request,$id)
    {
        $getData = APK::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'APK List');
        
    }
    public function destroy($id)
    {

        $getData = APK::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'APK has been Deleted');
    }

    public function approve(Request $request, $id)
    {
        // dd($date = );
        $getData = APK::find($id);
        
        if($request['approval_status'] == "Pending"){
            $getData->approver_remark = Null;
            $getData->approval_status = "Pending";
            $getData->approved_date = Null;
            $getData->approved_by = Null;
        }else{
            $getData->approver_remark = $request['approver_remark'];
            $getData->approval_status = $request['approval_status'];
            $getData->approved_date = date('Y-m-d H:i:s');
            $getData->approved_by = auth()->user()->id;
        }
       
        $getData->save();
        return $this->sendResponse($getData, 'APK has been '.$request['approval_status']);
    }
}
