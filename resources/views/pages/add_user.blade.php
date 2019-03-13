@extends('layout.main_layout')

@section('title')
    Add New User
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Add New User
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Add New User
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-6">
            <form action="{{ route('add-user') }}" id="add_user_form" method="POST">
                <div class="modal-body">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                        <label for="account_type">Account Type <span class="text-red">*</span>:</label>
                        <select class="form-control" id="account_type" name="account_type">
                            <option value="" disabled selected>-- Select Account Type --</option>
                            <option value="Admin">Admin</option>
                            <option value="Employee">Employee</option>
                            <option value="Customer">Customer</option>
                            <option value="Water Reader">Water Reader</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="firstname">First Name <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="firstname" id="" value="" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="lastname" id="" value="" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number :</label>
                        <div class="input-group">
                            <div class="input-group-addon">+63</div>
                            <input type="text" class="form-control" name="mobile_number" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" id="mobile_number" value="" placeholder="Ex. 9123456789">
                        </div>
                        <div id="mobile_number_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="address" id="address" value="" placeholder="Address">
                    </div>
                    <div id="additional_input" style="display:block;">
                        <div class="form-group">
                            <label for="zone">Zone <span class="text-red">*</span>:</label>
                            <select class="form-control meter-detail" id="custype_id1" disabled name="zone">
                                <option value="" disabled selected>-- Select Zone --</option>
                                @for($i=1; $i <= 15; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <h3>
                            <strong><u>Meter Details</u></strong> </small>
                            <button class="btn btn-xs btn-success" type="button" id="btn_additional_meter">
                                <i class="fa fa-plus"></i>
                            </button>
                            <br /><small>No. of Meter(s) : <strong><span id="total_meter_no">1</span></strong>
                            <input type="hidden" name="no_of_meter" id="no_of_meter" value="1" />
                        </h3>
                        <div id="meter_details">
                            <div id="meter_content">
                                <h4>
                                    <strong>Meter <span class="meter_no">1</span></strong>
                                    <button class="btn btn-xs btn-danger hide-content btn_remove_meter" id="btn_remove_meter" type="button" id="" data-id="">
                                        Remove
                                    </button>
                                </h4>
                                @if(!empty($custype_records))
                                <div class="form-group">
                                    <label for="custype_id">Customer Type <span class="text-red">*</span>:</label>
                                    <select class="form-control meter-detail" id="custype_id1" disabled name="custype_id[]">
                                        <option value="" disabled selected>-- Select Customer Type --</option>
                                        @foreach($custype_records as $record)
                                            <option value="{{ $record->custype_id }}">{{ $record->custype_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="meter_serial_no">Meter Serial No. <span class="text-red">*</span>:</label>
                                    <input type="text" class="form-control meter-detail" name="meter_serial_no[]" id="meter_serial_no1" disabled value="" placeholder="Meter Serial No.">
                                </div>
                                <div class="form-group">
                                    <label for="meter_model">Meter Model <span class="text-red">*</span>:</label>
                                    <input type="text" class="form-control meter-detail" name="meter_model[]" id="meter_model1" disabled value="" placeholder="Meter Model">
                                </div>
                                <div class="form-group">
                                    <label for="meter_duedate">Meter Due Date <span class="text-red">*</span>:</label>
                                    <input type="date" class="form-control meter-detail" name="meter_duedate[]" id="meter_duedate1" disabled value="" placeholder="Due Date">
                                </div>
                                <div class="form-group">
                                    <label for="meter_address">Meter Address <span class="text-red">*</span>:</label>
                                    <input type="text" class="form-control meter-detail" name="meter_address[]" id="meter_address1" disabled value="" placeholder="Meter Address">
                                </div>
                            </div>
                        </div>
                        <div id="additional_meter">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <br>
    <br>
</div>
@endsection

@section('additional_script')
<script>
    $(function(){
        var account_type = $('#account_type').val();
        if (!account_type) {
            $('div#additional_input').hide();
            $('.meter-detail').attr("disabled", true);
        }
        $('#add_user_form').validate({ 
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
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "mobile_number") {
                    error.insertAfter("#mobile_number_error");
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $('select#account_type').change(function(){
            if ( $(this).val() == "Customer" ) {
                $('div#additional_input').show();
                $('.meter-detail').attr("disabled", false);
            }else {
                $('div#additional_input').hide();
                $('.meter-detail').attr("disabled", true);
            }
        });
        $('select#custype_id').change(function(){
            var min_cubic_meter = $(this).find(':selected').data('min-cubic-meter');
            var cubic_meter_rate = $(this).find(':selected').data('cubic-meter-rate');
            var min_peso_rate = $(this).find(':selected').data('min-peso-rate');
            $('#min_cubic_meter').val(min_cubic_meter);
            $('#cubic_meter_rate').val(cubic_meter_rate);
            $('#min_peso_rate').val(min_peso_rate);
        });
        $('#btn_additional_meter').click(function(){
            var total_meter_no = parseFloat($('span#total_meter_no').text())+1;
            $('span#total_meter_no').text(total_meter_no);
            $('input[name=no_of_meter]').val(total_meter_no);
            var clone_meter = $( "#meter_details" ).clone();
            let meter_no = parseFloat($('span.meter_no', clone_meter).text());
            $('.btn_remove_meter',clone_meter).attr('data-id',total_meter_no);
            $("#meter_content", clone_meter).attr('id', 'meter_content'+total_meter_no);

            $('#custype_id1', clone_meter).attr('id', 'custype_id'+total_meter_no);
            $('#meter_serial_no1', clone_meter).attr('id', 'meter_serial_no'+total_meter_no);
            $('#meter_model1', clone_meter).attr('id', 'meter_model'+total_meter_no);
            $('#meter_duedate1', clone_meter).attr('id', 'meter_duedate'+total_meter_no);
            $('#meter_address1', clone_meter).attr('id', 'meter_address'+total_meter_no);

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

                    $('#custype_id'+meter_no).attr('id', 'custype_id'+(meter_no-1));
                    $('#meter_serial_no'+meter_no).attr('id', 'meter_serial_no'+(meter_no-1));
                    $('#meter_model'+meter_no).attr('id', 'meter_model'+(meter_no-1));
                    $('#meter_duedate'+meter_no).attr('id', 'meter_duedate'+(meter_no-1));
                    $('#meter_address'+meter_no).attr('id', 'meter_address'+(meter_no-1));
                }
            });
        });
    });
</script>
@endsection