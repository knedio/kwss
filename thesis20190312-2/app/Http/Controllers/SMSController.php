<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;
use App\Models\MeterModel;
use App\Models\UserModel;
use App\Models\MeterReadingModel;
use App\Models\MeterReaderModel;
use App\Models\PaymentDetailModel;
use App\Models\SMSModel;

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class SMSController extends Controller
{
    protected $userM;
    protected $meterM;
    protected $meter_readM;
    protected $meter_readerM;
    protected $pay_detailM;
    protected $smsM;

    public function __construct(
        MeterModel $meterM, 
        MeterReadingModel $meter_readM, 
        MeterReaderModel $meter_readerM, 
        PaymentDetailModel $pay_detailM,
        UserModel $user,
        SMSModel $smsM)
    {
        $this->smsM = new $smsM;
        $this->userM = new $user;
        $this->meterM = new $meterM;
        $this->meter_readM = new $meter_readM;
        $this->meter_readerM = new $meter_readerM;
        $this->pay_detailM = new $pay_detailM;

        $this->middleware(function ($request, $next){
            if(!session('user_logged')){
                return redirect()->route('login');
            }
            $response = $next($request);
            return $response
            ->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        });
    }

    public function index()
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

        $sms_records = $this->smsM->get_all();
        return view('pages.sms',[
            'sms_records' =>  $sms_records,
        ]);
    }

    public function add(Request $request){
    	$sms_api_key = $request->sms_api_key;
    	$sms_device_id = $request->sms_device_id;
    	$data = array(
    		'sms_api_key'	=>$sms_api_key,
    		'sms_device_id'	=>$sms_device_id,
    	);
    	$add_sms = $this->smsM->add($data);
        return redirect()->route('sms');
    }

    public function delete(Request $request) 
    {
        $id = $request->id;
        $delete = $this->smsM->delete_sms($id);
        return redirect()->route('sms');
    }

    public function edit_sms(Request $request){
        $id = $request->id;
        $api_key = $request->sms_api_key;
        $device_id = $request->sms_device_id;
        $data = array(
            'sms_api_key'       => $api_key,
            'sms_device_id'     => $device_id
        );
        if ($id) {
            $edit = $this->smsM->update_by_id($id,$data);
        }else{
            $add_sms = $this->smsM->add($data);
        }
        return redirect()->route('sms');
    }

    public function use_sms(Request $request){
        $get_used = $this->smsM->get_by_status('Used');
        // printx($get_used);
        foreach ($get_used as $col) {
            $used_id = $col->sms_id;
            $data_used = array(
                'sms_status'    => 'Not Used'
            );
            $edit = $this->smsM->update_by_id($used_id,$data_used);
        }
        $id = $request->id;
        $data = array(
            'sms_status'    => 'Used'
        );
        $edit = $this->smsM->update_by_id($id,$data);
        return redirect()->route('sms');
    }

    // public function send_sms($data){
    //     $sms_record = $this->smsM->get_used();
    //     // printx($sms_record);
    //     if ($sms_record) {
    //         $getDefaultConfiguration = Configuration::getDefaultConfiguration();
    //         $config->setApiKey('Authorization', $sms_record->sms_api_key);
    //         $apiClient = new ApiClient($config);
    //         $messageClient = new MessageApi($apiClient);
    //         $deviceId = $sms_record->sms_device_id;

    //         $sendMessageRequest = new SendMessageRequest([
    //             'phoneNumber' => '+63'.$cus_mobile_no,
    //             'message' => '[Note: KWSS Advisory] Hello '.$cus_lastname."'s, we did our water reading ".$reading_date.' and its due date is '.$due_date.'. Thank you and have a nice day.',
    //             'deviceId' => $deviceId
    //         ]);
            
    //         $sendMessages = $messageClient->sendMessages([
    //             $sendMessageRequest
    //         ]);
    //     }
    // }
}
