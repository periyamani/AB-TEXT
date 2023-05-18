<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\HealthTestStructure;
use Illuminate\Http\Request;


class HealthTestStructureController extends BaseController
{
    protected $healthteststructure = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HealthTestStructure $healthteststructure)
    {
        // $this->middleware('auth:api');
        $this->healthteststructure = $healthteststructure;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $healthteststructures = $this->healthteststructure->where('status','1')->latest()->paginate(10);

        return $this->sendResponse($healthteststructures, 'Health Test Structure list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $healthteststructures = $this->healthteststructure->where('status','1')->get();

        return $this->sendResponse($healthteststructures, 'Health Test Structure list');
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
    public function addHealthTestStructure(Request $request)
    {
       
        $listData = [];
        if(count($request->category) > 0){
            for($i=0;$i<count($request->category);$i++){
                $list;
                $list['category'] = $request->category[$i];
                $list['field']= $request->field[$i];
                $list['type']= $request->type[$i];
                $list['value']= "";
                array_push($listData,$list);
            };
        }
       
        $getData = HealthTestStructure::where('vin_number',$request->get('vin_number'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'VIN Number Already exist');
        }else{
            // dd(auth()->user());
            $getData = $this->healthteststructure->create([
                'name' => $request->get('name'),
                'vin_number' => $request->get('vin_number'),
                'field' => json_encode($listData),
                // 'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Health Test Structure Created Successfully');
        }
        
    }

    public function updateHealthTestStructure(Request $request)
    {
       
        $listData = [];
        if(count($request->category) > 0){
            for($i=0;$i<count($request->category);$i++){
                $list;
                $list['category'] = $request->category[$i];
                $list['field']= $request->field[$i];
                $list['type']= $request->type[$i];
                $list['value']= "";
                array_push($listData,$list);
            };
        }
            $getData = HealthTestStructure::find($request->get('id'));
            $getData->name = $request->get('name');
            $getData->vin_number = $request->get('vin_number');
            $getData->field = json_encode($listData);
            $getData->save();
    
            return $this->sendResponse($getData, 'Health Test Structure Updated Successfully');
        
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
        
        $getData = $this->healthteststructure->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Health Test Structure has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = HealthTestStructure::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getHealthTestStructureData(Request $request,$id)
    {
        $getData = HealthTestStructure::where('active','1')->get();
        return $this->sendResponse($getData, 'Health Test Structure List');
        
    }
    public function destroy($id)
    {

        $getData = HealthTestStructure::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Health Test Structure has been Deleted');
    }
    public function enableData($id,$name)
    {
        // dd($request->all());
        $getData = HealthTestStructure::find($id);
        if($name == 'Enable'){
            $getData->active = 1;
        }else{
            $getData->active = 0;
        }
                $getData->save();
        return $this->sendResponse($getData, 'Health Test Structure has been '.$name.'d');
    }
    public function updatadatahealthteststructure($id)
    {
        // dd($id);
        $getData = HealthTestStructure::where('id',$id)->first();
       
        return $this->sendResponse($getData, 'Health Test Structure List');
    }

    public function healtestView(Request $request)
    {
        $getData = HealthTestStructure::where('vin_number',$request->vin_number)->where('active','1')->first();
        $fieldData = $getData->field;
        $getData['field'] = json_decode($fieldData);
        $customerDetails['name'] = "";
        $customerDetails['mobile'] = "";
        $customerDetails['address'] = "";
        $customerDetails['city'] = "";
        $getData['customer_details'] = $customerDetails;
        $vehicle['name'] = "";
        $vehicle['year'] = "";
        $vehicle['modal'] = "";
        $vehicle['vin'] = "";
        $vehicle['vehicle_id'] = "";
        $getData['vehicle_details'] = $vehicle;
        $dealer['dealer_id'] = "";
        $dealer['dealer_comments'] = "";
        $dealer['delivery_date'] = "";
        $getData['dealer_details'] = $dealer;
        unset($getData['stage2']);
        unset($getData['stage3']);
        unset($getData['active']);
        unset($getData['status']);
        unset($getData['created_by']);
        unset($getData['created_at']);
        unset($getData['updated_at']);
        return $this->sendResponse($getData, 'Health Test Structure List');
        
    }
   
    
}
