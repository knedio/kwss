@extends('layout.main_layout')

@section('title')
    Employee Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Employee Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Employee Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Mobile Number</th>
                        <th>Address</th>
                        <th>Account Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($records) > 0)
                        @php $count=1; echo url()->previous().'test'; @endphp
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record->username }}</td>
                                <td>{{ $record->emp_firstname }}</td>
                                <td>{{ $record->emp_lastname }}</td>
                                <td>{{ $record->emp_mobile_number }}</td>
                                <td>{{ $record->emp_address }}</td>
                                <td>{{ $record->account_type }}</td>
                                <td>
                                    <a href="{{ route('profile_employee',['id'=>$record->emp_id]) }}" class="btn btn-default">View/Edit</a>
                                    <a href="{{ route('delete_employee',['id'=>$record->emp_id]) }}" class="btn btn-default">Delete</a>
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
@endsection