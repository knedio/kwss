@extends('layout.main_layout')

@section('title')
    Edit Meter Reading Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Edit Meter Reading Page
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Edit Meter Reading Page
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! Session::get('success') !!}
                </div>
                @php session()->forget('success'); @endphp
            @endif
        </div>
        <div class="col-xs-6">
            <form action="{{ route('update-meter-reading') }}" method="post">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label for="water_reader">Water Reader</label>
                    <select class="form-control" id="reader_id" name="reader_id">
                        <option value="" disabled>-- Select Reader --</option>
                        @foreach($reader_records as $reader)
                            <option {{($reader->reader_id==$reading_records->reader_id)? 'selected' : ''}} value="{{ $reader->reader_id }}">
                                {{ ucwords(strtolower($reader->reader_lastname.', '.$reader->reader_firstname)) }} 
                            </option>
                        @endforeach
                    </select>
                </div>
                @php
                // printx($reading_records);
                @endphp
                <div class="form-group">
                    <label for="water_reader">Customer <span class="text-red">*</span>:</label>
                    <input type="text" class="form-control" required id="customer" value="{{ ucwords(strtolower($reading_records->cus_lastname.', '.$reading_records->cus_firstname)) }}" readonly>
                    {{-- <select class="form-control" id="customer" name="customer">
                        <option value="" disabled>-- Select Reader --</option>
                        @foreach($cus_records as $cus)
                            <option 
                                {{($cus->cus_id==$reading_records->cus_id)? 'selected' : ''}} 
                                data-cus-id="{{ $cus->cus_id }}" 
                                value="{{ $cus->cus_id }}">
                                {{ $cus->cus_lastname.', '.$cus->cus_firstname  }} 
                            </option>
                        @endforeach
                    </select> --}}
                </div>
                <div id="meter_info" >
                    <div class="form-group">
                        <label for="water_reader">Customer Meter <span class="text-red">*</span>:</label>
                        <select class="form-control" id="meter_id" name="meter_id">
                            <option value="" id="default" selected>-- Select Meter --</option>
                            @foreach($meter_records as $meter)
                                <option 
                                    class="meter-option" 
                                    {{ ($meter->meter_id==$reading_records->meter_id)? 'selected' : '' }} 
                                    data-custype="{{ $meter->custype_type }}" 
                                    data-cus-id="{{ $meter->cus_id }}" 
                                    data-min-cubic-meter="{{ $meter->custype_min_cubic_meter }}" 
                                    data-cubic-meter-rate="{{ $meter->custype_cubic_meter_rate }}" 
                                    data-min-peso-rate="{{ $meter->custype_min_peso_rate }}" 
                                    data-duration="{{ $meter->custype_due_date_duration }}" 
                                    data-penalty="{{ $meter->custype_due_date_penalty }}" 
                                    data-total-consumed="{{ $meter->meter_total_consumed }}" 
                                    value="{{ $meter->meter_id }}">
                                    {{ $meter->meter_serial_no  }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="cus_info">
                    <input type="hidden" name="cus_id" id="cus_id" value="{{ $reading_records->cus_id  }}">
                    <div class="form-group">
                        <label for="custype_type">Customer Type :</label>
                        <input type="text" class="form-control" required id="custype_type" value="{{ $reading_records->custype_type }}" readonly>
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
                                <input type="text" class="form-control" id="min_cubic_meter" value="{{ $reading_records->custype_min_cubic_meter }}" readonly>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <input type="text" class="form-control" id="cubic_meter_rate" value="{{ $reading_records->custype_cubic_meter_rate }}" readonly>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <input type="text" class="form-control" id="min_peso_rate" value="{{ $reading_records->custype_min_peso_rate }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <label for="">Due Date Duration :</label>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <label for="">Due Date Penalty :</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <input type="text" class="form-control" id="duration" value="{{ $reading_records->custype_due_date_duration }}" readonly>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <input type="text" class="form-control" id="penalty" value="{{ $reading_records->custype_due_date_penalty }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Water Consumed">Total Water Consumed (&#13221;) :</label>
                        <input type="text" class="form-control" name="reading_prev_waterconsumed" value="{{ $reading_records->reading_prev_waterconsumed }}" readonly id="prev_water_consumed" placeholder="200">
                    </div>
                </div>
                <div id="reading_info" >
                    <div class="form-group">
                        <label for="Water Consumed">Water Consumed (&#13221;) <span class="text-red">*</span>:</label>
                        <input type="number" step="any" class="form-control" name="reading_waterconsumed" value="{{ $reading_records->reading_waterconsumed }}" id="waterconsumed" required placeholder="200">
                    </div>
                    <div class="form-group">
                        <label for="Water Consumed">Amount (₱) <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="reading_amount" value="{{ $reading_records->reading_amount }}" readonly="" required id="reading_amount" placeholder="200">
                    </div>
                    <div class="form-group">
                        <label for="Water Consumed">Other Payment (₱) :</label>
                        <input type="number" step="any" class="form-control" name="reading_other_payment" value="{{ $reading_records->reading_other_payment }}" id="reading_other_payment" placeholder="200">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Status">Status <span class="text-red">*</span>:</label>
                    <select class="form-control" id="reading_status" name="reading_status">
                        <option value="" disabled>-- Select Status --</option>
                        <option {{($reading_records->reading_status=='Read')? 'selected' : ''}} value="Read">
                            Read
                        </option>
                        <option {{($reading_records->reading_status=='Unread')? 'selected' : ''}} value="Unread">
                            Unread
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Reading Date">Reading Date <span class="text-red">*</span>:</label>
                    <input type="date" class="form-control" name="reading_date" value="{{ date('Y-m-d',strtotime($reading_records->reading_date)) }}" id="">
                </div>
                <div class="form-group">
                    <label for="Due Date">Due Date <span class="text-red">*</span>:</label>
                    <input type="date" class="form-control" disabled name="due_date" value="{{ date('Y-m-d',strtotime($reading_records->due_date)) }}" id="">
                </div>
                <div class="form-group">
                    <label for="Water Consumed">Remarks :</label>
                    <textarea class="form-control" name="reading_remarks" value="{{ $reading_records->reading_remarks }}" id="" rows="3"></textarea>
                </div>
                <hr />
                <a href="{{route('meter-reading')}}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
                <input type="hidden" name="reading_id" value="{{$reading_records->reading_id}}">
            </form>
        </div>
    </div>
    <br>
    <br>
</div>
@include('modals.modals')
@endsection

@section('additional_script')
<script>
    $(function(){
        let status = $('select#reading_status').val();
        if (status == 'Read') {
            $('#reading_info').css('display','block');
            $('#reading_amount').removeAttr('disabled');
            $('#waterconsumed').removeAttr('disabled');
            $('#reading_other_payment').removeAttr('disabled');
            $('input[name=send_sms]').removeAttr('disabled');
        }else{
            $('#reading_info').css('display','none');
            $('#reading_amount').attr('disabled','disabled');
            $('#waterconsumed').attr('disabled','disabled');
            $('#reading_other_payment').attr('disabled','disabled');
            $('input[name=send_sms]').attr('disabled','disabled');
        }
        $("#edit_meter_reading_form").validate({
            rules: {
                    reader_id: {
                        required: true,
                    },customer: {
                        required: true,
                    },meter_id: {
                        required: true,
                    },reading_waterconsumed: {
                        required: true,
                        number: true
                    },reading_date: {
                        required: true,
                    },reading_status: {
                        required: true,
                    }
                }
        });
        $('select#reading_status').change(function(){
            let change_status = $(this).val();
            if (change_status == 'Read') {
                $('#reading_info').css('display','block');
                $('#reading_amount').removeAttr('disabled');
                $('#waterconsumed').removeAttr('disabled');
                $('#reading_other_payment').removeAttr('disabled');
                $('input[name=send_sms]').removeAttr('disabled');
            }else{
                $('#reading_info').css('display','none');
                $('#reading_amount').attr('disabled','disabled');
                $('#waterconsumed').attr('disabled','disabled');
                $('#reading_other_payment').attr('disabled','disabled');
                $('input[name=send_sms]').attr('disabled','disabled');
            }
        });
        $('select#meter_id').change(function(){
            $('#reading_info').css('display','block');
            var custype = $(this).find(':selected').data('custype');
            var cus_id = $(this).find(':selected').data('cus-id');
            var min_cubic_meter = $(this).find(':selected').data('min-cubic-meter');
            var cubic_meter_rate = $(this).find(':selected').data('cubic-meter-rate');
            var min_peso_rate = $(this).find(':selected').data('min-peso-rate');
            var penalty = $(this).find(':selected').data('penalty');
            var duration = $(this).find(':selected').data('duration');
            var prev_water_consumed = $(this).find(':selected').data('total-consumed');
            $('#cus_info #custype_type').val(custype);
            $('#cus_info #cus_id').val(cus_id);
            $('#cus_info #min_cubic_meter').val(min_cubic_meter);
            $('#cus_info #cubic_meter_rate').val(cubic_meter_rate);
            $('#cus_info #min_peso_rate').val(min_peso_rate);
            $('#cus_info #prev_water_consumed').val(prev_water_consumed);
            $('#cus_info #duration').val(duration);
            $('#cus_info #penalty').val(penalty);
        });
        $('select#customer').change(function(){
            var cus_id = $(this).find(':selected').data('cus-id');
            $('#cus_info #custype_type').val('');
            $('#cus_info #cus_id').val('');
            $('#cus_info #min_cubic_meter').val('');
            $('#cus_info #cubic_meter_rate').val('');
            $('#cus_info #min_peso_rate').val('');
            $('#cus_info #prev_water_consumed').val('');
            $('#cus_info #duration').val('');
            $('#cus_info #penalty').val('');

            $.ajax({
                method: 'GET',
                url: "{{ route('get-by-cus-id',['cus_id'=>'']) }}"+cus_id,
                success: function(resp){
                    console.log('test');
                    // var select = $("#meter_id_test")[0];
                    var select = document.getElementById("meter_id");
                    $('select#meter_id option[class="meter-option"]').remove();
                    var opt;
                    for (var i = 0; i < resp.length; i++) {
                        opt = new Option(resp[i].meter_serial_no,resp[i].meter_id);
                        opt.className = "meter-option";
                        opt.setAttribute('data-custype', resp[i]['custype_type']);
                        opt.setAttribute('data-min-cubic-meter', resp[i]['custype_min_cubic_meter']);
                        opt.setAttribute('data-cubic-meter-rate', resp[i]['custype_cubic_meter_rate']);
                        opt.setAttribute('data-min-peso-rate', resp[i]['custype_min_peso_rate']);
                        opt.setAttribute('data-duration', resp[i]['custype_due_date_duration']);
                        opt.setAttribute('data-penalty', resp[i]['custype_due_date_penalty']);
                        opt.setAttribute('data-total-consumed', resp[i]['meter_total_consumed']);
                        select.options[select.options.length] = opt;
                        
                    }
                    // $('select#meter_id option[id="default"]').attr('selected');
                }
            });
            $('#meter_info').css('display','block');
        });
        $('#waterconsumed').on('input',function(e){
            console.log('test');
            var waterconsumed = $(this).val();
            var prev_water_consumed = parseFloat($('#prev_water_consumed').val() || 0);
            var min_cubic_meter = parseFloat($('#min_cubic_meter').val());
            var cubic_meter_rate = parseFloat($('#cubic_meter_rate').val());
            var min_peso_rate = parseFloat($('#min_peso_rate').val());
            waterconsumed = waterconsumed ? waterconsumed : 1;

            var amount_pay = Math.abs(waterconsumed-prev_water_consumed);
            if (amount_pay > min_cubic_meter) {
                amount_pay -= min_cubic_meter;
            }else{
                amount_pay = 1;
            }
            amount_pay_total = (amount_pay * cubic_meter_rate + min_peso_rate);
            $('#reading_amount').val(amount_pay_total);

        });

    });
</script>
@endsection