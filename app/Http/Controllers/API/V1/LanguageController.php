<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends BaseController
{
    protected $language = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Language $language)
    {
        // $this->middleware('auth:api');
        $this->language = $language;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $languages = $this->language->where('status','1')->latest()->paginate(10);

        return $this->sendResponse($languages, 'Language list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $languages = $this->language->where('status','1')->get();

        return $this->sendResponse($languages, 'Language list');
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
        // dd($request->get('name'));
        $getData = Language::where('name',$request->get('name'))->where('status','1')->get();
        if(count($getData) > 0){
            return $this->sendResponse($getData, 'Already exist');
        }else{
            // dd(auth()->user());
            $getData = $this->language->create([
                'name' => $request->get('name'),
                // 'created_by' => auth()->user()->id,
            ]);
    
            return $this->sendResponse($getData, 'Language Created Successfully');
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
        
        $getData = $this->language->findOrFail($id);

        $getData->update($request->all());

        return $this->sendResponse($getData, 'Language has been updated');
    }
    public function checkdata(Request $request,$id,$name)
    {
        $getData = Language::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getLanguageData(Request $request,$id)
    {
        $getData = Language::where('active','1')->get();
        return $this->sendResponse($getData, 'Language List');
        
    }
    public function destroy($id)
    {

        $getData = Language::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Language has been Deleted');
    }
}
