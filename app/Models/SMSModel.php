<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSModel extends Model
{
    

	protected $tbl_name = 'tbl_sms';
    protected $prefix = 'sms_';
    protected $tbl_id = 'sms_id';

	public function get_all(){
		$results = \DB::table($this->tbl_name)
        ->get();
        // printx($results);
        return $results;

	}

    public function get_by_status($status)
    {
        $result = \DB::table($this->tbl_name)
        ->where($this->prefix.'status',$status)
        ->get();
        return $result;
    }

    public function get_used()
    {
        $result = \DB::table($this->tbl_name)
        ->where($this->prefix.'status','Used')
        ->first();
        return $result;
    }

	public function get_by_id($id)
    {
		$results = \DB::table($this->tbl_name)
        ->where($this->tbl_id,$id)
        ->first();
        return $results;

	}

    public function delete_sms($id) 
    {
        $delete = \DB::table($this->tbl_name)
        ->where($this->tbl_id, $id)
        ->delete();
        return $delete;
    }

    public function add($data)
    {
    	$add_meter = \DB::table($this->tbl_name)->insertGetId($data);
        return $add_meter;
    }

    public function update_by_id($id,$data)
    {
    	 $edit = \DB::table($this->tbl_name)->where($this->tbl_id, $id)->update($data);
        return $edit;
    }
}
