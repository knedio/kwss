@extends('layout.main_layout')

@section('title')
    Meter Reading Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Meter Reading Lists asdsad
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard asdsad
                </li>
                <li class="active">
                    Meter Reading Lists
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_meter_reading">Add Meter Reading</button>
        </div>
        <div class="col-xs-12">
            <hr>
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
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! Session::get('error') !!}
                </div>
                @php session()->forget('error'); @endphp
            @endif
            <div class="table-responsive">
                
            </div>
        </div>
    </div>
</div>
@include('modals.modals')
@endsection

@section('additional_script')

@endsection