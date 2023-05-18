<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use File;

class ComplaintController extends BaseController
{
    protected $complaint = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Complaint $complaint)
    {
        $this->middleware('auth:api');
        $this->complaint = $complaint;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('jhdfkjd');
        $complaints = $this->complaint->where('complaints.status','1')
        ->join('users','users.id','=','complaints.dealer_id')
        ->leftjoin('users as tech','tech.id','=','complaints.updated_by')
        ->select('complaints.*','users.name as dealer_name','users.mobile_no as dealer_mobile','tech.name as technician_name')
        ->latest()->paginate(10);

        return $this->sendResponse($complaints, 'Complaint list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $complaints = $this->complaint->where('status','1')->get();

        return $this->sendResponse($complaints, 'Complaint list');
    }
    public function getList(Request $request)
    {
        $complaints = $this->complaint->where('status','1')->where('dealer_id',$request->user_id)->get();

        return $this->sendResponse($complaints, 'Complaint list');
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
        
            // dd($request);
            $audio_name = Null;
            $video_name = Null;
            $doc_name = Null;
            if(isset($request->audio) && $request->hasFile('audio')){
                $onaudio = $request->file('audio');
                $audio_name = time().'1.'.$onaudio->getClientOriginalExtension();
                $destinationPath = public_path('/images/complaint_audio');
                $onaudio->move($destinationPath,$audio_name);
               }
               if(isset($request->video) && $request->hasFile('video')){
                $onvideo = $request->file('video');
                $video_name = time().'1.'.$onvideo->getClientOriginalExtension();
                $destinationPath = public_path('/images/complaint_video');
                $onvideo->move($destinationPath,$video_name);
               }
               if(isset($request->document) && $request->hasFile('document')){
                $ondocument = $request->file('document');
                $doc_name = time().'1.'.$ondocument->getClientOriginalExtension();
                $destinationPath = public_path('/images/complaint_document');
                $ondocument->move($destinationPath,$doc_name);
               }

            $openDate = date("d/m/Y");

            $getData = new Complaint;
            $getData->category = $request['category'];
            $getData->remark = $request['remark'];
            $getData->dealer_id = $request['dealer_id'];
            $getData->open_date = $openDate;
            if($audio_name){
                $getData->complaint_audio = $audio_name;
                $getData->audio_path = '/images/complaint_audio/'.$audio_name; 
            }
            if($video_name){
                $getData->complaint_video = $video_name;
                $getData->video_path = '/images/complaint_video/'.$video_name; 
            }
            if($doc_name){
                $getData->complaint_document = $doc_name;
                $getData->doc_path = '/images/complaint_document/'.$doc_name; 
            }
            // $getData->close_remark = $request->get('close_remark');
            $getData->save();
            // $getData = $this->complaint->create([
            //     'category' => $request->get('name'),
            //     'remark' => $request->get('remark'),
            //     'dealer_id' => $request->get('user_id'),
            //     'open_date' => $openDate,
            //     'close_remark' => $request->get('close_remark'),
            // ]);
    
            return $this->sendResponse($getData, 'Complaint Created Successfully');
        
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
        $closeDate = date("d/m/Y");
        $input = $request->all();
        $getData = $this->complaint->findOrFail($id);
        if($input['active'] == 'Open'){
            $input['updated_by'] = null;
            $input['close_date'] = null;
            $input['close_remark'] = null;
        }else{
            // dd(auth()->user()->id);
            $input['updated_by'] = auth()->user()->id;
            $input['close_date'] = $closeDate;
            $input['close_remark'] = $input['close_remark'];
        }
        // dd($input);
        $getData->update($input);

        return $this->sendResponse($getData, 'Complaint has been updated');
    }

    public function userupdate(Request $request,$id)
    {
        $input = $request->all();
        // dd($input);
        $getData = $this->complaint->findOrFail($request->id);
        $audio_name = Null;
        $video_name = Null;
        $doc_name = Null;
        if(isset($request->audio) && $request->hasFile('audio')){
            $onaudio = $request->file('audio');
            $audio_name = time().'1.'.$onaudio->getClientOriginalExtension();
            $destinationPath = public_path('/images/complaint_audio');
            $onaudio->move($destinationPath,$audio_name);
           }
           if(isset($request->video) && $request->hasFile('video')){
            $onvideo = $request->file('video');
            $video_name = time().'1.'.$onvideo->getClientOriginalExtension();
            $destinationPath = public_path('/images/complaint_video');
            $onvideo->move($destinationPath,$video_name);
           }
           if(isset($request->document) && $request->hasFile('document')){
            $ondocument = $request->file('document');
            $doc_name = time().'1.'.$ondocument->getClientOriginalExtension();
            $destinationPath = public_path('/images/complaint_document');
            $ondocument->move($destinationPath,$doc_name);
           }

        $openDate = date("d/m/Y");

        $getData = Complaint::findOrFail($id);
        $getData->category = $request['category'];
        $getData->remark = $request['remark'];
        $getData->dealer_id = $request['dealer_id'];
        $getData->open_date = $openDate;
        if($audio_name){
            $getData->complaint_audio = $audio_name;
            $getData->audio_path = '/images/complaint_audio/'.$audio_name; 
        }
        if($video_name){
            $getData->complaint_video = $video_name;
            $getData->video_path = '/images/complaint_video/'.$video_name; 
        }
        if($doc_name){
            $getData->complaint_document = $doc_name;
            $getData->doc_path = '/images/complaint_document/'.$doc_name; 
        }
        // $getData->close_remark = $request->get('close_remark');
        $getData->save();
        // $getData = $this->complaint->create([
        //     'category' => $request->get('name'),
        //     'remark' => $request->get('remark'),
        //     'dealer_id' => $request->get('user_id'),
        //     'open_date' => $openDate,
        //     'close_remark' => $request->get('close_remark'),
        // ]);
        // $getData->update($input);

        return $this->sendResponse($getData, 'Complaint has been updated');
    }

    public function checkdata(Request $request,$id,$name)
    {
        $getData = Complaint::where('name',$name)->where('active','1')->get();
        if(count($getData) > 0){
            return response()->json(['data' => true,'message' => 'Already Exist!']);
        }else{
            return response()->json(['data' => false]);
        }
        
    }
    public function getComplaintData(Request $request,$id)
    {
        $getData = Complaint::where('active','1')->get();
        return $this->sendResponse($getData, 'Complaint List');
        
    }
    public function destroy($id)
    {

        $getData = Complaint::find($id);
        $getData->status = 0;
        $getData->save();
        return $this->sendResponse($getData, 'Complaint has been Deleted');
    }
}
