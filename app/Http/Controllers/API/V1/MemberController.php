<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class MemberController extends BaseController
{
    protected $members = '';

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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = $this->user->where('active','1')
        ->where('type','user')
        ->join('basic_details as basic','basic.user_id','=','users.id')
        ->select('users.*','basic.gender as gender','basic.age as age')
        ->latest()->paginate(10);

        return $this->sendResponse($members, 'Members list');
    }

    public function fulldetails(Request $request , $id)
    {
        
        $members = $this->user->where('users.id',$id)->where('type','user')
        ->join('basic_details as basic','basic.user_id','=','users.id')
        ->leftjoin('m_mother_tongue as tongue','tongue.id','=','basic.mother_togue')
        ->leftjoin('m_religion as religion','religion.id','=','basic.religion')
        ->leftjoin('m_caste as caste','caste.id','=','basic.caste')
        ->leftjoin('m_country as country','country.id','=','basic.country')
        ->leftjoin('m_state as state','state.id','=','basic.state')
        ->leftjoin('m_district as district','district.id','=','basic.district')
        ->leftjoin('m_taluk as city','city.id','=','basic.city')
        ->select('users.email as email','users.mobile as mobile','basic.*','basic.photo as photoname',
        'religion.name as religionName','tongue.name as tongueName','caste.name as casteName',
        'country.name as countryName','state.name as stateName','district.name as districtName',
        'city.name as cityName','users.mobile_verify as mobile_verify','users.email_verify as email_verify','users.proof_verify as proof_verify','users.subscription as subscription'
        )
        ->first();
        return $this->sendResponse($members, 'Members list');
    }

    public function personaldetails(Request $request , $id)
    {
        
        $members = $this->user->where('users.id',$id)->where('type','user')
        ->join('personal_details as basic','basic.user_id','=','users.id')
        ->leftjoin('m_star as star','star.id','=','basic.star')
        ->leftjoin('m_rasi as rasi','rasi.id','=','basic.rasi')
        ->leftjoin('m_dhosam as dhosam','dhosam.id','=','basic.dhosam')
        ->leftjoin('m_education as education','education.id','=','basic.education')
        ->leftjoin('m_occupation as occupation','occupation.id','=','basic.occupation')
        ->select('basic.*','basic.proof_image as photoname',
        'star.name as starName','rasi.name as rasiName','dhosam.name as dhosamName',
        'education.name as educationName','occupation.name as occupationName'
        )
        ->first();
        return $this->sendResponse($members, 'Members list');
    }

    public function familydetails(Request $request , $id)
    {
        
        $members = $this->user->where('users.id',$id)->where('type','user')
        ->join('family_details as basic','basic.user_id','=','users.id')
        ->select('basic.*')
        ->first();
        return $this->sendResponse($members, 'Members list');
    }

    public function partnerdetails(Request $request , $id)
    {
        
        $members = $this->user->where('users.id',$id)->where('type','user')
        ->join('partner_details as basic','basic.user_id','=','users.id')
        ->leftjoin('m_mother_tongue as tongue','tongue.id','=','basic.mother_togue')
        ->leftjoin('m_religion as religion','religion.id','=','basic.religion')
        ->leftjoin('m_caste as caste','caste.id','=','basic.caste')
        ->leftjoin('m_country as country','country.id','=','basic.country')
        ->leftjoin('m_state as state','state.id','=','basic.state')
        ->leftjoin('m_district as district','district.id','=','basic.district')
        ->leftjoin('m_taluk as city','city.id','=','basic.city')
        ->leftjoin('m_star as star','star.id','=','basic.star')
        ->leftjoin('m_rasi as rasi','rasi.id','=','basic.rasi')
        ->leftjoin('m_dhosam as dhosam','dhosam.id','=','basic.dosham')
        ->leftjoin('m_education as education','education.id','=','basic.education')
        ->leftjoin('m_occupation as occupation','occupation.id','=','basic.occupation')
        ->select('basic.*',
        'religion.name as religionName','tongue.name as tongueName','caste.name as casteName',
        'country.name as countryName','state.name as stateName','district.name as districtName',
        'city.name as cityName','star.name as starName','rasi.name as rasiName','dhosam.name as dhosamName',
        'education.name as educationName','occupation.name as occupationName')
        ->first();
        return $this->sendResponse($members, 'Members list');
    }
  
    public function membersdelete($id)
    {

        $delete = $this->user->where('id',$id)->delete();
        $basic = DB::table('basic_details')->where('user_id',$id)->delete();
        $personal = DB::table('personal_details')->where('user_id',$id)->delete();
        $family = DB::table('family_details')->where('user_id',$id)->delete();
        $partner = DB::table('partner_details')->where('user_id',$id)->delete();
        return $this->sendResponse($delete, 'Member has been Deleted');
    }

}
