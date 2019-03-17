@extends('layout.main_layout')

@section('title')
    Add Meter Reading
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Add Meter Reading
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Add Meter Reading
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-6">
            <form action="{{ route('add-meter-reading') }}" id="add_meter_reading_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Meter Reading</h4>
                </div>
                <div class="modal-body">
                    @if(!empty($reader_records))
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label for="water_reader">Water Reader <span class="text-red">*</span>:</label>
                        <select class="form-control" id="reader_id" name="reader_id">
                            <option value="" disabled selected>-- Select Reader --</option>
                            @foreach($reader_records as $reader)
                                <option value="{{ $reader->reader_id }}">
                                    {{ $reader->reader_lastname.', '.$reader->reader_firstname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="customer_info" style="display:none;">
                        <label for="water_reader">Customer <span class="text-red">*</span>:</label>
                        <input type="hidden" name="cus_id" value="{{$result->cus_id}}" />
                        <input type="text" class="form-control" id="cus_name" name="cus_name" value="{{ $result->cus_lastname.', '.$result->cus_firstname }}" readonly>
                    </div>
                    <div id="meter_info">
                        <div class="form-group">
                            <label for="water_reader">Meter Serial No. :</label>
                            <input type="text" class="form-control" id="meter_serial_no" value="{{$result->meter_serial_no}}" readonly>
                            </select>
                        </div>
                    </div>
                    <div id="meter_info">
                        <div class="form-group">
                            <label for="water_reader">Meter Address :</label>
                            <input type="text" class="form-control" id="meter_address" value="{{$result->meter_address}}" readonly>
                            </select>
                        </div>
                    </div>
                    <div id="cus_info">
                        <div class="form-group">
                            <label for="custype_type">Customer Type :</label>
                            <input type="text" class="form-control" id="custype_type" value="{{$result->custype_type}}" readonly>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="">Min. (&#13221;) Rate :</label>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="">Multiplied (&#13221;) :</label>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="">Min. Peso Rate (₱) :</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="text" class="form-control" id="min_cubic_meter" value="{{$result->custype_min_cubic_meter}}" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="text" class="form-control" id="cubic_meter_rate" value="{{$result->custype_cubic_meter_rate}}" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="text" class="form-control" id="min_peso_rate" value="{{$result->custype_min_peso_rate}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="">Due Date Duration :</label>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="">Due Date Penalty :</label>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="">Zone :</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="text" class="form-control" id="duration" name="duration" value="{{$result->custype_due_date_duration}}" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="text" class="form-control" id="penalty" name="penalty" value="{{$result->custype_due_date_penalty}}" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="text" class="form-control" id="zone" name="zone" value="{{$result->custype_zone}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="reading_info">
                        <div class="form-group">
                            <label for="Water Consumed">Total Water Consumed:</label>
                            <input type="text" class="form-control" name="reading_prev_waterconsumed" value="{{$result->meter_total_consumed}}" readonly id="prev_water_consumed">
                        </div>
                        <div class="form-group">
                            <label for="Water Consumed">Water Consumed <span class="text-red">*</span>:</label>
                            <input type="number" step="any" class="form-control" name="reading_waterconsumed" value="" id="waterconsumed">
                        </div>
                        <div class="form-group">
                            <label for="Water Consumed">Amount (₱) <span class="text-red">*</span>:</label>
                            <input type="number" step="any" class="form-control" name="reading_amount" value="" readonly="" id="reading_amount">
                        </div>
                        <div class="form-group">
                            <label for="Water Consumed">Other Payment :</label>
                            <div class="row">
                                <div class="col-xs-12 col-sm-8 col-md-8">
                                    <input type="text" class="form-control" name="reading_other_payment_name" value="" id="reading_other_payment_name" placeholder="Enter Name">
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <input type="number" step="any" class="form-control" name="reading_other_payment" value="" id="reading_other_payment" placeholder="Enter Amount">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="Reading Date">Reading Date <span class="text-red">*</span>:</label>
                        <input type="date" class="form-control" name="reading_date" value="{{date('Y-m-d')}}" id="">
                    </div>
                    <div class="form-group">
                        <label for="Water Consumed">Remarks :</label>
                        <textarea class="form-control" name="reading_remarks" id="" rows="2"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="">
                    <input type="hidden" name="reading_status" id="reading_status" value="Read">
                    <input type="hidden" name="meter_id" id="reading_status" value="{{$result->meter_id}}">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <br>
    <br>
</div>
@endsection

@section('additional_script')
<script>
    $(function(){

        $("#add_meter_reading_form").validate({
            rules: {
                reader_id: {
                    required: true,
                },cus_id: {
                    required: true,
                },reading_amount: {
                    required: true,
                },reading_waterconsumed: {
                    required: true,
                    number: true
                },reading_date: {
                    required: true,
                }
            }
        });
        $('#waterconsumed').on('input',function(e){
            var waterconsumed = $(this).val();
            var prev_water_consumed = parseFloat($('#reading_info #prev_water_consumed').val() || 0);
            var min_cubic_meter = parseFloat($('#cus_info #min_cubic_meter').val());
            var cubic_meter_rate = parseFloat($('#cus_info #cubic_meter_rate').val());
            var min_peso_rate = parseFloat($('#cus_info #min_peso_rate').val());
            waterconsumed = waterconsumed ? waterconsumed : 1;

            var amount_pay = (waterconsumed-prev_water_consumed);
            var amount_pay_temp = (waterconsumed-prev_water_consumed);

            if (amount_pay > min_cubic_meter) {
                amount_pay -= min_cubic_meter;
            }else{
                amount_pay = 1;
            }
            if (amount_pay_temp  <= min_cubic_meter) {
                amount_pay_total = min_peso_rate;
            }else{
                amount_pay_total = (amount_pay * cubic_meter_rate + min_peso_rate);
            }

            $('#reading_info #reading_amount').val(amount_pay_total.toFixed(2));

        });
    })
</script>
@endsection