@extends('layouts.master')
@section('content')
<style>
body { padding-right: 0 !important }
</style>

@php
$sch_status_list = get_schedule_status_list();
$staff_sch_status_list = get_staff_schedule_status_list();
$sms_status_list = get_sms_status_list();
@endphp

<section class="content-header">
    <h1>
        Schedule
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('event')}}">Events</a></li>
        <li>Edit schedule</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box collapsed-box-off collapsed-box">
                <div class="box-header">
                    <h3 class="box-title">Edit Schedule
                        <span class="label m-l-20 {{ get_label_class_by_key('confirmed') }} "> Confirmed (
                            {{$staff_status['total_confirmed']}} ) </span>
                        <span class="label m-l-20 {{ get_label_class_by_key('pending') }} "> Pending (
                            {{$staff_status['total_pending']}} ) </span>
                        <span class="label m-l-20 {{ get_label_class_by_key('dropout') }} "> Dropout (
                            {{$staff_status['total_dropout']}} ) </span>
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="display:none">
                    <form role="form" id="edit_schedule_form" action="{{route('event_update',$schedule_data['id'])}}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <!--  BEGIN Form-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div clas="row">
                                        <div class="col-md-6 p-l-0">
                                            <div class="form-group">
                                                <label for="Company">To </label>
                                                <input type="text" class="form-control" readonly name="schedule_to"
                                                    value="{{$schedule_data['schedule_to']}}" />
                                            </div>


                                        </div>


                                        <div class="col-md-6 p-l-0">
                                            <div class="form-group">
                                                <label for="Company">From </label>
                                                <input type="text" class="form-control" readonly name="schedule_from"
                                                    value="{{$schedule_data['schedule_from']}}" />
                                            </div>

                                        </div>
                                        <!--- /.row -->
                                    </div>
                                    <!--- /.col-md-6 -->
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Company">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">Select</option>
                                            @if($sch_status_list)
                                            @foreach($sch_status_list as $key => $arr)
                                            <option value="{{$key}}"
                                                {{ ($schedule_data['status'] == $key ) ? 'selected' : '' }}>{{$arr}}
                                            </option>
                                            @endforeach
                                            @endIf
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div clas="row">
                                        <div class="col-md-4 p-l-0">

                                            <div class="form-group">
                                                <label for="Company">Client</label>
                                                <select class="form-control" id="client_id" name="client_id"
                                                    onChange="getClientData(this.value)">
                                                    <option value="">Select</option>
                                                    @if($client)
                                                    @foreach($client as $obj)
                                                    <option value="{{ $obj->id }}"
                                                        {{ ($obj->id == $schedule_data['client_id']) ? 'selected' : '' }}>
                                                        {{ $obj->property_name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-4 p-r-0">

                                            <div class="form-group">
                                                <label for="Company">Contact Person</label>
                                                <input type="text" class="form-control alphabets_only"
                                                    value="{{$schedule_data['contact_person']}}"
                                                    id="client_contact_person" name="contact_person" />
                                            </div>
                                        </div>

                                        <div class="col-md-4 p-r-0">

                                            <div class="form-group">
                                                <label for="Company">Contact No.</label>
                                                <input type="text" class="form-control number_only"
                                                    id="client_contact_no" name="contact_no"
                                                    value="{{$schedule_data['contact_no']}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div clas="row">
                                        <div class="col-md-6 p-l-0">
                                            <div class="form-group">
                                                <label for="Company">Event Name</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $schedule_data['event_name'] }}" id="event_name"
                                                    name="event_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 p-r-0">
                                            <div class="form-group">
                                                <label for="Company">Event type</label>
                                                <input type="text" class="form-control" id="event_type"
                                                    name="event_type" value="{{ $schedule_data['event_type'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Date picker -->
                                <div class="col-md-6">
                                    <div class="bootstrap-timepicker row">
                                        <label class="col-md-12 d-block">Event Date </label>
                                        <div class="form-group col-md-6">
                                            <small><strong>Start : </strong></small>
                                            <div class="input-group">
                                                <input type="text" readonly
                                                    value="{{ (!empty($schedule_data['start_date']) && ($schedule_data['start_date'] != '0000-00-00')) ?  date('m/d/Y',strtotime($schedule_data['start_date'])) : '' }}"
                                                    name="start_date" id="event_start_date"
                                                    class="form-control datepicker start_date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <small><strong>End : </strong></small>
                                            <div class="input-group">
                                                <input type="text" readonly
                                                    value="{{ (!empty($schedule_data['end_date']) && ($schedule_data['end_date'] != '0000-00-00')) ?  date('m/d/Y',strtotime($schedule_data['end_date'])) : '' }}"
                                                    name="end_date" class="form-control datepicker end_date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- time Picker -->
                                <div class="col-md-6">
                                    <div class="bootstrap-timepicker row">
                                        <label class="col-md-12 d-block">Event Timing </label>
                                        <div class="form-group col-md-6">
                                            <small><strong>From : </strong></small>
                                            <div class="input-group">
                                                <input type="text" readonly value="{{$schedule_data['start_time']}}"
                                                    id="event_start_time" name="start_time"
                                                    class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <small><strong>To : </strong></small>
                                            <div class="input-group">
                                                <input type="text" readonly value="{{$schedule_data['end_time']}}"
                                                    name="end_time" class="form-control timepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.form group -->
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="">Security Required </label>
                                        <input type="number" class="form-control" name="total_staff"
                                            value="{{$schedule_data['total_staff']}}" min="1" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1 ">Location</label>

                                        <div class="input-group" style="width: 100%;">
                                            <input class="form-control" name="location" id="keyword" data-type="default"
                                                data-element="text" placeholder="Enter Venue Location" type="text"
                                                autocomplete="off" value="{{$schedule_data['location']}}">
                                        </div>
                                        <div class="input-group typeahead-dropdown" id="keyword_result_dropdown"
                                            style="margin-bottom: 0px;display:none;">
                                            <ul class="typeahead-dropdown-menu"
                                                style="max-height: 270px !important;overflow-y: scroll;">

                                            </ul>
                                        </div>
                                    </div>



                                    <div class="form-group hide">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" name="location-off" />
                                        <input type="text" class="form-control" id="event_id" value="{{ $id }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 ">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-info">Update</button>
                                    </div>
                                </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.box -->


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Add Staff Schedule </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="heading bold text-green underline">{{ $schedule_data['event_name'] }} </h4>
                </div>
                <div class="col-md-4 pull-right mb-3 text-left">
                    @if($staff_types)
                    @foreach($staff_types as $index =>$st)

                    <label class="control-label dp-inlines staff_type_labels  {{ ($st->id == 1) ? 'active' : '' }} "
                        id="staff_type_label-{{$loop->iteration}}">
                        <input type="radio" {{ ($st->id == 1) ? "checked" : "" }} data-typeid="{{$st->id}}"
                            id="staff_type_{{$st->id}}" name="staff_type_radio" class=" staff_type_radio">
                        {{$st->type}}</label>
                    @endforeach
                    @endif
                </div>
            </div>



            <div class="row">

                <div class="col-md-4 col-md-offset-0">
                    <div class="col-md-12 customlist">
                        <ul id="staffList_filled" class="users_multi_listbox">
                        </ul>
                    </div>
                </div>

                <div class="col-md-4 " style="text-align: center;">
                    <a class="btn btn-danger disabled confrimStaffScheduleRemove"
                        data-target="#RemoveStaffSchConfirmation" id="staffList_filled-move"><i
                            class="fa fa-arrow-right ml-1"> </i> Remove</a>
                    <a class="btn btn-warning hide " data-toggle="modal" data-target="#SendSMSPopup"
                        id="send_sms_btn"><i class="fa fa-whatsapp mr-1"> </i>Send SMS </a>
                    -
                    <a class="btn btn-success staffList_new-move" onclick="updateStaff();" id="add_staff_btn"
                        class=""><i class="fa fa-arrow-left mr-1"> </i>ADD </a>
                    <div class="col-md-12 customlist">

                        <ul id="temp_staff_list" class="users_multi_listbox">
                        </ul>
                    </div>
                </div>


                <div class="col-md-4">
                    <label>Search </label>
                    <input type="text" class="form-control" id="search" placeholder="Search by name & phone-number">

                    <div class="col-md-12 customlist">

                        <ul id="staffList_new" class="users_multi_listbox">
                        </ul>
                    </div>
                </div>

            </div>





        </div>
        <!-- /.box-body -->


    </div>
    <!-- /.BOX -->




    <div class="box">
        <div class="box-header">
            <h3 class="box-title"> Staff Schedule Details </h3>
            <div class="box-tools pull-right">
            <a class="btn btn-warning" href="{{route('update_message',$id)}}">Update Messages status</a>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">


            <form role="form" id="add_staff_schedule_form" action="{{ route('event_staff_schedule_update',$id)}}"
                onSubmit="return CheckValidation();" method="POST">
                <div class="row">
                    <div class="col-md-12 table-responsive" style="">
                        @csrf
                        <table id="staff_schedule_table"
                            class="table table-bordered table-striped-off table-responsive fixed_header ">
                            <thead class="bg-grey">
                                <tr>
                                    <th style=""><input type="checkbox" id="" class="selectAll big_checkbox" name="" />
                                    </th>
                                    <th style="width:15%">Staff Name</th>
                                    <th>SIRA NO.</th>
                                    <th>Cont. No.</th>
                                    <th style="width:8%">Start Time</th>
                                    <th style="width:8%">End Time</th>
                                    <th style="width:8%">Hours</th>
                                    <th style="width:10%">Rate per hour</th>
                                    <th style="width:13%">Staff Status</th>
                                    <th style="width:12%">SMS Status</th>
                                    <th style="width:12%">Message Response</th>
                                    <th style="width:13%"> </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(sizeof($staff_schedule_details) > 0)
                                @foreach($staff_schedule_details as $index =>$obj)
									@if($obj->staff)
                                <tr id="row-{{$obj->staff->id}}"
                                    class="{{ ($obj->sms_status == 'not_sent') ?  'enable_sms' : 'disable_sms' }} {{ ($obj->status == 'dropout' ) ? 'row_disabled' : ''}} {{ ($obj->is_payroll_active == 1) ? 'row_payroll':'' }}">

                                    <td style=""><input type="checkbox" id="staff_checkbox-{{$obj->staff->id}}"
                                            class="staff_sch_check big_checkbox" name=""
                                            data-contact="{{ $obj->staff->contact_number }} "
                                            data-staff_id="{{ $obj->staff_id }}" /></td>

                                    <td class="staff_image_temp"><img src="{{img($obj->staff->picture)}}"
                                            class="img-circle user_image" /> <span class="username">
                                            {{ $obj->staff->name }} </span> </td>
                                    <td class="">{{ $obj->staff->sira_id_number }}</td>
                                    <td class="staff_contact_no td_contact_no {{ ($obj->sms_status == 'not_sent') ?  'enable_sms' : 'disable_sms' }}"
                                        data-contact="{{ $obj->staff->contact_number }} "
                                        data-staff_id="{{ $obj->staff_id }}">{{ $obj->staff->contact_number }}</td>
                                    <td class=""><input type="" readonly
                                            onChange="calculateStaffHours({{$obj->staff->id}})"
                                            class="form-control timepicker start_time_input"
                                            id="start_time_input-{{$obj->staff->id}}"
                                            name="array_staff[{{$index}}][start_time]" data-name="start_time"
                                            value="{{ $obj->start_time }}" data-validation='true' /></td>
                                    <td class=""><input type="" readonly
                                            onChange="calculateStaffHours({{$obj->staff->id}})"
                                            class="form-control timepicker end_time_input"
                                            id="end_time_input-{{$obj->staff->id}}"
                                            name="array_staff[{{$index}}][end_time]" data-name="end_time"
                                            value="{{ $obj->end_time }}" data-validation='true' /></td>

                                    @php

                                    $start = \Carbon\Carbon::parse($obj->start_time);
                                    $end = \Carbon\Carbon::parse($obj->end_time);
                                    $minutes = $end->diffInMinutes($start);
                                    $value = floor($minutes / 60).':'.($minutes - floor($minutes / 60) * 60);
                                    $parts = explode(':', $value);
                                    $hours = $parts[0] + floor(($parts[1]/60)*100) / 100 . PHP_EOL;

                                    @endphp

                                    <td class=""><input type="" class="form-control"
                                            id="staff_hours-{{$obj->staff->id}}" name="array_staff[{{$index}}][hours]"
                                            data-name="hours" data-name="availability" value="{{ $hours }}" readonly
                                            data-validation='true' /></td>

                                    <td class="">
                                        <input type="hidden" class="form-control" id="staff_id-{{$obj->staff->id}}"
                                            name="array_staff[{{$index}}][staff_id]" value="{{ $obj->staff->id }}"
                                            data-name="staff_id" data-validation='true' readonly />

                                        <input type="hidden" class="form-control" id=""
                                            name="array_staff[{{$index}}][event_id]" value="{{ $obj->event_id }}"
                                            readonly />

                                        <input type="" class="form-control number_only" id="rph-{{$obj->staff->id}}"
                                            name="array_staff[{{$index}}][rate_per_hour]"
                                            value="{{ $obj->rate_per_hour }}" data-name="rate_per_hour"
                                            data-validation='true'
                                            {{ ($obj->availability == 0) ? 'readonly="readonly"' : '' }} />
                                    </td>
                                    <td class="" id="td_staff_status-{{$obj->staff->id}}">
                                        <select class="form-control staff_status_select"
                                            id="staff_status-{{$obj->staff->id}}" name="array_staff[{{$index}}][status]"
                                            data-name="status" data-validation='true'
                                            onChange="CheckTotalConfirmation()">
                                            <option value="" disabled>Select</option>
                                            @if($staff_sch_status_list)
                                            @foreach($staff_sch_status_list as $key => $arr)
                                            <option value="{{$key}}" {{ ($obj->status == $key ) ? "selected" : "" }}>
                                                {{$arr}}</option>
                                            @endforeach
                                            @endIf
                                        </select>
                                    </td>
                                    <td class="">
                                        <input type="hidden" readonly
                                            value="{{ ($obj->sms_status) ? $obj->sms_status : 'not_sent' }}"
                                            id="sms_status-{{$obj->staff->id}}"
                                            name="array_staff[{{$index}}][sms_status]" data-name="sms_status"
                                            data-validation="false" class="form-control pull-left" style="width:70%"
                                            value="" />

                                        <span
                                            class="bold label_sms_status label {{ get_label_class_by_key($obj->sms_status) }}">
                                            {{   get_status_name_by_key ($obj->sms_status,'sms')}} </span>

                                        @if($obj->sms_status == "not_sent")
                                        @if($obj->status != "dropout")
                                        <button type="button" data-toggle="modal" data-staff_id="{{$obj->staff->id}}"
                                            data-contact="{{ $obj->staff->contact_number }}" data-target=""
                                            data-staff_name="{{ $obj->staff->name}}"
                                            id="sms_status_btn-{{$obj->staff->id}}"
                                            class="staff_sms_btn btn btn-sm btn-warning pull-right"
                                            id="staff_sms_btn-{{$obj->staff->id}}"
                                            onclick="initializeSmsTrigger(this.id)"><i class="fa fa-whatsapp" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($obj->wa_response))
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#WA_Response-{{$obj->staff->id}}">
                                            <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                        </button>

                                        <div class="modal fade" id="WA_Response-{{$obj->staff->id}}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        Message : <span
                                                            class="bold">{{ (!empty($obj->wa_response)) ? $obj->wa_response : '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> @endif
                                    </td>
                                    <td class="">
                                        <select id="" class="form-control allow_check" data-id="{{$obj->staff->id}}"
                                            data-validation='true' id="availability-{{$obj->staff->id}}"
                                            name="array_staff[{{$index}}][availability]">
                                            <option value="1" {{ ( ($obj->availability == 1) ) ? "selected" : ""}}>
                                                shown </option>
                                            <option value="0" {{ ( ($obj->availability == 0) ) ? "selected" : ""}}> not
                                                shown</option>
                                        </select>
                                        <label class="availability_label hide"> <input type="checkbox"
                                                {{ ($obj->availability) ? 'checked' : 0 }} class="allow_check-off"
                                                data-validation='off' /></label>
                                    </td>

                                </tr>
								  @endif
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="10" align="center" class="text-muted"> No Data Found</td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" value="1" name="submit_staff_schedule"
                            class=" {{ (sizeof($staff_schedule_details) > 0 ) ? '' : 'hide'}} btn btn-info mt-3  add_staff_schedule_btn">Update
                            Staff Schedule</button>

                        <button type="button" value="1" name="send_payroll" id="send_payroll"
                            onClick="confirm_password('add_staff_schedule_form')"
                            class=" send_payroll_btn {{ ($confirmedStaff > 0 ) ? '' : 'hide'}} btn btn-success mt-3"> <i
                                class="fa fa-money m-r-10"></i>Send Payroll</button>

                        <button type="button" value="1" name="send_bulk_sms" onClick="openBulkSmsPopup();"
                            class=" {{ ($sms_not_sent > 0 ) ? '' : ''}} btn btn-warning mt-3"> <i
                                class="fa fa-whatsapp m-r-10"></i>Send Bulk SMS</button>
                    </div>
                </div>

            </form>
            <!-- /.row -->



        </div>
        <!-- /.box-body -->


    </div>
    <!-- /.BOX -->

</section>
<!-- /.content -->
@endsection
@section('content_js')
<script>
function calculateStaffHours(id) {
    var start = moment.utc($('#start_time_input-' + id).val(), "HH:mm");
    var end = moment.utc($('#end_time_input-' + id).val(), "HH:mm");
    var hour = moment.utc(end.diff(start)).format("HH");
    var minute = moment.utc(end.diff(start)).format("mm");
    var duration = parseFloat(minute / 60) + parseFloat(hour) || 0;

    $('#staff_hours-' + id).val(duration.toFixed(2));


}


function RemoveSelectedStaffFromList() {
    $('#staffList_filled li.selected').each(function(index, value) {
        var data_id = $(this).children('img').attr('data-id');
        var data_staffid = $(this).children('img').attr('data-staffid');
        $('#staff_schedule_table tbody tr#row-' + data_staffid).remove();
    });


    if ($("#staff_schedule_table tbody tr").length == 0) {
        $(".add_staff_schedule_btn").addClass('hide');
    }



    $('#staffList_filled').jqListbox('transferSelectedTo', $('#staffList_new'));
    iziToast.show({
        title: 'Removed',
        color: 'red', // blue, red, green, yellow
        message: 'Staff Removed From Schedule!!!'
    });



    return false;
}

// Remove Staff Schedule
function RemoveStaffchedule() {
    var formData = $("#removeStaffScheduleForm").serialize();
    var myJsonData = {
        formData: formData
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('/DeleteStaffSchedule/{{$id}}', myJsonData, function(response) {
        var obj = JSON.parse(response);
        $(".response_message").text(obj.message);

        if (obj.status) {
            setTimeout(function() {
                location.reload(true);
            }, 850);


            RemoveSelectedStaffFromList();
            $(".response_message").addClass('text-green');
            $(".response_message").removeClass('text-red');

        } else {

            $(".response_message").addClass('text-red');
            $(".response_message").removeClass('text-green');

        }

    });

}

// GET DATA OF CLIENT BY ID //
function getClientData(id) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.get('/get_client_data_json/' + id, function(response) {
        var obj = JSON.parse(response);
        if (obj != null || obj != "") {
            $("#client_contact_person").val(obj.venue_manager_name);
            $("#client_contact_no").val(obj.venue_manager_number);
        } else {
            // empty case
        }

    });
}

$(document).on('submit','#add_staff_schedule_form',function(event){
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:"POST",
        url:$(this).attr('action'),
        data:$(this).serialize(),
        success:function(response)
        {
            if(response.status===true)
            {
                iziToast.show({
                    title: 'Success',
                    color: 'green', // blue, red, green, yellow
                    message: 'Staff added to Schedule successfully!'
                });

            }else
            {
                iziToast.show({
                    title: 'Error',
                    color: 'red', // blue, red, green, yellow
                    message: response.message
                });
            }
            setTimeout(()=>{
                location.reload();
            },1500);
        },
        error:function(error)
        {
                iziToast.show({
                    title: 'Error',
                    color: 'red', // blue, red, green, yellow
                    message: 'Something Went Wrong'
                });
        }
    });
})

