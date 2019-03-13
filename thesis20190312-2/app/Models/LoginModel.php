<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    public function index() 
    {
        
    }

    public function check_user($username,$password) 
    {
        $user = \DB::table('tbl_account')
        ->where('username', '=', $username)
        ->where('password', '=', $password)
        ->first();

        if($user){
            $account_type = $user->account_type;
            $username = $user->username;
            $acc_id = $user->acc_id;

            if($account_type == 'Customer'){
                $user = \DB::table('tbl_account as acc')
                ->leftJoin('tbl_customer as cus', 'acc.acc_id', '=', 'cus.acc_id')
                ->where('acc.acc_id', '=', $acc_id)
                ->first();
            }else{
                $user = \DB::table('tbl_account as acc')
                ->leftJoin('tbl_employee as emp', 'acc.acc_id', '=', 'emp.acc_id')
                ->where('acc.acc_id', '=', $acc_id)
                ->first();
            }
        }

        return $user;

    }

}
