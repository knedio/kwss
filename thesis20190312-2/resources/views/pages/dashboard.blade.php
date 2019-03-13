@extends('layout.main_layout')

@section('title')
Dashboard
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
            Dashboard
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $count_admin }}</div>
                            <div>Total Admin</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('users') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $count_employee }}</div>
                            <div>Total Emplyee</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('users') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $count_customer }}</div>
                            <div>Total Customer</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('users') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $count_reader }}</div>
                            <div>Total Water Reader</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('users') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-ticket fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $count_pending_request }}</div>
                            <div>Total Pending Request</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('request') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $count_custype }}</div>
                            <div>Total Customer Type</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('customer-type') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <br />  
    <div class="row" id="new_cus">
        <div class="col-xs-12">
            <div style="padding:20px;border: 1px solid #ccc;border-radius: 8px;">
                <h2 style="margin-top:0px;"><strong>Lists of New Customer</strong></h2>
                <div class="table-responsive" style="padding-bottom:5em;">
                    <form action="{{ route('dashboard') }}#new_cus">
                        <div class="row">
                            <div class="col-xs-12 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <select name="m" class="form-control">
                                        @foreach($months as $key => $value)
                                            <option {{($m==$key+1)? 'selected' : ''}} value="{{$key+1}}">{{$value}}</option>
                                            {{-- @php
                                                if ($key+1 == $m) break;
                                            @endphp --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <select name="y" class="form-control">
                                        @foreach($years as $year)
                                            <option {{($y==$year)? 'selected' : ''}} value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </form>
                    <table id='datatables2' class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID No.</th>
                                <th>Full Name</th>
                                <th>Mobile Number</th>
                                <th>Address</th>
                                <th>Zone</th>
                                @if(session('account_type') == 'Admin')
                                <th>Password</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($cus_records) > 0)
                                @php $count=1; @endphp
                                @foreach($cus_records as $record)
                                    <tr>
                                        <td>{{ $count }}</td>
                                        <td>{{ $record->username }}</td>
                                        <td>{{ $record->cus_lastname.', '.$record->cus_firstname }}</td>
                                        <td>{{ $record->cus_mobile_number }}</td>
                                        <td>{{ $record->cus_address }}</td>
                                        <td>{{ $record->cus_zone }}</td>
                                        @if(session('account_type') == 'Admin')
                                        <td>{{ $record->password }}</td>
                                        @endif
                                        <td>
                                            <a href="{{ route('user-profile',['account_type'=>$record->account_type,'id'=>$record->cus_id]) }}"><span class="label label-primary">View/Update</span></a>
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
    <br />  
    <div class="row" id="disconnected_list">
        <div class="col-xs-12">
            <div style="padding:20px;border: 1px solid #ccc;border-radius: 8px;">
                <h2 style="margin-top:0px;"><strong>Lists of Disconnected Customer</strong></h2>
                <div class="table-responsive" style="padding-bottom:5em;">
                    <form action="{{ route('dashboard') }}#disconnected_list">
                        <div class="row">
                            <div class="col-xs-12 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <select name="dm" class="form-control">
                                        @foreach($months as $key => $value)
                                            <option {{($dm==$key+1)? 'selected' : ''}} value="{{$key+1}}">{{$value}}</option>
                                            {{-- @php
                                                if ($key+1 == $m) break;
                                            @endphp --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <select name="dy" class="form-control">
                                        @foreach($years as $year)
                                            <option {{($dy==$year)? 'selected' : ''}} value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </form>
                    <table id='datatables' class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                {{-- <th>ID No.</th> --}}
                                <th>Full Name</th>
                                <th>Meter Serial No.</th>
                                <th>Reading Date</th>
                                <th>Due Date</th>
                                <th>Disconnected Date</th>
                                <th>Reconnected Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($exceed_due_date_records) > 0)
                                @php $count=1; @endphp
                                @foreach($exceed_due_date_records as $record)
                                    <tr>
                                        <td>{{ $count }}</td>
                                        {{-- <td>{{ $record->username }}</td> --}}
                                        <td>{{ $record->cus_lastname.', '.$record->cus_firstname }}</td>
                                        <td>{{ $record->meter_serial_no }}</td>
                                        <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                        <td>{{ $record->due_date }}</td>
                                        <td>{{ add_day($record->due_date,1) }}</td>
                                        <td>
                                            @if($record->pay_status == 'Full')
                                                {{ date('Y-m-d',strtotime($record->trans_date)) }}
                                            @endif
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
    <br />  
    <div class="row">
        <div class="col-xs-12">
            <div style="padding:20px;border: 1px solid #ccc;border-radius: 8px;">
                <h2 style="margin-top:0px;"><strong>Lists of Customer Type</strong></h2>
                <div class="table-responsive">
                <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Min. Cubic Meter Rate</th>
                            <th>Multiplied Cubic Meter</th>
                            <th>Min. Peso Rate</th>
                            <th>Due Date Duration</th>
                            <th>Due Date Penalty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($custype_records) > 0)
                        @php $count=1; @endphp
                        @foreach($custype_records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record->custype_id }}</td>
                                <td>{{ $record->custype_type }}</td>
                                <td>{{ $record->custype_min_cubic_meter }}</td>
                                <td>{{ $record->custype_cubic_meter_rate }}</td>
                                <td>{{ $record->custype_min_peso_rate }}</td>
                                <td>{{ $record->custype_due_date_duration }}</td>
                                <td>{{ $record->custype_due_date_penalty }}</td>
                                <td>
                                    <a href="#edit_custype" 
                                        data-toggle="modal" 
                                        data-id="{{$record->custype_id}}" 
                                        data-type="{{$record->custype_type}}" 
                                        data-min-cubic-meter="{{$record->custype_min_cubic_meter}}" 
                                        data-cubic-meter-rate="{{$record->custype_cubic_meter_rate}}" 
                                        data-min-peso-rate="{{$record->custype_min_peso_rate}}"
                                        data-duration="{{$record->custype_due_date_duration}}"
                                        data-penalty="{{$record->custype_due_date_penalty}}">
                                         <span class="label label-primary">Update</span>
                                    </a>
                                    <a href="{{ route('delete-custype',['id'=>$record->custype_id]) }}"><span class="label label-danger">Delete</span></a>
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
    <br />  
    <br />  