// jQUERY INITIALIZE
$(function() {



    $(".selectAll").change(function() {
        $('input.staff_sch_check:checkbox').not(this).prop('checked', this.checked);
        getTotalActiveCheckBoxes();
    });



    function allowPaymentCheck() {
        $(".allow_check-off").click(function(ev) {
            var id = $(this).attr('data-id');

            if ($(this).prop('checked') == true) {
                $("#rph-" + id).removeAttr('readonly');
            } else {
                $("#rph-" + id).attr('readonly', 'readonly');
                $("#rph-" + id).val(0);
            }
        });

        $(".allow_check").change(function(ev) {
            var id = $(this).attr('data-id');

            if ($(this).val() == true) {
                $("#rph-" + id).removeAttr('readonly');
            } else {
                $("#rph-" + id).attr('readonly', 'readonly');
                // $("#rph-"+id).val();
            }
        });

    }
    // vALIDATOR
    $('#edit_schedule_form').bootstrapValidator({

        message: 'This value is not valid',
        feedbackIcons: {
            valid: '',
            invalid: '',
            validating: 'glyphicon glyphicon-refresh2'
        },
        fields: {
            client_id: {
                validators: {
                    notEmpty: {
                        message: 'Select Client from list'
                    }
                }
            },
            event_name: {
                validators: {
                    notEmpty: {
                        message: 'Event Name is required'
                    }
                }
            },
            event_type: {
                validators: {
                    notEmpty: {
                        message: 'Event Type is required'
                    }
                }
            },
            total_staff: {
                validators: {
                    notEmpty: {
                        message: 'Minimum Staff Required'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'Enter total no. staff in digits'
                    }
                }
            },
            start_time: {
                validators: {
                    notEmpty: {
                        message: 'End Date is required'
                    }
                }
            },
            end_time: {
                validators: {
                    notEmpty: {
                        message: 'End Date is required'
                    }
                }
            },
            location: {
                validators: {
                    notEmpty: {
                        message: 'Location is required'
                    }
                }
            }
        }
    });


    $('.number_only').keyup(function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $('.alphabets_only').keyup(function() {
        this.value = this.value.replace(/[^a-zA-Z \.]/g, '');
    });


    loadTimePicker();
    allowPaymentCheck();
    // custom list box
    var staffList = $('#staffList_new').html();


    $('#staffList_new').jqListbox({
        targetInput: false,
        //selectedClass: 'selected-item',
        initialValues: [
            @if($remainingStaff)
            @foreach($remainingStaff as $obj)
            '<li data-pos="{{$loop->iteration}}" data-move="true" data-id="" data-view="{{ ($obj->stafftypes->id == 1) ? "on" : "off" }}" data-stafftype="{{$obj->stafftypes->id}}" data-exist="false" data-dropout="false" class="abbc"><span class="li_position_no bold">   </span>   <img src="{{img($obj->picture)}}" data-exist ="false" class="custom staff_list_view img-circle" data-id="{{$loop->iteration}}"  data-sira="{{$obj->sira_id_number}}" data-staffid="{{ $obj->id}}" data-contact="{{$obj->contact_number}}" data-start_time="" data-end_time="" data-rph="0" data-hours="" data-status="pending" data-sms_status="not_sent"  data-label_sms_status="{{ ($obj->sms_status ) ? "Not Sent" : "Not Sent" }}" data-availability="0" /> <span class="list_staff_name ">{{$obj->name}} </span>   <span class="list_staff_contact_no"> {{$obj->contact_number}} </span> </li>',
            @endforeach
            @endif
        ],
        onBeforeRender: function(listbox) {
            if (listbox.countSelected() == 0) {
                $('#example4-copy,.staffList_new-move,#send_sms_btn').addClass('disabled');
            } else {
                $('#example4-copy,.staffList_new-move').removeClass('disabled');
            }
        },
        onAfterRender: function(listbox) {
            // console.log(listbox);
            var active_staff_type_id = $("input[type='radio']:checked").attr('id');
            $("input#" + active_staff_type_id + "[type='radio']").trigger('change');
            //Code should be here
            //$('ul#temp_staff_list li').parent().prepend($('ul#staffList_new li.selected'));
            var html = '';
            [].forEach.call(document.querySelectorAll('ul#staffList_new li.selected'), function(
                item) {
                return html += '<li>' + item.innerHTML + '</li>';
            })
            console.log(html);
            $('ul#temp_staff_list').empty();
            $('ul#temp_staff_list').append(html);
        }
    });



    $('#staffList_filled').jqListbox({
        targetInput: false,
        initialValues: [
            @if($staff_schedule_details)
				@foreach($staff_schedule_details as $obj)
					@if($obj->staff)
						'<li data-pos="{{$loop->iteration}}" data-id="" data-view="on" data-stafftype="{{$obj->staff->stafftypes->id}}" data-exist="true" data-dropout="{{ ($obj->status == "dropout") ? "true" : "false" }}"><span class="li_position_no bold">{{$loop->iteration}} - </span>  <img src="{{img($obj->staff->picture)}}" data-exist ="true" class="custom staff_list_view img-circle" data-id="{{$loop->iteration}}"  data-sira="{{$obj->staff->sira_id_number}}" data-staffid="{{ $obj->staff->id}}" data-contact="{{$obj->staff->contact_number}}" data-start_time="{{$obj->start_time}}" data-end_time="{{$obj->end_time}}" data-hours="{{$obj->hours}}" data-rph="{{$obj->rate_per_hour}}" data-status="{{ ($obj->status) ? $obj->status : "pending" }}" data-sms_status="{{   ($obj->sms_status ) ? $obj->sms_status : "not_sent" }}" data-label_sms_status="{{ ($obj->sms_status ) ? get_status_name_by_key($obj->sms_status,"sms") : "Not Sent" }}" data-availability="{{ $obj->availability }}" /> <span class="list_staff_name "> {{$obj->staff->name}} </span> - <span class="list_staff_contact_no"> {{$obj->staff->contact_number}} </span></li>',
					@endif
				@endforeach
            @endif

        ],
        onBeforeRender: function(listbox) {
            if (listbox.countSelected() == 0) {
                $('#staffList_filled-copy,#staffList_filled-move').addClass('disabled');
            } else {
                $('#staffList_filled-copy,#staffList_filled-move,#send_sms_btn').removeClass(
                    'disabled');
            }
        },
        onAfterRender: function(listbox) {
            GenerateIndexing();
            //$('ul#staffList_new li.selected').parent().prepend($('ul#temp_staff_list li.selected'));
        }
    });



    $('.staffList_new-move').click(function(e) {
		 e.preventDefault();

			$('.wrapper').addClass('background-blur');
            $('#loader').css('display','block');
        $(".add_staff_schedule_btn").removeClass('hide');
        $('#staffList_new').jqListbox('transferSelectedTo', $('#staffList_filled'));
        $("#staff_schedule_table tbody").empty();
        $('#staffList_filled li').each(function(index, value) {
            var row_no = index;

            var data_id = $(this).children('img').attr('data-id');
            var data_staffid = $(this).children('img').attr('data-staffid');
            var data_image = $(this).children('img').attr('src');
            var data_sira = $(this).children('img').attr('data-sira');
            var data_contact = $(this).children('img').attr('data-contact');
            var data_rph = $(this).children('img').attr('data-rph');
            var data_hours = $(this).children('img').attr('data-hours');
            var data_start_time = $(this).children('img').attr('data-start_time');
            var data_end_time = $(this).children('img').attr('data-end_time');
            var data_status = $(this).children('img').attr('data-status');
            var data_sms_status = $(this).children('img').attr('data-sms_status');
            var label_sms_status = $(this).children('img').attr('data-label_sms_status');
            var availability = $(this).children('img').attr('data-availability');
            var text = $(this).find('span.list_staff_name ').text();
            var data_dropout = $(this).attr('data-dropout');
            var staff_type_id = $(this).attr('data-stafftype');

            var schedule_table_start_time = $(
                "form#edit_schedule_form input[name='start_time']").val();
            var schedule_table_end_time = $("form#edit_schedule_form input[name='end_time']")
                .val();


            // Bulk Action Checkbox
            var bulk_checkbox = '<input type="checkbox" id="staff_checkbox-' + data_staffid +
                '" class="staff_sch_check big_checkbox" name="" data-contact="' + data_contact +
                '" data-staff_id="' + data_staffid + '" autocomplete="off">';



            if (data_start_time === "" || data_start_time === null || data_start_time ===
                "0:00") {
                data_start_time = schedule_table_start_time;
            }

            if (data_end_time === "" || data_end_time === null || data_end_time === "0:00") {
                data_end_time = schedule_table_end_time;
            }

            var staff_status_opt = '<select id="staff_status-' + data_staffid +
                '" class="form-control staff_status_select" onClick="changeSmsStatus(this.id);" name="array_staff[' +
                row_no +
                '][status]"  data-name="status"  data-validation="true" onChange="CheckTotalConfirmation()">';
            staff_status_opt += '<option value="" disabled>Select</option>';
            @foreach($staff_sch_status_list as $key => $arr)
            staff_status_opt += '<option value="{{$key}}" >{{$arr}}</option>';
            @endforeach
            staff_status_opt += '</select>';

            sms_status_opt = '<input type="hidden" readonly   value="' + data_sms_status +
                '" class="fff form-control pull-left"  id="sms_status-' + data_staffid +
                '" name="array_staff[' + row_no +
                '][sms_status]"  data-name="sms_status"  data-validation="false"  style="width:70%" /> ';

            var bg_class = null;
            if (data_sms_status == 'pending') {
                bg_class = 'bg-yellow';
            } else if (data_sms_status == 'confirmed') {
                bg_class = 'bg-green';
            } else if (data_sms_status == 'declined') {
                bg_class = 'bg-danger';
            } else {
                bg_class = 'bg-blue';
            }

            sms_status_opt += '<span class="bold label_sms_status label ' + bg_class + '">' +
                label_sms_status + '   </span>';

            if (data_sms_status == "not_sent") {
                sms_status_opt +=
                    '<button type="button" data-toggle="modal" data-target="" data-staff_id="' +
                    data_staffid + '"  data-contact="' + data_contact + '" data-staff_name="' +
                    text + '" id="sms_status_btn-' + data_staffid +
                    '" class="staff_sms_btn btn btn-sm btn-warning pull-right"  onclick="initializeSmsTrigger(this.id)"><i class="fa fa-whatsapp" aria-hidden="true"></i></button>';
            }

            var enable_disable_check = (data_sms_status == 'not_sent') ? 'enable_sms' :
                'disable_sms';
            var row_enable_disable = (data_status == 'dropout') ? 'row_disabled' : '';

            var availability_check = (availability) ? "selected" : "";
            var allow_pay_selected = (availability) ? "selected" : "";
            var rph_readonly_check = (availability) ? "readonly" : "";


            var rph_field = "";
            rph_field = '<input class="form-control number_only rate_per_hour"  value="' +
                data_rph + '" data-validation="true"  id="rph-' + data_staffid +
                '"  data-name="rate_per_hour"  name="array_staff[' + row_no +
                '][rate_per_hour]"  ' + rph_readonly_check + ' />';

            var availability_label =
                '<select id="" class="form-control allow_check" data-id="' + data_staffid +
                '"  data-validation="true" data-name="availability"  id="availability-' +
                data_staffid + '" name="array_staff[' + row_no + '][availability]">';
            availability_label += '<option value="1"  ' + allow_pay_selected +
                '> shown </option>';
            availability_label += '<option value="0"  ' + allow_pay_selected +
                '> not shown</option>';
            availability_label += '</select>';


            var availability_label_off =
                '<label class="availability_label hide"> <input type="checkbox" ' +
                availability_check + ' data-id="' + data_staffid + '" id="availability-' +
                data_staffid + '" name="array_staff[' + row_no +
                '][availability]"   data-validation="true" class="allow_check" /></label> ';

            $("#staff_schedule_table tbody").append('<tr id="row-' + data_staffid +
                '" class="' + enable_disable_check + ' ' + row_enable_disable + ' " ><td>' +
                bulk_checkbox + '</td><td class="staff_image_temp"><img src="' +
                data_image +
                '" class="staff_list_view img-circle user_image"/> <span class="list_staff_name" >' +
                text +
                '</span><input type ="hidden" value="{{$schedule_data->id}}"name="array_staff[' +
                row_no + '][event_id]"  readonly /><input type ="hidden" value="' +
                data_staffid + '"name="array_staff[' + row_no +
                '][staff_id]" id="staff_id-' + data_staffid +
                '" data-name="staff_id"   data-validation="true"  readonly /></td><td><span>' +
                data_sira + '<span></td><td class="staff_contact_no td_contact_no ' +
                enable_disable_check + '" ><span >' + data_contact +
                '</span></td><td><input class="form-control timepicker start_time_input"  type="" readonly id="start_time_input-' +
                data_staffid + '"  name="array_staff[' + row_no +
                '][start_time]" data-name="start_time" data-validation="true"  value="' +
                data_start_time + '" onChange="calculateStaffHours(\'' + data_staffid +
                '\')" /></td><td><input class="form-control timepicker end_time_input" type="" readonly id="end_time_input-' +
                data_staffid + '" name="array_staff[' + row_no +
                '][end_time]"  data-name="end_time"  data-validation="true"   value="' +
                data_end_time + '"   onChange="calculateStaffHours(\'' + data_staffid +
                '\')" /></td><td><input class="form-control " type="" id="staff_hours-' +
                data_staffid + '"  value="' + data_hours +
                '" data-validation="true" name="array_staff[' + row_no +
                '][hours]" data-name="hours"  readonly /></td><td>  ' + rph_field +
                '   </td><td id="staff_status_td">' + staff_status_opt + '</td><td >' +
                sms_status_opt + '</td><td></td><td>  ' + availability_label + '   </td></tr>')

            $('select[name="array_staff[' + row_no + '][status]"]').val(data_status);
            $('select[name="array_staff[' + row_no + '][sms_status]"]').val(data_sms_status);

        });

        //$("select#staff_status-"+data_staffid).val('pending');

        $('.number_only').keyup(function() {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        });

        // //Timepicker
        loadTimePicker();

        // ALLOW PAYMENT CHECKBOX FOR DOOR SEC STAFF
        allowPaymentCheck();
        // iziToast.show({
        //     title: 'Success',
        //     color: 'green', // blue, red, green, yellow
        //     message: 'Staff added to Schedule successfully!'
        // });
		   setTimeout(function() {
				loader_close();
		   },1000);
        return false;

        $('ul#temp_staff_list').empty();
    });

    $('#staffList_filled-move-off').click(function(e) {




    });

    /** SMS POPUP CODE **/
    $("#send_sms_btn").click(function(ev) {

        $('#staffList_new li.selected,#staffList_filled li.selected').each(function(ev) {
            var data_contact = $(this).children('img').attr('data-contact');
            $('input#phone_number').tagsinput('add', data_contact);

        });

        $("#SendSMSPopup").modal();

    });


    /** Staff Schedule Confirmation **/
    $(".confrimStaffScheduleRemove").click(function(ev) {
        var data_staffid = "";
        var comma_string = "";
        var target_modal = $(this).attr('data-target');
        var data_exist = "";

        $(".response_message").text('');
        $("form#removeStaffScheduleForm")[0].reset();

        $('#staffList_filled li.selected').each(function(index, value) {
            if (index > 0) {
                comma_string = ",";
            }
            data_exist = $(this).children('img').attr('data-exist');

            if (data_exist == "true") {
                data_staffid += comma_string + $(this).children('img').attr('data-staffid');
            }

            $(this).children('img').attr('data-exist', 'false');
            $(this).attr('data-exist', 'false');
        });

        if (data_staffid) {
            $("" + target_modal + " input[name='data_id']").val(data_staffid);
            $(target_modal + " .deleteBtnTrigger").attr('onClick', 'RemoveStaffchedule()');
            $(target_modal).modal();
        } else {
            RemoveSelectedStaffFromList();
        }


    });


    /*  STAFF TYPE SELECTION RADIO BUTTON  */

    $("input.staff_type_radio[type='radio']").change(function() {
        var staff_type_id = $(this).attr('data-typeid');
        // alert($(this).attr('id'));
        $('.staff_type_labels').removeClass('active');
        $('#staff_type_label-' + staff_type_id).removeClass('active');
        $('#staff_type_label-' + staff_type_id).addClass('active');
        $("ul#staffList_new li").attr('data-view', 'off');
        if (staff_type_id) {
            $("ul#staffList_new li[data-stafftype=" + staff_type_id + "]").attr('data-view', 'on');

        }
    });

    // DATE PICKER

    $('.number_only').keyup(function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $('.alphabets_only').keyup(function() {
        this.value = this.value.replace(/[^a-zA-Z\.]/g, '');
    });


    $(".start_date").datepicker({
        todayBtn: 1,
        autoclose: true,
    }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.end_date').datepicker('setStartDate', minDate);
    });

    $(".end_date").datepicker()
        .on('changeDate', function(selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.start_date').datepicker('setEndDate', maxDate);
        });

    /** SMS POPUP CODE **/
    //function  initializeSmsTrigger()
    // {

    // return false;
    // }


}); // jQuery Ends..


function GenerateIndexing() {

    $('#staffList_filled li').each(function(index, value) {
        var index = $(this).index() + 1;
        $(this).find('.li_position_no').text(index + " - ")

    }); // filled boxx

}

function CheckValidation() {
    var form_id = "add_staff_schedule_form";
    var validation = true;
    form_id = form_id;


    $("#" + form_id + " .form-control[data-validation='true']").each(function(e) {
        if (this.id) {
            if (this.value == null || this.value == '' || this.value == " ") {
                $("#" + this.id).addClass('error');
                validation = false;
            } else {
                $("#" + this.id).removeClass('error');
            }
        }

    });

    return validation;
}

function changeSmsStatus(fid) {
    //alert(fid);

}


function initializeSmsTrigger(btnID) {
    $("form#send_sms_form .bulk_staff_temp_list").html('');
    $('form#send_sms_form .send_sms_submit').removeAttr('onClick');
    $('form#send_sms_form')[0].reset();
    $('form#send_sms_form input[name="phone_number"]').tagsinput('removeAll');
    var data_contact = $("#" + btnID).attr('data-contact');
    var staff_id = $("#" + btnID).attr('data-staff_id');
    var staff_name = $("#" + btnID).attr('data-staff_name');
    var staff_image_temp = '<span class="staff_tags_temp bold">' + staff_name + '</span>';
    $("form#send_sms_form .bulk_staff_temp_list").html(staff_image_temp);
    $('form#send_sms_form input[name="phone_number"]').tagsinput('add', data_contact);
    $('form#send_sms_form .bootstrap-tagsinput input').css('width', '0'); // for non edit able
    $('form#send_sms_form .bootstrap-tagsinput input').attr('readonly', 'readonly'); // for non edit able
    $('form#send_sms_form input[name="staff_ids"]').val(staff_id);
    $("form#send_sms_form .bootstrap-tagsinput span[data-role='remove']").removeAttr('data-role');
    $(".response_message").text('');
	// $('form#send_sms_form .send_sms_submit').attr('onClick','SendSmstoStaff("'+'923458012482'+'","'+'23huhu23h4uh3u42'+'")');

    $('form#send_sms_form .send_sms_submit').attr('onClick', 'sentWhatsappMsg("' + staff_id + '","' + data_contact +'",'+ false +')');
    $("#SendSMSPopup").modal();
}
//update data of sent Whatsapp
function SendSmstoStaff(ph_number, MessageId, data_staff_id) {
    $('form#send_sms_form input[name="message_body"]').val('ksdfjhksdfjhksdfjhksdfjhk');

    if ($("#message_body").val()) {
        // $('form#send_sms_form .response_message').text(' ');
        var staff_id = $('form#send_sms_form input[name="staff_ids"]').val();
        var phone_number = $('form#phone_number input[name="phone_number"]').val();
        updateStaffScheduleJson(staff_id, ph_number, MessageId, data_staff_id);

        $("form#add_staff_schedule_form #sms_status-" + staff_id).val('pending');
        $("form#add_staff_schedule_form #sms_status-" + staff_id).next('.label_sms_status').text('Sent');
        // $("#SendSMSPopup").modal('hide');
    } else {
        $('form#send_sms_form .response_message').text('Type Your Message');
    }
    return false;
}

// Whatsapp Data update to DB

function updateStaffScheduleJson(staff_id, ph_number, MessageId, data_staff_id) {

    // alert(staff_id);
    // alert(data_staff_id);return;
    var event_arrving_time = $('form#send_sms_form input[name="arrving_time"]').val();
    var event_briefing = $('form#send_sms_form input[name="briefing_time"]').val();
    var event_loc_guide = $('form#send_sms_form input[name="event_loc_guide"]').val();
    var signMeetPt = $('form#send_sms_form input[name="signMeetPt"]').val();
    var strtTimeE = $('form#send_sms_form input[name="strtTimeE"]').val();
    var event_dress_code = $('form#send_sms_form textarea[name="event_dress_code"]').val();
    $("#start_time_input-" + data_staff_id).val(strtTimeE);
    var event_location_fil = $('#keyword').val();
    var event_date = $('#event_start_date').val();

    var event_start_time = $('#event_start_time').val();
    var event_start_date = $('#event_start_date').val();

    // GENERATE TEMPORRY FORM TO SUBMIT EACH ROW
    var formID = 'row_schedule_form-' + staff_id + '';
    $("#" + formID).remove();

    $("tr#row-" + staff_id).prepend('<form id="' + formID + '"></form>');
    $("tr#row-" + staff_id + " #" + formID).prepend('@csrf');


    $("tr#row-" + staff_id + " td input[data-validation='true'] , tr#row-" + staff_id +
        " td select[data-validation='true']").each(function(ev) {
        if ($(this).attr('id') != "") {
            // serialization += $(this).attr('name')+'='+$(this).val()+'&';

            var data_name = $(this).attr("data-name");
           // console.log($(this).attr('id') + " // " + data_name);
            var gen_input = '<input type="" name="' + data_name + '" value=' + $(this).val() + ' >';
            $('#' + formID).append(gen_input);
        }
    });


    var event_id = $("#event_id").val();
    // var phone_number = $("#phone_number").val();
    var formData = $('#' + formID).serialize();
    var myJsonData = {
        formData: formData,
        data_staff_id: data_staff_id,
        event_id: event_id,
        phone_number: ph_number,
        MessageId: MessageId,
        is_bulk_sms: 0,
        event_arrving_time: event_arrving_time,
        event_loc_guide: event_loc_guide,
        event_dress_code: event_dress_code,
        signMeetPt: signMeetPt,
        event_location_fil: event_location_fil,
        event_start_time: event_start_time,
        event_start_date: event_start_date,
        event_briefing: event_briefing,
        event_date: event_date
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('/update_staff_schedule_json', myJsonData, function(response) {
        var obj = JSON.parse(response);
        $(".response_message").text(obj.message);

        // return false;
        if (obj.status == "200") {

            $(".response_message").text('Message successfully sent');
            $(".response_message").addClass('text-green');
            $(".response_message").removeClass('text-red');
            $("#" + formID).remove();
            $("tr#row-" + staff_id).addClass('disable_sms');
            $("tr#row-" + staff_id + " td.td_contact_no").removeClass('enable_sms');
            $("tr#row-" + staff_id + " td.td_contact_no").addClass('disable_sms');
            $("#sms_status_btn-" + staff_id).remove();
            $("form#send_sms_form")[0].reset();
            $("#SendSMSPopup").modal('hide');
			loader_close();
            //updateStaff();
             //$("#SendSMSPopup").modal('hide');
        } else {

            $(".response_message").addClass('text-red');
            $(".response_message").removeClass('text-green');

        }

    });
    return false;
}

function sentWhatsappMsg(data_staff_id, data_contact, flag='') {
	//return false;
    $("#strtTimeE").removeClass('error');
    var event_arr_timeReq = $('form#send_sms_form input[name="arrving_time"]').val();
    var eventBrie_timeReq = $('form#send_sms_form input[name="briefing_time"]').val();
    var event_loc_guideReq = $('form#send_sms_form input[name="event_loc_guide"]').val();
    var signMeetPt = $('form#send_sms_form input[name="signMeetPt"]').val();
    var strtTimeE = $('form#send_sms_form input[name="strtTimeE"]').val();
    var event_dress_codeReq = $('form#send_sms_form textarea[name="event_dress_code"]').val();
    var event_startTime = $('form#add_staff_schedule_form input[id="start_time_input-' + data_staff_id + '"]').val();
    // alert(data_staff_id);
    // return;
   // var data_contact = parseInt(data_contact, 10);
    if (event_arr_timeReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#arrving_time'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Arriving Time!!!'
        });
        return false;
    }
    if (eventBrie_timeReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#briefing_time'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Briefing Time!!!'
        });
        return false;
    }
    if (event_loc_guideReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#event_loc_guide'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Location guide!!!'
        });
        return false;
    }
    if (event_dress_codeReq == '') {
        $('#staffList_filled').jqListbox('transferSelectedTo', $('#event_dress_code'));
        iziToast.show({
            title: 'Required',
            color: 'red', // blue, red, green, yellow
            message: 'Dress Code!!!'
        });
        return false;
    }
    if (signMeetPt == '') {
        $('#signMeetPt').jqListbox('transferSelectedTo', $('#signMeetPt'));
        iziToast.show({
            title: 'Required',
            color: 'red',
            message: 'Siging Area/Meeting point!!!'
        });
        return false;
    }
    if (strtTimeE == '') {
        $('#strtTimeE').jqListbox('transferSelectedTo', $('#strtTimeE'));
        iziToast.show({
            title: 'Required',
            color: 'red',
            message: 'Start Time!!!'
        });
        return false;
    }
    var regexp = /([01][0-9]|[02][0-3]):[0-5][0-9]/;
    var formatTime = ($('#strtTimeE').val().search(regexp) >= 0) ? true : false;
    if (formatTime == false) {
        $("#strtTimeE").addClass('error');
        $('#strtTimeE').jqListbox('transferSelectedTo', $('#strtTimeE'));
        iziToast.show({
            title: 'Required',
            color: 'red',
            message: 'Start Time Format is Incorrect!!!'
        });
        return false;
    }
	//after slow issue
	//send Whatsapp Message with Curl command
	if(flag){
		var ph_number = data_contact;
	}else{
		var obj = [];
		var temp = {"staff_id":data_staff_id, "number":data_contact};
		obj.push(temp);
		console.log(obj);
		var ph_number = obj;
	}
	// return false;
	var event_id = $("#event_id").val();

