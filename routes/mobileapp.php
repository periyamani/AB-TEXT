<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Auth;

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

Route::get('apilogin', function (Request $request) {
    // return response()->json(['version' => config('app.version')]);
    // dd($request->email);
    if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        $token = Auth::user()->createToken('ISMT')->accessToken;
        return response()->json(['token' => $token,'user_id' => $user->id, 'statusCode'=>200]);
    }
    else {
        return response()->json(['loginDetail' => 'Unauthorised', 'statusCode'=>500]);
    }
});

// Route::get('apilogin', 'LoginController@userLogin');
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
    Route::get('userdetail/list', 'UserController@list');
    Route::get('vcidetail/list', 'VciDetailController@list');
    Route::get('subscription/list', 'SubscriptionPlanController@list');
    Route::get('paymentmode/list', 'PaymentmodeController@list');
    Route::get('getUserVciDetails/{id}', 'VciDetailController@getUserVciDetails');
    Route::get('getPlanDetails/{id}', 'SubscriptionPlanController@getPlanDetails');
    Route::get('vehiclefunction/list', 'VehicleFunctionController@list');
    Route::get('ecidetail/list', 'EcuDetailController@list');
    Route::get('vehicletype/list', 'VehicleTypeController@list');
    Route::get('vehicledetail/list', 'VehicleDetailController@list');
    Route::post('vehicledetail/post/{id}', 'VehicleDetailController@update');
    Route::post('apkmgmt/post/{id}', 'VehicleDetailController@update');
    Route::post('livedata/import', 'LiveDataController@import');
    Route::post('writedata/import', 'WriteDataController@import');
    Route::post('dtcdata/import', 'DtcDataController@import');
    Route::post('iodata/import', 'IoDataController@import');
    Route::post('routinedata/import', 'RoutineDataController@import');
    
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
    ]);
});
