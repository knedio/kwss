<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserModel;
use App\Models\CustomerTypeModel;
use App\Models\MeterModel;
use App\Models\PaymentModel;
use App\Models\MeterReadingModel;
use App\Models\RequestModel;

use Ifsnop\Mysqldump as IMysqldump;

class DatabaseBackupController extends Controller
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

    public function index()
    {
        $file_path ='uploads/DB_BackUp';
        // printx($file_path);
        if (!file_exists($file_path)) {
            mkdir ($file_path, 0777, true);
        }
        if(!allowed_user_role(array('Admin','Employee'))){
            return redirect(url()->previous());
        }

		$files = array_diff(scandir($file_path), array('.', '..'));
		// printx($files);
    	return view('pages.database_backup',[
    		'files'=>$files,
    		'path'=>$file_path,
    	]);
    }

    public function add_backUp(Request $request)
    {
        $file_path ='uploads/DB_BackUp';
        if (!file_exists($file_path)) {
            mkdir ($file_path, 0777, true);
        }
        try {
            $database_record = config('database.connections.mysql');
            $dump = new IMysqldump\Mysqldump('mysql:host='.$database_record['host'].';dbname='.$database_record['database'], $database_record['username'], $database_record['password']);
            
            $dump->start($file_path.'/db_system_'.date('Ymd-His').'.sql');
            if ($dump) {
                $request->session()->flash('success', '<strong>Successfully!</strong> Added Backup.');
            }
        } catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
        }
        return redirect()->route('database-backup');
    }

    public function delete_backup(Request $request)
    {
    	$filename = $request->filename;
        $file_path ='uploads/DB_BackUp';
    	if (file_exists($file_path.'/'.$filename)) {
    		unlink($file_path.'/' .$filename);
            $request->session()->flash('success', '<strong>Successfully!</strong> Deleted Backup.');
    	}else{
            $request->session()->flash('error', '<strong>File not exist!</strong>');
    	}
        return redirect()->route('database-backup');
    }
}