//	paramerter defined in Clickatell console
	var myJsonData = {"parameters": {
                        1: "{{ isset($schedule_data)?$schedule_data['event_name']:'' }}",
                        2: "{{ isset($schedule_data)?$schedule_data['location']:'' }}" +
                            ' Location Guide ' + event_loc_guideReq,
                        3: signMeetPt,
                        4: "{{ isset($schedule_data)?$schedule_data['start_date']:'' }} To {{ isset($schedule_data)?$schedule_data['end_date']:'' }}",
                        5: event_arr_timeReq,
                        6: eventBrie_timeReq,
                        7: strtTimeE,
                        8: event_dress_codeReq
				},
				"ph_number":ph_number,
				"event_id":event_id,
				"location":"{{ isset($schedule_data)?$schedule_data['location']:'' }}",
				"location_guide":event_loc_guideReq,
				"start_date":"{{ isset($schedule_data)?$schedule_data['start_date']:'' }}",
				"end_date": "{{ isset($schedule_data)?$schedule_data['end_date']:'' }}"
				};
	//return false;
//Ajax to send whatsapp message and update data
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		async:true
    });

    $.post('/event/clickatelTest', myJsonData, function(data) {
			if (data) {
				resp = JSON.parse(data);
				$("#SendSMSPopup").modal('hide');
				iziToast.show({
					title: 'Required',
					color: resp.color, // blue, red, green, yellow
					message: resp.message
				});
				location.reload();
			} else {
				iziToast.show({
					title: 'Required',
					color: 'red', // blue, red, green, yellow
					message: 'Something Wrong!!'
				});
				return false;
			}
    });
	return false;
}

