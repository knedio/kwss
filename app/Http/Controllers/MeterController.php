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

class MeterController extends Controller
{
    protected $userM;
    protected $meterM;
    protected $meter_readM;
    protected $meter_readerM;
    protected $pay_detailM;
    protected $smsM;

    public function __construct
    (
        MeterModel $meterM, 
        MeterReadingModel $meter_readM, 
        MeterReaderModel $meter_readerM, 
        PaymentDetailModel $pay_detailM,
        UserModel $user,
        SMSModel $smsM
    )
    {
        $this->userM = new $user;
        $this->meterM = new $meterM;
        $this->meter_readM = new $meter_readM;
        $this->meter_readerM = new $meter_readerM;
        $this->pay_detailM = new $pay_detailM;
        $this->smsM = new $smsM;

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

    public function add_reading_page(Request $request)
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

        $cus_id = $request->cus_id;
        $meter_id = $request->meter_id;

        $result = $this->userM->get_customer_by_id_meter_id($cus_id,$meter_id);
        $reader_records = $this->meter_readerM->get_all_meter_reader();
        // printx($result);
        return view('pages.add_meter_reading',[
            'result' =>  $result,
            'reader_records' =>  $reader_records,
        ]);
    }

    public function index()
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

        $cus_records = $this->userM->get_all_cus();
        $reading_records = $this->meter_readM->get_all_meter_reading();
        $reader_records = $this->meter_readerM->get_all_meter_reader();
        return view('pages.meter_reading',[
            'cus_records' =>  $cus_records,
            'reading_records' =>  $reading_records,
            'reader_records' =>  $reader_records,
        ]);
    }

    public function monthly_reading(Request $request)
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }
        
        $zone = $request->zone ?: 1;

        $customer_records = $this->userM->get_customer_with_meter_by_zone($zone);
        if ($customer_records) {
            foreach ($customer_records as $result) {
                $check = $this->meter_readM->get_meter_reading_by_month_with_cus($result->cus_id,$result->meter_id);
                if ($check) {
                    $result->reading = $check;
                }
            }
        }
        // printx($customer_records);
        $cus_records = $this->userM->get_all_cus();
        $meter_records = $this->meterM->get_all_meter();
        $reading_records = $this->meter_readM->get_meter_reading_by_montly('All');
        $unread_records = $this->meter_readM->get_meter_reading_by_montly('Unread');
        // $unread_records = $this->userM->get_monthly_unread_customer();
        $read_records = $this->meter_readM->get_meter_reading_by_montly('Read');
        $reader_records = $this->meter_readerM->get_all_meter_reader();
        return view('pages.monthly_meter_reading',[
            'cus_records' =>  $cus_records,
            'meter_records' =>  $meter_records,
            'reading_records' =>  $reading_records,
            'unread_records' =>  $unread_records,
            'read_records' =>  $read_records,
            'reader_records' =>  $reader_records,
            'customer_records' =>  $customer_records,
            'zone' =>  $zone,
        ]);
    }

    public function edit_meter_reading_page(Request $request) 
    {   
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }
        
        $id = $request->id;
        $cus_records = $this->userM->get_all_cus();
        $reading_records = $this->meter_readM->get_meter_reading_by_id($id);
        $reader_records = $this->meter_readerM->get_all_meter_reader();
        $meter_records = $this->meterM->get_by_cus_id($reading_records->cus_id);
        return view('pages.edit_meter_reading',[
            'reading_records' =>  $reading_records,
            'cus_records' =>  $cus_records,
            'reader_records' =>  $reader_records,
            'meter_records' =>  $meter_records,
        ]);
    }

    public function add_meter_reading(Request $request) 
    {
        $send_sms = $request->send_sms;
        $reader_id = $request->reader_id;
        $cus_id = $request->cus_id;
        $meter_id = $request->meter_id;
        $reading_date = $request->reading_date;
        $cus_last_record = $this->meter_readM->get_last_reading($meter_id);
        if ($cus_last_record) {
            $last_reading_due_date = $cus_last_record->due_date;
            if ($reading_date < $last_reading_due_date) {
                $message = '<strong>Unsuccessful!</strong> Please update unread meter reading details by clicking "Update". <a href="'.route('edit-meter-reading',['id'=>$cus_last_record->reading_id]).'">Update</a>';
                $request->session()->flash('error', $message);
                return redirect()->route('meter-reading');
            }
        }
        $reading_waterconsumed = $request->reading_waterconsumed;
        $reading_other_payment = $request->reading_other_payment;
        $reading_other_payment_name = $request->reading_other_payment_name;
        $reading_prev_waterconsumed = $request->reading_prev_waterconsumed;
        $reading_amount = $request->reading_amount;
        $reading_status = $request->reading_status;
        $reading_remarks = $request->reading_remarks;
        $total_amount = $reading_amount + $reading_other_payment;
        $data = [
            'reader_id' => $reader_id,
            'meter_id' => $meter_id,
            'reading_date' => $reading_date,
            'reading_waterconsumed' => $reading_waterconsumed,
            'reading_other_payment_name' => $reading_other_payment_name,
            'reading_other_payment' => $reading_other_payment,
            'reading_prev_waterconsumed' => $reading_prev_waterconsumed,
            'reading_amount' => $reading_amount,
            'reading_status' => $reading_status,
            'reading_remarks' => $reading_remarks
        ];
        $reading_id = $this->meter_readM->add_meter_reading($data);
        if ($reading_id) {
            $data = array(
                'meter_total_consumed'  => $reading_waterconsumed+$reading_prev_waterconsumed
            );
            $this->meterM->update_meter($meter_id,$data);
            // if ($send_sms == 'Yes') {
            //     $cus_record = $this->userM->get_cus_details_by_id($cus_id);
            //     $cus_mobile_no = $cus_record->mobile_number;
            //     $cus_lastname = $cus_record->lastname;
            //     $zone = $cus_record->zone;
            //     $duration = $request->duration;
            //     $due_date = get_due_date($reading_date,$duration,$zone);
            //     if($cus_record->mobile_number){
            //         $sms_record = $this->smsM->get_used();
            //         // printx($sms_record);
            //         if ($sms_record) {
            //             $config = Configuration::getDefaultConfiguration();
            //             $config->setApiKey('Authorization', $sms_record->sms_api_key);
            //             $apiClient = new ApiClient($config);
            //             $messageClient = new MessageApi($apiClient);
            //             $deviceId = $sms_record->sms_device_id;

            //             $sendMessageRequest = new SendMessageRequest([
            //                 'phoneNumber' => '+63'.$cus_mobile_no,
            //                 'message' => '[Note: KWSS Advisory] Hello '.$cus_lastname."'s, we did our water reading ".$reading_date.' , the due date is '.$due_date.' and the water bill is '.$reading_amount.' and the other payment is '.$reading_other_payment.'. Thank you and have a nice day.',
            //                 'deviceId' => $deviceId
            //             ]);
                        
            //             $sendMessages = $messageClient->sendMessages([
            //                 $sendMessageRequest
            //             ]);
            //         }else{
                        
            //         }
            //     }
            // }
            // session()->push('success', TRUE);
            if ($reading_status == 'Read') {
                $message = 'Successfully added meter reading. Click "Download" if you want to download billing receipt. 
                    <a href="'.route('pdf_billing',['reading_id'=>$reading_id]).'">Download</a>';
                $request->session()->flash('success', $message);
            }else{
                $message = 'Successfully added unread meter reading.';
                $request->session()->flash('success', $message);
            }
        }else{
            $message = 'Unsuccessful adding Meter Reading.';
            $request->session()->flash('error', $message);
        }
        
        return redirect()->route('monthly-meter-reading');
    }

    public function update_meter_reading(Request $request) 
    {
        $reading_id = $request->reading_id;
        $reader_id = $request->reader_id;
        $cus_id = $request->cus_id;
        $meter_id = $request->meter_id;
        $reading_date = $request->reading_date;
        $reading_waterconsumed = $request->reading_waterconsumed;
        $reading_other_payment = $request->reading_other_payment;
        $reading_other_payment_name = $request->reading_other_payment_name;
        $reading_prev_waterconsumed = $request->reading_prev_waterconsumed;
        $reading_amount = $request->reading_amount;
        $reading_status = $request->reading_status;
        $reading_remarks = $request->reading_remarks;
        $data = [
            'reader_id' => $reader_id,
            'meter_id' => $meter_id,
            'reading_date' => $reading_date,
            'reading_waterconsumed' => $reading_waterconsumed,
            'reading_other_payment' => $reading_other_payment,
            'reading_other_payment_name' => $reading_other_payment_name,
            'reading_prev_waterconsumed' => $reading_prev_waterconsumed,
            'reading_amount' => $reading_amount,
            'reading_status' => $reading_status,
            'reading_remarks' => $reading_remarks
        ];
        $edit = $this->meter_readM->update_meter_reading($reading_id,$data);
        // printx($cus_id);
        if ($edit) {
            $data = array(
                'meter_total_consumed'  => $reading_waterconsumed+$reading_prev_waterconsumed
            );
            $update_meter = $this->meterM->update_meter($meter_id,$data);
           if ($update_meter) {
                session()->push('success', TRUE);
                $message = 'Successfully updated Meter Reading. Click "Download" if you want to download billing receipt. 
                        <a href="'.route('pdf_billing',['reading_id'=>$reading_id]).'">Download</a>';
                $request->session()->flash('success', $message);
           }
        }
        return redirect(url()->previous());
    }

    public function delete_meter_reading(Request $request) 
    {
        $id = $request->id;
        $delete = $this->meter_readM->delete_meter_reading($id);
        return redirect()->route('meter-reading');
    }

    public function json_get_by_cus_id(Request $request)
    {
        $cus_id = $request->cus_id;
        $records = $this->meterM->get_by_cus_id($cus_id);
        if ($records) {
            return response($records,200)
                    ->header('Content-Type', 'application/json');
        }
        return response($records,400)
                    ->header('Content-Type', 'application/json');
    }

    public function pdf_billing(Request $request){
        $reading_id = $request->reading_id;
        $record = $this->meter_readM->get_meter_reading_by_id($reading_id);
        $reading_id = $record->reading_id;
        $cus_id = $record->cus_id;
        $arrears = $this->meter_readM->calculate_prev_unpaid($reading_id,$cus_id);
        $previous_record = $this->meter_readM->get_previous_meter_reading($reading_id,$cus_id);
        // printx($previous_record);
        $data = array(
            'record'    => $record,
            'previous_record'    => $previous_record,
            'arrears'    => $arrears,
        );
       return PDF::loadView('pdf.billing_pdf',$data)->setPaper([0, 0, 450, 390], 'portrait')->download('billing_'.date('Ymd-His').'.pdf');
    }

    public function pdf_billing_no_num(Request $request){

        $records = $this->meter_readM->get_meter_reading_by_month_no_num();
        $infos = array();
        foreach($records as $col) {
            $reading_id = $col->reading_id;
            $cus_id = $col->cus_id;
            $arrears = $this->meter_readM->calculate_prev_unpaid($reading_id,$cus_id);
            $previous_record = $this->meter_readM->get_previous_meter_reading($reading_id,$cus_id);
            $infos[] = array(
                'record'    => $col,
                'previous_record'    => $previous_record,
                'arrears'    => $arrears,
            );
        }
       if ($records) {
           return PDF::loadView('pdf.billing_pdf_no_num',array('infos'=>$infos))->setPaper([0, 0, 450, 390], 'portrait')->download('billing_'.date('Ymd-His').'.pdf');
       }else{
            return redirect(url()->previous());
       }
    }

    public function pdf_billing_monthly(Request $request){

        $records = $this->meter_readM->get_meter_reading_by_month();
        $infos = array();
        foreach($records as $col) {
            $reading_id = $col->reading_id;
            $cus_id = $col->cus_id;
            $arrears = $this->meter_readM->calculate_prev_unpaid($reading_id,$cus_id);
            $previous_record = $this->meter_readM->get_previous_meter_reading($reading_id,$cus_id);
            $infos[] = array(
                'record'    => $col,
                'previous_record'    => $previous_record,
                'arrears'    => $arrears,
            );
        }
       if ($records) {
           return PDF::loadView('pdf.billing_pdf_no_num',array('infos'=>$infos))->setPaper([0, 0, 450, 390], 'portrait')->download('monthly_billing_'.date('Ymd-His').'.pdf');
       }else{
            return redirect(url()->previous());
       }
    }

    public function export_meter_reading(Request $request)
    {
        $reading_records = $this->meter_readM->get_all_meter_reading();
        // printx($reading_records);
        $data = array(
            'reading_records'   => $reading_records
        );
       return PDF::loadView('pdf.meter_reading_pdf',$data)->download('meter-reading'.date('Ymd-His').'.pdf');
    }

    public function send_sms_meter_reading(Request $request){

        $zone = $request->zone;

        $customer_records = $this->userM->get_customer_with_meter_by_zone($zone);

        if ($customer_records) {
            foreach ($customer_records as $result) {
                $check = $this->meter_readM->get_meter_reading_by_month_with_cus($result->cus_id,$result->meter_id);
                if ($check) {
                    $result->reading = $check;
                }
            }
        }

        foreach ($customer_records as $record) {
            $cus_mobile_no = $record->cus_mobile_number;
            $cus_lastname = $record->cus_lastname;
            $zone = $record->custype_zone;

            $sms_record = $this->smsM->get_used();
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', $sms_record->sms_api_key);
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);
            $deviceId = $sms_record->sms_device_id;

            if($cus_mobile_no){
                if ($sms_record) {
                    if (!empty($record->reading)) {
                        $duration = $record->reading->custype_due_date_duration;
                        $reading_date = $record->reading->reading_date;
                        $due_date = get_due_date($reading_date,$duration,$zone);

                        $sendMessageRequest = new SendMessageRequest([
                            'phoneNumber' => '+63'.$cus_mobile_no,
                            'message' => '[Note: KWSS Advisory] Hello '.$cus_lastname."'s, we did our water reading ".date('Y-m-d',strtotime($reading_date)).' , the due date is '.$due_date.' and the water bill is '.$record->reading->reading_amount.' and the other payment is '.$record->reading->reading_other_payment.'. Thank you and have a nice day.',
                            'deviceId' => $deviceId
                        ]);
                        
                        $sendMessages = $messageClient->sendMessages([
                            $sendMessageRequest
                        ]);
                    }else{

                        $sendMessageRequest = new SendMessageRequest([
                            'phoneNumber' => '+63'.$cus_mobile_no,
                            'message' => '[Note: KWSS Advisory] Hello '.$cus_lastname.", we would like to inform you that we couldn't read your Meter Serial No. ".$record->meter_serial_no.".Thank you and have a nice day.",
                            'deviceId' => $deviceId
                        ]);
                        $sendMessages = $messageClient->sendMessages([
                            $sendMessageRequest
                        ]);
                    }
                }else{
                    
                }
            }
        }
        return redirect(url()->previous());
    }
}
