@extends('layout.main_layout')

@section('title')
    Payment Details Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1>Payment Details</h1>
            </div>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Payment Details
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12 total-payment">
            <strong>Amount To Pay: </strong><span id="amount_pay">0.00</span> <br />
            <strong>Total Partial Paid: </strong><span id="partial_payment">0.00</span>  <br />
            <strong>Total Amount: </strong><span id="total_amount">0.00</span> 
        </div>
    </div>
    @php $count=1;$partial_payment=0;$payment_amount=0; @endphp

    <form action="" method="post">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_waterconsumed">Meter Serial No: </label>
                    <input type="text" class="form-control" name="meter_serial_no" disabled value="{{ $records{0}->meter_serial_no }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_waterconsumed">Zone: </label>
                    <input type="text" class="form-control" name="cus_zone" disabled value="{{ $records{0}->cus_zone }}" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="cus_id">Customer: </label>
                    <input type="text" class="form-control" name="cus_id" disabled value="{{ ucwords(strtolower($records{0}->cus_firstname.' '.$records{0}->cus_lastname)) }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="emp_id">Received By: </label>
                    <input type="text" class="form-control" name="emp_id" disabled value="{{ ucwords(strtolower($records{0}->emp_firstname.' '.$records{0}->emp_lastname)) }}" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reader_id">Read By: </label>
                    <input type="text" class="form-control" name="reader_id" disabled value="{{ ucwords(strtolower($records{0}->reader_firstname.' '.$records{0}->reader_lastname)) }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="pay_status">Payment Status: </label>
                    <input type="text" class="form-control" name="pay_status" disabled value="{{ $records{0}->pay_status }}" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_date">Read Date: </label>
                    <input type="text" class="form-control" name="reading_date" disabled value="{{ date('Y-m-d',strtotime($records{0}->reading_date)) }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="">Due Date: </label>
                    <input type="text" class="form-control" name="" disabled value="{{ $records{0}->due_date }}" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_waterconsumed">Water Consumed: </label>
                    <input type="text" class="form-control" name="reading_waterconsumed" disabled value="{{ number_format($records{0}->reading_waterconsumed,2) }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_waterconsumed">Reading Payment: </label>
                    <input type="text" class="form-control" name="reading_waterconsumed" disabled value="{{ number_format($records{0}->reading_amount,2) }}" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_waterconsumed">Other Payment: </label>
                    <input type="text" class="form-control" name="reading_waterconsumed" disabled value="{{ number_format($records{0}->reading_other_payment,2) }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="">Due Date Penalty: </label>
                    <input type="text" class="form-control" name="due_date_penalty_amount" disabled value="" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="reading_waterconsumed">Total Payment: </label>
                    <input type="text" class="form-control" name="total_payment_amount" disabled value="{{ number_format($records{0}->reading_other_payment,2) }}" id="" >
                </div>
            </div>
        </div>
    </form> 
    @foreach($records as $record)
    <form action="" method="post">
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        <div class="row">
            <div class="col-xs-12">
                <h4><strong>Payment Details {{$count}}</strong></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="trans_payment">Paid Amount: </label>
                    <input type="number" step="0.01" disabled class="form-control" name="trans_payment" value="{{ $record->trans_payment }}" id="" >
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="trans_arrears_amount">Arrears Paid Amount: </label>
                    <input type="number" step="0.01" disabled class="form-control" name="trans_arrears_amount" value="{{ $record->trans_arrears_amount }}" id="" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="trans_date">Paid Date: </label>
                    <input type="text" class="form-control" name="trans_date" disabled value="{{ ( $record->trans_date ? date('Y-m-d',strtotime($record->trans_date)) : '' ) }}" id="" >
                </div>
            </div>
        </div>
        <input type="hidden" name="trans_id" value="{{ $record->trans_id }}">
        <input type="hidden" name="reading_date" value="{{ $record->reading_date }}">
        <input type="hidden" name="reading_waterconsumed" value="{{ $record->reading_waterconsumed }}">
        <input type="hidden" name="reading_id" value="{{ $record->reading_id }}">
        <input type="hidden" name="pay_id" value="{{ $record->pay_id }}">
        <input type="hidden" name="due_date_penalty" value="{{$record->custype_due_date_penalty}}">
        <input type="hidden" name="amount_pay" value="">
        <input type="hidden" name="reading_other_payment" value="{{$record->reading_other_payment}}">
        <input type="hidden" name="prev_amount" value="{{$record->trans_payment}}">
        <input type="hidden" name="pay_status" value="{{$record->pay_status}}">
        <input type="hidden" name="partial_amount" value="">
        <input type="hidden" name="penalty_amount" value="">
        <input type="hidden" name="duration" value="">
    </form>
    <hr />
    @php    
        $count++;
        $partial_payment +=$record->trans_payment;
    @endphp
    @endforeach
    <div class="row">
        <div class="col-xs-12">
            <a href="{{ route('customer-home') }}" class="btn btn-default">Back</a>
        </div>
    </div>
    <br />
    <br />
    <br />
    @php    
        $reading_amount = $record->reading_amount; 
        $pay_status = $record->pay_status; 
        $penalty = $record->custype_due_date_penalty;
        $duration = $record->custype_due_date_duration;
        $other_payment = $record->reading_other_payment;
        $reading_date = $record->reading_date;
        $zone = $record->cus_zone;
        $payment_amount_no_f = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_payment,$pay_status,FALSE,FALSE);
        $payment_amount = check_if_penalty($duration,$reading_date,$reading_amount,$penalty,$zone,$other_payment,$partial_payment,$pay_status,FALSE,FALSE);
        // printx(' - '.$total_amount.' - '.$partial_payment.' - '.$payment_amount);
        // printx($payment_amount);
        // $test = calculate_penalty($payment_amount,$penalty,$partial_payment);
        if (check_due_date($reading_date,$duration,$zone)) {
            $due_date_penalty_amount = $reading_amount * ($penalty/100);
        }else{
            $due_date_penalty_amount = 0;
        }
        $total_amount = $reading_amount + $other_payment + $due_date_penalty_amount;
    @endphp
