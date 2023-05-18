<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Users\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // if (!\Gate::allows('isAdmin')) {
       //     return $this->unauthorizedResponse();
       // }
        // $this->authorize('isAdmin');

        $users = User::where('status','1')->where('type','user')->latest()
        ->paginate(10);

        return $this->sendResponse($users, 'Users list');
    }
    public function list()
    {
        // if (!\Gate::allows('isAdmin')) {
        //     return $this->unauthorizedResponse();
        // }
        // $this->authorize('isAdmin');

        $users = User::where('status','1')->where('type','user')->latest()
        ->get();

        return $this->sendResponse($users, 'Users list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->get('name'),
            'name2' => $request->get('name2'),
            'accName' => $request->get('accName'),
                'email' => $request->get('email'),
                'mobile_no' => $request->get('mobile_no'),
                'telNo' => $request->get('telNo'),
                'address1' => $request->get('address1'),
                'address2' => $request->get('address2'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'shortCode' => $request->get('shortCode'),
                'firm' => $request->get('firm'),
                'country' => $request->get('country'),
                'pin' => $request->get('pin'),
                'stockAC' => $request->get('stockAC'),
                'transport' => $request->get('transport'),
                'bookingPlace' => $request->get('bookingPlace'),
                'through' => $request->get('through'),
                'llName' => $request->get('llName'),
                'ledgerRole' => $request->get('ledgerRole'),
                'ledgerStatus' => $request->get('ledgerStatus'),
                'line' => $request->get('line'),
                'area' => $request->get('area'),
                'password' => Hash::make($request->get('password')),
                'type' => 'user',
                // 'created_by' => auth()->user()->id,
        ]);

        return $this->sendResponse($user, 'User Created Successfully');
    }

    /**
     * Update the resource in storage
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());

        return $this->sendResponse($user, 'User Information has been updated');
    }
    
    public function mobileupdate(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());

        return $this->sendResponse($user, 'User Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return $this->sendResponse([$user], 'User has been Deleted');
    }
    public function approve($id)
    {
        $user = User::find($id);
        $user->approved_status = 1;
        $user->approved_by = auth()->user()->id;
        $user->save();
        return $this->sendResponse([$user], 'User has been Deleted');
    }
    public function unapprove($id)
    {
        $user = User::find($id);
        $user->approved_status = 0;
        $user->approved_by = auth()->user()->id;
        $user->save();
        return $this->sendResponse([$user], 'User has been Deleted');
    }

    public function pin_set(Request $request)
    {
   // dd($request->all());
        $user = User::find($request['id']);
        $user->device_id = $request['device_id'];
        $user->pin_number = $request['pin_number'];
        $user->save();

        return $this->sendResponse($user, 'User Information has been updated');
    }
     public function getProfile(Request $request)
    {
   // dd($request->all());
        $user = User::where('id',$request['id'])->first();

        return $this->sendResponse($user, 'User Information');
    }
    public function sendEmail(Request $request)
    {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    switch ($status) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'status'        => 'success',
                    'message' => 'Password reset link send into mail.',
                    'data' =>''], 201);
            case Password::INVALID_USER:
                return response()->json([
                    'status'        => 'failed',
                    'message' =>   'Unable to send password reset link.'
                ], 401);
        }  
}
public function resetEmailCheck(Request $request)
    {
 
        $user = User::where('email',$request['email'])->get();
	if(count($user) > 0){
	return response()->json([
                    'status'        => 'success',
                    'message' => 'Valid Email ID'], 200);
	}else{
	return response()->json([
                    'status'        => 'failed',
                    'message' => 'Invalid Email ID'], 500);
	}
    }
    
    public function resetPassword(Request $request)
    {
 
        $user = User::where('email',$request['email'])->get();
	if(count($user) > 0){
		$getData = User::find($user[0]->id);;
            $getData->password = Hash::make($request['password']);
            $getData->save();
            return response()->json([
                    'status'        => 'success',
                    'message' => 'Password Reseted Sucessfully'], 200);
	}else{
	return response()->json([
                    'status'        => 'failed',
                    'message' => 'Password Not Reseted'], 500);
	}
    }
    public function setLang(Request $request)
    {
        $user = User::find($request['user']);
        $user->language = $request['id']; 
        $user->save();

        return $this->sendResponse($user, 'User Language has been updated');
    }

}