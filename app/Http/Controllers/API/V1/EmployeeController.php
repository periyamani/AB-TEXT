<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        // $this->authorize('isAdmin');

        $users = User::where('users.status','1')->where('type','admin')
        ->join('role','role.id','=','users.role')
        ->select('users.*','role.name as role_name')
        ->latest()
        ->paginate(10);

        return $this->sendResponse($users, 'Users list');
    }
    public function list()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return $this->sendResponse([$user], 'User has been Deleted');
    }
}
