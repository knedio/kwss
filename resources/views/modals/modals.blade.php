<!-- Modal for Add Customer Type -->
<div class="modal fade" id="add_custype" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <form action="{{ route('add-custype') }}" id="add_custype_form" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Customer Type</h4>
            </div>
            <div class="modal-body">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label for="custype_type">Customer Type <span class="text-red">*</span>:</label>
                    <input type="text" class="form-control" name="type" id="">
                </div>
                <div class="form-group">
                    <label for="">Min. (&#13221;) Rate <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="min_cubic_meter" id="">
                </div>
                <div class="form-group">
                    <label for="">Multiplied (&#13221;) <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="cubic_meter_rate" id="">
                </div>
                <div class="form-group">
                    <label for="">Min. Peso Rate (₱) <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="min_peso_rate" id="">
                </div>
                <div class="form-group">
                    <label for="">Due Date Duration : <input type="checkbox" name="duration_checkbox" id="duration_checkbox"> <small>Note: Tick to input day of due date.</small></label>
                    <div id="duration_radio">
                        {{-- <div class="radio">
                            <label>
                                <input type="radio" name="duration_radio" id="" value="Monthly" checked>
                                Monthly
                            </label>
                        </div> --}}
                        <div class="radio">
                            <label>
                                <input type="radio" name="duration_radio" id="" value="By Zone">
                                By Zone
                            </label>
                        </div>
                    </div>
                    <input type="number" class="form-control" disabled name="due_date_duration" id="" style="display:none;">
                </div>
                <div class="form-group">
                    <label for="">Due Date Penalty <span class="text-red">*</span>:</label>
                    <input type="number" class="form-control" name="due_date_penalty" id="">
                </div>
                <div class="form-group">
                    <label for="zone">Zone <span class="text-red">*</span>:</label>
                    <select class="form-control" id="zone" name="zone">
                        <option value="" disabled selected>-- Select Zone --</option>
                        @for($i=1; $i <= 15; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    
  </div>
</div>

<!-- Modal for Edit Customer Type -->
<div class="modal fade" id="edit_custype" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
        <form action="{{ route('edit-custype') }}" id="edit_custype_form" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Customer Type</h4>
            </div>
            <div class="modal-body">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label for="custype_type">Customer Type <span class="text-red">*</span>:</label>
                    <input type="text" class="form-control" name="type" id="">
                </div>
                <div class="form-group">
                    <label for="">Min. (&#13221;) Rate <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="min_cubic_meter" id="min_cubic_meter">
                </div>
                <div class="form-group">
                    <label for="">Multiplied (&#13221;) <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="cubic_meter_rate" id="cubic_meter_rate">
                </div>
                <div class="form-group">
                    <label for="">Min. Peso Rate (₱) <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" name="min_peso_rate" id="min_peso_rate">
                </div>
                <div class="form-group">
                    <label for="">Due Date Duration : <input type="checkbox" name="duration_checkbox" id="duration_checkbox"> <small>Note: Tick to input day of due date.</small></label>
                    <div id="duration_radio">
                        {{-- <div class="radio">
                            <label>
                                <input type="radio" name="duration_radio" id="" value="Monthly" checked>
                                Monthly
                            </label>
                        </div> --}}
                        <div class="radio">
                            <label>
                                <input type="radio" name="duration_radio" id="" value="By Zone">
                                By Zone
                            </label>
                        </div>
                    </div>
                    <input type="number" class="form-control" disabled name="due_date_duration" id="" style="display:none;">
                </div>
                <div class="form-group">
                    <label for="">Due Date Penalty <span class="text-red">*</span>:</label>
                    <input type="number" class="form-control" name="due_date_penalty" id="">
                </div>
                <div class="form-group">
                    <label for="zone">Zone <span class="text-red">*</span>:</label>
                    <select class="form-control" id="zone" name="zone">
                        <option value="" disabled>-- Select Zone --</option>
                        @for($i=1; $i <= 15; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    
  </div>
</div>

<div class="modal fade" id="prev_custype" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <form action="" id="edit_custype_form" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Previous Data</h4>
            </div>
            <div class="modal-body">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                    <label for="custype_type">Customer Type :</label>
                    <input type="text" class="form-control" disabled name="type" id=""               </div>
                <div class="form-group">
                    <label for="">Min. (&#13221;) Rate :</label>
                    <input type="number" step="any" class="form-control" disabled name="min_cubic_meter" id=""               </div>
                <div class="form-group">
                    <label for="">Multiplied (&#13221;) :</label>
                    <input type="number" step="any" class="form-control" disabled name="cubic_meter_rate" id=""               </div>
                <div class="form-group">
                    <label for="">Min. Peso Rate (₱) :</label>
                    <input type="number" step="any" class="form-control" disabled name="min_peso_rate" id=""               </div>
                <div class="form-group">
                    <label for="">Due Date Duration :</label>
                    <input type="text" class="form-control" disabled name="due_date_duration" id=""               </div>
                <div class="form-group">
                    <label for="">Due Date Penalty :</label>
                    <input type="number" step="any" class="form-control" disabled name="due_date_penalty" id=""               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
    
  </div>
</div>

<!-- Modal for Add User -->
<div class="modal fade" id="add_user" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <form action="" id="add_user_form" method="POST">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New User</h4>
            </div>
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
                    <input type="text" class="form-control" name="firstname" id="" value="">
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name <span class="text-red">*</span>:</label>
                    <input type="text" class="form-control" name="lastname" id="" value="">
                </div>
                <div class="form-group">
                    <label for="mobile_number">Mobile Number :</label>
                    <div class="input-group">
                        <div class="input-group-addon">+63</div>
                        <input type="text" class="form-control" name="mobile_number" oninput="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="10" id="mobile_number" value="" 9123456789">
                    </div>
                    <div id="mobile_number_error"></div>
                </div>
                <div class="form-group">
                    <label for="address">Address <span class="text-red">*</span>:</label>
                    <input type="text" class="form-control" name="address" id="address" value="">
                </div>
                <div id="additional_input" style="display:none;">
                    @if(!empty($custype_records))
                    <div class="form-group">
                        <label for="zone">Zone <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="zone" id="" value="">
                    </div>
                    <div class="form-group">
                        <label for="custype_id">Customer Type <span class="text-red">*</span>:</label>
                        <select class="form-control" id="customer_type" disabled name="custype_id">
                            <option value="" disabled selected>-- Select Customer Type --</option>
                            @foreach($custype_records as $record)
                                <option value="{{ $record->custype_id }}">{{ $record->custype_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="meter_model">Meter Model <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="meter_model" id="meter_model" disabled value="">
                    </div>
                    <div class="form-group">
                        <label for="meter_duedate">Due Date <span class="text-red">*</span>:</label>
                        <input type="date" class="form-control" name="meter_duedate" id="meter_duedate" disabled value="">
                    </div>
                    <div class="form-group">
                        <label for="meter_address">Meter Address <span class="text-red">*</span>:</label>
                        <input type="text" class="form-control" name="meter_address" id="meter_address" disabled value="">
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    
  </div>
</div>


<!-- Modal for Add Payment -->
<div class="modal fade" id="add_payment" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <form action="{{ route('add-payment') }}" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Payment</h4>
            </div>
            <div class="modal-body">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <input type="hidden" name="pay_id" value="">
                <input type="hidden" name="cus_id" value="">
                <input type="hidden" name="reading_id" value="">
                <input type="hidden" name="trans_id" value="">
                <input type="hidden" name="reading_waterconsumed" value="">
                <input type="hidden" name="arrears" value="0">
                <div class="form-group">
                    <label for="">Received By :</label>
                    <input type="text" class="form-control" name="emp_id" value="{{ session('lastname').', '.session('firstname') }}" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Customer :</label>
                    <input type="text" class="form-control" name="customer_name" value="" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Water Consumed :</label>
                    <input type="text" class="form-control" name="water_consumed" value="" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Reading Payment Amount (₱) :</label>
                    <input type="text" class="form-control" name="reading_amount" value="" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Other Payment Amount (₱) :</label>
                    <input type="text" class="form-control" name="other_payment" value="" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Penalty Amount (₱) :</label>
                    <input type="text" class="form-control" name="penalty_amount" value="" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Partial Payment (₱) :</label>
                    <input type="text" class="form-control" name="partial_payment" value="" id="" readonly>
                </div>
                <div class="form-group">
                    <label for="">Total Amount to Pay (₱) :</label>
                    <input type="text" class="form-control" name="amount_pay" value="" id="" readonly>
                </div>
                <div id="unfinished_payment_info">
                    <div class="form-group">
                        <label for="">Payments: <br /><small>Note: Click the checkbox if you want to pay it also.</small></label>
                        <div class="checkbox" style="margin-top: 0px;">

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="payment">Payment Amount (₱) <span class="text-red">*</span>:</label>
                    <input type="number" step="any" class="form-control" required name="payment" step=".01" id="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
  </div>
</div>

