@extends('layout.main_layout')

@section('title')
    Database Backup Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Database Backup Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Database Backup Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
            <a href="{{ route('add-backup') }}" class="btn btn-success pull-right">
                Backup Database
            </a>
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
                <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Date Created</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count = 0; @endphp
                        @foreach($files as $file)
                        <tr>
                            <td>{{$count+1}}</td>
                            <td>{{ $file }}</td>
                            <td>{{ formatSizeUnits(filesize($path. '/' . $file)) }}</td>
                            <td>{{date('Y-m-d h:i:s a',filemtime($path. '/' . $file))}}</td>
                            <td>
                                <a href="{{$path. '/' . $file}}">
                                    <span class="label label-primary">Download</span>
                                </a>
                                <a href="{{ route('delete-backup',['filename'=>$file]) }}">
                                    <span class="label label-danger">Delete</span>
                                </a>
                            </td>
                        </tr>
                        @php $count++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_script')
@endsection