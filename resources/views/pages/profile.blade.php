@extends('layout.main_layout')

@section('title')
    Profile
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                {{ucfirst($record->lastname)}}'s Profile
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    {{ucfirst($record->lastname)}}'s Profile
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row customer-info hide-content">
        <div class="col-xs-12">
            <button type="button" 
                    class="btn btn-success pull-right" 
                    data-id="{{$record->id}}"
                    data-toggle="modal" 
                    data-target="#add_request">
                    Request
            </button>
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
            @endif
        </div>
    </div>
    <form action="{{ route('edit-user') }}" method="post" id="profile_form" >
        <div class="row">
            <div class="col-xs-6">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id" value="{{$record->id}}">
                <input type="hidden" name="session_account_type" id="session_account_type" value="{{session('account_type')}}">
                <div class="form-group">
                    <label for="username">Account Type :</label>
                    <input type="text" readonly class="form-control" name="account_type" id="account_type" value="{{$record->account_type}}" placeholder="Username">
                </div>
                @if($record->account_type != 'Water Reader')
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" disabled class="form-control" name="username" id="username" value="{{$record->username}}" placeholder="Username">
                </div>
                @endif
                <div class="form-group">
                    <label for="firstname">First Name :</label>
                    <input type="text" class="form-control" name="firstname" id="firstname" value="{{ucwords(strtolower($record->firstname))}}" placeholder="First Name">
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name :</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" value="{{ucwords(strtolower($record->lastname))}}" placeholder="Last Name">
                </div>
                <div class="form-group">
                    <label for="mobile_number">Mobile Number :</label>
                    <div class="input-group">
                        <div class="input-group-addon">+63</div>
                        <input type="text" class="form-control" name="mobile_number" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" id="mobile_number" value="{{$record->mobile_number}}" placeholder="Ex. 9123456789">
                    </div>
                    <div id="mobile_number_error"></div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="address">Address :</label>
                    <input type="text" class="form-control" name="address" id="address" value="{{ $record->address }}" placeholder="Address">
                </div>
                @if($record->account_type != 'Water Reader')
                <div id="password_section" class="hide-content">
                    <div class="form-group">
                        <label for="password">Password <span class="text-red">*</span>:</label>
                        <input type="password" class="form-control" disabled name="password" autocomplete="off" id="password" value="{{$record->password}}" placeholder="***">
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div id="additional_input" class="hide-content">
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <strong><u>Meter Details</u></strong>
                        <button class="btn btn-xs btn-success" type="button" id="btn_additional_meter">
                            <i class="fa fa-plus"></i>
                        </button>
                        <br /><small>No. of Meter(s) : <strong><span id="total_meter_no">1</span></strong> </small>
                        <input type="hidden" name="no_of_meter" id="no_of_meter" value="1" />
                    </h3>
                </div>
            </div>
            <div class="row">
                @php $count = 0; @endphp
                @foreach($meter_records as $m_rec)
                    <div id="meter_details">
                        <div id="meter_content{{$count+1}}">
                            <div class="col-xs-6">
                                <h4>
                                    <strong>Meter <span class="meter_no">{{$count+1}}</span></strong>
                                    @if($count==0)
                                        <button class="btn btn-xs btn-danger hide-content btn_remove_meter" id="btn_remove_meter{{$count+1}}" type="button" data-id="{{$count+1}}">
                                            Remove
                                        </button>
                                    @endif
                                </h4>
                                @if(!empty($custype_records))
                                <div class="form-group">
                                    <label for="custype_id">Customer Type <span class="text-red">*</span>:</label>
                                    <select class="form-control meter-detail" id="custype_id{{$count+1}}" disabled name="custype_id[]">
                                        <option value="" disabled selected>-- Select Customer Type --</option>
                                        @foreach($custype_records as $col)
                                            <option {{($m_rec->custype_id==$col->custype_id)? 'selected' : ''}} value="{{ $col->custype_id }}">{{ $col->custype_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="meter_serial_no">Meter Serial No. <span class="text-red">*</span>:</label>
                                    <input type="text" class="form-control meter-detail" name="meter_serial_no[]" id="meter_serial_no{{$count+1}}" disabled value="{{$m_rec->meter_serial_no}}" placeholder="Meter Serial No.">
                                </div>
                                <div class="form-group">
                                    <label for="meter_model">Meter Model <span class="text-red">*</span>:</label>
                                    <input type="text" class="form-control meter-detail" name="meter_model[]" id="meter_model{{$count+1}}" disabled value="{{$m_rec->meter_model}}" placeholder="Meter Model">
                                </div>
                                <div class="form-group">
                                    <label for="meter_duedate">Meter Due Date <span class="text-red">*</span>:</label>
                                    <input type="date" class="form-control meter-detail" name="meter_duedate[]" id="meter_duedate{{$count+1}}" disabled value="{{date('Y-m-d',strtotime($m_rec->meter_duedate))}}" placeholder="Due Date">
                                </div>
                                <div class="form-group">
                                    <label for="meter_address">Meter Address <span class="text-red">*</span>:</label>
                                    <input type="text" class="form-control meter-detail" name="meter_address[]" id="meter_address{{$count+1}}" disabled value="{{$m_rec->meter_address}}" placeholder="Meter Address" />
                                </div>
                                <input type="hidden" class="meter-detail" name="meter_id[]" id="meter_id{{$count+1}}" disabled value="{{$m_rec->meter_id}}" />
                            </div>
                        </div>
                    </div>
                    @php 
                        $count++;
                        if ($count == 1 && $record->account_type=='Customer') {
                            echo '<div id="additional_meter">';
                        }
                    @endphp
                @endforeach
                @if($record->account_type=='Customer')
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <hr />
                <a href="{{route('users')}}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
    <br>
    <br>
    <div class="modal fade" id="add_request" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('add-request') }}" id="add_request_form" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Request</h4>
                    </div>
                    <div class="modal-body">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                        <div class="form-group">
                            <label for="">Request Type <span class="text-red">*</span>:</label>
                            <select class="form-control" id="request_type" name="request_type">
                                <option value="" disabled selected>-- Select Request Type --</option>
                                <option value="Name">Change Name</option>
                                <option value="Address">Change Address</option>
                                <option value="Meter">Change Meter</option>
                            </select>
                        </div>
                        <div id="name_info" class="hide-content">
                            <div class="form-group">
                                <label for="">First Name <span class="text-red">*</span>:</label>
                                <input type="text" class="form-control" disabled name="request_firstname" id="request_firstname" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="">Last Name <span class="text-red">*</span>:</label>
                                <input type="text" class="form-control" disabled name="request_lastname" id="request_lastname" placeholder="">
                            </div>
                        </div>
                        <div id="address_info" class="hide-content">
                            <div class="form-group">
                                <label for="">Address <span class="text-red">*</span>:</label>
                                <input type="text" class="form-control" disabled name="request_address" id="request_address" placeholder="">
                            </div>
                            {{-- <div class="form-group">
                                <label for="">Zone <span class="text-red">*</span>:</label>
                                <input type="text" step="any" class="form-control" disabled name="request_zone" id="request_zone" placeholder="">
                            </div> --}}
                        </div>
                        <div id="custype_info" class="hide-content">
                            <div class="form-group">
                                <label for="req_meter_id">Meter Serial No. <span class="text-red">*</span>:</label>
                                <select class="form-control" id="req_meter_id" disabled name="req_meter_id">
                                    <option value="" disabled selected>-- Select Meter Serial No. --</option>
                                    @foreach($meter_records as $col)
                                        <option data-custype-id="{{$col->custype_id}}" value="{{ $col->meter_id }}">{{ $col->meter_serial_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="req_custype_id">Customer Type <span class="text-red">*</span>:</label>
                                <select class="form-control" id="req_custype_id" disabled name="req_custype_id">
                                    <option value="" disabled selected>-- Select Customer Type --</option>
                                    @foreach($custype_records as $col)
                                        <option value="{{ $col->custype_id }}">{{ $col->custype_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <input type="hidden" name="cus_id" />
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_script')
<script>
    $(function(){

        $('#add_request_form').validate({ 
            rules: {
                request_type: {
                    required: true,
                },
                request_firstname: {
                    required: true,
                },
                request_lastname: {
                    required: true,
                },
                request_address: {
                    required: true,
                },
                req_meter_id: {
                    required: true,
                },
                req_custype_id: {
                    required: true,
                },
            },
        });
        var account_type = $('#account_type').val();
        if (account_type != 'Water Reader') {
            $('#password_section').removeClass('hide-content');
            $('input[name=password]').removeAttr('disabled');
            // $('input[name=confirm_password]').removeAttr('disabled');
        }
        if (account_type == 'Customer') {
            $('.customer-info').removeClass('hide-content');
            $('#additional_input').removeClass('hide-content');
            $('.meter-detail').removeAttr('disabled');
            $('#address').attr('disabled','disabled');
            $('#firstname').attr('disabled','disabled');
            $('#lastname').attr('disabled','disabled');
            $('#zone').attr('disabled','disabled');
            $('span#total_meter_no').text({{$count}});
            $('input[name=no_of_meter]').val({{$count}});

            if ($('#session_account_type').val() == 'Customer') {
                $('.meter-detail').attr('disabled','disabled');
                $('#btn_additional_meter').addClass('hide-content');
            }
        }
        if ("{{session('account_type')}}" == 'Admin') {
            $('#password').attr('type','text');
        }
        $('#profile_form').validate({ 
            rules: {
                account_type: {
                    required: true,
                },
                firstname: {
                    required: true,
                },
                lastname: {
                    required: true,
                },
                mobile_number: {
                    maxlength: 10,
                    minlength: 10,
                },
                address: {
                    required: true,
                },
                zone: {
                    required: true,
                },
                'custype_id[]': {
                    required: true,
                },
                'meter_serial_no[]': {
                    required: true,
                },
                'meter_model[]': {
                    required: true,
                },
                'meter_duedate[]': {
                    required: true,
                },
                'meter_address[]': {
                    required: true,
                },
                password: {
                    required: true,
                    maxlength: 8,
                    minlength: 8,
                },
                // confirm_password: {
                //     required: true,
                //     equalTo: "#password"
                // },
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "mobile_number") {
                    error.insertAfter("#mobile_number_error");
                } else {
                    error.insertAfter(element);
                }
            },
            // messages: {
            //     confirm_password: {
            //         equalTo: "Password not match! Please try again."
            //     }
            // }
        });
        $('select#req_meter_id').change(function(){
            var custype_id = $(this).find(':selected').data('custype-id');
            console.log(custype_id)
            $('select#req_custype_id').val(custype_id);
        });
        $('select#account_type').change(function(){
            if ( $(this).val() == "Customer" ) {
                $('.customer-info').removeClass('hide-content');
                $('div#additional_input').show();
                $('.meter-detail').attr("disabled", false);
                $('#address').attr('disabled','disabled');
                $('#firstname').attr('disabled','disabled');
                $('#lastname').attr('disabled','disabled');
                $('#zone').attr('disabled','disabled');
            }else {
                $('div#additional_input').hide();
                $('.meter-detail').attr("disabled", true);

                $('.customer-info').addClass('hide-content');
                $('div#additional_input').hide();
                $('#address').removeAttr('disabled');
                $('#firstname').removeAttr('disabled');
                $('#lastname').removeAttr('disabled');
                $('#zone').removeAttr('disabled');
            }

            if ($(this).val() == 'Water Reader') {
                $('#password_section').addClass('hide-content');
                $('input[name=password]').attr('disabled','disabled');
                // $('input[name=confirm_password]').attr('disabled','disabled');
            }
        });
        $('#btn_additional_meter').click(function(){
            var total_meter_no = parseFloat($('span#total_meter_no').text())+1;
            $('span#total_meter_no').text(total_meter_no);
            $('input[name=no_of_meter]').val(total_meter_no);
            var clone_meter = $( "#meter_details" ).clone();
            let meter_no = parseFloat($('span.meter_no', clone_meter).text());
            $('.btn_remove_meter',clone_meter).attr('data-id',total_meter_no);
            $("#meter_content1", clone_meter).attr('id', 'meter_content'+total_meter_no);

            $('#meter_id1', clone_meter).attr('id', 'meter_id'+total_meter_no).val('');
            $('#custype_id1', clone_meter).attr('id', 'custype_id'+total_meter_no).val('');
            $('#meter_serial_no1', clone_meter).attr('id', 'meter_serial_no'+total_meter_no).val('');
            $('#meter_model1', clone_meter).attr('id', 'meter_model'+total_meter_no).val('');
            $('#meter_duedate1', clone_meter).attr('id', 'meter_duedate'+total_meter_no).val('');
            $('#meter_address1', clone_meter).attr('id', 'meter_address'+total_meter_no).val('');


            $(".btn_remove_meter", clone_meter).attr('id', 'btn_remove_meter'+total_meter_no);
            $('.btn_remove_meter',clone_meter).removeClass('hide-content');
            $('span.meter_no', clone_meter).text(total_meter_no);
            clone_meter.appendTo( "#additional_meter" );
        });
        $( '#additional_meter' ).on( "click",'.btn_remove_meter', function(e) {
            let id = $(this).attr('data-id');

            let total_meter_no = parseFloat($('span#total_meter_no').text())-1;
            $('span#total_meter_no').text(total_meter_no);
            $('input[name=no_of_meter]').val(total_meter_no);
            $('#meter_content'+id).remove();
            console.log(id+'-id');
            $('#additional_meter span.meter_no').each(function(i, obj) {
                let meter_no = parseFloat($(this).text());
                if (meter_no > id) {
                    let btn_id = parseFloat($('#btn_remove_meter'+meter_no).attr('data-id'));
                    $('#meter_content'+meter_no).attr('id','meter_content'+(meter_no-1));
                    $('#btn_remove_meter'+meter_no).attr('data-id',meter_no-1);
                    $('#btn_remove_meter'+meter_no).attr('id','btn_remove_meter'+(meter_no-1));
                    $(this).text(meter_no-1);

                    $('#meter_id'+meter_no).attr('id', 'meter_id'+(meter_no-1));
                    $('#custype_id'+meter_no).attr('id', 'custype_id'+(meter_no-1));
                    $('#meter_serial_no'+meter_no).attr('id', 'meter_serial_no'+(meter_no-1));
                    $('#meter_model'+meter_no).attr('id', 'meter_model'+(meter_no-1));
                    $('#meter_duedate'+meter_no).attr('id', 'meter_duedate'+(meter_no-1));
                    $('#meter_address'+meter_no).attr('id', 'meter_address'+(meter_no-1));
                }
            });
        });

        $('#add_request').on('show.bs.modal', function(e) {
            let cus_id = $(e.relatedTarget).data('id');
            $('#add_request_form input[name=cus_id]').val(cus_id);
        });
        $('select#request_type').change(function(){
            let req_typ = $(this).val();
            if (req_typ == 'Name') {
                $('#address_info').addClass('hide-content');
                $('#custype_info').addClass('hide-content');
                $('#request_address').attr('disabled','disabled');
                $('#request_zone').attr('disabled','disabled');
                $('#req_custype_id').attr('disabled','disabled');
                $('#req_meter_id').attr('disabled','disabled');
                $('#name_info').removeClass('hide-content');
                $('#request_firstname').removeAttr('disabled');
                $('#request_lastname').removeAttr('disabled');
            }else if (req_typ == 'Address') {
                $('#name_info').addClass('hide-content');
                $('#custype_info').addClass('hide-content');
                $('#request_firstname').attr('disabled','disabled');
                $('#request_lastname').attr('disabled','disabled');
                $('#req_custype_id').attr('disabled','disabled');
                $('#req_meter_id').attr('disabled','disabled');
                $('#address_info').removeClass('hide-content');
                $('#request_address').removeAttr('disabled');
                $('#request_zone').removeAttr('disabled');
            }else if (req_typ == 'Meter') {
                $('#name_info').addClass('hide-content');
                $('#address_info').addClass('hide-content');
                $('#request_firstname').attr('disabled','disabled');
                $('#request_lastname').attr('disabled','disabled');
                $('#request_address').attr('disabled','disabled');
                $('#request_zone').attr('disabled','disabled');
                $('#custype_info').removeClass('hide-content');
                $('#req_custype_id').removeAttr('disabled');
                $('#req_meter_id').removeAttr('disabled');
            }
        });
    });
</script>
@endsection