function SendBulkSms() {
	//return false;
    var obj = [];
	var data_contact='';
	var data_staff_id='';
    $('.staff_sch_check:checked:checked').each(function() {
        var number = $(this).attr('data-contact');
        data_staff_id = $(this).attr('data-staff_id');
         data_contact = $(this).attr('data-contact');
        var event_arr_timeReq = $('form#send_sms_form input[name="arrving_time"]').val();
        var eventBrie_timeReq = $('form#send_sms_form input[name="briefing_time"]').val();
        var event_loc_guideReq = $('form#send_sms_form input[name="event_loc_guide"]').val();
        var event_dress_codeReq = $('form#send_sms_form textarea[name="event_dress_code"]').val();
		var event_id = $("#event_id").val();

		var temp = {"staff_id":data_staff_id, "number":data_contact};
		obj.push(temp);

    });
	sentWhatsappMsg(data_staff_id, obj,true);
	// console.log(obj);
    return false;
}

function getTotalCheckedStaff() {
    var total = $("#staff_schedule_table").find('input.staff_sch_check:checked').length;
    if (total == 0) {
        iziToast.show({
            title: 'Removed',
            color: 'red', // blue, red, green, yellow
            message: 'Please Select Staff To Send Bulk Sms!!!'
        });


        //alert("Please Select Staff To Send Bulk Sms!!! ")
        return false;
    } else {
        return total;
    }
}