</div>
@endsection

@section('additional_script')
<script>
    $(function(){
        var duration = "{{ $duration }}";
        var reading_amount = "{{ $reading_amount }}";
        var pay_status = "{{ $pay_status }}";
        var partial_payment = {{ $partial_payment }};
        var amount_pay = "{{ $payment_amount }}";
        console.log(amount_pay);
        var amount_pay_no_f = "{{ $payment_amount_no_f }}";
        var due_date_penalty_amount = "{{ $due_date_penalty_amount }}";
        var total_payment_amount = "{{ $total_amount }}";
        $('input[name="duration"]').val(duration);
        $('input[name="amount_pay"]').val(amount_pay_no_f);
        $('input[name="total_payment_amount"]').val(parseFloat(total_payment_amount).toFixed(2));
        $('input[name="due_date_penalty_amount"]').val(parseFloat(due_date_penalty_amount).toFixed(2));
        $('input[name="penalty_amount"]').val(parseFloat(due_date_penalty_amount).toFixed(2));
        // $('input[name="due_date_penalty_amount"]').val(parseFloat(amount_pay_no_f)-parseFloat(reading_amount));
        $('span#partial_payment').html('Php '+(partial_payment.toFixed(2)));
        $('span#amount_pay').html('Php '+amount_pay);
        $('span#total_amount').html('Php '+(parseFloat(amount_pay_no_f)+parseFloat(partial_payment)).toFixed(2));
        $('input[name="partial_amount"]').val(partial_payment);
        if (pay_status == 'Full') {
            $('span#amount_pay').html('Fully Paid');
        }
    });
</script>
@endsection