<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MeterModel;
use App\Models\MeterReadingModel;
use App\Models\MeterReaderModel;
use App\Models\PaymentDetailModel;
use App\Models\PaymentModel;
use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\RequestModel;

use Carbon\Carbon;

class APIController extends Controller
{
    protected $loginM;
    protected $meterM;
    protected $meter_readM;
    protected $meter_readerM;
    protected $pay_detailM;
    protected $payM;
    protected $userM;
    
    public function __construct 
    (
        MeterModel $meterM, 
        MeterReadingModel $meter_readM, 
        MeterReaderModel $meter_readerM, 
        PaymentDetailModel $pay_detailM,
        PaymentModel $payM,
        LoginModel $loginM,
        UserModel $userM,
        RequestModel $reqM
    )
    {
        $this->meterM = new $meterM;
        $this->meter_readM = new $meter_readM;
        $this->meter_readerM = new $meter_readerM;
        $this->pay_detailM = new $pay_detailM;
        $this->payM = new $payM;
        $this->loginM = new $loginM;
        $this->userM = new $userM;
        $this->reqM = new $reqM;
    }

    public function get_search_payment(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $pay_status = $request->pay_status;
        $user_id = $request->user_id;

        $data = [
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'pay_status'    => $pay_status,
            'user_id'    => $user_id
        ];
        if($pay_status == 'Unpaid'){
            $records = $this->meter_readM->get_unpaid_pay_by_date($data);
        }elseif($pay_status == 'All'){
            $records = $this->meter_readM->get_all_pay_by_date($data);
        }else{
            $records = $this->payM->get_pay_by_date_with_status($data);
        }

        return response($records,200)
        ->header('Content-Type', 'application/json');
    }
    public function check_login(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        // echo '<pre>'.print_r($requestData,1).'</pre>';exit();
        $user = $this->loginM->check_user($requestData['id_number'],$requestData['password']);
        $result = array();
        if($user){
            if($user->account_type == 'Customer'){
                $result = [
                    'user_logged' => TRUE,
                    'username' => $user->username,
                    'user_id' => $user->cus_id,
                    'firstname' => $user->cus_firstname,
                    'lastname' => $user->cus_lastname,
                ];
            }
        }
        if ($request->getMethod() === "OPTIONS") {
            return response('');
        }else {
            return response()->json($result)
            ->header('Content-Type', 'application/json');
        }
    }

    public function get_paid_payment(Request $request)
    {
        $user_id = $request->id;
        $records = $this->meter_readM->get_cus_details_by_status($user_id,'Full');

        return response($records,200)
        ->header('Content-Type', 'application/json');
    }

    public function get_unpaid_payment(Request $request)
    {   
        $user_id = $request->id;
        $records = $this->meter_readM->get_cus_details_unpaid($user_id);
        // $info = array();
        
        // foreach($records as $record){
        //     $due_date = date('Y-m-d',strtotime(Carbon::parse($record->reading_date)->addMonth()));
        //     $info [] = [
        //         'reading_id'                => $record->reading_id,
        //         'username'                  => $record->username,
        //         'reader_firstname'          => $record->reader_firstname,
        //         'reader_lastname'           => $record->reader_lastname,
        //         'reader_address'            => $record->reader_address,
        //         'reader_mobile_number'      => $record->reader_mobile_number,
        //         'meter_id'                  => $record->meter_id,
        //         'custype_id'                => $record->custype_id,
        //         'meter_model'               => $record->meter_model,
        //         'meter_duedate'             => $record->meter_duedate,
        //         'meter_address'             => $record->meter_address,
        //         'cus_firstname'             => $record->cus_firstname,
        //         'cus_lastname'              => $record->cus_lastname,
        //         'cus_mobile_number'         => $record->cus_mobile_number,
        //         'cus_address'               => $record->cus_address,
        //         'custype_type'              => $record->custype_type,
        //         'custype_rate'              => $record->custype_rate,
        //         'reading_date'              => $record->reading_date,
        //         'reading_waterconsumed'     => $record->reading_waterconsumed,
        //         'due_date'                  => $due_date
        //     ];
        // }
        
        return response($records,200)
        ->header('Content-Type', 'application/json');
    }

    public function get_partial_payment(Request $request)
    {
        $user_id = $request->id;
        $records = $this->meter_readM->get_cus_details_by_status($user_id,'Partial');
        // printx($records);
        return response($records,200)
        ->header('Content-Type', 'application/json');
    }
    
