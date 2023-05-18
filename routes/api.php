<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Passport\HasApiTokens;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// MOBILE API
Route::post('apiregister', function (Request $request) {
    // return response()->json(['version' => config('app.version')]);
    // dd($request->all()); 
    $getData = User::where('email',$request->get('email'))->where('status','1')->get();
    if(count($getData) > 0){
        return response()->json(['message' => 'Email ID Already exist']);
    }else{
    $user = User::create([
        'name' => $request->get('name'),
            'email' => $request->get('email'),
            'mobile_no' => $request->get('mobile_no'),
            'address1' => $request->get('address1'),
            'address2' => $request->get('address2'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'password' => Hash::make($request->get('password')),
            'type' => 'user',
    ]);
   }
   if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    $user = Auth::user();
    $token = Auth::user()->createToken('ISMT')->accessToken;
    return response()->json(['token' => $token,'user_id' => $user->id, 'statusCode'=>200,'message'=>'Registration Completed']);
}
else {
    return response()->json(['loginDetail' => 'Unauthorised', 'statusCode'=>500]);
}
});


Route::post('registration', function (Request $request) {
    // return response()->json(['version' => config('app.version')]);
    // dd($request->all()); 
   //  Log::info('Showing the user profile for user: '.json_encode($request->all()));
    $getData = User::where('email',$request->get('email'))->where('status','1')->get();
    if(count($getData) > 0){
        return response()->json(['message' => 'Email ID Already exist']);
    }else{
    $user = User::create([
        // 'name' => $request->get('name'),
            'email' => $request->get('email'),
            'alternative_email' => $request->get('alternative_email'),
            'mobile_no' => $request->get('mobile_no'),
            'alternative_mobile' => $request->get('alternative_mobile'),
            'address1' => $request->get('address'),
            'alternative_address' => $request->get('alternative_address'),
            'device_id' => $request->get('device_id'),
            'pin_number' => $request->get('pin_number'),
            'password' => Hash::make($request->get('password')),
            'type' => 'user',
    ]);
   }
   if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    $user = Auth::user();
    $token = Auth::user()->createToken('ISMT')->accessToken;
    return response()->json(['token' => $token,'user' => $user, 'statusCode'=>200,'message'=>'Registration Completed'],200);
}
else {
    return response()->json(['loginDetail' => 'Unauthorised', 'statusCode'=>500]);
}
});

Route::post('email-check', function (Request $request) {
    $getData = User::where('email',$request->get('email'))->where('status','1')->get();
    if(count($getData) > 0){
        return response()->json(['message' => 'Email ID Already exist', 'statusCode'=>401],401);
    }else{
    	return response()->json(['message' => 'Email ID Ok', 'statusCode'=>200],200);
   }
});


Route::post('apilogin', function (Request $request) {
    // return response()->json(['version' => config('app.version')]);
    // dd($request->email);
    if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        $token = Auth::user()->createToken('ISMT')->accessToken;
        return response()->json(['token' => $token,'user' => $user, 'statusCode'=>200],200);
    }
    else {
        return response()->json(['loginDetail' => 'Credentials Invalid', 'statusCode'=>401],401);
    }
});

Route::post('device-id-check', function (Request $request) {
    $getData = User::where('device_id',$request->get('device_id'))->where('status','1')->first();
    
    if($getData && $getData['id']){
        return response()->json(['message' => "true"],200);
    }else{
        return response()->json(['message' => "false"],500);
    }
});

Route::post('pin-check', function (Request $request) {
    $getData = User::where('device_id',$request->get('device_id'))
    ->where('pin_number',$request->get('pin_number'))
    ->where('status','1')->first();
    if($getData && $getData['id']){
            $user = $getData;
            $token = $user->createToken('ISMT')->accessToken;
            return response()->json(['token' => $token,'user' => $user, 'statusCode'=>200],200);
         }
         else {
             return response()->json(['loginDetail' => 'Pin Number Invalid', 'statusCode'=>401],401);
         }
});

Route::post('forget-password', 'App\Http\Controllers\API\V1\UserController@sendEmail');
 
