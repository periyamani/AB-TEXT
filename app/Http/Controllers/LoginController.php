<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function userLogin(Request $request) {
        // dd($request->all());
        if(Auth::attempt(['email' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $token = Auth::user()->createToken('ARDUNIO')->accessToken;
            return response()->json(['token' => $token,'user_id' => $user->id, 'statusCode'=>200]);
        }
        else {
            return response()->json(['loginDetail' => 'Unauthorised', 'statusCode'=>500]);
        }
    }

    public function register(Request $request) {
        dd($request->all());

        $getData = User::where('email',$request->get('email'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Email ID Already exist');
        }else{
        $user = User::create([
            'name' => $request->get('name'),
                'email' => $request->get('email'),
                'mobile_no' => $request->get('mobile_no'),
                'address1' => $request->get('address1'),
                'address2' => $request->get('address2'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'role' => $request->get('role'),
                'password' => Hash::make($request->get('password')),
                'type' => 'admin',
                'created_by' => auth()->user()->id,
        ]);
       }
        if(Auth::attempt(['email' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $token = Auth::user()->createToken('ARDUNIO')->accessToken;
            return response()->json(['token' => $token,'user_id' => $user->id, 'statusCode'=>200,'message'=>'Registration Completed']);
        }
        else {
            return response()->json(['loginDetail' => 'Unauthorised', 'statusCode'=>500]);
        }
    }
    
}
