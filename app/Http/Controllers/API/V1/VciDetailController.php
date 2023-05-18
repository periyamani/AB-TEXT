<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\VciDetail;
use App\Models\Subscription;
use Illuminate\Http\Request;

class VciDetailController extends BaseController
{
    protected $vcidetail = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VciDetail $vcidetail)
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
        $vcidetails = VciDetail::where('vci_details.status','1')
        ->join('users','users.id','=','vci_details.created_by')
        ->select('vci_details.*','users.name as technician_name')
        ->latest()->paginate(10);

        return $this->sendResponse($vcidetails, 'Product list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $vcidetails = VciDetail::where('status','1')->get();

        return $this->sendResponse($vcidetails, 'Product list');
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
        $getData = VciDetail::where('vci_number',$request->get('vci_number'))->where('user_id',$request->get('user_id'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
 
            $getData = $this->vcidetail->create([
                'name' => $request->get('name'),
                'created_by' => auth()->user()->id,
                'vci_number' => $request->get('vci_number'),
            ]);
    
            return $this->sendResponse($getData, 'Product Created Successfully');
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
        $input = $request->all();
        $input['updated_by'] = auth()->user()->id;
        // dd($input);
        $getData->update($input);

        return $this->sendResponse($getData, 'Product has been updated');
    }

    public function mapping(Request $request, $id)
    {
        
        $getData = $this->vcidetail->findOrFail($id);

        $getData->update(['user_id'=>$request->user_id,'sold_status'=>'1']);

        return $this->sendResponse($getData, 'Product has been Mapped');
    }

    public function checkdata(Request $request,$id,$name)
    {
        $getData = VciDetail::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getVciDetailData(Request $request,$id)
    // {
    //     $getData = VciDetail::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Vci Detail List');
        
    // }
    public function getUserVciDetails(Request $request,$id)
    {
        $getData = VciDetail::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Product List');
        
    }
    public function destroy($id)
    {

        $getData = VciDetail::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Product has been Deleted');
    }
    public function getUnsoldProduct()
    {
        // dd('jhdfkjd');
        $vcidetails = VciDetail::where('vci_details.status','1')->where('vci_details.sold_status','0')
        ->join('users','users.id','=','vci_details.created_by')
        ->select('vci_details.*','users.name as technician_name')
        ->latest()->paginate(10);

        return $this->sendResponse($vcidetails, 'Product list');
    }
    public function getSoldProduct()
    {
        $vcidetails = VciDetail::where('vci_details.status','1')->where('vci_details.sold_status','1')
        ->join('users','users.id','=','vci_details.created_by')
        ->join('users as customer','customer.id','=','vci_details.user_id')
        // ->leftjoin('subscription','subscription.vci_id','=','vci_details.id')
        // ->groupBy('vci_details.status')
        ->select('vci_details.*','users.name as technician_name','customer.name as customer_name','customer.mobile_no as customer_mobile')
        ->latest()->paginate(10);
        if(count($vcidetails)>0){
            if(count($vcidetails) > 0){
                foreach($vcidetails as $key => $val){
                    $functionData = Subscription::where('vci_id',$vcidetails[$key]['id'])->latest()->limit(1)->get();
                    if(count($functionData)){
                        // dd($functionData);
                        $vcidetails[$key]['start_date'] = $functionData[0]->start_date; 
                        $vcidetails[$key]['end_date'] = $functionData[0]->end_date;  
                        $vcidetails[$key]['subscriptionstatus'] = $functionData[0]->active;  
                    }else{
                        $vcidetails[$key]['start_date'] = null; 
                        $vcidetails[$key]['end_date'] = null;  
                        $vcidetails[$key]['subscriptionstatus'] = null;
                    }
                    
                }
            }
        }

        return $this->sendResponse($vcidetails, 'Product list');
    }
}
