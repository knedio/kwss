<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $id = DB::table('tbl_account')->insertGetId ([
            'password' => 'kwss.123',
            'account_type' => 'Admin',
        ]);
        $username = str_pad($id, 6, '0', STR_PAD_LEFT);
        $username = date('Y').$username;
        $update_account = DB::table('tbl_account')->where('acc_id',$id)->update(array('username'=>$username));

        $data = [
            'acc_id'              => $id,
            'emp_firstname'     => 'Admin',
            'emp_lastname'      => 'Name',
            'emp_mobile_number' => '',
            'emp_address'       => 'CDO',
            'emp_created'       => date('Y-m-d H:i:s')
        ];

        $add_user = DB::table('tbl_employee')->insertGetId($data);

        $custype = array('Residential','Commercial');
        $custype_prefix = 'custype_';
        
        foreach ($custype as $type) {
        	for ($i=1; $i <= 15 ; $i++) { 
        		$data = [
		            $custype_prefix.'type'  => $type,
		            $custype_prefix.'min_cubic_meter'   => 8,
		            $custype_prefix.'cubic_meter_rate'  => 14,
		            $custype_prefix.'min_peso_rate'     => 70,
		            $custype_prefix.'due_date_penalty'  => 10,
		            $custype_prefix.'zone'  => $i,
		            $custype_prefix.'due_date_duration'  => 'By Zone'
		        ];
        		$add_custype = DB::table('tbl_customer_type')->insert($data);
        	}
        }
    }
}
