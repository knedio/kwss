@extends('layout.main_layout')

@section('title')
    Admin Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Admin Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Admin Lists
                </li>
            </ol>
        </div>
        <div class="col-xs-12">
            <a href="{{ route('add-user-page') }}" class="btn btn-info float-right">Add New User</a>

             <form action="{{ route('upload-users') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <strong>Select file to upload users:</strong>
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <input type="file" required name="upload_users" id="upload_users" style="margin-bottom: 5px;">
                <input type="submit" class="btn btn-primary btn-sm" value="Upload File">
            </form>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-12 distance" style="padding-top:20px; padding-bottom: 20px;">
        @if(session('add_successfull'))
        <div class="alert alert-success alert-dismissible fade in">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ Session::get('add_successfull') }}
        </div>
        @php session()->forget('add_successfull'); @endphp
        @endif
          <ul id="navbar_menu" class="nav nav-tabs" role="tablist">
            <li class="active"><a data-toggle="tab" href="#admin">Admin</a></li>
            <li><a data-toggle="tab" href="#employee">Employee</a></li>
            <li><a data-toggle="tab" href="#customer">Customer</a></li>
            <li><a data-toggle="tab" href="#water_reader">Water Reader</a></li>
          </ul>
        </div>
        <div class="tab-content" >
            <div class="tab-pane active" id="admin">
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID No.</th>
                                    <th>Full Name</th>
                                    <th>Mobile Number</th>
                                    <th>Address</th>
                                    @if(session('account_type') == 'Admin')
                                    <th>Password</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($admin_records) > 0)
                                    @php $count=1 @endphp
                                    @foreach($admin_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ $record->username }}</td>
                                            <td>{{ ucwords(strtolower($record->emp_lastname.', '.$record->emp_firstname)) }}</td>
                                            <td>{{ $record->emp_mobile_number }}</td>
                                            <td>{{ $record->emp_address }}</td>
                                            @if(session('account_type') == 'Admin')
                                            <td>{{ $record->password }}</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('user-profile',['account_type'=>$record->account_type,'id'=>$record->emp_id]) }}"><span class="label label-primary">View/Update</span></a>
                                                <a href="{{ route('delete-user',['account_type'=>$record->account_type,'id'=>$record->emp_id]) }}"><span class="label label-danger">Delete</span></a>
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
            <div class="tab-pane" id="employee">
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables2' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID No.</th>
                                    <th>Full Name</th>
                                    <th>Mobile Number</th>
                                    <th>Address</th>
                                    @if(session('account_type') == 'Admin')
                                    <th>Password</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($employee_records) > 0)
                                    @php $count=1 @endphp
                                    @foreach($employee_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ $record->username }}</td>
                                            <td>{{ ucwords(strtolower($record->emp_lastname.' '.$record->emp_firstname)) }}</td>
                                            <td>{{ $record->emp_mobile_number }}</td>
                                            <td>{{ $record->emp_address }}</td>
                                            @if(session('account_type') == 'Admin')
                                            <td>{{ $record->password }}</td>
                                            @endif
                                            <td>
                                                <a href="{{ route('user-profile',['account_type'=>$record->account_type,'id'=>$record->emp_id]) }}"><span class="label label-primary">View/Update</span></a>
                                                <a href="{{ route('delete-user',['account_type'=>$record->account_type,'id'=>$record->emp_id]) }}"><span class="label label-danger">Delete</span></a>
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
            <div class="tab-pane" id="customer">
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
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
                                @if(count($customer_records) > 0)
                                    @php $count=1 @endphp
                                    @foreach($customer_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ $record->username }}</td>
                                            <td>{{ ucwords(strtolower($record->cus_lastname.', '.$record->cus_firstname)) }}</td>
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
            <div class="tab-pane" id="water_reader">
                <div class="col-lg-12">
                    <div class="table-responsive" style="padding-bottom:5em;">
                        <table id='datatables4' class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Full Name</th>
                                    <th>Mobile Number</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($wr_records) > 0)
                                    @php $count=1 @endphp
                                    @foreach($wr_records as $record)
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ ucwords(strtolower($record->reader_lastname.', '.$record->reader_firstname)) }}</td>
                                            <td>{{ $record->reader_mobile_number }}</td>
                                            <td>{{ $record->reader_address }}</td>
                                            <td>
                                                <a href="{{ route('user-profile',['account_type'=>'Water Reader','id'=>$record->reader_id]) }}"><span class="label label-primary">View/Update</span></a>
                                                <a href="{{ route('delete-user',['account_type'=>'Water Reader','id'=>$record->reader_id]) }}"><span class="label label-danger">Delete</span></a>
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
    </div>
</div>
@endsection

@section('additional_script')
<script>
    
    // $(function(){
    //     $('#add_user').on('show.bs.modal', function(e) {
    //         var route = $(e.relatedTarget).data('route');
    //         $('#add_user_form').attr('action', route);
    //     });
    //     $('select#account_type').change(function(){
    //         if ( $(this).val() == "Customer" ) {
    //            $('#additional_input').show();
    //            $('#customer_type').attr("disabled", false);
    //            $('#meter_model').attr("disabled", false);
    //            $('#meter_duedate').attr("disabled", false);
    //            $('#meter_address').attr("disabled", false);
    //         }else {
    //             $('#additional_input').hide();
    //             $('#customer_type').attr("disabled", true);
    //            $('#meter_model').attr("disabled", true);
    //            $('#meter_duedate').attr("disabled", true);
    //            $('#meter_address').attr("disabled", true);
    //         }
    //     });
    // });
</script>
@endsection