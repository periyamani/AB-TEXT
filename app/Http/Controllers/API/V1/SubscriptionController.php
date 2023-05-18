<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Mail;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\VciDetail;

class SubscriptionController extends BaseController
{
    protected $subscription = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
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
        $subscriptions = $this->subscription->where('subscription.status','1')
        ->join('users','users.id','=','subscription.user_id')
        ->join('vci_details','vci_details.id','=','subscription.vci_id')
        ->select('subscription.*','users.name as custname','users.mobile_no as custmobile','vci_details.name as vciname',
        'vci_details.vci_number as vcinumber')
        ->latest()->paginate(10);

        return $this->sendResponse($subscriptions, 'Subscription list');
    }

    public function getUserSubscription(Request $request)
    {
        // dd('jhdfkjd');
        $subscriptions = $this->subscription->where('subscription.status','1')->where('subscription.user_id', $request->user_id)
        ->join('users','users.id','=','subscription.user_id')
        ->join('vci_details','vci_details.id','=','subscription.vci_id')
        ->select('subscription.*','users.name as custname','users.mobile_no as custmobile','vci_details.name as vciname',
        'vci_details.vci_number as vcinumber')
        ->latest()->get();

        return $this->sendResponse($subscriptions, 'Subscription list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $subscriptions = $this->subscription->where('active','1')->pluck('name', 'id','amount','duration');

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
        
            
            $monthCount = explode(' ',$request->get('duration'));
            $startDate = date("d/m/Y");
            // dd($monthCount);
            $endDate  = date("d/m/Y", strtotime($monthCount[0]." month"));
// dd($request->all());
if($request->get('payment_mode_id') == '1'){
    $getData = $this->subscription->create([
        'user_id' => $request->get('user_id'),
        'vci_id' => $request->get('vci_id'),
        'payment_mode_id' => $request->get('payment_mode_id'),
        'subscription_plan_id' => $request->get('subscription_plan_id'),
        'start_date' => $startDate,
        'end_date' => $endDate,
        'amount' => $request->get('amount'),
        'duration' => $monthCount[0],
        'created_by' => auth()->user()->id,
    ]);
    $user = User::find($request->get('user_id'))->toArray();
    $plan = SubscriptionPlan::where('id', $request->get('subscription_plan_id'))->first();
    $vci = VciDetail::where('id',$request->get('vci_id'))->first();
    $user['plan']= $plan;
    $user['vci']= $vci;
    $user['subscription']= $getData;
    $uri = $request->path();
    // dd(base_url());
    
    Mail::send('emails.mail', $user, function($message) use ($user) {
        $message->to($user['email']);
        $message->subject('Subscription');
    });

    return $this->sendResponse($getData, 'Subscription added successfully.Payment link sent email.');
}else{
    $getData = $this->subscription->create([
        'user_id' => $request->get('user_id'),
        'vci_id' => $request->get('vci_id'),
        'payment_mode_id' => $request->get('payment_mode_id'),
        'subscription_plan_id' => $request->get('subscription_plan_id'),
        'start_date' => $startDate,
        'end_date' => $endDate,
        'amount' => $request->get('amount'),
        'duration' => $monthCount[0],
        'created_by' => auth()->user()->id,
        'payment_status' => 'Completed'
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
        
        $getDatas = $this->subscription->findOrFail($id);
        $monthCount = explode(' ',$request->get('duration'));
        
        $startDate = date("d/m/Y");
        $endDate  = date("Y/m/d", strtotime($monthCount[0]." month"));

        $getData = $getDatas->update([
            'user_id' => $request->get('user_id'),
            'vci_id' => $request->get('vci_id'),
            'payment_mode_id' => $request->get('payment_mode_id'),
            'subscription_plan_id' => $request->get('subscription_plan_id'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'amount' => $request->get('amount'),
            'duration' => $monthCount[0],
        ]);
        // $getData->update($getData->all());

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
    public function getSubscriptionData(Request $request,$id)
    {
        $getData = Subscription::where('active','1')->get();
        return $this->sendResponse($getData, 'Subscription List');
        
    }
    public function destroy($id)
    {

        $getData = Subscription::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Subscription has been Deleted');
    }
}