function openBulkSmsPopup() {
    $('form#send_sms_form')[0].reset();
    $(".response_message").text('');
    $('form#send_sms_form input[name="phone_number"]').tagsinput('removeAll');
    $("form#send_sms_form .bulk_staff_temp_list").html('');

    var ret = getTotalCheckedStaff();
    if (ret == false) {
        return ret;
    }
    var staff_ids = "";
    $("table#staff_schedule_table tr td input.staff_sch_check:checked").each(function(ev) {
        var data_contact = $(this).attr('data-contact');
        console.log($(this).parent('td').parent('tr').find('td.staff_image_temp').html());
        var staff_image_temp = '<span class="staff_tags_temp ">' + $(this).parent('td').parent('tr').find(
            'td.staff_image_temp').html() + '</span>';

        staff_ids += $(this).attr('data-staff_id') + ",";
        var data_contact = $(this).attr('data-contact');
        $('form#send_sms_form input[name="phone_number"]').tagsinput('add', data_contact);
        $('form#send_sms_form .bulk_staff_temp_list.sms').append(staff_image_temp);
    }); // LOOP ENDS

    //return false;

    var staff_ids = staff_ids.replace(/^,|,$/g, '');
    $('form#send_sms_form input[name="staff_ids"]').val(staff_ids);

    $('form#send_sms_form .bootstrap-tagsinput input').css('width', '0'); // for non edit able
    $('form#send_sms_form .bootstrap-tagsinput input').attr('readonly', 'readonly'); // for non edit able
    $("form#send_sms_form .bootstrap-tagsinput span[data-role='remove']").removeAttr('data-role');
    $('form#send_sms_form .send_sms_submit').attr('onClick', 'SendBulkSms()');
    $("#SendSMSPopup").modal();
}

