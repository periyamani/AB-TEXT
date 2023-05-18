<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\SubscriptionPlan;
use Session;
use Redirect;
use App\Models\Subscription;

class RazorpayController extends Controller
{    
    public function payWithRazorpay()
    {        
        return view('payWithRazorpay');
    }

    public function payment(Request $request)
    {
        $input = $request->all();
        // dd(env('RAZORPAY_KEY'));
        $api = new Api('rzp_test_UViP0JYG0M2kWq', 'yAZxVQhKOiuxTdVLC9yyLWRb');
       
        $payment = $api->payment->fetch($request->razorpay_payment_id);
      
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {

                $payment->capture(array('amount'=>$payment['amount']));
               
            } catch (\Exception $e) {
                return  $e->getMessage();
                return redirect()->back();
            }
        }

        $getDatas = Subscription::findOrFail($request->id);

        $getData = $getDatas->update([
            'razor_pay_id' => $request->razorpay_payment_id,
            'payment_status' => 'Completed',
        ]);

        return response()->json(['success' => 'Payment successful']);
    }
}