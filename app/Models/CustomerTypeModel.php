<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTypeModel extends Model
{
    protected $tbl_name = 'tbl_customer_type';
    protected $prefix = 'custype_';
    protected $tbl_id = 'custype_id';
    
    public function get_all_custype()
    {
        $results = \DB::table($this->tbl_name)
        ->where($this->prefix.'published',1)
        ->get();
        return $results;
    }

    public function get_by_id($id)
    {
        $result = \DB::table($this->tbl_name)
        ->where($this->prefix.'id',$id)
        ->first();
        return $result;
    }

    public function get_by_type($type){
        $result = \DB::table($this->tbl_name)
        ->where($this->prefix.'type',$type)
        ->first();

        $tbl_id = $result->custype_id;

        return $tbl_id;
    }

    public function get_count()
    {
        $result = \DB::table($this->tbl_name)->count();
        return $result;
    }

    public function add_custype($data)
    {
        $add = \DB::table($this->tbl_name)->insert($data);
        return $add;
    }

    public function edit_custype($id, $data)
    {
        $update = \DB::table($this->tbl_name)
            ->where($this->prefix.'id',$id)
            ->update($data);
        return $update;
    }

    public function delete_custype($id)
    {
        $data = [
            $this->prefix.'published' => 0
        ];
        $update = \DB::table($this->tbl_name)
            ->where($this->prefix.'id',$id)
            ->update($data);
        // $delete = \DB::table($this->tbl_name)->where($this->tbl_id, $id)->delete();
        return $update;
    }

    public function get_old_by_id($id)
    {
        $results = \DB::table('tbl_customer_type_old')
        ->where($this->prefix.'id',$id)
        ->get();
        return $results;
    }

    public function add_custype_old($data)
    {
        $add = \DB::table('tbl_customer_type_old')->insert($data);
        return $add;
    }

}
