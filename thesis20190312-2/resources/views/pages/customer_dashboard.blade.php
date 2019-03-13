@extends('layout.main_layout')

@section('title')
    Dashboard
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
            {{ session('lastname') }}'s Dashboard
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
        <div class="col-lg-12 distance" style="padding-top:20px; padding-bottom: 20px;">
            <ul id="navbar_menu" class="nav nav-tabs" role="tablist">
            <li class="active"><a data-toggle="tab" href="#unpaid">Unpaid</a></li>
            <li><a data-toggle="tab" href="#partial">Partial</a></li>
            <li><a data-toggle="tab" href="#full">Full</a></li>
            </ul>
        </div>
        <div class="tab-content" >
            <div class="tab-pane active" id="unpaid">
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer</th>
                                    <th>Water Consumed</th>
                                    <th>Reading Payment</th>
                                    <th>Other Payment</th>
                                    <th>Read Date</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($unpaid_records))
                                    @php $count=1; @endphp
                                    @foreach($unpaid_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname)) }}</td>
                                            <td>{{ number_format($record->reading_waterconsumed,2) }}</td>
                                            <td>Php {{ number_format($record->reading_amount,2) }}</td>
                                            <td>Php {{ number_format($record->reading_other_payment,2) }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
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
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables2' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer</th>
                                    <th>Received By</th>
                                    <th>Water Consumed</th>
                                    <th>Total Paid (PHP)</th>
                                    <th>Read Date</th>
                                    <th>Latest Paid Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
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
                                            <td>{{ ucwords(strtolower($record->emp_lastname.', '.$record->emp_firstname)) }}</td>
                                            <td>{{ number_format($record->reading_waterconsumed,2) }}</td>
                                            <td>Php {{ number_format($record->trans_payment,2) }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                            <td>
                                                {{ ( $record->trans_date ? date('Y-m-d',strtotime($record->trans_date)) : '' )}}
                                            </td>
                                            <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
                                            <td>{{ ( $record->pay_status ? $record->pay_status : 'Unpaid' ) }}</td>
                                            <td>
                                                <a href="{{ route('customer-payment', ['id'=>$record->pay_id]) }}">
                                                    <span class="label label-primary">View</span>
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
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer</th>
                                    <th>Received By</th>
                                    <th>Water Consumed</th>
                                    <th>Total Paid (PHP)</th>
                                    <th>Read Date</th>
                                    <th>Latest Paid Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
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
                                            <td>{{ ucwords(strtolower($record->emp_lastname.', '.$record->emp_firstname)) }}</td>
                                            <td>{{ number_format($record->reading_waterconsumed,2) }}</td>
                                            <td>Php {{ number_format($record->trans_payment,2) }}</td>
                                            <td>{{ date('Y-m-d',strtotime($record->reading_date)) }}</td>
                                            <td>
                                                {{ ( $record->trans_date ? date('Y-m-d',strtotime($record->trans_date)) : '' )}}
                                            </td>
                                            <td>{{ date('Y-m-d',strtotime($record->due_date)) }}</td>
                                            <td>{{ ( $record->pay_status ? $record->pay_status : 'Unpaid' ) }}</td>
                                            <td>
                                                <a href="{{ route('customer-payment', ['id'=>$record->pay_id]) }}">
                                                    <span class="label label-primary">View</span>
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
@endsection