    public function get_paid_payment_by_id(Request $request)
    {
        $user_id = $request->id;
        $pay_id = $request->pay_id;
        $records = $this->payM->get_pay_by_id($pay_id);
        // $records = $this->meter_readM->get_cus_details_by_status($user_id,'Full',$pay_id);

        return response($records,200)
        ->header('Content-Type', 'application/json');
    }

    public function get_unpaid_payment_by_id(Request $request)
    {   
        
        $user_id = $request->id;
        $reading_id = $request->reading_id;
        $records = $this->meter_readM->get_cus_details_unpaid_id($user_id,$reading_id);
        return response($records,200)
        ->header('Content-Type', 'application/json');
    }

    public function get_partial_payment_by_id(Request $request)
    {
        $user_id = $request->id;
        $pay_id = $request->pay_id;
        $records = $this->payM->get_pay_by_id($pay_id);
        return response($records,200)
        ->header('Content-Type', 'application/json');
    }

    public function get_user_deatils(Request $request)
    {
        $user_id = $request->id;
        $record = $this->userM->get_cus_details_by_id($user_id);
        // printx($record);
        return response(json_encode($record),200)
        ->header('Content-Type', 'application/json');
    }

    public function update_profile(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $id = $requestData['id'];
        $username = $requestData['username'];
        $firstname = $requestData['firstname'];
        $lastname = $requestData['lastname'];
        $mobile_number = $requestData['mobile_number'];
        $address = $requestData['address'];
        $password = $requestData['password'];
        $data = [
            'id'            => $id,
            'account_type'  => 'Customer',
            'firstname'     => $firstname,
            'lastname'      => $lastname,
            'mobile_number' => $mobile_number,
            'address'       => $address,
            'password'      => $password,
        ];
        $edit = $this->userM->update_user($data);

        return response($edit,200)
        ->header('Content-Type', 'application/json');
    }

    public function get_meter_deatils(Request $request)
    {
        $user_id = $request->id;
        $records = $this->meterM->get_by_cus_id($user_id);
        // printx($records);
        return response(json_encode($records),200)
        ->header('Content-Type', 'application/json');
    }

    public function add_request(Request $request)
    {
        // $data = $request->filter_input(type, variable_name)();
        // print_r($this->getQueryString());exit();
        $requestData = json_decode($request->getContent(), true);
        // var_dump();exit();
        // var_dump($request->all());exit();
        // printx($request->all());
        $request_type = $requestData['request_type'];
        $cus_id = $requestData['id'];
        $check_if_exist = $this->reqM->check_if_exist($cus_id,$request_type);
        if ($check_if_exist) {
            $data = [
                'message'   => 'You already sent a request.',
                'response'  => true
            ];
            return response(json_encode($data),200)
            ->header('Content-Type', 'application/json');
        }
        // printx($check_if_exist);
        $cus_record = $this->userM->get_user_by_id($cus_id,'Customer');
        $cus_firstname = $cus_record->firstname;
        $cus_lastname = $cus_record->lastname;
        $cus_address = $cus_record->address;
        $cus_zone = $cus_record->zone;
        if ($request_type == 'Name') {
            $request_firstname = $requestData['firstname'];
            $request_lastname = $requestData['lastname'];
            $data_serialized = array(
                'first name'    => $request_firstname,
                'last name' => $request_lastname,
            );
            $prev_data_serialized = array(
                'first name'    => $cus_firstname,
                'last name' => $cus_lastname,
            );
        }else if ($request_type == 'Address') {
            $request_address = $requestData['address'];
            $request_zone = $requestData['zone'];
            $data_serialized = array(
                'address'   => $request_address,
                'zone'  => $request_zone,
            );
            $prev_data_serialized = array(
                'address'   => $cus_address,
                'zone'  => $cus_zone,
            );
        }
        $data = array(
            'cus_id'    => $cus_id,
            'request_type'  => $request_type,
            'request_prev_data_serialized'  => serialize($prev_data_serialized),
            'request_data_serialized'   => serialize($data_serialized),
        );
        // printx($data);
        $add = $this->reqM->add($data);
        if ($add) {
            $data = [
                'message'   => 'Successfull added a request.',
                'response'  => true
            ];
            return response(json_encode($data),200)
            ->header('Content-Type', 'application/json');
        }else{
            $data = [
                'message'   => 'Unsuccessfull added a request.',
                'response'  => true
            ];
            return response(json_encode($data),200)
            ->header('Content-Type', 'application/json');
        }
        $data = [
            'message'   => 'Request not sent',
            'response'  => true
        ];
        return response(json_encode($data),400)
        ->header('Content-Type', 'application/json');
    }

}
