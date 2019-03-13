@extends('layout.main_layout')

@section('title')
    Customer Type Old Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Customer Type Old Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Customer Type Old Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    @if(count($results) > 0)
        @php
        $count = 0;
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive" style="padding-bottom:5em;">
                    <table id='datatables' class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Customer Type</th>
                                <th>Min. (&#13221;) Rate</th>
                                <th>Multiplied (&#13221;)</th>
                                <th>Min. Peso Rate (₱)</th>
                                <th>Due Date Duration</th>
                                <th>Due Date Penalty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count=1 @endphp
                            @foreach($results as $result)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{$result->custype_type}}</td>
                                    <td>{{$result->custype_min_cubic_meter}}</td>
                                    <td>{{$result->custype_cubic_meter_rate}}</td>
                                    <td>{{$result->custype_min_peso_rate}}</td>
                                    <td>{{$result->custype_due_date_duration}}</td>
                                    <td>{{$result->custype_due_date_penalty}}</td>
                                </tr>
                                @php $count++ @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
    @else
        <div class="row">
            <div class="col-xs-12 text-center">
                <h3>No Previous Data!</h3>
            </div>
        </div>
    @endif
</div>
@include('modals.modals')
@endsection

@section('additional_script')
<script>
</script>
@endsection