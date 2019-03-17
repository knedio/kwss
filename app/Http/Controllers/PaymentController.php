<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MeterModel;
use App\Models\MeterReadingModel;
use App\Models\MeterReaderModel;
use App\Models\PaymentDetailModel;
use App\Models\PaymentModel;
use App\Models\SMSModel;

use PDF;

class PaymentController extends Controller
{
    protected $meterM;
    protected $meter_readM;
    protected $meter_readerM;
    protected $pay_detailM;
    protected $payM;
    protected $smsM;

    public function __construct
    (
        MeterModel $meterM, 
        MeterReadingModel $meter_readM, 
        MeterReaderModel $meter_readerM, 
        PaymentDetailModel $pay_detailM,
        PaymentModel $payM,
        SMSModel $smsM
    )
    {
        $this->meterM = new $meterM;
        $this->meter_readM = new $meter_readM;
        $this->meter_readerM = new $meter_readerM;
        $this->pay_detailM = new $pay_detailM;
        $this->payM = new $payM;
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

    public function index()
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

        $full_records = $this->meter_readM->get_pay_by_status('Full');
        $partial_records = $this->meter_readM->get_pay_by_status('Partial');
        $unpaid_records = $this->meter_readM->get_pay_unpaid();
        return view('pages.payment',[
            'full_records'      => $full_records,
            'partial_records'   => $partial_records,
            'unpaid_records'    => $unpaid_records,
            'meter_readM'    => $this->meter_readM,
        ]);
    }

    public function edit_payment_page(Request $request)
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

