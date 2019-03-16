<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use App\Models\CustomerTypeModel;
use App\Models\MeterModel;
use App\Models\PaymentModel;
use App\Models\MeterReadingModel;
use App\Models\RequestModel;
use App\Models\SMSModel;

use Carbon\Carbon;
use PDF;
// use Spatie\DbDumper\Databases;
use Ifsnop\Mysqldump as IMysqldump;

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
  
use Importer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends Controller
{   
    protected $userM;
    protected $custypeM;
    protected $meterM;
    protected $payM;
    protected $meter_readM;
    protected $reqM;
    protected $smsM;

    public function __construct(
        UserModel $user, 
        CustomerTypeModel $custypeM, 
        MeterModel $meterM,
        PaymentModel $payM,
        MeterReadingModel $meter_readM,
        RequestModel $reqM, 
        SMSModel $smsM) 
    {
        $this->userM = new $user;
        $this->custypeM = new $custypeM;
        $this->meterM = new $meterM;
        $this->payM = new $payM;
        $this->meter_readM = new $meter_readM;
        $this->reqM = new $reqM;
        $this->smsM = new $smsM;
        
        $this->middleware(function ($request, $next){
            if(!session('user_logged')){
                return redirect()->route('login');
            }
            $response = $next($request);
            return $response
            ->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        });
    }

    public function index()
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

        $admin_records = $this->userM->get_users_by_account_type('Admin');
        $employee_records = $this->userM->get_users_by_account_type('Employee');
        $customer_records = $this->userM->get_users_by_account_type('Customer');
        $wr_records = $this->userM->get_users_by_account_type('Water Reader');
        $custype_records = $this->custypeM->get_all_custype();
        return view('pages.user',[
            'admin_records'     =>  $admin_records,
            'employee_records'  =>  $employee_records,
            'customer_records'  =>  $customer_records,
            'wr_records'        =>  $wr_records,
            'custype_records'   =>  $custype_records,
        ]);
    }

    public function profile(Request $request)
    {
        $id = $request->id;
        $account_type = $request->account_type;
        $result = $this->userM->get_user_by_id($id,$account_type);
        $custype_records = $this->custypeM->get_all_custype();
        $meter_records = $this->meterM->get_by_cus_id($result->id);
        // printx($meter_records);
        return view('pages.profile',[
            'record' =>  $result,
            'custype_records' =>  $custype_records,
            'meter_records' =>  $meter_records,
        ]);
    }

    public function add_user_page(Request $request)
    {
        $custype_records = $this->custypeM->get_all_custype();
        return view('pages.add_user',[
            'custype_records'   =>  $custype_records,
        ]);
    }

    public function add_user(Request $request)
    {
        $account_type = $request->account_type;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $mobile_number = $request->mobile_number;
        $address = $request->address;
        $data = [
            'account_type'  => $account_type,
            'firstname'     => $firstname,
            'lastname'      => $lastname,
            'mobile_number' => $mobile_number,
            'address'       => $address
        ];
        $user_info = $this->userM->add_user($data);
        if($account_type == 'Customer'){
            // printx($request->custype_id);
            $data_meter = [];
            $custype_id = $request->custype_id;
            $no_of_meter = $request->no_of_meter;
            $meter_serial_no = $request->meter_serial_no;
            $meter_model = $request->meter_model;
            $meter_duedate = $request->meter_duedate;
            $meter_address = $request->meter_address;

            for ($i=0; $i < $no_of_meter; $i++) { 
                $data_meter[] = [
                    'cus_id'        => $user_info['user_id'],
                    'custype_id'    => $custype_id[$i],
                    'meter_serial_no'   => $meter_serial_no[$i],
                    'meter_model'   => $meter_model[$i],
                    'meter_duedate' => $meter_duedate[$i],
                    'meter_address' => $meter_address[$i],
                ];
            }
            // printx($data_meter);
            $add_meter = $this->meterM->add_meter($data_meter);
        }
        if ($account_type != 'Water Reader' && $user_info) {
            $request->session()->flash('add_successfull', 'Successfully added "'.$lastname.'" and its ID Number is '.$user_info['username'].'.');
        }
        return redirect()->route('users');
    }

    public function edit_user(Request $request)
    {
        $id = $request->id;
        $account_type = $request->account_type;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $mobile_number = $request->mobile_number;
        $address = $request->address;
        $password = $request->password;
        $zone = $request->zone;
       
        if($account_type == 'Customer'){
            $no_of_meter = $request->no_of_meter;
            $data = [
                'id'            => $id,
                'account_type'  => $account_type,
                'firstname'     => $firstname,
                // 'lastname'      => $lastname,
                'mobile_number' => $mobile_number,
                // 'address'       => $address,
                'password'       => $password,
                // 'zone'       => $zone,
            ];
            $meter_id = $request->meter_id;
            $custype_id = $request->custype_id;
            $no_of_meter = $request->no_of_meter;
            $meter_serial_no = $request->meter_serial_no;
            $meter_model = $request->meter_model;
            $meter_duedate = $request->meter_duedate;
            $meter_address = $request->meter_address;
            // $data_meter = [];
            if ($meter_id) {
                for ($i=0; $i < $no_of_meter; $i++) {
                    $data_meter = [
                        'cus_id'    => $id,
                        'custype_id'    => $custype_id[$i],
                        'meter_serial_no'   => $meter_serial_no[$i],
                        'meter_model'   => $meter_model[$i],
                        'meter_duedate' => $meter_duedate[$i],
                        'meter_address' => $meter_address[$i],
                    ];
                    if ($meter_id[$i]) {
                        $edit_meter = $this->meterM->update_meter($meter_id[$i],$data_meter);
                    }else{
                        $add_meter = $this->meterM->add_meter($data_meter);
                    }
                }
                // printx($data_meter);       
            }
            
        }else{
            $data = [
                'id'            => $id,
                'account_type'  => $account_type,
                'firstname'     => $firstname,
                'lastname'      => $lastname,
                'mobile_number' => $mobile_number,
                'address'       => $address,
                'password'       => $password,
            ];
        }
        $edit = $this->userM->update_user($data);
        if ($edit) {
            $request->session()->flash('success', '<strong>Successfully!</strong> Updated Profile.');
            // session()->push('success', TRUE);
        }
        return redirect(url()->previous());
    }

    public function delete_user(Request $request)
    {
        $id = $request->id;
        $account_type = $request->account_type;
        
        if($account_type == 'Customer'){
            $result = $this->userM->get_user_by_id($id,$account_type);
            $meter_id = $result->meter_id;
            $delete_meter = $this->meterM->delete_meter($meter_id);
        }
        $delete_user = $this->userM->delete_user($id,$account_type);
        
        return redirect()->route('users');
    }

    // Customer
    public function cus_dashboard()
    {
        if(!allowed_user_role(array('Customer'))){
            return redirect(url()->previous());
        }

        $user_id = session('user_id');
        $full_records = $this->meter_readM->get_cus_details_by_status($user_id,'Full');
        $partial_records = $this->meter_readM->get_cus_details_by_status($user_id,'Partial');
        $unpaid_records = $this->meter_readM->get_cus_details_unpaid($user_id);
        return view('pages.customer_dashboard',[
            'full_records'      => $full_records,
            'partial_records'   => $partial_records,
            'unpaid_records'    => $unpaid_records,
        ]);
    }
    public function cus_payment(Request $request)
    {
        if(!allowed_user_role(array('Customer'))){
            return redirect(url()->previous());
        }

        $pay_id = $request->id;
        $records = $this->payM->get_pay_by_id($pay_id);
        return view('pages.customer_payment', [
            'records'   => $records,
        ]);
    }

    public function send_montly_bill()
    {
        $sms_record = $this->smsM->get_used();
        if ($sms_record) {
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', $sms_record->sms_api_key);
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);
            $deviceId = $sms_record->sms_device_id;
            
            $unpaid_users = $this->meter_readM->get_sms_details();
            $current_date = date('Y-m-d');
            // echo $added_month; exit();
            foreach($unpaid_users as $record)
            {
                // $reading_date = Carbon::parse($record->reading_date);
                $reading_date = $record->reading_date;
                $due_date = $record->due_date;
                $due_date_added = $due_date;
                // $deducted_date = $reading_date->subWeek();

                // $due_date = $reading_date->addMonth();

                $due_date_format = date('Y-m-d',strtotime($due_date));

                $deducted_date = date('Y-m-d',strtotime(Carbon::parse($due_date)->subWeek()));
                // echo $current_date.' - '.$deducted_date;exit();

                $status = $record->pay_status;
                $amount_to_pay = $record->reading_amount;
                $payment_amount = $record->payment_amount;
                $partial_amount = $record->trans_payment;
                $lastname = $record->cus_lastname;
                $mobile_number = $record->cus_mobile_number;

                if ($status == 'Partial') {
                    $amount_to_pay -= $partial_amount;
                }

                $amount_to_pay = number_format($amount_to_pay,2);

                if ($mobile_number) {
                    // echo $deducted_date; exit();
                    if($deducted_date == $current_date){
                        // echo $deducted_date; exit();
                        $sendMessageRequest = new SendMessageRequest([
                            'phoneNumber' => '+63'.$mobile_number,
                            'message' => '[Note: KWSS Advisory] Hello '.$lastname.', you need to pay PHP '.$payment_amount.' within this week. The due date of your billing is '.$due_date_format. '. Thank you and have a nice day.',
                            'deviceId' => $deviceId
                        ]);
                        
                        $sendMessages = $messageClient->sendMessages([
                            $sendMessageRequest
                        ]);
                    }

                    $added_month = date('Y-m-d',strtotime( Carbon::parse($due_date_added)->addDay()));

                    // printx($deducted_date);
                    // $added_month = date('Y-m-d',strtotime($due_date->addDay()));
                    
                    if($current_date > $added_month){

                        // echo $added_month; exit();
                        $sendMessageRequest = new SendMessageRequest([
                            'phoneNumber' => '+63'.$mobile_number,
                            'message' => '[Note: KWSS Advisory] Hello '.$lastname.", you didn't pay your last billing. The due date of your billing is ".$due_date_format." and the amount is PHP ".$payment_amount.'. Thank you and have a nice day.',
                            'deviceId' => $deviceId
                         ]);
                        
                        $sendMessages = $messageClient->sendMessages([
                            $sendMessageRequest
                        ]);
                    }
                }
            }
        }else{

        }
        return redirect(url()->previous());
        
    }

    public function upload_users(Request $request)
    {
        $path = $request->file('upload_users')->getRealPath();

        $data = Excel::load($path)->get();
        $arr = array();
        if($data->count()){
            foreach ($data as $key => $value) {
                $account_type = $value->account_type;
                $firstname = $value->firstname;
                $lastname = $value->lastname;
                $mobile_number = $value->mobile_number;
                $address = $value->address;
                $data = [
                    'account_type'  => $account_type,
                    'firstname'     => $firstname,
                    'lastname'      => $lastname,
                    'mobile_number' => $mobile_number,
                    'address'       => $address
                ];
                $user_info = $this->userM->add_user($data);
                if($account_type == 'Customer'){
                    $zone = explode('&&', $value->zone);
                    $meter_serial_no = explode('&&', $value->meter_serial_no);
                    $meter_model = explode('&&', $value->meter_model);
                    $meter_duedate = explode('&&', $value->meter_duedate);
                    $meter_address = explode('&&', $value->meter_address);

                    for ($i=0; $i < count($zone); $i++) {
                        $custype_id = $this->custypeM->get_by_zone($zone[$i]);
                    // printx($this->custypeM->get_by_type($custype_type[$i]));
                        $data_meter = [
                            'cus_id'        => $user_info['user_id'],
                            'custype_id'    => $custype_id,
                            'meter_serial_no'   => $meter_serial_no[$i],
                            'meter_model'   => $meter_model[$i],
                            'meter_duedate' => $meter_duedate[$i],
                            'meter_address' => $meter_address[$i],
                        ];
                        $add_meter = $this->meterM->add_meter($data_meter);
                    }
                    // printx($data_meter);
                }
            }
        }

        if ($user_info) {
            $request->session()->flash('add_successfull', 'Successfully uploaded users!');
        }
        return redirect(url()->previous());
    }

    public function json_get_all_cus_by_zone(Request $request)
    {
        $zone = $request->zone;
        $records = $this->userM->get_all_cus_by_zone($zone);
        if ($records) {
            return response($records,200)
                    ->header('Content-Type', 'application/json');
        }
        return response($records,400)
                    ->header('Content-Type', 'application/json');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }

    public function htmltopdfview(Request $request)
    {
        // $products = Products::all();
        // view()->share('products',$products);
    // if($request->has('download')){

           return PDF::loadView('pdf.billing_pdf')->download('htmltopdfview.pdf');
           // return PDF::loadView('pages.htmltopdfview')->setPaper([0, 0, 185.98, 296.85], 'landscape')->download('htmltopdfview.pdf');
        // redirect()->route('address-request');
        // }
        // return view('pages.htmltopdfview');
    }
    public function test(){
        printx(config('database.connections.mysql'));
        // $sms_record = $this->smsM->get_used();
        // // printx($sms_record);
        // if ($sms_record) {
        //     $config = Configuration::getDefaultConfiguration();
        //     $config->setApiKey('Authorization', $sms_record->sms_api_key);
        //     $apiClient = new ApiClient($config);
        //     $messageClient = new MessageApi($apiClient);
        //     $deviceId = $sms_record->sms_device_id;

        //     $sendMessageRequest = new SendMessageRequest([
        //         'phoneNumber' => '+639652354567',
        //         'message' => 'test',
        //         'deviceId' => $deviceId
        //     ]);
            
        //     $sendMessages = $messageClient->sendMessages([
        //         $sendMessageRequest
        //     ]);
        // }
        // $reading_records = $this->meter_readM->get_all_meter_reading();

        // return view('pdf.meter_reading_pdf',[
        //     'reading_records' =>  $reading_records,
        // ]);
        // Spatie\DbDumper\Databases\MySql::create()
        // ->setDbName('db_system')
        // ->setUserName('root')
        // ->setPassword('')
        // ->dumpToFile('dump.sql');
        // try {
        //     $dump = new IMysqldump\Mysqldump('mysql:host=localhost;dbname=db_system', 'root', '');
        //     $dump->start(public_path().'\uploads\db_backup\db_system_'.date('Ymd-His').'.sql');
        // } catch (\Exception $e) {
        //     echo 'mysqldump-php error: ' . $e->getMessage();
        // }

    }
}
