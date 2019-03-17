<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CustomerTypeModel;

class CustomerTypeController extends Controller
{

    protected $cusTypeM;

    public function __construct(CustomerTypeModel $cusTypeM)
    {
        $this->cusTypeM = $cusTypeM;
    }

    public function index()
    {
        $results = $this->cusTypeM->get_all_custype();
        return view('pages.customer_type',[
            'records' =>  $results
        ]);
    }

    public function add_custype(Request $request)
    {
        $prefix = 'custype_';
        $type = $request->type;
        $min_cubic_meter = $request->min_cubic_meter;
        $cubic_meter_rate = $request->cubic_meter_rate;
        $min_peso_rate = $request->min_peso_rate;
        $due_date_penalty = $request->due_date_penalty;
        $due_date_duration = $request->due_date_duration ?: $request->duration_radio;
        $zone = $request->zone;
        // printx($due_date_duration);
        $data = [
            $prefix.'type'  => $type,
            $prefix.'min_cubic_meter'   => $min_cubic_meter,
            $prefix.'cubic_meter_rate'  => $cubic_meter_rate,
            $prefix.'min_peso_rate'     => $min_peso_rate,
            $prefix.'due_date_penalty'  => $due_date_penalty,
            $prefix.'due_date_duration'  => $due_date_duration,
            $prefix.'zone'  => $zone
        ];
        $add = $this->cusTypeM->add_custype($data);
            
        return redirect()->route('customer-type');
    }

    public function edit_custype(Request $request)
    {
        $prefix = 'custype_';
        $id = $request->id;
        $type = $request->type;
        $min_cubic_meter = $request->min_cubic_meter;
        $cubic_meter_rate = $request->cubic_meter_rate;
        $min_peso_rate = $request->min_peso_rate;
        $due_date_penalty = $request->due_date_penalty;
        $due_date_duration = $request->due_date_duration ?: $request->duration_radio;
        $zone = $request->zone;
        $data = [
            $prefix.'type'  => $type,
            $prefix.'min_cubic_meter'  => $min_cubic_meter,
            $prefix.'cubic_meter_rate'  => $cubic_meter_rate,   
            $prefix.'min_peso_rate'  => $min_peso_rate,
            $prefix.'due_date_penalty'  => $due_date_penalty,
            $prefix.'due_date_duration'  => $due_date_duration,
            $prefix.'zone'  => $zone
        ];
        $data_serialized = serialize($this->cusTypeM->get_by_id($id));
        $data[$prefix.'previous_data'] = $data_serialized;

        $old = $this->cusTypeM->get_by_id($id);

        // $data_old = [
        //     $prefix.'id'  => $id,
        //     $prefix.'type'  => $type,
        //     $prefix.'min_cubic_meter'  => $min_cubic_meter,
        //     $prefix.'cubic_meter_rate'  => $cubic_meter_rate,   
        //     $prefix.'min_peso_rate'  => $min_peso_rate,
        //     $prefix.'due_date_penalty'  => $due_date_penalty,
        //     $prefix.'due_date_duration'  => $due_date_duration
        // ];

        $add_old = $this->cusTypeM->add_custype_old((array)$old);
        $update = $this->cusTypeM->edit_custype($id,$data);
        return redirect()->route('customer-type');
    }

    public function delete_custype(Request $request)
    {
        $id = $request->id;
        $delete = $this->cusTypeM->delete_custype($id);
        return redirect()->route('customer-type');
    }

    public function get_by_id(Request $request){
        $id = $request->id;
        $result = $this->cusTypeM->get_by_id($id);

        if (!$result) {
            return response($result,400)
            ->header('Content-Type', 'application/json');
        }
        return response(json_encode($result),200)
        ->header('Content-Type', 'application/json');
    }

    public function get_old_by_custype_id(Request $request){
        $custype_id = $request->id;
        $results = $this->cusTypeM->get_old_by_id($custype_id);

        return view('pages.customer_type_old',[
            'results' =>  $results
        ]);
    }
}
