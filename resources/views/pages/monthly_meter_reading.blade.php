@extends('layout.main_layout')

@section('title')
    Meter Reading Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Monthly Reading Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Monthly Reading Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        {{-- <div class="col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_meter_reading">Add Meter Reading</button>
        </div> --}}
        <div class="col-xs-12">
            <h5><strong>Export</strong></h5>
            <a href="{{ route('export-meter-reading') }}" class="btn btn-success btn-sm">All Meter Reading</a>
            <a href="{{ route('pdf_billing_monthly') }}" class="btn btn-primary btn-sm"> Monthly Billing</a>
            <a href="{{ route('pdf_billing_no_num') }}" class="btn btn-warning btn-sm">Monthly Billing No Number</a>
        </div>
    </div>
    <br />
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
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        	<form action="" class="form-inline">
                <div class="form-group">
                    <label for="zone">Zone <span class="text-red">*</span>:</label>
                    <select class="form-control" id="zone" name="zone">
                        <option value="" disabled>-- Select Zone --</option>
                        @for($i=1; $i <= 15; $i++)
                            <option {{$zone == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
        	</form>
        </div>
   </div>
   <div class="row">
   	 	<div class="col-xs-12" style="margin-top:10px;">
   	 		<a href="{{ route('send_sms_meter_reading',['zone'=>$zone]) }}"
                class="btn btn-success btn-sm">
                Send Monthly Reading
            </a>
        </div>
   </div>
   <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive" style="padding-bottom:5em;">
                <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID No.</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Meter Serial No.</th>
                            <th>Meter Address</th>
                            <th>Zone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($customer_records) > 0)
                            @php $count=1 @endphp
                            @foreach($customer_records as $record)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $record->username }}</td>
                                    <td>{{ ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname)) }}</td>
                                    <td>{{ $record->cus_address }}</td>
                                    <td>{{ $record->meter_serial_no }}</td>
                                    <td>{{ $record->meter_address }}</td>
                                    <td>{{ $record->custype_zone }}</td>
                                    <td>
                                    	{{-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_meter_reading">Add Meter Reading</button> --}}

	                                    {{-- <a href="#add_meter_reading" 
	                                        data-toggle="modal" 
	                                        data-cus-id="{{$record->cus_id}}"
	                                        data-cus-name="{{ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname))}}">
	                                         <span class="label label-primary">Add Reading</span>
	                                    </a> --}}
                                        {{-- <a href="{{ route('user-profile',['account_type'=>$record->account_type,'id'=>$record->cus_id]) }}"><span class="label label-primary">View/Update</span></a> --}}
                                        @if(empty($record->reading))
                                        	<a href="{{ route('add-reading-page',['cus_id'=>$record->cus_id,'meter_id'=>$record->meter_id]) }}"><span class="label label-success">Add Reading</span></a>
                                        @else
                                        	
	                                        <a href="{{ route('pdf_billing',['reading_id'=>$record->reading->reading_id]) }}">
	                                            <span class="label label-success">Billing Receipt</span>
	                                        </a>
	                                        <a href="{{ route('edit-meter-reading',['id'=>$record->reading->reading_id]) }}">
	                                            <span class="label label-primary">View/Update</span>
	                                        </a>
                                        @endif
                                        <a href="{{ route('delete-user',['account_type'=>$record->account_type,'id'=>$record->cus_id]) }}"><span class="label label-danger">Delete</span></a>
                                    </td>
                                </tr>
                                @php $count++ @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
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
                },zone: {
                    required: true,
                },meter_id: {
                    required: true,
                },
                reading_amount: {
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

        $('#add_meter_reading').on('show.bs.modal', function(e) {
            let cus_id = $(e.relatedTarget).data('cus-id');

            // $('#cus_info #custype_type').val('');
            $('#add_meter_reading #cus_id').val(cus_id);
            // $('#cus_info #min_cubic_meter').val('');
            // $('#cus_info #cubic_meter_rate').val('');
            // $('#cus_info #min_peso_rate').val('');
            // $('#reading_info #prev_water_consumed').val('');
            // $('#cus_info #duration').val('');
            // $('#cus_info #penalty').val('');
            
            $.ajax({
                method: 'GET',
                url: "{{ route('get-by-cus-id',['cus_id'=>'']) }}"+cus_id,
                success: function(resp){
                    console.log(resp);
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
                        opt.setAttribute('data-total-consumed', resp[i]['meter_total_consumed'] || 0);
                        select.options[select.options.length] = opt;
                        
                    }
                    // $('select#meter_id option[id="default"]').attr('selected');
                }
            });
            $('#meter_info').css('display','block');

		});


        $("#add_meter_reading select#cus_id").select2({theme: "bootstrap"});
        $("#add_meter_reading select#reader_id").select2({theme: "bootstrap"});
        $('#add_meter_reading select#meter_id').change(function(){
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
            $('#reading_info #prev_water_consumed').val(prev_water_consumed);
            $('#cus_info #duration').val(duration);
            $('#cus_info #penalty').val(penalty);
        });
        $('#add_meter_reading select#zone').change(function(){
            $('#customer_info').css('display','block');
            let zone = $(this).val();
            console.log(zone)
            $.ajax({
                method: 'GET',
                url: "{{ route('all-user-by-zone',['zone'=>'']) }}"+zone,
                success: function(resp){
                    console.log(resp);
                    var select = document.getElementById("cus_id");
                    $('select#cus_id option[class="cus-option"]').remove();
                    var opt;
                    if (resp.length > 0) {
                        for (var i = 0; i < resp.length; i++) {
                            opt = new Option(resp[i].cus_firstname+' '+resp[i].cus_lastname,resp[i].cus_id);
                            opt.className = "cus-option";
                            opt.setAttribute('cus-id', resp[i]['cus_id']);
                            select.options[select.options.length] = opt;
                            
                        }    
                    }else{
                        opt = new Option('No Data','No Data');
                        opt.className = "cus-option";
                        opt.setAttribute('disabled', true);
                        select.options[1] = opt;
                    }
                }
            });
        });

        $('#add_meter_reading select#reading_status').change(function(){
            let status = $(this).val();
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
        });
        $('#add_meter_reading select#cus_id').change(function(){
            // var cus_id = $(this).find(':selected').data('cus-id');
            let cus_id = $(this).val();

            $('#cus_info #custype_type').val('');
            $('#cus_info #cus_id').val('');
            $('#cus_info #min_cubic_meter').val('');
            $('#cus_info #cubic_meter_rate').val('');
            $('#cus_info #min_peso_rate').val('');
            $('#reading_info #prev_water_consumed').val('');
            $('#cus_info #duration').val('');
            $('#cus_info #penalty').val('');

            $.ajax({
                method: 'GET',
                url: "{{ route('get-by-cus-id',['cus_id'=>'']) }}"+cus_id,
                success: function(resp){
                    console.log(resp);
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
                        opt.setAttribute('data-total-consumed', resp[i]['meter_total_consumed'] || 0);
                        select.options[select.options.length] = opt;
                        
                    }
                    // $('select#meter_id option[id="default"]').attr('selected');
                }
            });
            $('#meter_info').css('display','block');
        });
        $('#waterconsumed').on('input',function(e){
            var waterconsumed = $(this).val();
            var prev_water_consumed = parseFloat($('#reading_info #prev_water_consumed').val() || 0);
            var min_cubic_meter = parseFloat($('#cus_info #min_cubic_meter').val());
            var cubic_meter_rate = parseFloat($('#cus_info #cubic_meter_rate').val());
            var min_peso_rate = parseFloat($('#cus_info #min_peso_rate').val());
            waterconsumed = waterconsumed ? waterconsumed : 1;

           var amount_pay = (waterconsumed-prev_water_consumed);

            // if (amount_pay > min_cubic_meter) {
            //     amount_pay -= min_cubic_meter;
            // }else{
            //     amount_pay = 1;
            // }
            if (amount_pay  <= min_cubic_meter) {
                amount_pay_total = min_peso_rate;
            }else{
                amount_pay_total = (amount_pay * cubic_meter_rate + min_peso_rate);
            }

            $('#reading_info #reading_amount').val(amount_pay_total.toFixed(2));

        });
    });
</script>
@endsection