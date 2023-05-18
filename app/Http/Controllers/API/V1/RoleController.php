<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use DB;
use View;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends BaseController
{
    protected $role = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->middleware('auth:api');
        $this->role = $role;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        // $rolelist = DB::table('role')->where('id',auth()->user()->role)->first();
        // $parseList = json_decode($rolelist->permission);
        // if(in_array("role_list",$parseList) != ''){
            $roles = $this->role->where('role.status','1')
            ->latest()->paginate(10);
            if(count($roles) > 0){
                foreach($roles as $key => $val){
                    $ecuData = [];
                    if($roles[$key]['permission']){
                        $ecuData = DB::table('permission')->whereIn('permission',json_decode($roles[$key]['permission']))->get(['name','permission']);
                    }
                    $roles[$key]['permissionlist'] = $ecuData; 
                }
            }
            return $this->sendResponse($roles, 'Role list');
        // }else{
        //     //  return view('notfound');
        //      return redirect('notfound');
             
        // }
        
    }

    public function getRoleDetails(Request $request)
    {
        // dd('jhdfkjd');
        $roles = $this->role->where('role.status','1')
        ->latest()->paginate(10);
        if(count($roles) > 0){
            foreach($roles as $key => $val){
                $ecuData = [];
                if($roles[$key]['permission']){
                    $ecuData = DB::table('permission')->where('permission',json_decode($roles[$key]['permission']))->get(['name','permission','id']);
                }
                $roles[$key]['permissionlist'] = $ecuData; 
            }
        }
        return $this->sendResponse($roles, 'Role list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $roles = $this->role->where('status','1')->get();

        return $this->sendResponse($roles, 'Role list');
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
        // dd($request->all());
        $getData = Role::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd($request);
            $getData = $this->role->create([
                'name' => $request->get('name'),
                'permission' => json_encode($request->get('permission')),
                'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Role Created Successfully');
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
        
        $getData = $this->role->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Role has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = Role::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    // public function getRoleData(Request $request,$id)
    // {
    //     $getData = Role::where('active','1')->get();
    //     return $this->sendResponse($getData, 'Role List');
        
    // }
    public function getUserRoles(Request $request,$id)
    {
        $getData = Role::where('status','1')->where('user_id',$id)->get();
        return $this->sendResponse($getData, 'Role List');
        
    }
    public function destroy($id)
    {

        $getData = Role::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Role has been Deleted');
    }

    public function permissionList()
    {

        $getData = DB::table('permission')->where('status','1')->get();
        return $this->sendResponse($getData, 'Permission List');
    }

}
