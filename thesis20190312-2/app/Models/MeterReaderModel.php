<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterReaderModel extends Model
{
    protected $tbl_name = 'tbl_water_reader';
    protected $tbl_id = 'reader_id';
    protected $prefix = 'reader_';

    public function get_all_meter_reader()
    {
        $results = \DB::table($this->tbl_name)->get();
        return $results;
    }
}
