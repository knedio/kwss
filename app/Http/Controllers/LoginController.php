<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginModel;

class LoginController extends Controller
{   
    protected $loginM;
    protected $meterM;
    protected $meter_readM;
    protected $meter_readerM;
    protected $pay_detailM;
    protected $payM;
    
    public function __construct(LoginModel $loginM)
    {
        $this->loginM = $loginM;
        $this->middleware(function ($request, $next){
                if(session('account_type') == 'Customer'){
                    return redirect()->route('customer-home');
                }elseif(session('user_logged') == 'Employee' || session('user_logged') == 'Admin'){
                    return redirect()->route('dashboard');
                }
            $response = $next($request);
            return $response
            ->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        });
    }
    
    public function index() {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $user = $this->loginM->check_user($request->username,$request->password);
        if($user) {
            $account_type = $user->account_type;
            if($account_type == 'Customer'){
                $prefix = 'cus_';
                $route = 'customer-home';
            }else{
                $prefix = 'emp_';
                $route = 'dashboard';
            }
            $id = $prefix.'id';
            $firstname = $prefix.'firstname';
            $lastname = $prefix.'lastname';
            session([
                'user_logged' => TRUE,
                'username' => $user->username,
                'account_type' => $account_type,
                'user_id' => $user->$id,
                'firstname' => $user->$firstname,
                'lastname' => $user->$lastname,
            ]);
            return redirect()->route($route);
        }else {
            session()->push('error_message', TRUE);
            return redirect()->route('login');
        }
    }
}