        $pay_id = $request->id;
        $records = $this->payM->get_pay_by_id($pay_id);
        // printx($records);
        return view('pages.edit_payment', [
            'records'   => $records,
        ]);
    }

    public function add_payment(Request $request)
    {
        $other_reading_id = $request->payment_checkbox;
        // printx($other_reading_id);

        $payment = $request->payment;
        $amount_pay = $request->amount_pay;
        if ($other_reading_id) {
            $result = array();
            foreach ($other_reading_id as $reading_id) {
                $result= $this->meter_readM->get_meter_reading_by_id($reading_id);
                
                $pay_id = $result->pay_id;
                $cus_id = $result->cus_id;
                $reading_id = $result->reading_id;

                $penalty = $result->custype_due_date_penalty;
                $reading_date = $result->reading_date;
                $reading_amount = $result->reading_amount;
                $other_payment = $result->reading_other_payment;
                $pay_status = $result->pay_status;

                // $payment = $result->payment;
                $water_consumed = $result->reading_waterconsumed;
                $partial_amount = $result->trans_payment;
                $payment_amount = $result->payment_amount;

                $duration = $result->custype_due_date_duration;
                // $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);

                $add_payment_data = array(
                    'pay_id'            => $pay_id,
                    'cus_id'            => $cus_id,
                    'reading_id'        => $reading_id,
                    'water_consumed'    => $water_consumed,
                    'amount_pay'        => $reading_amount-$partial_amount,
                    'partial_amount'    => $partial_amount,
                    'payment'           => $payment_amount,
                );
                $this->insert_payment_data($add_payment_data);
                $payment -= $payment_amount;
                $amount_pay -= $payment_amount;
            }
        }
        $pay_id = $request->pay_id;
        $cus_id = $request->cus_id;
        $reading_id = $request->reading_id;
        $water_consumed = $request->reading_waterconsumed;
        $partial_amount = $request->partial_payment;
        $other_payment = $request->other_payment;
        $arrears = $request->arrears;

        $add_payment_data = array(
            'pay_id'            => $pay_id,
            'cus_id'            => $cus_id,
            'reading_id'        => $reading_id,
            'water_consumed'    => $water_consumed,
            'amount_pay'        => $amount_pay,
            'partial_amount'    => $partial_amount,
            'payment'           => $payment,
            'arrears'           => $arrears,
        );

        $add = $this->insert_payment_data($add_payment_data);
        if ($add) {
            if ($other_reading_id) {
                $other_reading_id = implode(',', $other_reading_id);
            }else{
                $other_reading_id = 0;
            }
            $message = 'Successfully added Payment. Click "Download" if you want to download receipt. 
                        <a href="'.route('pdf-receipt',['reading_id'=>$reading_id]).'">Download</a>';
            $request->session()->flash('success', $message);
        }
        return redirect()->route('payments');
    }

    private function insert_payment_data($pay_data){
        $arrears = 0;
        extract($pay_data);
        $emp_id = session('user_id');
        $trans_date = date('Y-m-d H:i:s');

        // $water_consumed = $this->meter_readM->get_waterconsumed($reading_id);

        // $calculated_payment = $this->payM->calculate_payment($reading_id);
        // $total_payment = $payment + $partial_amount;

        if($payment < $amount_pay){
            $pay_status = 'Partial';
        }else{
            $pay_status = 'Full';
        }

        $data = [
            'cus_id'    => $cus_id,
            'emp_id'    => $emp_id,
            'pay_status'    => $pay_status,
        ];
        if($pay_id){
            $update = $this->payM->update_payment($pay_id,$data);
        }else{
            $pay_id = $this->payM->add_payment($data);
        }

        $paydetail_data = [
            'pay_id'    => $pay_id,
            'reading_id' => $reading_id,
            'trans_date'    => $trans_date,
            'trans_payment'    => $payment,
            'trans_arrears_amount'    => $arrears,
        ];

        $trans_id = $this->pay_detailM->add_payment_detail($paydetail_data);
        return $trans_id;
    }
    public function update_payment(Request $request)
    {

        $pay_id = $request->pay_id;
        $trans_id = $request->trans_id;
        $reading_id = $request->reading_id;
        $penalty = $request->due_date_penalty;
        $reading_date = $request->reading_date;
        $payment = $request->trans_payment;
        $water_consumed = $request->reading_waterconsumed;
        $amount_pay = abs($request->amount_pay);
        $partial_amount = $request->partial_amount;
        $prev_amount = $request->prev_amount;
        $pay_status = $request->pay_status;
        $penalty_amount = $request->penalty_amount;
        $duration = $request->duration;
        $zone = $request->zone;

        if ($pay_status == 'Full' && check_due_date($reading_date,$duration,$zone)) {
            $amount_pay -= $penalty_amount;
        }
        if ($pay_status == 'Full') {
            $other_partial_amount = abs($partial_amount - $prev_amount);
            $total_payment = abs($payment);
            
            $amount_pay = abs($partial_amount - $amount_pay - $other_partial_amount);
            // printx( $payment.' - '.$other_partial_amount.' - test:'.$amount_pay.' - '.$partial_amount.' - '.$amount_pay);
        }else{
            $total_payment = abs($payment);
            $amount_pay += $prev_amount;
        }

        // printx( $total_payment.' - '.$amount_pay );
        if($total_payment < $amount_pay){
            $pay_status = 'Partial';
        }else{
            $pay_status = 'Full';
        }

        $data = [
            'pay_status'    => $pay_status,
        ];
        $update = $this->payM->update_payment($pay_id,$data);

        $paydetail_data = [
            'trans_payment'    => $payment,
        ];

        $update = $this->pay_detailM->update_payment_detail($trans_id,$paydetail_data);

        return redirect(url()->previous());
    }

    /* ----------------- */

    public function json_get_unfinished_pay_by_cus_id(Request $request)
    {
        $cus_id = $request->cus_id;
        $records = $this->meter_readM->get_unfinished_pay_by_id($cus_id);
        $data = [];
        foreach ($records as $col) {
            $other_payment = $col->reading_other_payment;
            $penalty = $col->custype_due_date_penalty;
            $reading_amount = $col->reading_amount;
            $reading_date = $col->reading_date;
            $partial_amount = $col->trans_payment;
            $pay_status = $col->pay_status;
            $duration = $col->custype_due_date_duration;
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            $data[] = $col;
        }

        // printx($data);
        return response($data,200)
        ->header('Content-Type', 'application/json');
    }

    public function json_get_payment_by_reading_id(Request $request)
    {
        $reading_id = $request->reading_id;
        $data = array();
        $record = $this->meter_readM->get_meter_reading_by_id($reading_id);

        $other_payment = $record->reading_other_payment;
        $penalty = $record->custype_due_date_penalty;
        $reading_amount = $record->reading_amount;
        $reading_date = $record->reading_date;
        $partial_amount = $record->trans_payment;
        $pay_status = $record->pay_status;
        $duration = $record->custype_due_date_duration;
        $zone = $record->cus_zone;
        $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
        $record->payment_amount = $payment_amount;
        if (check_due_date($reading_date,$duration,$zone)) {
            $due_date_penalty_amount = $reading_amount * $penalty;
        }else{
            $due_date_penalty_amount = 0;
        }
        $record->penalty_amount = $due_date_penalty_amount;
        // printx($payment_amount);
        return response(json_encode($record),200)
        ->header('Content-Type', 'application/json');
    }

    public function pdf_receipt(Request $request){
        $reading_id = $request->reading_id;
        
        $record = $this->meter_readM->get_meter_reading_by_id($reading_id);
        // printx($record);
        $data = array(
            'record'    => $record,
        );
       return PDF::loadView('pdf.receipt_pdf',$data)->setPaper([0, 0, 450, 300], 'portrait')->download('billing_'.date('Ymd-His').'.pdf');
    }

    public function pdf_receipt_old(Request $request){
        $reading_id = $request->reading_id;
        $other_reading_id = $request->other_reading_id;
        $total_arrears = 0;
        
        $record = $this->meter_readM->get_meter_reading_by_id($reading_id);
        // printx($record);
        if ($other_reading_id) {
            $other_reading_id = explode(',', $other_reading_id);
            foreach ($other_reading_id as $reading_id) {
                $result= $this->meter_readM->get_meter_reading_by_id($reading_id);
                $total_arrears +=$result->payment_amount;
            }
        }
        $data = array(
            'record'    => $record,
            'total_arrears'    => $total_arrears,
        );
       return PDF::loadView('pdf.receipt_pdf',$data)->setPaper([0, 0, 450, 300], 'portrait')->stream('billing_'.date('Ymd-His').'.pdf');
    }

    public function export_payment_by_status(Request $request)
    {
        $status = $request->status;
        if ($status == 'Full') {
            $records = $this->meter_readM->get_pay_by_status('Full');
        }elseif ($status == 'Partial') {
            $records = $this->meter_readM->get_pay_by_status('Partial');
        }else{
            $records = $this->meter_readM->get_pay_unpaid();
        }

        // printx($records);
        $data = array(
            'status'    => $status,
            'records'    => $records,
            'meter_readM'    => $this->meter_readM,
        );
       return PDF::loadView('pdf.payment_status',$data)->download('meter-reading'.date('Ymd-His').'.pdf');
    }

}