</div>
@include('modals.modals')
@endsection

@section('additional_script')

<script>
    $(function(){

        $("form#add_custype_form,form#edit_custype_form").each(function(){
            $(this).validate({ 
                rules: {
                    type: {
                        required: true,
                    },
                    min_cubic_meter: {
                        required: true,
                        number: true
                    },
                    cubic_meter_rate: {
                        required: true,
                        number: true
                    },
                    min_peso_rate: {
                        required: true,
                        number: true
                    },
                    due_date_penalty: {
                        required: true,
                        number: true
                    },
                    due_date_duration: {
                        required: true,
                        digits: true
                    },
                },
            });
        });
        // $('#edit_custype').on('click', function(e) {
        $('#edit_custype').on('show.bs.modal', function(e) {

                    // console.log($(e.relatedTarget).data('id'));
            var id = $(e.relatedTarget).data('id');
            
            $.ajax({
                method: 'GET',
                url: "{{ route('get-custype-by-id',['id'=>'']) }}"+'/'+id,
                // dataType: 'json',
                success: function(resp){
                    console.log(resp);
                    var duration = resp.custype_due_date_duration;
                    if (duration == 'Monthly') {
                        $('input[name="due_date_duration"]').attr('disabled','disabled');
                    }else{
                        $('input[name="duration_checkbox"]').attr('checked','checked');
                    }
                    $('input[name="type"]').val(resp.custype_type);
                    $('input[name="min_cubic_meter"]').val(resp.custype_min_cubic_meter);
                    $('input[name="cubic_meter_rate"]').val(resp.custype_cubic_meter_rate);
                    $('input[name="min_peso_rate"]').val(resp.custype_min_peso_rate);
                    $('input[name="due_date_penalty"]').val(resp.custype_due_date_penalty);
                    $('input[name="due_date_duration"]').val(duration);
                    $('input[name="id"]').val(resp.custype_id);
                },
                error: function(err){
                    console.log(err);
                }
            });
            
        });

        $('input[name="duration_checkbox"]').click(function(e) {
            var check_checkbox = $(this).is(':checked');
            if (check_checkbox) {
                $('input[name=due_date_duration]').removeAttr("disabled");         
            }else{
                $('input[name=due_date_duration]').attr('disabled','disabled');         
            }
        });
    });
</script>
@endsection