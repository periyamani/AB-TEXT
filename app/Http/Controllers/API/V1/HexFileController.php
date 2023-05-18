<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\HexFile;
use App\Models\EcuDetail;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Validator;

class HexFileController extends BaseController
{
    protected $hexfile = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HexFile $hexfile)
    {
        $this->middleware('auth:api');
        $this->hexfile = $hexfile;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $hexfile = $this->hexfile->where('hex_file.status','1')
        ->join('ecu_details','ecu_details.id','=','hex_file.ecu_id')
        ->join('vehicle_details','vehicle_details.id','=','hex_file.vehicle_id')
        ->join('users as technician','technician.id','=','hex_file.created_by')
        ->leftjoin('users as approver','approver.id','=','hex_file.approved_by')
        ->select('hex_file.*','ecu_details.name as ecu_name','vehicle_details.name as vehicle_name','approver.name as approver_name','technician.name as technician_name')
        ->latest()->paginate(10);
        return $this->sendResponse($hexfile, 'HexFile list');
    }

    public function getHexFile(Request $request)
    {
        // dd('jhdfkjd');
        $hexfile = $this->hexfile->where('status','1')->where('ecu_id',$request->ecu_id)
        ->where('vehicle_id',$request->vehicle_id)
        ->latest()->first();
        return $this->sendResponse($hexfile, 'HexFile list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $hexfile = $this->hexfile->where('status','1')->get();

        return $this->sendResponse($hexfile, 'HexFile list');
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
        //  dd($request->all());
        $getData = HexFile::where('vehicle_id',$request->get('vehicle_id'))->where('ecu_id',$request->get('ecu_id'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($request->all(), 'Already exist');
        }else{
            $onimage = $request->file('hex_file');
            $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
            $destinationPath = public_path('/images/hexfile');
            $onimage->move($destinationPath,$onimage_name);
            $getSize = File::size($request->file('hex_file'));
            $getData = new HexFile;
            $getData->vehicle_id = $request['vehicle_id'];
            $getData->ecu_id = $request['ecu_id'];
            $getData->name = $request['name'];
            $getData->hex_file = $onimage_name; 
            $getData->file_path = '/images/hexfile/'.$onimage_name; 
            $getData->file_size = number_format($getSize / 1048576,2).' MB';
            $getData->created_by = auth()->user()->id;
            $getData->remark = $request['remark'];
            $getData->save();
    
            return $this->sendResponse($getData, 'Hex File Created Successfully');
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
       
        $onimage_name = null;
        if(isset($request->image) && $request->hasFile('hex_file')){
         $onimage = $request->file('hex_file');
         $oldImage = HexFile::find($id);
         if($oldImage->hex_file){
             File::delete('images/hexfile/'.$oldImage->image);
         }
         $onimage_name = time().'1.'.$onimage->getClientOriginalExtension();
         $destinationPath = public_path('/images/hexfile');
         $onimage->move($destinationPath,$onimage_name);
         $getSize = File::size($request->file('hex_file'));
        }
        $getData = HexFile::find($id);
        $getData->vehicle_id = $request['vehicle_id'];
            $getData->ecu_id = $request['ecu_id'];
            $getData->name = $request['name'];
        if($onimage_name){
            $getData->hex_file = $onimage_name; 
            $getData->file_path = '/images/hexfile/'.$onimage_name; 
            $getData->file_size = number_format($getSize / 1048576,2).' MB';
        }
        $getData->updated_by = auth()->user()->id;
        $getData->remark = $request['remark'];
        $getData->save();

        return $this->sendResponse($getData, 'Hex File has been updated');
    }
  
    public function approve(Request $request, $id)
    {
        // dd($request->all());
        $getData = HexFile::find($id);
        if($request['file_status'] == "Pending"){
            $getData->approved_by = Null;
            $getData->approver_remark = Null;
            $getData->file_status = "Pending";
            $getData->approved_date = Null;
        }else{
            $getData->approved_by = auth()->user()->id;
            $getData->approver_remark = $request['approver_remark'];
            $getData->file_status = $request['file_status'];
            $getData->approved_date = date('Y-m-d H:i:s');
        }
        
        $getData->save();
        return $this->sendResponse($getData, 'Hex File has been '.$request['file_status']);
    }

    public function destroy($id)
    {
        $getData = HexFile::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Hex File has been Deleted');
    }
}
