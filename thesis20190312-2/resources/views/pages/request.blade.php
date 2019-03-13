@extends('layout.main_layout')

@section('title')
    Request Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Request Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Request Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    {{-- <div class="row">
        <div class="col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_address_request">Add New Type</button>
        </div>
    </div>
    <br /> --}}
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
                <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Previous Data</th>
                            <th>Requested Data</th>
                            <th>Request Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($records) > 0)
                        @php $count=1; @endphp
                        {{-- @php printx($records); @endphp --}}
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record['id'] }}</td>
                                <td>{!! $record['prev_data_serialized'] !!}</td>
                                <td>{!! $record['data_serialized'] !!}</td>
                                <td>{{ $record['type'] }}</td>
                                <td>{{ $record['status'] }}</td>
                                <td>
                                   @if($record['status'] == 'Pending')
                                    <a href="{{ route('update-request', ['id'=>$record['id'],'status'=>'Approved']) }}">
                                        <span class="label label-primary">Approve</span>
                                    </a>
                                    <a href="{{ route('update-request', ['id'=>$record['id'],'status'=>'Declined']) }}">
                                        <span class="label label-danger">Deline</span>
                                    </a>
                                   @endif
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
@endsection

@section('additional_script')
@endsection