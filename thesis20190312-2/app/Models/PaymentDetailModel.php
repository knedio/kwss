<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetailModel extends Model
{
    protected $tbl_name = 'tbl_payment_details';
    protected $prefix = 'trans_';
    protected $tbl_id = 'trans_id';

    public function get_all_payment_detail()
    {
        $results = \DB::table($this->tbl_name.' as paydetail')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->leftJoin('tbl_employee as emp', 'pay.emp_id', '=', 'emp.emp_id')
        ->leftJoin('tbl_meter_reading as mReading', 'paydetail.reading_id', '=', 'mReading.reading_id')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_meter as meter', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->get();
        return $results;
    }

    public function get_by_pay_id($pay_id)
    {
        $results = \DB::table($this->tbl_name.' as paydetail')
        ->leftJoin('tbl_payment as pay', 'paydetail.pay_id', '=', 'pay.pay_id')
        ->where('paydetail.pay_id',$pay_id)
        ->get();

        return $results;
    }

    public function add_payment_detail($data)
    {
        $add_meter = \DB::table($this->tbl_name)->insertGetId($data);
        return $add_meter;
    }
    
    public function update_payment_detail($id, $data)
    {
        $update = \DB::table($this->tbl_name)->where($this->tbl_id, $id)->update($data);
        return $update;
    }

}
