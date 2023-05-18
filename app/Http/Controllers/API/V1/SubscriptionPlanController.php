<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends BaseController
{
    protected $subscription = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SubscriptionPlan $subscription)
    {
        $this->middleware('auth:api');
        $this->subscription = $subscription;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $subscriptions = $this->subscription->where('status','1')->latest()->paginate(10);

        return $this->sendResponse($subscriptions, 'Subscription list');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $subscriptions = $this->subscription->where('status','1')->get();

        return $this->sendResponse($subscriptions, 'Subscription list');
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
        $getData = SubscriptionPlan::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request);
            $getData = $this->subscription->create([
                'name' => $request->get('name'),
                'duration' => $request->get('duration'),
                'amount' => $request->get('amount'),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Subscription Created Successfully');
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
        
        $getData = $this->subscription->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Subscription has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = Subscription::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getPlanDetails(Request $request,$id)
    {
        $getData = SubscriptionPlan::where('status','1')->where('id',$id)->first();
        return $this->sendResponse($getData, 'Subscription Plan List');
        
    }
    public function destroy($id)
    {

        $getData = SubscriptionPlan::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Subscription has been Deleted');
    }
}
