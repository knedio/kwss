<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{

	protected $tbl_name = 'tbl_request';
    protected $prefix = 'request_';
    protected $tbl_id = 'request_id';

	public function get_all(){
		$results = \DB::table($this->tbl_name)
        ->get();
        $records = [];
        foreach ($results as $result) {
            $type = $result->request_type;
            $prev_data_unserialized = unserialize($result->request_prev_data_serialized);
            $data_unserialized = unserialize($result->request_data_serialized);
            $current_data = '';
            $prev_data = '';
            if ($data_unserialized) {
                foreach ($data_unserialized as $key => $value) {
                    if ($key == 'id') {
                        continue;
                    }
                    $current_data .= ucfirst($key).': '.$value.'<br />'; 
                }
            }
            if ($prev_data_unserialized) {
                foreach ($prev_data_unserialized as $key => $value) {
                    if ($key == 'id') {
                        continue;
                    }
                    $prev_data .= ucfirst($key).': '.$value.'<br />'; 
                }
            }
            $records[] = [
                'id'    => $result->request_id,
                'cus_id'    => $result->cus_id,
                'prev_data_serialized'    => $prev_data,
                'data_serialized'    => $current_data,
                'type'    => $result->request_type,
                'status'    => $result->request_status,
            ];
        }
        // printx($records);
        return $records;

	}

    public function get_count_pending()
    {
        $result = \DB::table($this->tbl_name)
        ->where($this->prefix.'status','Pending')
        ->count();
        return $result;
    }

    public function get_by_id($id){
        $results = \DB::table($this->tbl_name)
        ->where($this->tbl_id,$id)
        ->first();
        return $results;

    }

	public function get_by_cus_id($cus_id){
		$results = \DB::table($this->tbl_name)
        ->where('cus_id',$cus_id)
        ->first();
        return $results;

	}

    public function add($data){
    	$add_meter = \DB::table($this->tbl_name)->insertGetId($data);
        return $add_meter;
    }

    public function update_by_id($id,$data){
    	 $edit = \DB::table($this->tbl_name)->where($this->tbl_id, $id)->update($data);
        return $edit;
    }

    public function check_if_exist($cus_id,$type){
        $check = \DB::table($this->tbl_name)
        ->where('request_type', $type)
        ->where('request_status', 'Pending')
        ->where('cus_id', $cus_id)
        ->first();
        return $check;
    }
}
