<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use App\Models\CustomerTypeModel;
use App\Models\MeterModel;
use App\Models\PaymentModel;
use App\Models\MeterReadingModel;
use App\Models\RequestModel;

use PDF;

class DashboardController extends Controller
{
    protected $userM;
    protected $custypeM;
    protected $meterM;
    protected $payM;
    protected $meter_readM;
    protected $reqM;

    public function __construct(
        UserModel $user, 
        CustomerTypeModel $custypeM, 
        MeterModel $meterM,
        PaymentModel $payM,
        MeterReadingModel $meter_readM,
        RequestModel $reqM) 
    {
        $this->userM = new $user;
        $this->custypeM = new $custypeM;
        $this->meterM = new $meterM;
        $this->payM = new $payM;
        $this->meter_readM = new $meter_readM;
        $this->reqM = new $reqM;
        
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

    public function index(Request $request)
    {
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }
        // printx($request->cus_month ?);
        $m = $request->m ?: date('m');
        $y = $request->y ?: date('Y');
        $dm = $request->dm ?: date('m');
        $dy = $request->dy ?: date('Y');
        // printx($y);
        $exceed_due_date_records = $this->meter_readM->get_cus_exceed_due_date($dm,$dy);
        $years = range(date('Y'), 1980);
        // printx($years);
        // printx($cus_exceed_due_date);
        $months = array('January','February','March','April','May','June','July ','August','September','October','November','December',);
        $cus_records = $this->userM->get_cus_by_month_created($m,$y);
        $custype_records = $this->custypeM->get_all_custype();
        $count_custype = $this->custypeM->get_count();
        $count_admin = $this->userM->get_count_admin();
        $count_employee = $this->userM->get_count_employee();
        $count_customer = $this->userM->get_count_customer();
        $count_reader = $this->userM->get_count_reader();
        $count_pending_request = $this->reqM->get_count_pending();

        return view('pages.dashboard', [
            'years'     => $years,
            'months'    => $months,
            'm'       => $m,
            'y'       => $y,
            'dm'       => $dm,
            'dy'       => $dy,
            'cus_records'       => $cus_records,
            'count_custype'     => $count_custype,
            'count_admin'       => $count_admin,
            'count_employee'    => $count_employee,
            'count_customer'    => $count_customer,
            'count_reader'      => $count_reader,
            'count_pending_request'     => $count_pending_request,
            'custype_records'           => $custype_records,
            'exceed_due_date_records'   => $exceed_due_date_records,
        ]);
    }

    public function pdf_disconnected_list(Request $request){

        $dm = $request->dm;
        $dy = $request->dy;
        // printx($y);
        $exceed_due_date_records = $this->meter_readM->get_cus_exceed_due_date($dm,$dy);

        if ($exceed_due_date_records) {
           return PDF::loadView('pdf.pdf_disconnected_list',array('exceed_due_date_records'=>$exceed_due_date_records))->download('disconnected_list'.$dm.$dy.'.pdf');
        }else{
            return redirect(url()->previous());
        }
    }

    public function pdf_disconnected_list_all(Request $request){

        $exceed_due_date_records = $this->meter_readM->get_cus_exceed_due_date_all();

        if ($exceed_due_date_records) {
           return PDF::loadView('pdf.pdf_disconnected_list',array('exceed_due_date_records'=>$exceed_due_date_records))->download('disconnected_list'.date('Ymd-His').'.pdf');
        }else{
            return redirect(url()->previous());
        }
    }
}
