@extends('layout.main_layout')

@section('title')
    Payment Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Payment Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Payment Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="{{ route('monthly-check') }}"
                class="btn btn-primary">
                Click to Send Monthly Bill
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! Session::get('success') !!}
                </div>
                @php session()->forget('success'); @endphp
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! Session::get('error') !!}
                </div>
                @php session()->forget('error'); @endphp
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 distance" style="padding-top:20px; padding-bottom: 20px;">
            <ul id="navbar_menu" class="nav nav-tabs" role="tablist">
            <li class="active"><a data-toggle="tab" href="#unpaid">Unpaid</a></li>
            <li><a data-toggle="tab" href="#partial">Partial</a></li>
            <li><a data-toggle="tab" href="#full">Full</a></li>
            </ul>
        </div>
        <div class="tab-content" >
            <div class="tab-pane active" id="unpaid">
                <div class="col-xs-12">
                    <a href="{{ route('export-payment-by-status', ['status'=> 'Unpaid']) }}" class="btn btn-sm btn-success pull-right"> Export Unpaid PDF</a>
                </div>
                <div class="col-lg-12">
                    <br />
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer name</th>
                                    <th>Meter serial number</th>
                                    <th>Zone</th>
                                    <th>Reading date</th>
                                    <th>Total water consumed</th>
                                    <th>Amount to be paid</th>
                                    <th>Due date</th>
                                    <th>Meter address</th>
                                    <th>Read by</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($unpaid_records))
                                    @php $count=1; @endphp
                                    @foreach($unpaid_records as $record)
                                    @php 
                                    // printx($record);
                                        $penalty = $record->custype_due_date_penalty;
                                        $duration = $record->custype_due_date_duration;
                                        $payment_amount = $record->reading_amount;
                                        $other_payment = $record->reading_other_payment;
                                        // $total_amount = $payment_amount + $other_payment;
                                        $reading_date = $record->reading_date;
                                        $payment_amount = check_if_penalty($duration,$reading_date,$payment_amount,$penalty,$other_payment);
                                    @endphp
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname)) }}</td>
                                            <td>{{ $record->meter_serial_no }}</td>
                                            <td>{{ $record->cus_zone }}</td>
                                            <td>{{ date('Y-m-d',strtotime($reading_date)) }}</td>
                                            <td>{{ number_format($record->reading_waterconsumed+$record->reading_prev_waterconsumed,2) }}</td>
                                            <td class="text-right">{{ $record->payment_amount }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
                                            <td>{{ $record->meter_address }}</td>
                                            <td>{{ ucwords(strtolower($record->reader_lastname.', '.$record->reader_firstname)) }}</td>
                                            <td>
                                                <a href="#add_payment" 
                                                    data-toggle="modal" 
                                                    data-reading-id="{{$record->reading_id}}"
                                                    data-cus-id="{{$record->cus_id}}">
                                                    <span class="label label-success">Pay</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $count++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="partial">
                <div class="col-xs-12">
                    <a href="{{ route('export-payment-by-status', ['status'=> 'Partial']) }}" class="btn btn-sm btn-success pull-right"> Export Partial PDF</a>
                </div>
                <div class="col-lg-12">
                    <br />
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables2' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer name</th>
                                    <th>Meter serial number</th>
                                    <th>Zone</th>
                                    <th>Reading date</th>
                                    <th>Last paid date</th>
                                    <th>Total water consumed</th>
                                    <th>Amount to be paid</th>
                                    <th>Due date</th>
                                    <th>Meter address</th>
                                    <th>Received by</th>
                                    <th>Read by</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($partial_records))
                                    @php $count=1; @endphp
                                    @foreach($partial_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname)) }}</td>
                                            <td>{{ $record->meter_serial_no }}</td>
                                            <td>{{ $record->cus_zone }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->trans_date)) }}</td>
                                            <td>{{ number_format($record->reading_waterconsumed+$record->reading_prev_waterconsumed,2) }}</td>
                                            <td class="text-right">{{ $record->payment_amount }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
                                            <td>{{ $record->meter_address }}</td>
                                            <td>{{ ucwords(strtolower($record->emp_lastname.', '.$record->emp_firstname)) }}</td>
                                            <td>{{ ucwords(strtolower($record->reader_lastname.', '.$record->reader_firstname)) }}</td>
                                            <td>
                                                <a href="{{ route('pdf-receipt', ['reading_id'=>$record->reading_id]) }}">
                                                    <span class="label label-success">Billing Receipt</span>
                                                </a>
                                                <a href="#add_payment" 
                                                    data-toggle="modal" 
                                                    data-reading-id="{{$record->reading_id}}"
                                                    data-cus-id="{{$record->cus_id}}">
                                                    <span class="label label-success">Pay</span>
                                                </a>
                                                <a href="{{ route('edit-payment', ['id'=>$record->pay_id]) }}">
                                                    <span class="label label-primary">View/Update</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $count++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="full">
                <div class="col-xs-12">
                    <a href="{{ route('export-payment-by-status', ['status'=> 'Full']) }}" class="btn btn-sm btn-success pull-right"> Export Full PDF</a>
                </div>
                <div class="col-lg-12">
                    <br />
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer name</th>
                                    <th>Meter serial number</th>
                                    <th>Zone</th>
                                    <th>Reading date</th>
                                    <th>Last paid date</th>
                                    <th>Total water consumed</th>
                                    <th>Total paid</th>
                                    <th>Due date</th>
                                    <th>Meter address</th>
                                    <th>Received by</th>
                                    <th>Read by</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($full_records))
                                    @php $count=1; @endphp
                                    @foreach($full_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname)) }}</td>
                                            <td>{{ $record->meter_serial_no }}</td>
                                            <td>{{ $record->cus_zone }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->trans_date)) }}</td>
                                            <td>{{ number_format($record->reading_waterconsumed+$record->reading_prev_waterconsumed,2) }}</td>
                                            <td class="text-right">{{ $record->trans_payment }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
                                            <td>{{ $record->meter_address }}</td>
                                            <td>{{ ucwords(strtolower($record->emp_lastname.', '.$record->emp_firstname)) }}</td>
                                            <td>{{ ucwords(strtolower($record->reader_lastname.', '.$record->reader_firstname)) }}</td>
                                            <td>
                                                <a href="{{ route('pdf-receipt', ['reading_id'=>$record->reading_id]) }}">
                                                    <span class="label label-success">Billing Receipt</span>
                                                </a>
                                                <a href="{{ route('edit-payment', ['id'=>$record->pay_id]) }}">
                                                    <span class="label label-primary">View/Update</span>
                                                </a>
                                                <a href="">
                                                    <span class="label label-danger">Delete</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $count++; @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.modals')
@endsection

@section('additional_script')
<script>
	function ajax_call(reading_id){
        $.ajax({
        	method: 'GET',
            url: "{{ route('json-get-pay-reading-id',['reading_id'=>'']) }}"+'/'+reading_id,
            success: function(resp){
            	console.log(resp);
            	var firstname = resp.cus_firstname;
            	var lastname = resp.cus_lastname;
            	var reading_amount = parseFloat(resp.reading_amount);
            	var other_payment = parseFloat(resp.reading_other_payment);
            	var pay_id = resp.pay_id;
            	var cus_id = resp.cus_id;
            	var water_consumed = parseFloat(resp.reading_waterconsumed);
            	var reading_id = resp.reading_id;
            	var partial_amount = parseFloat(resp.trans_payment||0);
            	var trans_id = resp.trans_id;
                var payment_amount = parseFloat(resp.payment_amount);
            	var penalty_amount = parseFloat(resp.penalty_amount);
            	// var total_amount = payment_amount + other_payment;

	            $('#add_payment input[name="customer_name"]').val(lastname+', '+firstname);
	            $('#add_payment input[name="water_consumed"]').val(water_consumed);
	            $('#add_payment input[name="reading_waterconsumed"]').val(water_consumed.toFixed(2));
	            $('#add_payment input[name="pay_id"]').val(pay_id);
	            $('#add_payment input[name="cus_id"]').val(cus_id);
	            $('#add_payment input[name="trans_id"]').val(trans_id);
	            $('#add_payment input[name="reading_id"]').val(reading_id);
	            $('#add_payment input[name="reading_amount"]').val(reading_amount.toFixed(2));
	            $('#add_payment input[name="other_payment"]').val(other_payment.toFixed(2));
	            $('#add_payment input[name="partial_payment"]').val(partial_amount.toFixed(2));
                $('#add_payment input[name="amount_pay"]').val(payment_amount.toFixed(2));
	            $('#add_payment input[name="penalty_amount"]').val(penalty_amount.toFixed(2));
            },
            error: function(err){
            	console.log(err);
            }
        });
	}

    function ajax_call_arrears_section(pay_reading_id,cus_id){
        $.ajax({
            method: 'GET',
            url: "{{ route('json-get-unfinished',['cus_id'=>'']) }}"+'/'+cus_id,
            success: function(resp){
                var checkbox = '';
                for (var i = 0; i <  resp.length; i++) {
                    var info = resp[i];
                    var reading_id = info.reading_id;
                    if (pay_reading_id == reading_id) { continue; }
                    var payment_amount = info.payment_amount || 0;
                    var reading_waterconsumed = info.reading_waterconsumed || 0;
                    var trans_payment = info.trans_payment || 0;
                    var due_date = info.due_date;
                    var pay_btn = ' <a href="#" data-reading-id="'+reading_id+'" data-cus-id="'+cus_id+'" id="pay_btn"><span class="label label-success"> Click to only Pay this! </span></a>';
                    checkbox += '<label>';
                    checkbox += '<input type="checkbox" name="payment_checkbox[]" id="payment_checkbox" value="'+reading_id+'" data-amount="'+payment_amount+'" data-water-consumed="'+reading_waterconsumed+'" data-partial-amount="'+trans_payment+'">Due Date: '+due_date+' Amount: '+parseFloat(payment_amount).toFixed(2);
                    checkbox += '</label>'+pay_btn+'<br /><br />';
                    // console.log(resp[i]);
                }
                $('#unfinished_payment_info .checkbox').html(checkbox)
            }
        });
    }
	function check_amount_payment(){
		$('input[name=payment]').keyup(function(){
	        var amount_pay = parseFloat($('#add_payment input[name="amount_pay"]').val());
	        var payment = parseFloat($(this).val());
	        console.log(payment);
	        console.log(amount_pay);
	        if (payment >= amount_pay) {
	        	$('#add_payment button[type=submit]').removeAttr("disabled");         
	        }else {
	        	$('#add_payment button[type=submit]').attr("disabled", "disabled");
	        }
		});
	}
    $(function(){
    	$( '#unfinished_payment_info' ).on( "click",'a#pay_btn', function(e) {
            let pay_reading_id = $(this).attr('data-reading-id');
            let cus_id = $(this).attr('data-cus-id');
            ajax_call(pay_reading_id);
            ajax_call_arrears_section(pay_reading_id,cus_id);
		});
    	$( '#unfinished_payment_info' ).on( "click",'#payment_checkbox', function(e) {
    		var countChecked = $('input[name="payment_checkbox[]"]').filter(':checked').length;
    		console.log(countChecked);
    		if (countChecked > 0) {
	        	$('#add_payment button[type=submit]').attr("disabled", "disabled");
    			check_amount_payment();
    		}else{
	        	$('#add_payment button[type=submit]').removeAttr("disabled");         
    			$('#add_payment input[name=payment]').off('keyup');
    		}
    		var total_amount_pay,total_partial_amount,total_water_consumed,total_arrears;
            var payment_amount = parseFloat($(this).attr('data-amount'));
            var water_consumed = parseFloat($(this).attr('data-water-consumed'));
            var partial_amount = parseFloat($(this).attr('data-partial-amount'));
            var amount_pay = parseFloat($('#add_payment input[name="amount_pay"]').val());
	        var arrears = parseFloat($('#add_payment input[name="arrears"]').val());
	        var partial_payment = parseFloat($('#add_payment input[name="partial_payment"]').val());
	        var reading_waterconsumed = parseFloat($('#add_payment input[name="water_consumed"]').val());
    		if ($(this).is(":checked")) {
                total_arrears = arrears+payment_amount;
		        total_amount_pay = amount_pay+payment_amount;
		        total_partial_amount = partial_payment+partial_amount;
		        total_water_consumed = reading_waterconsumed+water_consumed;
    		}else{
                total_arrears = arrears-payment_amount;
		        total_amount_pay = amount_pay-payment_amount;
		        total_partial_amount = partial_payment-partial_amount;
		        total_water_consumed = reading_waterconsumed-water_consumed;
    		}
            $('#add_payment input[name="arrears"]').val(total_arrears.toFixed(2));
            $('#add_payment input[name="amount_pay"]').val(total_amount_pay.toFixed(2));
            $('#add_payment input[name="partial_payment"]').val(total_partial_amount.toFixed(2));
            $('#add_payment input[name="reading_waterconsumed"]').val(total_water_consumed.toFixed(2));
            $('#add_payment input[name="water_consumed"]').val(total_water_consumed.toFixed(2));
		});
        $('#add_payment').on('show.bs.modal', function(e) {
            var cus_id = $(e.relatedTarget).data('cus-id');
            var pay_reading_id = $(e.relatedTarget).data('reading-id');

            ajax_call(pay_reading_id);
            ajax_call_arrears_section(pay_reading_id,cus_id);
        });
    });
</script>
@endsection