function CheckTotalConfirmation() {
    var count = 0;
    $("select.staff_status_select").each(function(ev) {

        if ($(this).val() == 'confirmed') {
            count++;
        }

    });
    if (count == 0) {
        $(".send_payroll_btn").hide();
    } else {
        $(".send_payroll_btn").show();
    }
}

function confirm_password(form_name) {

    $('#sendToPayroll').modal('show');
}
$('.submit_password').on('click', function() {
    var formData = $("#sendToPayrollform").serialize();
    var myJsonData = {
        formData: formData
    };
    console.log(myJsonData);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //verify password
    $.post('/DeleteRecord', myJsonData, function(response) {
        var obj = JSON.parse(response);
        $('.response_message_send_to_payroll').text(obj.message);
        if (obj.status) {
            // console.log("ss");
            $('#send_payroll').attr('type', 'submit');
            setTimeout(function() {
                $('#send_payroll').trigger("click");
                //  location.reload(true);
            }, 850);

            $(".response_message_send_to_payroll").addClass('text-green');
            $(".response_message_send_to_payroll").removeClass('text-red');

        } else {

            $(".response_message_send_to_payroll").addClass('text-red');
            $(".response_message_send_to_payroll").removeClass('text-green');

        }


    });
});
jQuery("#search").keyup(function() {
    var filter = jQuery(this).val();
    jQuery("ul#staffList_new li").each(function() {
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).hide();
        } else {
            jQuery(this).show();
        }
    });
});
jQuery("#search").blur(function() {
    var filter = jQuery(this).val();
    jQuery("ul#staffList_new li").each(function() {
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).hide();
        } else {
            jQuery(this).show();
        }
    });
});

function updateStaff() {
    setTimeout(function() {
        $("#add_staff_schedule_form").submit();
    }, 1000);
}
</script>
<script>
let vm = new Vue({
    el: "#appsss",
    data: {
        localtionGuide: '',
        dressCode: '',
        briefing_time: '',
        arrving_time: '',
        signMeetPt: '',
        strtTimeE: '',
    },
    // mounted: function(){
    // var self = this;
    // $("#arrving_time").datepicker({
    // 	onSelect: function(selectedDate, datePicker){
    // 		self.arrving_time = selectedDate;
    // 	}
    // });
    // }
})
</script>
@endsection