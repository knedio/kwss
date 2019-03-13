@extends('layout.main_layout')

@section('title')
    SMS Page
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                SMS Lists
            </h1>
            <ol class="breadcrumb">
                <li class="">
                    <i class="fa fa-dashboard"></i> Dashboard
                </li>
                <li class="active">
                    SMS Lists
                </li>
            </ol>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_sms">Add SMS</button>
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
                            <th>API Key</th>
                            <th>Device ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($sms_records) > 0)
                        @php $count=1; @endphp
                        {{-- @php printx($sms_records); @endphp --}}
                        @foreach($sms_records as $record)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ mb_strimwidth($record->sms_api_key, 0, 50, "...") }}</td>
                                <td>{{ $record->sms_device_id }}</td>
                                <td>{{ $record->sms_status }}</td>
                                <td>
                                    @if($record->sms_status == 'Not Used')
                                        <a href="{{ route('use-sms',['id'=>$record->sms_id]) }}">
                                            <span class="label label-success">Use</span>
                                        </a>
                                    @endif
                                    <a href="#" 
                                        data-toggle="modal" 
                                        data-target="#edit_sms" 
                                        data-api-key="{{ $record->sms_api_key }}"
                                        data-device-id="{{ $record->sms_device_id }}"
                                        data-id="{{ $record->sms_id }}">
                                        <span class="label label-primary">Update</span>
                                    </a>
                                    <a href="{{ route('delete-sms',['id'=>$record->sms_id]) }}">
                                        <span class="label label-danger">Delete</span>
                                    </a>
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
<div class="modal fade" id="edit_sms" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <form action="{{ route('edit-sms') }}" id="edit_sms_form" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span></span> SMS</h4>
            </div>
            <div class="modal-body">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label for="sms_type">API Key <span class="text-red">*</span>:</label>
                    <textarea class="form-control" name="sms_api_key" rows="4" id="sms_api_key" ></textarea>
                </div>
                <div class="form-group">
                    <label for="">Device ID <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="sms_device_id" id="sms_device_id" placeholder="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <input type="hidden" name="id">
        </form>
    </div>
    
  </div>
</div>
@endsection

@section('additional_script')
<script>
    $(function(){
        $('#edit_sms').on('show.bs.modal', function(e) {
            let id = $(e.relatedTarget).data('id');
            if (id) {
                let api_key = $(e.relatedTarget).data('api-key');
                let device_id = $(e.relatedTarget).data('device-id');
                $('.modal-title span').text('Edit');
                $('#edit_sms textarea[name=sms_api_key]').val(api_key);
                $('#edit_sms input[name=sms_device_id]').val(device_id);
                $('#edit_sms input[name=id]').val(id);
            }else{
                $('.modal-title span').text('Add New');
                $('#edit_sms textarea[name=sms_api_key]').val('');
                $('#edit_sms input[name=sms_device_id]').val('');
                $('#edit_sms input[name=id]').val('');
            }
        });
        $('#edit_sms_form').validate({ 
            rules: {
                sms_api_key: {
                    required: true,
                },
                sms_device_id: {
                    required: true,
                }
            },
        });
    });
</script>
@endsection