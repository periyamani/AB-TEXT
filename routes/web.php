<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/payment', function () {
    return view('payment');
});
Route::get('/thankyou', function () {
    return view('thankyou');
});
Route::get('/faq', function () {
    return view('faq');
});
Route::get('/contact-us', function () {
    return view('contact');
});
Route::get('/about-us', function () {
    return view('aboutus');
});
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('home', function () {
    return redirect('/dashboard');
});
// Route::get('dashboard', 'App\Http\Controllers\API\V1\DashboardController@index');
Route::get('/{vue_capture?}', function () {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*')->middleware('auth');
