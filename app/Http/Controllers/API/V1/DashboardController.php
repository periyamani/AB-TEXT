<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use App\Models\VciDetail;
use App\Models\Subscription;
use App\Models\Complaint;
use App\Models\EcuFlash;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    protected $user = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware('auth:api');
        $this->user = $user;
    }
    public function salesAnalysis(Request $request)
    {
        // dd(Carbon::now());
        $vci = '';
        $subscription = '';
        $totalvci = '';
        $totalamount = '';
        $currentTime = '';
        if($request->duration == 'today'){
            $vci =  VciDetail::where('status','1')->where('created_at', '>=', Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('H');
                });
                $subscription =  Subscription::where('status','1')->where('created_at', '>=',Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('H');
                    });
                $totalvci =  VciDetail::where('status','1')->where('created_at', '>=',Carbon::now()->startOfDay())->count();
                $totalamount =  Subscription::where('status','1')->where('created_at', '>=', Carbon::now()->startOfDay())->sum('amount');
                $currentTime = Carbon::now()->format('H');
        }else if($request->duration == 'month'){
            $vci =  VciDetail::where('status','1')->where('created_at', '>=', Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('d');
                });
                $subscription =  Subscription::where('status','1')->where('created_at', '>=',Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('d');
                    });
                $totalvci =  VciDetail::where('status','1')->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                $totalamount =  Subscription::where('status','1')->where('created_at', '>=', Carbon::now()->startOfMonth())->sum('amount');
                $currentTime = Carbon::now()->format('d');
        }else{
            $vci =  VciDetail::where('status','1')->where('created_at', '>=', Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
                });
                $subscription =  Subscription::where('status','1')->where('created_at', '>=',Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('m');
                    });
                $totalvci =  VciDetail::where('status','1')->where('created_at', '>=', Carbon::now()->startOfYear())->count();
                $totalamount =  Subscription::where('status','1')->where('created_at', '>=', Carbon::now()->startOfYear())->sum('amount');
                $currentTime = Carbon::now()->format('m');
        }
        
    //   dd(Carbon::now()->startOfYear());
        $time = [];
        $count = [];
        $amountData  = [];
        $data = [];
        
        // dd($currentTime);
        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
        for($i = 1 ;$i <= $currentTime;$i++){
            $vciData = 0;
            $subData = 0;
            foreach($vci as $key => $val){
                if($key == $i){
                    $vciData = count($val);
                }
            }
            foreach($subscription as $key1 => $subs){
                if($key1 == $i){
                    $amount = 0;
                    foreach($subs as $key2 => $subData){
                        $amount = $amount + $subData['amount'];
                    }
                    $subData = $amount;
                }
            }
            if($request->duration == 'today'){
            array_push($time,$i.':00');
            }else if($request->duration == 'month'){
                array_push($time,Carbon::now()->format('M').' '.$i);
            }else{
                
                array_push($time,$month[$i-1]);
            }
            if($vciData > 0){
                array_push($count,$vciData);
            }else{
                array_push($count,0);
            }
            if($subData > 0){
                array_push($amountData ,$subData);
            }else{
                array_push($amountData ,0);
            }
        }
        $data['time'] = $time;
        $data['count'] = $count;
        $data['amount'] = $amountData ;
        $data['totalvci']= $totalvci;
        $data['totalamount']= $totalamount;
        return $this->sendResponse($data, 'sales analysis');
    }

    public function complaintAnalysis(Request $request)
    {
        // dd(Carbon::now());
        $open = '';
        $close = '';
        $totalcomplaint = '';
        $openTot = '';
        $closeTot = '';
        $tot = '';
        $currentTime = '';
        if($request->duration == 'today'){
            $open =  Complaint::where('status','1')->where('active','Open')->where('created_at', '>=', Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('H');
                });
                $close =  Complaint::where('status','1')->where('active','Close')->where('updated_at', '>=',Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('H');
                    });
                $totalcomplaint =   Complaint::where('status','1')->where('created_at', '>=',Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('H');
                    });
                    $openTot =  Complaint::where('status','1')->where('active','Open')->where('created_at', '>=', Carbon::now()->startOfDay())->count();
                    $closeTot =  Complaint::where('status','1')->where('active','Close')->where('updated_at', '>=', Carbon::now()->startOfDay())->count();
                    $tot =  Complaint::where('status','1')->where('created_at', '>=', Carbon::now()->startOfDay())->count();
                $currentTime = Carbon::now()->format('H');
        }else if($request->duration == 'month'){
            $open =  Complaint::where('status','1')->where('active','Open')->where('created_at', '>=', Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('d');
                });
                $close =  Complaint::where('status','1')->where('active','Close')->where('updated_at', '>=',Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('d');
                    });
                $totalcomplaint =  Complaint::where('status','1')->where('created_at', '>=',Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('d');
                    });
                    $openTot =  Complaint::where('status','1')->where('active','Open')->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                    $closeTot =  Complaint::where('status','1')->where('active','Close')->where('updated_at', '>=', Carbon::now()->startOfMonth())->count();
                    $tot =  Complaint::where('status','1')->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                $currentTime = Carbon::now()->format('d');
        }else{
            $open =  Complaint::where('status','1')->where('active','Open')->where('created_at', '>=', Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
                });
                $close =  Complaint::where('status','1')->where('active','Close')->where('updated_at', '>=',Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                    return Carbon::parse($date->updated_at)->format('m');
                    });
                $totalcomplaint =  Complaint::where('status','1')->where('created_at', '>=',Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('m');
                    });
                    $openTot =  Complaint::where('status','1')->where('active','Open')->where('created_at', '>=', Carbon::now()->startOfYear())->count();
                    $closeTot =  Complaint::where('status','1')->where('active','Close')->where('updated_at', '>=', Carbon::now()->startOfYear())->count();
                    $tot =  Complaint::where('status','1')->where('created_at', '>=', Carbon::now()->startOfYear())->count();
                $currentTime = Carbon::now()->format('m');
        }
        
    //   dd(Carbon::now()->startOfYear());
        $time = [];
        $count = [];
        $closeData = [];
        $totalData = [];
        $data = [];
        
        // dd($currentTime);
        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
        for($i = 1 ;$i <= $currentTime;$i++){
            $vciData = 0;
            $subData = 0;
            $totData = 0;
            foreach($open as $key => $val){
                if($key == $i){
                    $vciData = count($val);
                }
            }
            foreach($close as $key1 => $val){
                if($key1 == $i){
                    $subData = count($val);
                }
            }
            foreach($totalcomplaint as $key2 => $val){
                if($key2 == $i){
                    $totData = count($val);
                }
            }

            if($request->duration == 'today'){
            array_push($time,$i.':00');
            }else if($request->duration == 'month'){
                array_push($time,Carbon::now()->format('M').' '.$i);
            }else{
                array_push($time,$month[$i-1]);
            }
            if($vciData > 0){
                array_push($count,$vciData);
            }else{
                array_push($count,0);
            }
            if($subData > 0){
                array_push($closeData,$subData);
            }else{
                array_push($closeData,0);
            }
            if($totData > 0){
                array_push($totalData,$totData);
            }else{
                array_push($totalData,0);
            }
        }
        $data['time'] = $time;
        $data['open'] = $count;
        $data['close'] = $closeData;
        $data['totalcomplaint']= $totalData;
        $data['openTot']= $openTot;
        $data['closeTot']= $closeTot;
        $data['tot']= $tot;
        return $this->sendResponse($data, 'Complaint analysis');
    }

    public function registrationanalysis(Request $request)
    {
        // dd(Carbon::now());
        $customerCount = '';
        $tot = '';
        $currentTime = '';
        if($request->duration == 'today'){
            $customerCount =  User::where('type','user')->where('created_at', '>=', Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('H');
                });
                $tot =  User::where('type','user')->where('created_at', '>=', Carbon::now()->startOfDay())->count();
                $currentTime = Carbon::now()->format('H');
        }else if($request->duration == 'month'){
            $customerCount =  User::where('type','user')->where('created_at', '>=', Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('d');
                });
                    $tot =  User::where('type','user')->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                $currentTime = Carbon::now()->format('d');
        }else{
            $customerCount =  User::where('type','user')->where('created_at', '>=', Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
                });
                    $tot =  User::where('type','user')->where('created_at', '>=', Carbon::now()->startOfYear())->count();
                $currentTime = Carbon::now()->format('m');
        }
        
    //   dd(Carbon::now()->startOfYear());
        $time = [];
        $count = [];
        $totalData = [];
        $data = [];
        
        // dd($currentTime);
        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
        for($i = 1 ;$i <= $currentTime;$i++){
            $custData = 0;
            foreach($customerCount as $key => $val){
                if($key == $i){
                    $custData = count($val);
                }
            }

            if($request->duration == 'today'){
            array_push($time,$i.':00');
            }else if($request->duration == 'month'){
                array_push($time,Carbon::now()->format('M').' '.$i);
            }else{
                array_push($time,$month[$i-1]);
            }
            if($custData > 0){
                array_push($count,$custData);
            }else{
                array_push($count,0);
            }
        }
        $data['time'] = $time;
        $data['customer'] = $count;
        $data['tot']= $tot;
        return $this->sendResponse($data, 'Complaint analysis');
    }
    public function flashanalysis(Request $request)
    {
        // dd(Carbon::now());
        $customerCount = '';
        $tot = '';
        $currentTime = '';
        if($request->duration == 'today'){
            $customerCount =  EcuFlash::where('created_at', '>=', Carbon::now()->startOfDay())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('H');
                });
                $tot =  EcuFlash::where('created_at', '>=', Carbon::now()->startOfDay())->count();
                $currentTime = Carbon::now()->format('H');
        }else if($request->duration == 'month'){
            $customerCount =  EcuFlash::where('created_at', '>=', Carbon::now()->startOfMonth())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('d');
                });
                    $tot =  EcuFlash::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
                $currentTime = Carbon::now()->format('d');
        }else{
            $customerCount =  EcuFlash::where('created_at', '>=', Carbon::now()->startOfYear())->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
                });
                    $tot =  EcuFlash::where('created_at', '>=', Carbon::now()->startOfYear())->count();
                $currentTime = Carbon::now()->format('m');
        }
        
    //   dd($currentTime);
        $time = [];
        $count = [];
        $totalData = [];
        $data = [];
        
        // dd($customerCount);
        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
        for($i = 1 ;$i <= $currentTime;$i++){
            $custData = 0;
            foreach($customerCount as $key => $val){
                if($key == $i){
                    $custData = count($val);
                }
            }

            if($request->duration == 'today'){
            array_push($time,$i.':00');
            }else if($request->duration == 'month'){
                array_push($time,Carbon::now()->format('M').' '.$i);
            }else{
                array_push($time,$month[$i-1]);
            }
            if($custData > 0){
                array_push($count,$custData);
            }else{
                array_push($count,0);
            }
        }
        $data['time'] = $time;
        $data['flash'] = $count;
        $data['tot']= $tot;
        return $this->sendResponse($data, 'Flash analysis');
    }
    public function dashboardData(Request $request)
    {
        // dd(Carbon::now());
        $data = [];
        $app = DB::table('apk_management')->where('approval_status','Approved')->latest()->first();
        $hex = DB::table('hex_file')->where('file_status','Approved')->latest()->first();
        $data['app'] = $app;
        $data['hex'] = $hex;
        return $this->sendResponse($data, 'Dashboard Recent Activities');
    }
    

}
