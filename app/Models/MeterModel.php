<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterModel extends Model
{
    protected $tbl_name = 'tbl_meter';
    protected $prefix = 'meter_';
    protected $tbl_id = 'meter_id';

    public function get_all_meter()
    {
        $results = \DB::table($this->tbl_name.' as meter')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->get();
        return $results;
    }
    public function get_by_cus_id($cus_id)
    {
        $results = \DB::table($this->tbl_name.' as meter')
        ->leftJoin('tbl_customer as cus', 'cus.cus_id', '=', 'meter.cus_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('meter.cus_id',$cus_id)
        ->get();
        return $results;
    }

    public function add_meter($data) 
    {
        $add_meter = \DB::table($this->tbl_name)->insert($data);
        return $add_meter;
    }

    public function update_meter($id,$data)
    {
        $edit = \DB::table($this->tbl_name)->where($this->tbl_id, $id)->update($data);
        return $edit;
    }

    public function delete_meter($id)
    {
        $query = "DELETE FROM ".$this->tbl_name." WHERE ".$this->tbl_id." = ?";

        $result = \DB::delete($query, array($id));
        return $result;
    }

}
