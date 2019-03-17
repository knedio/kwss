@extends('layout.main_layout')

@section('title')
    Customer Type Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Customer Type Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    Customer Type Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_custype">Add New Type</button>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table id='datatables3' class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Type</th>
                            <th>Min. Cubic Meter Rate</th>
                            <th>Multiplied Cubic Meter</th>
                            <th>Min. Peso Rate</th>
                            <th>Due Date Duration</th>
                            <th>Due Date Penalty</th>
                            <th>Zone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($records) > 0)
                        @php $count=1; @endphp
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $record->custype_type }}</td>
                                <td>{{ $record->custype_min_cubic_meter }}</td>
                                <td>{{ $record->custype_cubic_meter_rate }}</td>
                                <td>{{ $record->custype_min_peso_rate }}</td>
                                <td>{{ $record->custype_due_date_duration }}</td>
                                <td>{{ $record->custype_due_date_penalty }}</td>
                                <td>{{ $record->custype_zone }}</td>
                                <td>
                                    @if($record->custype_previous_data)
                                    <a href="{{ route('get-custype-old-by-id',['id'=>$record->custype_id]) }}"><span class="label label-success">View Prev Data</span></a>
                                    @endif
                                    <a href="#edit_custype" 
                                        data-toggle="modal" 
                                        data-id="{{$record->custype_id}}" >
                                         <span class="label label-primary">Update</span>
                                    </a>
                                    <a href="{{ route('delete-custype',['id'=>$record->custype_id]) }}"><span class="label label-danger">Trash</span></a>
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
@include('modals.modals')
@endsection

@section('additional_script')
<script>
    $(function(){

        $("form#add_custype_form,form#edit_custype_form").each(function(){
            $(this).validate({ 
                rules: {
                    type: {
                        required: true,
                    },
                    min_cubic_meter: {
                        required: true,
                        number: true
                    },
                    cubic_meter_rate: {
                        required: true,
                        number: true
                    },
                    min_peso_rate: {
                        required: true,
                        number: true
                    },
                    due_date_penalty: {
                        required: true,
                        number: true
                    },
                    due_date_duration: {
                        required: true,
                        digits: true
                    },
                    zone: {
                        required: true,
                    },
                },
            });
        });
        // $('#edit_custype').on('click', function(e) {
        $('#edit_custype').on('show.bs.modal', function(e) {

                    // console.log($(e.relatedTarget).data('id'));
            var id = $(e.relatedTarget).data('id');
            
            $.ajax({
                method: 'GET',
                url: "{{ route('get-custype-by-id',['id'=>'']) }}"+'/'+id,
                // dataType: 'json',
                success: function(resp){
                    // console.log(resp);
                    var duration = resp.custype_due_date_duration;
                    if (duration == 'Monthly') {
                        $('input[name="due_date_duration"]').attr('disabled','disabled');
                    }else{
                        $('input[name="duration_checkbox"]').attr('checked','checked');
                    }
                    $('input[name="type"]').val(resp.custype_type);
                    $('input[name="min_cubic_meter"]').val(resp.custype_min_cubic_meter);
                    $('input[name="cubic_meter_rate"]').val(resp.custype_cubic_meter_rate);
                    $('input[name="min_peso_rate"]').val(resp.custype_min_peso_rate);
                    $('input[name="due_date_penalty"]').val(resp.custype_due_date_penalty);
                    $('input[name="due_date_duration"]').val(duration);
                    $('select#zone').val(resp.custype_zone);
                    console.log(resp.custype_zone)
                    $('input[name="id"]').val(resp.custype_id);
                },
                error: function(err){
                    console.log(err);
                }
            });
            
        });

        $('#prev_custype').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('id');
            var custype_type = $(e.relatedTarget).data('type');
            var custype_min_cubic_meter = $(e.relatedTarget).data('min-cubic-meter');
            var custype_cubic_meter_rate = $(e.relatedTarget).data('cubic-meter-rate');
            var custype_min_peso_rate = $(e.relatedTarget).data('min-peso-rate');
            var custype_due_date_penalty = $(e.relatedTarget).data('due-date-penalty');
            var duration = $(e.relatedTarget).data('duration');
            var penalty = $(e.relatedTarget).data('penalty');
            
            $('input[name="type"]').val(custype_type);
            $('input[name="min_cubic_meter"]').val(custype_min_cubic_meter);
            $('input[name="cubic_meter_rate"]').val(custype_cubic_meter_rate);
            $('input[name="min_peso_rate"]').val(custype_min_peso_rate);
            $('input[name="due_date_penalty"]').val(custype_due_date_penalty);
            $('input[name="due_date_duration"]').val(duration);
            $('input[name="due_date_penalty"]').val(penalty);
        });

        $('input[name="duration_checkbox"]').click(function(e) {
            var check_checkbox = $(this).is(':checked');
            if (check_checkbox) {
                $('#duration_radio').css('display','none');
                $('input[name=due_date_duration]').css('display','block');
                $('input[name=due_date_duration]').removeAttr("disabled");         
                $('input[name=duration_radio]').attr('disabled','disabled');         
            }else{
                $('#duration_radio').css('display','block');
                $('input[name=due_date_duration]').css('display','none');
                $('input[name=due_date_duration]').attr('disabled','disabled');         
                $('input[name=duration_radio]').removeAttr("disabled");         
            }
        });
    });
</script>
@endsection