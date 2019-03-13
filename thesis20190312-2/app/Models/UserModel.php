<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserModel extends Model
{
    public function get_cus_details_by_id($id)
    {
        $result = \DB::table('tbl_account as acc')
        ->leftJoin('tbl_customer as cus', 'acc.acc_id', '=', 'cus.acc_id')
        // ->leftJoin('tbl_meter as meter', 'cus.cus_id', '=', 'meter.cus_id')
        // ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('cus.cus_id', $id)
        ->select('acc.*', 'cus.cus_id as id', 'cus.cus_firstname as firstname', 'cus.cus_lastname as lastname', 'cus.cus_mobile_number as mobile_number', 'cus.cus_address as address', 'cus.cus_zone as zone')
        ->first();

        return $result;
    }

    public function get_user_by_id($id,$account_type)
    {
        if($account_type == 'Admin' || $account_type == 'Employee'){
            $result = \DB::table('tbl_account as acc')
            ->leftJoin('tbl_employee as emp', 'acc.acc_id', '=', 'emp.acc_id')
            ->where('emp.emp_id', $id)
            ->select('acc.*', 'emp.emp_id as id', 'emp.emp_firstname as firstname', 'emp.emp_lastname as lastname', 'emp.emp_mobile_number as mobile_number', 'emp.emp_address as address')
            ->first();
        }else if($account_type == 'Water Reader'){
            
            $result = \DB::table('tbl_water_reader')
            ->where('reader_id', $id)
            ->select('reader_id as id', 'reader_firstname as firstname', 'reader_lastname as lastname', 'reader_mobile_number as mobile_number', 'reader_address as address')
            ->first();
            $result->account_type = 'Water Reader';
        }else{
            $result = \DB::table('tbl_account as acc')
            ->leftJoin('tbl_customer as cus', 'acc.acc_id', '=', 'cus.acc_id')
            // ->leftJoin('tbl_meter as meter', 'cus.cus_id', '=', 'meter.cus_id')
            // ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
            ->where('cus.cus_id', $id)
            ->select('acc.*', 'cus.cus_id as id', 'cus.cus_firstname as firstname', 'cus.cus_lastname as lastname', 'cus.cus_mobile_number as mobile_number', 'cus.cus_address as address', 'cus.cus_zone as zone')
            ->first();
        }
        return $result;
    }

    public function get_users_by_account_type($account_type)
    {
        if($account_type == 'Admin' || $account_type == 'Employee'){
            $results = \DB::table('tbl_account as acc')
            ->leftJoin('tbl_employee as emp', 'acc.acc_id', '=', 'emp.acc_id')
            ->where('acc.account_type', $account_type)
            ->get();
        }else if($account_type == 'Water Reader'){
            $results = \DB::table('tbl_water_reader')
            ->get();
        }else{
            $results = \DB::table('tbl_account as acc')
            ->leftJoin('tbl_customer as cus', 'acc.acc_id', '=', 'cus.acc_id')
            // ->leftJoin('tbl_meter as meter', 'cus.cus_id', '=', 'meter.cus_id')
            // ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
            ->where('acc.account_type', $account_type)
            ->get();
        }
        return $results;
    }

    public function add_user($data)
    {
        extract($data);
        $username = '';
        if($account_type != 'Water Reader'){
            $id = \DB::table('tbl_account')->insertGetId(
                [
                    'password' => 'kwss.123',
                    'account_type' => $account_type
                ]
            ); 
            $username = str_pad($id, 6, '0', STR_PAD_LEFT);
            $username = date('Y').$username;
            $update_account = \DB::table('tbl_account')->where('acc_id',$id)->update(array('username'=>$username));

             if($account_type == 'Customer'){
                $prefix = 'cus_';
                $tbl_name = 'tbl_customer';

                $data = [
                    'acc_id'              => $id,
                    $prefix.'firstname'     => $firstname,
                    $prefix.'lastname'      => $lastname,
                    $prefix.'mobile_number' => $mobile_number,
                    $prefix.'address'       => $address,
                    $prefix.'zone'          => $zone,
                    $prefix.'created'       => date('Y-m-d H:i:s')
                ];
            }
            else{
                $prefix = 'emp_';
                $tbl_name = 'tbl_employee';

                $data = [
                    'acc_id'              => $id,
                    $prefix.'firstname'     => $firstname,
                    $prefix.'lastname'      => $lastname,
                    $prefix.'mobile_number' => $mobile_number,
                    $prefix.'address'       => $address,
                    $prefix.'created'       => date('Y-m-d H:i:s')
                ];
            }
        }else{
            $prefix = 'reader_';
            $tbl_name = 'tbl_water_reader';
            $data = [
                $prefix.'firstname'     => $firstname,
                $prefix.'lastname'      => $lastname,
                $prefix.'mobile_number' => $mobile_number,
                $prefix.'address'       => $address,
                $prefix.'created'       => date('Y-m-d H:i:s')
            ];
        }
        $add_user = \DB::table($tbl_name)->insertGetId($data);
        $info = array(
            'user_id'   => $add_user,
            'username'   => $username,
        );
        return $info;
    }

    public function update_cus_address($id, $data){

        $tbl_name = 'tbl_customer';
        $edit = \DB::table($tbl_name)->where('cus_id', $id)->update($data);
        return $edit;
    }

    public function update_user($data)
    {
        extract($data);
        if($account_type != 'Water Reader'){
            if($account_type == 'Customer'){
                $prefix = 'cus_';
                $tbl_name = 'tbl_customer';
                $data = [
                    'acc.account_type'                => $account_type,
                    'acc.password'                => $password,
                    // $tbl_name.'.'.$prefix.'firstname'     => $firstname,
                    // $tbl_name.'.'.$prefix.'lastname'      => $lastname,
                    $tbl_name.'.'.$prefix.'mobile_number' => $mobile_number
                ];
            }
            else{
                $prefix = 'emp_';
                $tbl_name = 'tbl_employee';
                $data = [
                    'acc.account_type'                => $account_type,
                    'acc.password'                => $password,
                    $tbl_name.'.'.$prefix.'firstname'     => $firstname,
                    $tbl_name.'.'.$prefix.'lastname'      => $lastname,
                    $tbl_name.'.'.$prefix.'mobile_number' => $mobile_number,
                    $tbl_name.'.'.$prefix.'address'       => $address
                ];
            }
            $edit = \DB::table('tbl_account as acc')
            ->leftJoin($tbl_name, 'acc.acc_id', '=', $tbl_name.'.acc_id')
            ->where($tbl_name.'.'.$prefix.'id', $id)
            ->update($data);
        }else{
            $prefix = 'reader_';
            $tbl_name = 'tbl_water_reader';
            $data = [
                $prefix.'firstname'     => $firstname,
                $prefix.'lastname'      => $lastname,
                $prefix.'mobile_number' => $mobile_number,
                $prefix.'address'       => $address
            ];
            $edit = \DB::table($tbl_name)->where($prefix.'id', $id) ->update($data);
        }
        return $edit;
    }

    public function update_cus_by_id($id,$data){
        $update = \DB::table('tbl_customer')
            ->where('cus_id', $id)
            ->update($data);
        return $update;
    }

    public function delete_user($id,$account_type)
    {
        if($account_type == 'Admin' || $account_type == 'Employee'){
            $query = "DELETE acc,emp FROM tbl_account as acc 
            LEFT JOIN tbl_employee as emp ON acc.acc_id = emp.acc_id  
            WHERE emp.emp_id = ?";
        }else if($account_type == 'Water Reader'){
            $query = "DELETE FROM tbl_water_reader
            WHERE reader_id = ?";
        }else{
            $query = "DELETE acc,cus FROM tbl_account as acc 
            LEFT JOIN tbl_customer as cus ON acc.acc_id = cus.acc_id  
            WHERE cus.cus_id = ?";
        }

        $result = \DB::delete($query, array($id));
        return $result;
    }

    /* ------------- */
    public function get_all_cus()
    {
        $results = \DB::table('tbl_customer as cus')
        ->get();
        return $results;
    }

    public function get_count_customer()
    {
        $result = \DB::table('tbl_customer')->count();
        return $result;
    }

    public function get_count_admin()
    {
        $result = \DB::table('tbl_account as acc')
        ->leftJoin('tbl_employee as emp', 'acc.acc_id', '=', 'emp.acc_id')
        ->where('acc.account_type','Admin')
        ->count();
        return $result;
    }

    public function get_count_employee()
    {
        $result = \DB::table('tbl_account as acc')
        ->leftJoin('tbl_employee as emp', 'acc.acc_id', '=', 'emp.acc_id')
        ->where('acc.account_type','Employee')
        ->count();
        return $result;
    }

    public function get_count_reader()
    {
        $result = \DB::table('tbl_water_reader')->count();
        return $result;
    }

    public function get_monthly_unread_customer()
    {
        $results = \DB::table('tbl_customer as cus')
        ->leftJoin('tbl_meter as meter', 'meter.cus_id', '=', 'cus.cus_id')
        ->leftJoin('tbl_meter_reading as mReading', 'mReading.meter_id', '=', 'meter.meter_id')
        ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
        ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where(function ($query) {
            $query->where(\DB::raw('MONTH(mReading.reading_date)'),date('m'))
                  ->where('mReading.reading_status','Unread');
        })
        ->orWhere(function ($query) {
            $query->where(\DB::raw('MONTH(mReading.reading_date)'),date('m'))
                  ->orWhereNull('mReading.reading_status','Unread');
        })
        // ->orWhereNull('mReading.meter_id')
        // ->select('acc.*', 'cus.cus_id as id', 'cus.cus_firstname as firstname', 'cus.cus_lastname as lastname', 'cus.cus_mobile_number as mobile_number', 'cus.cus_address as address', 'cus.cus_zone as zone')
        ->get();
        // printx($results);
        return $results;
    }
    // public function get_monthly_unread_customer()
    // {
    //     $results = \DB::table('tbl_customer as cus')
    //     ->leftJoin('tbl_meter as meter', 'meter.cus_id', '=', 'cus.cus_id')
    //     ->leftJoin('tbl_meter_reading as mReading', 'mReading.meter_id', '=', 'meter.meter_id')
    //     ->leftJoin('tbl_water_reader as mReader', 'mReading.reader_id', '=', 'mReader.reader_id')
    //     ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
    //     ->where('mReading.reading_status','Unread');
    //     // ->where(function ($query) {
    //     //     $duration = $query['custype'];
    //     //     printx($duration);
    //     //     if ($duration == 'Monthly') {
    //     //         $query->where(\DB::raw('MONTH(mReading.reading_date)'),date('m'))
    //     //     }else{

    //     //     }
    //     // })
    //     ->orWhereNull('mReading.meter_id')
    //     // ->select('acc.*', 'cus.cus_id as id', 'cus.cus_firstname as firstname', 'cus.cus_lastname as lastname', 'cus.cus_mobile_number as mobile_number', 'cus.cus_address as address', 'cus.cus_zone as zone')
    //     ->get();
    //     foreach ($results as $col) {
    //         if ($col->reading_id) {
    //             $duration = $col->custype_due_date_duration;
    //             if ($duration == 'Monthly') {
                    
    //             }
    //         }
    //     }
    //     // printx($results);
    //     return $results;
    // }

    public function get_cus_by_month_created($month,$year)
    {
        $results = \DB::table('tbl_account as acc')
        ->leftJoin('tbl_customer as cus', 'acc.acc_id', '=', 'cus.acc_id')
        // ->leftJoin('tbl_meter as meter', 'cus.cus_id', '=', 'meter.cus_id')
        // ->leftJoin('tbl_customer_type as custype', 'meter.custype_id', '=', 'custype.custype_id')
        ->where('acc.account_type', 'Customer')
        ->where(\DB::raw('MONTH(cus.cus_created)'), $month)
        ->where(\DB::raw('Year(cus.cus_created)'), $year)
        ->get();

        return $results;
    }

}
