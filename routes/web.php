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

// New Route
Route::get('/', 'LoginController@index')->name('login');
Route::get('/login', 'LoginController@index')->name('login');
Route::post('/check-user', 'LoginController@login')->name('check-user');
Route::get('/logout', 'UserController@logout')->name('logout')->middleware('usersession');;

Route::get('/home', 'UserController@cus_dashboard')->name('customer-home');
Route::get('/customer-payment/{id}', 'UserController@cus_payment')->name('customer-payment');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/users', 'UserController@index')->name('users');
Route::get('/add-user-page', 'UserController@add_user_page')->name('add-user-page');
Route::post('/add-user', 'UserController@add_user')->name('add-user');
Route::get('/user-profile/{account_type}/{id}', 'UserController@profile')->name('user-profile');
Route::post('/edit-user', 'UserController@edit_user')->name('edit-user');
Route::get('/delete-user/{account_type}/{id}', 'UserController@delete_user')->name('delete-user');
Route::get('/monthly-check', 'UserController@send_montly_bill')->name('monthly-check');
Route::post('/upload-users', 'UserController@upload_users')->name('upload-users');

Route::get('/pdf_disconnected_list/{dm}/{dy}', 'DashboardController@pdf_disconnected_list')->name('pdf_disconnected_list');
Route::get('/pdf_disconnected_list_all', 'DashboardController@pdf_disconnected_list_all')->name('pdf_disconnected_list_all');

Route::get('/all-user-by-zone', 'UserController@json_get_all_cus_by_zone')->name('all-user-by-zone');


Route::get('/customer-type', 'CustomerTypeController@index')->name('customer-type');
Route::post('/add-custype', 'CustomerTypeController@add_custype')->name('add-custype');
Route::post('/edit-custype', 'CustomerTypeController@edit_custype')->name('edit-custype');
Route::get('/delete-custype', 'CustomerTypeController@delete_custype')->name('delete-custype');
Route::get('/get-custype-by-id/{id}', 'CustomerTypeController@get_by_id')->name('get-custype-by-id');
Route::get('/get-custype-old-by-id/{id}', 'CustomerTypeController@get_old_by_custype_id')->name('get-custype-old-by-id');

Route::get('/meter-reading', 'MeterController@index')->name('meter-reading');
Route::get('/monthly-meter-reading', 'MeterController@monthly_reading')->name('monthly-meter-reading');
Route::post('/add-meter-reading', 'MeterController@add_meter_reading')->name('add-meter-reading');
Route::get('/edit-meter-reading/{id}', 'MeterController@edit_meter_reading_page')->name('edit-meter-reading');
Route::post('/update-meter-reading', 'MeterController@update_meter_reading')->name('update-meter-reading');
Route::get('/delete-meter-reading/{id}', 'MeterController@delete_meter_reading')->name('delete-meter-reading');
Route::get('/export-meter-reading', 'MeterController@export_meter_reading')->name('export-meter-reading');
Route::get('/add-reading-page/{cus_id}/{meter_id}', 'MeterController@add_reading_page')->name('add-reading-page');

Route::get('/payments', 'PaymentController@index')->name('payments');
Route::post('/add-payment', 'PaymentController@add_payment')->name('add-payment');
Route::get('/edit-payment/{id}', 'PaymentController@edit_payment_page')->name('edit-payment');
Route::post('/update-payment', 'PaymentController@update_payment')->name('update-payment');
Route::get('/pdf-receipt/{reading_id}', 'PaymentController@pdf_receipt')->name('pdf-receipt');
Route::get('/export-payment-by-status/{status}', 'PaymentController@export_payment_by_status')->name('export-payment-by-status');

Route::get('/request', 'RequestController@index')->name('request');
Route::get('/update-request/{id}/{status}', 'RequestController@update_request')->name('update-request');
Route::post('/add-request', 'RequestController@add_request')->name('add-request');

// Route::get('/htmltopdfview', 'UserController@htmltopdfview')->name('htmltopdfview');

Route::get('htmltopdfview',array('as'=>'htmltopdfview','uses'=>'UserController@htmltopdfview'));
// Route::post('/test', 'DashboardController@test')->name('test');

Route::get('/get-by-cus-id', 'MeterController@json_get_by_cus_id')->name('get-by-cus-id');

// Route::get('pdf_billing',array('as'=>'pdf_billing','uses'=>'MeterController@pdf_billing'));

Route::get('/pdf_billing/{reading_id}/', 'MeterController@pdf_billing')->name('pdf_billing');
Route::get('/pdf_billing_no_num/', 'MeterController@pdf_billing_no_num')->name('pdf_billing_no_num');
Route::get('/pdf_billing_monthly/', 'MeterController@pdf_billing_monthly')->name('pdf_billing_monthly');

Route::get('/json-get-unfinished/{cus_id}', 'PaymentController@json_get_unfinished_pay_by_cus_id')->name('json-get-unfinished');
Route::get('/json-get-pay-reading-id/{reading_id}', 'PaymentController@json_get_payment_by_reading_id')->name('json-get-pay-reading-id');

Route::get('/database-backup', 'DatabaseBackupController@index')->name('database-backup');
Route::get('/add-backup', 'DatabaseBackupController@add_backup')->name('add-backup');
Route::get('/delete-backup/{filename}', 'DatabaseBackupController@delete_backup')->name('delete-backup');

Route::get('/sms', 'SMSController@index')->name('sms');
Route::get('/use-sms/{id}', 'SMSController@use_sms')->name('use-sms');
Route::post('/edit-sms', 'SMSController@edit_sms')->name('edit-sms');
Route::get('/delete-sms', 'SMSController@delete')->name('delete-sms');
