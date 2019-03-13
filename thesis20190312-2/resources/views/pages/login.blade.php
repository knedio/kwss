@extends('layout.login_layout')
@section('title')
    Login Page
    @endsection
@section('content')
@if(session('error_message'))
<div class="alert alert-danger alert-dismissible fade in">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Please try again!</strong> Invalid ID Number or Password.
</div>
@php session()->forget('error_message'); @endphp
@endif
<div class="login-container">
    <div class="logo-container">
        <div class="row">
            <div class="col-xs-12">
                <img src="{{ asset('img/logo-2.png') }}" width="35%" class="img-responsive" height="auto" alt="">  
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form action="{{ route('check-user') }}" method="post">
                <div class="text-right">
                    <a href="uploads/thesis.apk">
                        <span class="label label-success"><i class="fa fa-download"></i>Download APK</span>
                    </a>
                </div>
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label for="username">ID Number</label>
                    <input type="text" class="form-control" required name="username" id="" placeholder="Enter ID Number">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" required name="password" id="exampleInputPassword1" placeholder="Enter Password">
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
