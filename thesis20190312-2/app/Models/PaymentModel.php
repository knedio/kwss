<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $tbl_name = 'tbl_payment';
    protected $prefix = 'pay_';
    protected $tbl_id = 'pay_id';
    
    public function get_pay_by_id($pay_id)
    {
        $results = \DB::table($this->tbl_name.' as pay')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->leftJoin('tbl_meter_reading as mReading', 'mReading.reading_id', '=', 'paydetail.reading_id')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('mReading.reading_status','Read')
        ->where('paydetail.pay_id',$pay_id)
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*')
        ->get();

        $data = [];
        foreach ($results as $col) {
            $other_payment = $col->reading_other_payment;
            $penalty = $col->custype_due_date_penalty;
            $reading_amount = $col->reading_amount;
            $reading_date = $col->reading_date;
            $partial_amount = $col->trans_payment;
            $pay_status = $col->pay_status;
            $duration = $col->custype_due_date_duration;
            $zone = $col->cus_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * $penalty;
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }
    
    public function get_pay_by_user_id($user_id)
    {
        $results = \DB::table($this->tbl_name.' as pay')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->leftJoin('tbl_meter_reading as mReading', 'mReading.reading_id', '=', 'paydetail.reading_id')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('cus.cus_id',$user_id)
        ->where('mReading.reading_status','Read')
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment')  ,\DB::raw('MAX(paydetail.trans_date) as trans_date'))
        ->groupBy('mReading.reading_id')
        ->get();
        return $results;
    }

    public function get_pay_by_date_with_status($data)
    {
        extract($data);
        $results = \DB::table($this->tbl_name.' as pay')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->leftJoin('tbl_meter_reading as mReading', 'mReading.reading_id', '=', 'paydetail.reading_id')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->whereBetween(\DB::raw('DATE(paydetail.trans_date)'), [$start_date, $end_date])
        ->where('pay.pay_status',$pay_status)
        ->where('mReading.reading_status','Read')
        ->where('cus.cus_id',$user_id)
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment')  ,\DB::raw('MAX(paydetail.trans_date) as trans_date'))
        ->groupBy('mReading.reading_id')
        ->get();

        $data = [];
        foreach ($results as $col) {
            $other_payment = $col->reading_other_payment;
            $penalty = $col->custype_due_date_penalty;
            $reading_amount = $col->reading_amount;
            $reading_date = $col->reading_date;
            $partial_amount = $col->trans_payment;
            $pay_status = $col->pay_status;
            $duration = $col->custype_due_date_duration;
            $zone = $col->cus_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * $penalty;
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_pay_by_reading_id($reading_id)
    {
        $results = \DB::table($this->tbl_name.' as pay')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('paydetail.reading_id',$reading_id)
        ->first();
        return $results;
    }
    
    public function add_payment($data)
    {
        $add_meter = \DB::table($this->tbl_name)->insertGetId($data);
        return $add_meter;
    }

    public function update_payment($id,$data)
    {
        $update = \DB::table($this->tbl_name)->where($this->tbl_id, $id) ->update($data);
        return $update;
    }

    public function check_payment()
    {

    }

    public function calculate_payment($reading_id)
    {
        $results = \DB::table($this->tbl_name.' as pay')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->where('paydetail.reading_id',$reading_id)
        ->sum('paydetail.trans_payment');
        // ->first();
        return $results;
    }

}
