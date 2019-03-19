<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterReadingModel extends Model
{
    protected $tbl_name = 'tbl_meter_reading';
    protected $tbl_id = 'reading_id';
    protected $prefix = 'reading_';

    public function get_meter_reading_by_id($id)
    {
        $result = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('mReading.'.$this->tbl_id,$id)
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment'),\DB::raw('SUM(paydetail.trans_arrears_amount) as total_arrears'))
        ->first();

        $other_payment = $result->reading_other_payment;
        $penalty = $result->custype_due_date_penalty;
        $reading_amount = $result->reading_amount;
        $reading_date = $result->reading_date;
        $partial_amount = $result->trans_payment;
        $pay_status = $result->pay_status;
        $duration = $result->custype_due_date_duration;
        $zone = $result->custype_zone;
        $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
        $result->due_date = get_due_date($reading_date,$duration,$zone);
        $result->payment_amount = $payment_amount;
        if (check_due_date($reading_date,$duration,$zone)) {
            $due_date_penalty_amount = $reading_amount * ($penalty/100);
        }else{
            $due_date_penalty_amount = 0;
            // printx($due_date_penalty_amount);
        }
        $result->penalty_amount = $due_date_penalty_amount;
        return $result;
    }

    public function get_meter_reading_by_month_no_num()
    {
        $current_month = date('m');
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where(\DB::raw('MONTH(mReading.reading_date)'),$current_month)
        ->where('cus.cus_mobile_number', '=', '')
        ->get();
        $infos = array();
        foreach ($results as $result) {
            $other_payment = $result->reading_other_payment;
            $penalty = $result->custype_due_date_penalty;
            $reading_amount = $result->reading_amount;
            $reading_date = $result->reading_date;
            $duration = $result->custype_due_date_duration;
            $zone = $result->custype_zone;

            $result->due_date = get_due_date($reading_date,$duration,$zone);
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
                // printx($due_date_penalty_amount);
            }
            $result->penalty_amount = $due_date_penalty_amount;
            $infos[] = $result;
        }
        return $infos;
    }

    public function get_meter_reading_by_month()
    {
        $current_month = date('m');
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where(\DB::raw('MONTH(mReading.reading_date)'),$current_month)
        ->get();
        $infos = array();
        foreach ($results as $result) {
            $other_payment = $result->reading_other_payment;
            $penalty = $result->custype_due_date_penalty;
            $reading_amount = $result->reading_amount;
            $reading_date = $result->reading_date;
            $duration = $result->custype_due_date_duration;
            $zone = $result->custype_zone;
            
            $result->due_date = get_due_date($reading_date,$duration,$zone);

            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
                // printx($due_date_penalty_amount);
            }
            $result->penalty_amount = $due_date_penalty_amount;
            $infos[] = $result;
        }
        
        return $infos;
    }

    public function get_all_meter_reading()
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->get();
        foreach ($results as $result) {
            $reading_date = $result->reading_date;
            $duration = $result->custype_due_date_duration;
            $zone = $result->custype_zone;
            $result->due_date = get_due_date($reading_date,$duration,$zone);
        }
        // echo '<pre>'.print_r($results,1).'</pre>';exit();
        return $results;
    }

    public function get_previous_meter_reading($reading_id,$cus_id)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('mReading.reading_id', '<', $reading_id)
        ->where('cus.cus_id', $cus_id)
        ->orderBy('mReading.reading_id', 'desc')
        ->limit(1)
        ->first();
        // echo '<pre>'.print_r($results,1).'</pre>';exit();
        return $results;
    }

    public function get_meter_reading_by_status($status)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('mReading.reading_status',$status)
        ->get();
        // echo '<pre>'.print_r($results,1).'</pre>';exit();
        return $results;
    }

    public function get_meter_reading_by_montly($status)
    {
        $current_month = date('m');
        if($status == 'All'){
            $results = \DB::table($this->tbl_name.' as mReading')
            ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
            ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
            ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
            ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
            ->where(\DB::raw('MONTH(mReading.reading_date)'),$current_month)
            ->get();
        }else{
            $results = \DB::table($this->tbl_name.' as mReading')
            ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
            ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
            ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
            ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
            ->where('mReading.reading_status',$status)
            ->where(\DB::raw('MONTH(mReading.reading_date)'),$current_month)
            ->get();
        }

        foreach ($results as $result) {
            $reading_date = $result->reading_date;
            $duration = $result->custype_due_date_duration;
            $zone = $result->custype_zone;
            $result->due_date = get_due_date($reading_date,$duration,$zone);
        }

        // echo '<pre>'.print_r($results,1).'</pre>';exit();
        return $results;
    }

    public function get_waterconsumed($id)
    {
        $result = \DB::table($this->tbl_name)
        ->where($this->tbl_id,$id)
        ->first();

        return $result->reading_waterconsumed;
    }

    public function get_all_meter_reading_with_pay()
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('mReading.reading_status','Read')
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
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            // printx($due_date_penalty_amount);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_pay_by_status($status)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('mReading.reading_status','Read')
        ->where('pay.pay_status',$status)
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment')  ,\DB::raw('MAX(paydetail.trans_date) as trans_date'))
        ->groupBy('mReading.reading_id')
        ->get();

        $data = [];
        foreach ($results as $col) {
            $other_payment = $col->reading_other_payment;
            $penalty = $col->custype_due_date_penalty;
            $payment_amount = $col->reading_amount;
            $reading_date = $col->reading_date;
            $partial_amount = $col->trans_payment;
            $pay_status = $col->pay_status;
            $duration = $col->custype_due_date_duration;
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$payment_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $payment_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_pay_unpaid()
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->whereNull('paydetail.reading_id')
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*')
        ->orderBy('cus.cus_id','desc')
        ->orderBy('mReading.reading_date','asc')
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
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            // printx($due_date_penalty_amount);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_cus_details_by_status($user_id,$status)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('cus.cus_id',$user_id)
        ->where('mReading.reading_status','Read')
        ->where('pay.pay_status',$status)
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
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }

        return $data;
    }

    public function get_cus_details_unpaid($user_id)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('cus.cus_id',$user_id)
        ->whereNull('paydetail.reading_id')
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
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_cus_details_by_status_id($user_id,$status, $pay_id)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('pay.pay_id',$pay_id)
        ->where('cus.cus_id',$user_id)
        ->where('mReading.reading_status','Read')
        ->where('pay.pay_status',$status)
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment')  ,\DB::raw('MAX(paydetail.trans_date) as trans_date'))
        ->groupBy('mReading.reading_id')
        ->first();
        return $results;
    }

    public function get_cus_details_unpaid_id($user_id, $reading_id)
    {
        $result = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->where('mReading.reading_id',$reading_id)
        ->whereNull('paydetail.reading_id')
        ->select('paydetail.*','pay.*','emp.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment')  ,\DB::raw('MAX(paydetail.trans_date) as trans_date'))
        ->groupBy('mReading.reading_id')
        ->first();

        $data = [];
        $other_payment = $result->reading_other_payment;
        $penalty = $result->custype_due_date_penalty;
        $reading_amount = $result->reading_amount;
        $reading_date = $result->reading_date;
        $partial_amount = $result->trans_payment;
        $pay_status = $result->pay_status;
        $duration = $result->custype_due_date_duration;
        $zone = $result->custype_zone;

        $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
        $result->due_date = get_due_date($reading_date,$duration,$zone);
        $result->payment_amount = $payment_amount;
        if (check_due_date($reading_date,$duration,$zone)) {
            $due_date_penalty_amount = $reading_amount * ($penalty/100);
        }else{
            $due_date_penalty_amount = 0;
        }
        $result->penalty_amount = $due_date_penalty_amount;
        $data[] = $result;
        return $data;
    }

    public function get_all_pay_by_date($data)
    {
        extract($data);
        
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->whereBetween(\DB::raw('DATE(mReading.reading_date)'), [$start_date, $end_date])
        ->where('cus.cus_id',$user_id)
        ->where('mReading.reading_status','Read')
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
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }
    
    public function get_unpaid_pay_by_date($data)
    {
        extract($data);
        
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->whereBetween(\DB::raw('DATE(mReading.reading_date)'), [$start_date, $end_date])
        ->where('cus.cus_id',$user_id)
        ->whereNull('paydetail.reading_id')
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
            $zone = $col->custype_zone;

            $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_amount,$pay_status,FALSE,FALSE);
            $col->due_date = get_due_date($reading_date,$duration,$zone);
            $col->payment_amount = $payment_amount;
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_unpaid_users()
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->whereNull('paydetail.reading_id')
        ->select('paydetail.*','pay.*','mReader.*','meter.*','cus.*','mReading.*')
        ->groupBy('mReading.reading_id')
        ->get();
        // echo '<pre>'.print_r($results,1).'</pre>';exit();
        return $results;
    }

    public function get_sms_details()
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->whereNull('paydetail.reading_id')
        ->orWhere('pay.pay_status', 'Partial')
        ->select('paydetail.*','pay.*','mReader.*','meter.*','cus.*','custype.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment'))
        ->groupBy('mReading.reading_id')
        ->get();
        // echo '<pre>'.print_r($results,1).'</pre>';exit();

        $data = [];
        foreach ($results as $col) {
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
            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
            }
            $col->penalty_amount = $due_date_penalty_amount;
            $data[] = $col;
        }
        
        return $data;
    }

    public function get_reading_by_user_id($id)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->orderBy('mReading.reading_date','desc')
        ->first();
        return $results;
    }
    /* ------------------------------------ */ 
    public function calculate_prev_unpaid($reading_id,$cus_id)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->where('mReading.reading_id', '<', $reading_id)
        ->where('cus.cus_id', $cus_id)
        ->where(function ($query) {
            $query->whereNull('paydetail.reading_id')
                  ->orWhere('pay.pay_status', 'Partial');
        })
        ->select('paydetail.*','pay.*','meter.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment'))
        // ->select(\DB::raw('SUM(paydetail.trans_payment) as total_sales')
        ->orderBy('mReading.reading_id', 'desc')
        ->groupBy('mReading.reading_id')
        ->get();
        // echo '<pre>'.print_r($results,1).'</pre>';exit();

        $total_arrears = 0;
        foreach ($results as $col) {
            $pay_id = $col->pay_id;
            $trans_payment = $col->trans_payment;
            $reading_amount = $col->reading_amount;
            if ($pay_id) {
                $total_arrears += ($reading_amount-$trans_payment);
            }else{
                $total_arrears += $reading_amount;
            }
        }
        return $total_arrears;
    }


    public function get_unfinished_pay_by_id($cus_id)
    {
        $results = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->leftJoin('tbl_payment_details as paydetail', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->where('cus.cus_id', $cus_id)
        ->where(function ($query) {
            $query->whereNull('paydetail.reading_id')
                  ->orWhere('pay.pay_status', 'Partial');
        })
        ->select('paydetail.*','pay.*','custype.*','meter.*','mReading.*',\DB::raw('SUM(paydetail.trans_payment) as trans_payment'))
        // ->select(\DB::raw('SUM(paydetail.trans_payment) as total_sales')
        ->orderBy('mReading.reading_id', 'desc')
        ->groupBy('mReading.reading_id')
        ->get();
        // echo '<pre>'.print_r($results,1).'</pre>';exit();

        return $results;
    }

    public function add_meter_reading($data) 
    {
        $add = \DB::table($this->tbl_name)->insertGetId($data);
        return $add;
    }

    public function update_meter_reading($id,$data)
    {
        $edit = \DB::table($this->tbl_name)->where($this->tbl_id, $id) ->update($data);
        return $edit;
    }

    public function delete_meter_reading($id) 
    {
        $delete = \DB::table($this->tbl_name)->where($this->tbl_id, $id)->delete();
        return $delete;
    }

    // public function check_reading($data){
    //     extract($data);

    //     $due_date = get_due_date($reading_date,$custype_due_date_duration);

    //     $result = \DB::table($this->tbl_name.' as mReading')
    //     ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
    //     ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
    //     ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
    //     ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
    //     ->where('mReading.reading_status','Read')
    //     ->first();



    //     return $result;
    // }

    public function get_last_reading($meter_id){
        $result = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('mReading.meter_id',$meter_id)
        ->where('mReading.reading_status','Unread')
        ->orderBy('mReading.reading_id','DESC')
        ->limit(1)
        ->first();

        if ($result) {
            $zone = $result->custype_zone;
            $reading_date = $result->reading_date;
            $duration = $result->custype_due_date_duration;
            $result->due_date = get_due_date($reading_date,$duration,$zone);
        }

        return $result;
    }

    public function get_cus_exceed_due_date($month,$year)
    {
        $records = $this->get_all_meter_reading_with_pay();
        // $partial_records = $this->get_pay_by_status('Partial');
        // $unpaid_records = $this->get_pay_unpaid();
        // $records = array_merge($partial_records,$unpaid_records);
        $results = array();
        // printx($records);
        foreach ($records as $col) {
            $due_date = $col->due_date;
            // printx($due_date);
            if ( date('Y-m-d') > $due_date && date('m',strtotime($due_date)) == $month && date('Y',strtotime($due_date)) == $year ) {
                $results[] = $col;
            }
        }
        // printx($results);
        return $results;
    }

    public function get_cus_exceed_due_date_all()
    {
        $records = $this->get_all_meter_reading_with_pay();
        $results = array();
        foreach ($records as $col) {
            $due_date = $col->due_date;
            if ( date('Y-m-d') > $due_date) {
                $results[] = $col;
            }
        }
        return $results;
    }

    public function get_meter_reading_by_month_with_cus($cus_id,$meter_id)
    {
        $current_month = date('m');
        $result = \DB::table($this->tbl_name.' as mReading')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where(\DB::raw('MONTH(mReading.reading_date)'),$current_month)
        ->where('cus.cus_id',$cus_id)
        ->where('meter.meter_id',$meter_id)
        ->first();
        // $infos = array();
        // foreach ($results as $result) {
        if ($result) {
            $other_payment = $result->reading_other_payment;
            $penalty = $result->custype_due_date_penalty;
            $reading_amount = $result->reading_amount;
            $reading_date = $result->reading_date;
            $duration = $result->custype_due_date_duration;
            $zone = $result->custype_zone;
            
            $result->due_date = get_due_date($reading_date,$duration,$zone);

            if (check_due_date($reading_date,$duration,$zone)) {
                $due_date_penalty_amount = $reading_amount * ($penalty/100);
            }else{
                $due_date_penalty_amount = 0;
                // printx($due_date_penalty_amount);
            }
            $result->penalty_amount = $due_date_penalty_amount;
        }
            // $infos[] = $result;
        // }
        
        return $result;
    }
}
