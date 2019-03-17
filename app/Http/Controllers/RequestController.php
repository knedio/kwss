<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RequestModel;
use App\Models\UserModel;
use App\Models\CustomerTypeModel;
use App\Models\MeterModel;

class RequestController extends Controller
{
    
    public function __construct(
        RequestModel $reqM,
        UserModel $userM,
        CustomerTypeModel $custypeM,
        MeterModel $meterM
    	) 
    {
        $this->reqM = new $reqM;
        $this->userM = new $userM;
        $this->custypeM = new $custypeM;
        $this->meterM = new $meterM;
        
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

        $records = $this->reqM->get_all();
        // printx($records);
        return view('pages.request',[
            'records' =>  $records
        ]);
    }

    public function update_request(Request $request)
    {
        $prefix = 'request_';
        $id = $request->id;
        $status = $request->status;

        $data = [
            $prefix.'status'  => $status,
        ];
        $result = $this->reqM->get_by_id($id);
        // printx($result);

        $update = $this->reqM->update_by_id($id,$data);
        if ($update && $status == 'Approved') {
            $result = $this->reqM->get_by_id($id);

            $type = $result->request_type;
            $cus_id = $result->cus_id;
            $data_unserialized = unserialize($result->request_data_serialized);

           	if ($type == 'Name') {
            	if ($data_unserialized) {
           			$data_cus = [
           				'cus_firstname'	=> $data_unserialized['first name'],
           				'cus_lastname'	=> $data_unserialized['last name'],
           			];
           		}
                $edit = $this->userM->update_cus_address($cus_id,$data_cus);
           	}elseif ($type == 'Address') {
                if ($data_unserialized) {
                    $data_cus = [
                        'cus_address'   => $data_unserialized['address'],
                    ];
                }
                $edit = $this->userM->update_cus_address($cus_id,$data_cus);
            }elseif ($type == 'Meter') {
                if ($data_unserialized) {
                    $data_prev_unserialized = unserialize($result->request_prev_data_serialized);
                    $data_cus = [
                        'custype_id'   => $data_unserialized['id'],
                    ];
                }
                $edit = $this->meterM->update_meter($data_prev_unserialized['id'],$data_cus);
            }
    		if ($edit) {
            	$request->session()->flash('success', '<strong>Successfully!</strong> Approved Request.');
    		}else{
            	$request->session()->flash('error', '<strong>Unsuccessful!</strong> Approving Request.');
    		}
        }else if ($update && $status == 'Declined') {
        	$request->session()->flash('success', '<strong>Successfully!</strong> Declined Request.');
        }else{
        	$request->session()->flash('error', '<strong>Action Unsuccessful!</strong>');
        }
        return redirect()->route('request');
    }

    public function add_request(Request $request)
    {
    	$request_type = $request->request_type;
    	$cus_id = $request->cus_id;
    	$check_if_exist = $this->reqM->check_if_exist($cus_id,$request_type);
    	if ($check_if_exist) {
            $request->session()->flash('success', '<strong>Unsuccessful!</strong> You have Pending Request for changing '.$request_type.'. Please wait for the approval.');
        	return redirect(url()->previous());
    	}
    	// printx($check_if_exist);
    	$cus_record = $this->userM->get_user_by_id($cus_id,'Customer');
    	$cus_firstname = $cus_record->firstname;
    	$cus_lastname = $cus_record->lastname;
    	$cus_address = $cus_record->address;
    	// $cus_zone = $cus_record->zone;

    	if ($request_type == 'Name') {
    		$request_firstname = $request->request_firstname;
    		$request_lastname = $request->request_lastname;
    		$data_serialized = array(
    			'first name'	=> $request_firstname,
    			'last name'	=> $request_lastname,
    		);
    		$prev_data_serialized = array(
    			'first name'	=> $cus_firstname,
    			'last name'	=> $cus_lastname,
    		);
    	}elseif ($request_type == 'Address') {
    		$request_address = $request->request_address;
    		// $request_zone = $request->request_zone;
    		$data_serialized = array(
    			'address'	=> $request_address,
    			// 'zone'	=> $request_zone,
    		);
    		$prev_data_serialized = array(
    			'address'	=> $cus_address,
    			// 'zone'	=> $cus_zone,
    		);
    	}elseif($request_type == 'Meter'){
            $meter_id = $request->req_meter_id;
            $custype_id = $request->req_custype_id;
            $cus_record = $this->userM->get_customer_by_id_meter_id($cus_id,$meter_id);
            $custype_record =  $this->custypeM->get_by_id($custype_id);
            $data_serialized = array(
                'meter_serial_no'   => $cus_record->meter_serial_no,
                'custype_type'   => $custype_record->custype_type,
                'id'   => $custype_record->custype_id,
            );
            $prev_data_serialized = array(
                'meter_serial_no'   => $cus_record->meter_serial_no,
                'custype_type'   => $cus_record->custype_type,
                'id'   => $cus_record->meter_id,
            );
        }

        
		$data = array(
			'cus_id'	=> $cus_id,
			'request_type'	=> $request_type,
			'request_prev_data_serialized'	=> serialize($prev_data_serialized),
			'request_data_serialized'	=> serialize($data_serialized),
		);
    	$add = $this->reqM->add($data);
    	if ($add) {
            $request->session()->flash('success', '<strong>Successfully!</strong> Added request.');
    	}else{
            $request->session()->flash('error', '<strong>Unsuccessful!</strong> Adding request.');
    	}
        return redirect(url()->previous());
    }
}