Route::post('get-reset-mail-check', 'App\Http\Controllers\API\V1\UserController@resetEmailCheck');
Route::post('reset-password', 'App\Http\Controllers\API\V1\UserController@resetPassword');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});


Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {
    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');
    Route::delete('membersdelete/{id}', 'MemberController@membersdelete');

    Route::post('adduserdetail', 'UserController@store');
    Route::post('userdetail/approve/{id}', 'UserController@approve');
    Route::post('userdetail/unapprove/{id}', 'UserController@unapprove');
    Route::get('userdetail/list', 'UserController@list');
    Route::get('vcidetail/list', 'VciDetailController@list');
    Route::get('subscription/list', 'SubscriptionPlanController@list');
    Route::get('paymentmode/list', 'PaymentmodeController@list');
    Route::get('getUserVciDetails/{id}', 'VciDetailController@getUserVciDetails');
    Route::get('getPlanDetails/{id}', 'SubscriptionPlanController@getPlanDetails');
    Route::get('subscriptionplan/list', 'SubscriptionPlanController@list');
    
    Route::get('vehiclefunction/list', 'VehicleFunctionController@list');
    Route::get('ecidetail/list', 'EcuDetailController@list');
    Route::get('getEcudetail', 'EcuDetailController@getEcidetail');
    Route::get('vehicletype/list', 'VehicleTypeController@list');
    Route::get('vehicledetail/list', 'VehicleDetailController@list');
    Route::get('getVehicleDetail', 'VehicleDetailController@getVehicleDetail');
    Route::get('getSingleVehicleDetail', 'VehicleDetailController@getSingleVehicleDetail');
    Route::post('vehicledetail/post/{id}', 'VehicleDetailController@update');
    Route::post('apkmgmt/post/{id}', 'APKController@update');
    Route::post('livedata/import', 'LiveDataController@import');
    Route::post('writedata/import', 'WriteDataController@import');
    Route::post('dtcdata/import', 'DtcDataController@import');
    Route::post('iodata/import', 'IoDataController@import');
    Route::post('routinedata/import', 'RoutineDataController@import');
    Route::get('complaint/getList', 'ComplaintController@getList');
    Route::put('complaint/update/{id}', 'ComplaintController@userupdate');
    // Route::post('complaint/add', 'ComplaintController@store');

    Route::get('getLiveDataDetails', 'LiveDataController@getLiveDataDetails');
    Route::get('getWriteDataDetails', 'WriteDataController@getWriteDataDetails');
    Route::get('getDtcDataDetails', 'DtcDataController@getDtcDataDetails');
    Route::get('getIoDataDetails', 'IoDataController@getIoDataDetails');
    Route::get('getRoutineDataDetails', 'RoutineDataController@getRoutineDataDetails');

    Route::get('getLatestApk', 'APKController@getApk');

    Route::get('getUserSubscription', 'SubscriptionController@getUserSubscription');
    Route::get('unsoldProduct', 'VciDetailController@getUnsoldProduct');
    Route::get('soldProduct', 'VciDetailController@getSoldProduct');
    Route::post('mapping/{id}', 'VciDetailController@mapping');

    Route::get('permission/list', 'RoleController@permissionList');
    Route::get('role/list', 'RoleController@list');

    Route::get('livedata/export', 'LiveDataController@export');
    Route::get('writedata/export', 'WriteDataController@export');
    Route::get('dtcdata/export', 'DtcDataController@export');
    Route::get('iodata/export', 'IoDataController@export');
    Route::get('routinedata/export', 'RoutineDataController@export');

    Route::post('salesAnalysis', 'DashboardController@salesAnalysis'); 
    Route::post('complaintAnalysis', 'DashboardController@complaintAnalysis');
    Route::post('registrationanalysis', 'DashboardController@registrationanalysis');
    Route::post('flashanalysis', 'DashboardController@flashanalysis');
    Route::get('dashboardData', 'DashboardController@dashboardData');
    
    Route::put('hexfile/approve/{id}', 'HexFileController@approve');
    Route::post('hexfile/update/{id}', 'HexFileController@update');
    Route::get('getHexFile', 'HexFileController@getHexFile');

    Route::put('apkmgmt/approve/{id}', 'APKController@approve');

    Route::post('ecuflash/store', 'ECUFlashController@store');
    Route::get('ecuflash/export', 'ECUFlashController@export');
    
    Route::post('livedata/store', 'LiveDataController@store');
    Route::post('livedata/post/{id}', 'LiveDataController@update');
    Route::post('livedata/enableData/{id}/{dataname}', 'LiveDataController@enableData');
    Route::post('livedata/imageDelete/{id}', 'LiveDataController@imageDelete');

    Route::post('writedata/store', 'WriteDataController@store');
    Route::post('writedata/post/{id}', 'WriteDataController@update');

    Route::post('dtcdata/store', 'DtcDataController@store');
    Route::post('dtcdata/post/{id}', 'DtcDataController@update');

    Route::post('iodata/store', 'IoDataController@store');
    Route::post('iodata/post/{id}', 'IoDataController@update');

    Route::post('routinedata/store', 'RoutineDataController@store');
    Route::post('routinedata/post/{id}', 'RoutineDataController@update');

    Route::post('pin-set', 'UserController@pin_set');

    Route::post('addHealthTestStructure','HealthTestStructureController@addHealthTestStructure');
    Route::post('updateHealthTestStructure','HealthTestStructureController@updateHealthTestStructure');
    Route::delete('healthteststructure/delete/{id}','HealthTestStructureController@destroy');
    Route::post('healthteststructure/enableData/{id}/{name}','HealthTestStructureController@enableData');
    Route::get('updatadatahealthteststructure/{id}','HealthTestStructureController@updatadatahealthteststructure');
    Route::get('healtest-view','HealthTestStructureController@healtestView');
    Route::post('healthtest-add','HealthTestController@addData');

    Route::get('get-profile', 'UserController@getProfile');
    Route::post('user-update/{id}', 'UserController@mobileupdate');

    Route::post('setLang', 'UserController@setLang');
    Route::get('language/list', 'LanguageController@list');
    Route::apiResources([
        // 'user' => 'UserController',
        'subscription' => 'SubscriptionPlanController',
        'paymentmode' => 'PaymentmodeController',
        'vehicletype' => 'VehicleTypeController',
        'userdetail' => 'UserController',
        'vcidetail' => 'VciDetailController',
        'subscriptionData' => 'SubscriptionController',
        'vehiclefunction' => 'VehicleFunctionController',
        'ecidetail' => 'EcuDetailController',
        'vehicledetail' => 'VehicleDetailController',
        'livedata' => 'LiveDataController',
        'writedata' => 'WriteDataController',
        'dtcdata' => 'DtcDataController',
        'iodata' => 'IoDataController',
        'routinedata' => 'RoutineDataController',
        'apkmgmt' => 'APKController',
        'employeedetail' => 'EmployeeController',
        'roledetail' => 'RoleController',
        'complaint' => 'ComplaintController',
        'hexfile' => 'HexFileController',
        'ecuflash' => 'ECUFlashController',
        'healthteststructure' => 'HealthTestStructureController',
        'language' => 'LanguageController',
    ]);
});
   
Route::post('payment','App\Http\Controllers\RazorpayController@payment');