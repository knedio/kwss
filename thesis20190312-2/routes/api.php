<?php

use Illuminate\Http\Request;

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
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/get-payments',function(){
// 	$all_records = $this->meter_readM->get_all_meter_reading_with_pay()->toJson();
//         return response($all_records,200)->header('Content-Type', 'application/json');
// });
Route::group(['middleware' => 'cors'], function() { 
    Route::get('/test', 'APIController@test');
    Route::get('/get-meter-details/{id}', 'APIController@get_meter_deatils');
    Route::get('/get-paid-payments/{id}', 'APIController@get_paid_payment');
    Route::get('/get-unpaid-payments/{id}', 'APIController@get_unpaid_payment');
    Route::get('/get-partial-payments/{id}', 'APIController@get_partial_payment');
    Route::get('/get-paid-payments-by-id/{id}/{pay_id}', 'APIController@get_paid_payment_by_id');
    Route::get('/get-unpaid-payments-by-id/{id}/{reading_id}', 'APIController@get_unpaid_payment_by_id');
    Route::get('/get-partial-payments-by-id/{id}/{pay_id}', 'APIController@get_partial_payment_by_id');
    Route::get('/get-user-details/{id}', 'APIController@get_user_deatils');
    Route::get('/get-search-payment/{user_id}/{start_date}/{end_date}/{pay_status}', 'APIController@get_search_payment');
    Route::post('/update-profile', 'APIController@update_profile');
    Route::post('/check-login', 'APIController@check_login');
    Route::post('/add-request', 'APIController@add_request');
 });