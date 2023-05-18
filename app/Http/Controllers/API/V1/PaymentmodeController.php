<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\PaymentMode;
use Illuminate\Http\Request;

class PaymentModeController extends BaseController
{
    protected $paymentmode = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentMode $paymentmode)
    {
        $this->middleware('auth:api');
        $this->paymentmode = $paymentmode;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $paymentmodes = $this->paymentmode->where('status','1')->latest()->paginate(10);

        return $this->sendResponse($paymentmodes, 'Payment Mode list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $paymentmodes = $this->paymentmode->where('status','1')->get();

        return $this->sendResponse($paymentmodes, 'Payment Mode list');
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
        $getData = PaymentMode::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request);
            $getData = $this->paymentmode->create([
                'name' => $request->get('name'),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Payment Mode Created Successfully');
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
        
        $getData = $this->paymentmode->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Payment Mode has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = PaymentMode::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getPaymentModeData(Request $request,$id)
    {
        $getData = PaymentMode::where('active','1')->get();
        return $this->sendResponse($getData, 'Payment Mode List');
        
    }
    public function destroy($id)
    {

        $getData = PaymentMode::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Payment Mode has been Deleted');
    }
}
