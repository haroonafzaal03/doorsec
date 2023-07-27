@extends('layouts.master')
@section('content')
<style>
    .guarding_calender {
        height: calc(100vh - 207px) !important;
    }

    .goBackDiv {
        display: none;
    }

    .content-wrapper {
        background-color: white;
    }
    #table-venue_shifts  {
        display:block !important;
    }
    #venue_shift_section{
        display:block !important;
    }
    #btnnn{
        display:initial !important;
    }
</style>
<div class="row p-t-10">
    <div class="col-md-12">
        <div class="">
            <form action="{{ route('guarding_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-8 col-lg-8 col-xl-7">
                    <div class="">
                        <div class="form-group col-md-6">
                            <small><strong>Select Client : </strong></small>
                            <select name="client_id" id="venue_client_id" data-validation="true" class="form-control image_select2 custom_css" style="width:100%">
                                <option value=""> Select</option>
                                @if(isset($clients))
                                @foreach($clients as $obj)
                                <option value="{{$obj->id}}" {{ ($guarding->client_id == $obj->id) ?'selected' :'' }} data-image="{{img($obj->client_logo)}}">{{$obj->property_name}} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <small><strong>Start Date : </strong></small>
                            <div class="input-group">
                            @php
                                $start_date = '';
                                $start_date = \Carbon\Carbon::parse($guarding->start_date)->format('m/d/Y');
                                $next_month_start = \Carbon\Carbon::create($start_date)->addMonth()->format('m/d/Y');
                                @endphp
                                <input type="text" readonly="" name="start_date" id="venue_start_date" class="form-control datepicker" value="{{$next_month_start }}" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <small><strong>End Date : </strong></small>
                            <div class="input-group">
                                @php
                                $end_date = '';
                                $end_date = \Carbon\Carbon::parse($guarding->end_date)->format('m/d/Y');
                                $next_month_end = \Carbon\Carbon::create($end_date)->addMonth()->format('m/d/Y');
                                @endphp
                                <input type="text" readonly="" name="end_date" id="venue_end_date" class="form-control datepicker" value="{{$next_month_end}}" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bootstrap-timepicker row">
                            <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                <small><strong>Day Shift From </strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" value="{{$guarding->day_start_time}}" name="day_outer_from" id="day_outer_from" class="form-control timepicker" onchange="calculateStaffHours();" autocomplete="off">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                <small><strong>Day Shift To </strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" value="{{$guarding->day_end_time}}" name="day_outer_to" id="day_outer_to" class="form-control timepicker" onchange="calculateStaffHours();">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                <small><strong>Night Shift From </strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" value="{{$guarding->night_start_time}}" name="night_outer_from" id="night_outer_from" class="form-control timepicker" onchange="calculateStaffHours();" autocomplete="off">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                <small><strong>Night Shift To </strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" value="{{$guarding->night_end_time}}" name="night_outer_to" id="night_outer_to" class="form-control timepicker" onchange="calculateStaffHours();">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-12 d-block">Assignment Types</label>
                            <input type="hidden" id="hours-hidden">
                            @if(isset($siraTypes))
                            @foreach($siraTypes as $obj)
                            <div class="form-group col-md-2">
                                <small><strong>{{$obj->type}} </strong></small>
                                <div class="checkbox-inline">
                                    <input type="checkbox" id="atc_{{$obj->id}}" class="assignment_type_checkbox" onclick="assignment_type_checkbox('{{$obj->id}}');">
                                    <input type="text" id="at_count_{{$obj->id}}" data-id="{{$obj->id}}" data-type="{{$obj->type}}" class="at_count" style="/* width: 91px; */width: 100%;" disabled>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @hasrole('add.guard')
                            <div class="form-group col-md-2">
                                <button class="btn btn-primary" id="add_shift" style="margin-top: 16px;">Add Shift</button>
                            </div>
                            @endhasrole
                        </div>
                    </div>
                    <!-- time Picker -->
                    <div class="col-md-12">
                        <div class="bootstrap-timepicker row" id="venue_shift_section" style="overflow-y: scroll;height: calc(100vh - 339px);border-top: 3px solid grey;">
                            <table class="table" id="table-venue_shifts">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Shift Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Assignment Type</th>
                                        <th>Staff</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="venue_shifts_tbody">
                                    @foreach($guarding->staffschedule as $key => $gss)
                                     <tr id="tr_{{$key+1}}">
                                    <td class="remove_staff-td"><i class="fa fa-minus-circle"></i>
                                        <input type="hidden" name="staff_schedule_id[]" value="{{$gss->id}}" />
                                    </td>
                                    <td><label class="col-md-12 d-block">
                                        <select onchange="select_shift_type('tr_{{$key+1}}',this.value)" data-validation="true" class="form-control custom_css" style="width:100%" name="shift_type[]" id="shift_type">
                                            <option value=""> Select</option>
                                            <option value="Day" {{ ($gss->shift_type=='Day'?'selected':'')}}>Day</option>
                                            <option value="Night" {{ ($gss->shift_type=='Night'?'selected':'')}}>Night</option>
                                        </select>
                                    </label>
                                    <input type="hidden" name="" value="">
                                    <td style="width:22%">
                                        <div class="form-group col-md-12">
                                            <div class="input-group">
                                                <input type="text" readonly name="start_time[]" class="form-control timepicker" value="{{$gss->start_time}}" onchange="calculateStaffHours('tr_{{$key+1}}' );" />
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td style="width:22%">
                                        <div class="form-group col-md-12">
                                            <div class="input-group">
                                                <input type="text" readonly name="end_time[]" class="form-control timepicker" value="{{$gss->end_time}}" onchange="calculateStaffHours( 'tr_{{$key+1}}');">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>


                                    <td><label class="col-md-12 d-block">{{$gss->sira_type['type']}}</label>
                                        <input type="hidden" value="{{$gss->assignment_type}}" name="assignment_type[]"/>
                                    </td>
                                    <td class="staff_image_temp"><img src="{{img($gss->staff->picture)}}" class="img-circle user_image"></td>

                                    <td>
                                        <a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff('tr_{{$key+1}}')">Select Staff</a>
                                        <br>
                                        <input data-validation="true" type="hidden" id="selected_staff-{{$key+1}}" name="selected_staff[]" value="{{$gss->staff->id}}"/>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-1" style="height: calc(100vh - 120px);">
                    <div class="col-md-12 add_staff_to_shiift" style="margin-top: 300px;padding:0px;">
                        <button class="btn btn-success pull-right" id="add_staff_shiift-btn"><i class="fa fa-arrow-left mr-1"> </i>ADD </button>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-4 vs_popup-staff-section">
                    <div class="col-md-12 m-b-20 text-center staff_types">
                        <label class="control-label inline-block staff_type_labels active" id="staff_type_label-1">
                            <input type="radio" checked="" data-typeid="1" id="staff_type_1" name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                            DoorSec Staff</label>

                        <label class="control-label inline-block staff_type_labels" id="staff_type_label-2">
                            <input type="radio" data-typeid="2" id="staff_type_2" name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                            Freelancer</label>

                        <label class="control-label inline-block staff_type_labels" id="staff_type_label-3">
                            <input type="radio" data-typeid="3" id="staff_type_3" name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                            Guard</label>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group ">
                                <input type="text" class="form-control"id="search" placeholder="serach by name"/>
                            </div>

                        </div>
                    <div class="col-md-12 customlist venue_calendar guarding_calender">
                        <ul id="staffList_new" class="users_multi_listbox">
                        </ul>
                    </div>
                    <div class="col-md-12 m-t-20 m-b-20">
                        <button type="submit" class="btn btn-warning pull-right" id="btnnn">
                            Duplicate</button>
                    </div>

                    <!--<ol id="selected_staff">

								</ol>-->
                </div>
            </form>
        </div>
    </div>
    @endsection
    @section('content_js')

    <script>
        var default_img = "{{ asset('avatar.jpg ') }}";
        var asset_path = "{{image_base()}}";
    </script>
    <script src="{{ asset('js/zt-scheduler.js')}}"></script>
    <script>
        $(function() {
            loadTimePicker();
            reinit();
            // $("#venue_start_date").datepicker({
            //     format: "mm-yyyy",
            //     viewMode: "months",
            //     minViewMode: "months"
            // });

            $(".image_select2").select2();
            var count = {{count($guarding->staffschedule) +1 }};
            $('#add_shift').click(function() {
                if ($('table#table-venue_shifts tbody#venue_shifts_tbody tr').length > 0) {
                    $.confirm({
                        title: 'Confirmation!',
                        content: 'Do you really want to add more shifts!',
                        buttons: {
                            confirm: function() {
                                this.close();
                                loader_open();
                                setTimeout(function() {
                                    var number_shifts = $('#number_shifts').val();
                                    var start_date = $('#venue_start_date').val();
                                    var end_date = $('#venue_end_date').val();
                                    var day_from = $('#day_outer_from').val();
                                    var day_to = $('#day_outer_to').val();
                                    var night_from = $('#night_outer_from').val();
                                    var night_to = $('#night_outer_to').val();
                                    var hours = $('#hours-hidden').val();
                                    //var shift_type = $('#shift_type').val();
                                    start_date = moment(start_date);
                                    var selected_staff = "";
                                    //if (number_shifts > 0) {
                                    $('table#table-venue_shifts').css('display', 'block');
                                    $('#add_staff_shiift-btn').css('display', 'block');
                                    $('#venue_shift_section').css('display', 'block');

                                    $('.at_count').each(function() {
                                        var type_count = $(this).val();
                                        var type = $(this).attr('data-type');
                                        var assignment_type_id = $(this).attr('data-id');


                                        var i = 0;
                                        for (i = 0; i < type_count; i++) {
                                            var newRow = $("<tr id=tr_" + count + ">");
                                            var cols = '<td class="remove_staff-td"><i class="fa fa-minus-circle"></i> <input type="hidden" name="staff_schedule_id[]" value="0" /></td>';
                                            cols += '<td><label class="col-md-12 d-block"><select onchange="select_shift_type(\'' + 'tr_' + count + '\',this.value)" data-validation="true" class="form-control custom_css" style="width:100%" name="shift_type[]" id="shift_type"><option value="" selected> Select</option><option value="Day">Day</option><option value="Night">Night</option></select></label><input type="hidden" name="" value=""></td>';
                                            cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="start_time[]" class="form-control timepicker" value="" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                            cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="end_time[]" class="form-control timepicker" value="" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                            cols += '<td class="">' + type + '<input type="hidden" name="assignment_type[]" value="' + assignment_type_id + '"></td>';
                                            cols += '<td class="staff_image_temp"><label class="label bg-red">No Staff</label></td>';
                                            cols += '<td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff(\'' + 'tr_' + count + '\')">Select Staff</a><br> <input data-validation="true" type="hidden" id="selected_staff-' + count + '" name="selected_staff[]" /></td>';


                                            newRow.append(cols);


                                            $('table #venue_shifts_tbody').append(newRow);


                                            count++;
                                            reinit();
                                        }
                                    });
                                    $('#btnnn').css('display', 'initial');
                                    //}
                                    loader_close();
                                    $('.at_count').val('');
                                    $('#number_shifts').val('');
                                    $('#outer_from').val('');
                                    $('#outer_to').val('');
                                    $('#hours-hidden').val('');
                                }, 100);
                                return false;
                            },
                            cancel: function() {
                                this.close();
                            }
                        }
                    });
                } else {
                    // if (!CheckValidation()) {
                    //     return false;
                    // }
                    loader_open();
                    setTimeout(function() {

                        var number_shifts   = $('#number_shifts').val();
                        var start_date      = $('#venue_start_date').val();
                        var end_date        = $('#venue_end_date').val();
                        var day_from        = $('#day_outer_from').val();
                        var day_to          = $('#day_outer_to').val();
                        var night_from      = $('#night_outer_from').val();
                        var night_to        = $('#night_outer_to').val();
                        var hours           = $('#hours-hidden').val();
                        //var shift_type = $('#shift_type').val();
                        start_date = moment(start_date);
                        var selected_staff = "";
                        //if (number_shifts > 0) {
                        $('table#table-venue_shifts').css('display', 'block');
                        $('#add_staff_shiift-btn').css('display', 'block');
                        $('#venue_shift_section').css('display', 'block');

                        $('.at_count').each(function() {
                            var type_count = $(this).val();
                            var type = $(this).attr('data-type');
                            var assignment_type_id = $(this).attr('data-id');


                            var i = 0;
                            for (i = 0; i < type_count; i++) {
                                var newRow = $("<tr id=tr_" + count + ">");
                                var cols = '<td class="remove_staff-td"><i class="fa fa-minus-circle"></i> <input type="hidden" name="staff_schedule_id[]" value="0" /></td>';
                                cols += '<td><label class="col-md-12 d-block"><select onchange="select_shift_type(\'' + 'tr_' + count + '\',this.value)" data-validation="true" class="form-control custom_css" style="width:100%" name="shift_type[]" id="shift_type"><option value="" selected> Select</option><option value="Day">Day</option><option value="Night">Night</option></select></label><input type="hidden" name="" value=""></td>';
                                cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="start_time[]" class="form-control timepicker" value="" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="end_time[]" class="form-control timepicker" value="" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                cols += '<td class="">' + type + '<input type="hidden" name="assignment_type[]" value="' + assignment_type_id + '"></td>';
                                cols += '<td class="staff_image_temp"><label class="label bg-red">No Staff</label></td>';
                                cols += '<td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff(\'' + 'tr_' + count + '\')">Select Staff</a><br> <input data-validation="true" type="hidden" id="selected_staff-' + count + '" name="selected_staff[]" /></td>';


                                newRow.append(cols);


                                $('table #venue_shifts_tbody').append(newRow);


                                count++;
                                reinit();
                            }
                        });
                        $('#btnnn').css('display', 'initial');
                        //}
                        loader_close();
                        $('.at_count').val('');
                        $('#number_shifts').val('');
                        $('#outer_from').val('');
                        $('#outer_to').val('');
                        $('#hours-hidden').val('');
                    }, 100);
                    return false;
                }
                return false;
                // $('.spinnerr').css('display','none');

            });

            /*  STAFF TYPE SELECTION RADIO BUTTON  */

            $("input.staff_type_radio[type='radio']").change(function () {

                    $('.staff_type_labels').removeClass('active');
                    $(this).parent('.staff_type_labels').addClass('active');
                    var staff_type_id = $(this).attr('data-typeid');
                    $("ul#staffList_new li").attr('data-view', 'off');
                    if (staff_type_id) {
                        $("ul#staffList_new li[data-stafftype=" + staff_type_id + "]").attr('data-view', 'on');
                    }
                    console.log(staff_type_id);
            });

            $('#add_staff_shiift-btn').click(function () {
                var arr = [];
                var selected_staff_image_src;
                if ($('#staffList_new li.selected').length > 0) {
                    $('#staffList_new li.selected').each(function () {
                        arr.push($(this).attr('data-id'));
                        selected_staff_image_src = $(this).attr('data-image');
                    });
                    //$('#'+$('#tr_shift_id').val()).css('background-color','transparent');
                    $('#' + $('#tr_shift_id').val()).find('td:nth-child(6)').html('<img src="' + selected_staff_image_src + '" class="img-circle user_image">');
                    $('#' + $('#tr_shift_id').val()).find('td:nth-child(7)').find('input[name="selected_staff[]"]').val(arr.join(','));
                    //CheckValidation();
                    iziToast.show({
                        title: 'Sucess!',
                        color: 'green', // blue, red, green, yellow
                        message: 'Staff added to shift successfully!'
                    });
                } else {
                    $('#' + $('#tr_shift_id').val()).find('td:nth-child(6)').html('<label class="label bg-red">No Staff</label>');
                    $('#' + $('#tr_shift_id').val()).find('td:nth-child(7)').find('input[name="selected_staff[]"]').val('');
                    //CheckValidation();
                }
                $('#btnnn').css('display', 'initial');
                return false;
            });
        });

        function assignment_type_checkbox(id) {
            if ($('#atc_' + id).prop("checked")) {
                $('#at_count_' + id).attr('disabled', false);
            } else {
                $('#at_count_' + id).attr('disabled', true);
            }
        }

        function calculateStaffHours(id = '', temp1 = '') {
            if (id != '' && temp1 == '') {
                var from = $('#' + id).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val();
                var to = $('#' + id).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val();
            } else if (id != '' && temp1 == 1) {
                var from = $('#' + id).find('input.shift_start_time').val();
                var to = $('#' + id).find('input.shift_end_time').val();
            } else {
                var from = $('#outer_from').val();
                var to = $('#outer_to').val();
            }
            var start = moment.utc(from, "HH:mm");
            var end = moment.utc(to, "HH:mm");
            var hour = moment.utc(end.diff(start)).format("HH");
            var minute = moment.utc(end.diff(start)).format("mm");
            var duration = parseFloat(minute / 60) + parseFloat(hour) || 0;

            if (id != '' && temp1 == '') {
                $('#' + id).closest('tr').find('td:nth-child(5)').find('input[name="shift_hours[]"]').val(duration.toFixed(2));
            } else if (id != '' && temp1 == 1) {
                $('tr#' + id).find('input.hours').val(duration.toFixed(2));
            } else {
                $('#hours-hidden').val(duration.toFixed(2));
            }
        }

        function reinit() {
            loadTimePicker();

            $('.remove_staff-td').click(function () {
                var row_id = $(this).closest('tr').find('input[name="staff_schedule_id[]"]').val();
                if ($('table#table-venue_shifts tbody#venue_shifts_tbody tr').length > 0) {
                    var row_id = $(this).closest('tr').find('input[name="staff_schedule_id[]"]').val();
                    var dom = $(this);
                    if(row_id > 0){
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'GET',
                            url: '/guarding/remove/'+row_id,
                            dataType: 'JSON',
                            success: function (data) {
                                console.log($(this));
                                dom.closest('tr').remove();
                                $('table#table-venue_shifts').css('display', 'none');
                                $('#btnnn').css('display', 'none');
                                $('#add_staff_shiift-btn').css('display', 'none');
                                $('#venue_shift_section').css('display', 'none');
                            }
                        });
                    }else{
                        $(this).closest('tr').remove();
                        $('table#table-venue_shifts').css('display', 'none');
                        $('#btnnn').css('display', 'none');
                        $('#add_staff_shiift-btn').css('display', 'none');
                        $('#venue_shift_section').css('display', 'none');
                    }
                }else{
                    iziToast.show({
                        title: 'Error!',
                        color: 'red', // blue, red, green, yellow
                        message: 'No Shift found'
                    });
                }
            });
        }

        function select_shift_type(id,value){
            var from = '';
            var to = '';
            if(value != ''){
                if(value == 'Day'){
                   from = $('#day_outer_from').val();
                   to = $('#day_outer_to').val();

                }else if(value == 'Night'){
                   from = $('#night_outer_from').val();
                   to = $('#night_outer_to').val();
                }
            }
            var tr = $('#' + id).closest('tr').attr('id');
            $('#' + id).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val(from);
            $('#' + id).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val(to);
        }

        function select_staff(id) {
    var runtime_selected_staff = [];
    var client_id = $('#venue_client_id').val();
    var tr = $('#' + id).closest('tr').attr('id');
    var start_time = $('#' + id).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val();
    var end_time = $('#' + id).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val();
    var stafflist = $('#' + id).closest('tr').find('input[name="selected_staff[]"]').val();
    $('input[name="selected_staff[]"]').each(function () {
        if ($(this).val() != '') {
            runtime_selected_staff.push({
                staff: $(this).val(),
                start_time: $(this).closest('tr').find('td:nth-child(3)').find('input[name="start_time[]"]').val(),
                end_time: $(this).closest('tr').find('td:nth-child(4)').find('input[name="end_time[]"]').val()
            });
        }
    });
    //console.log(runtime_selected_staff);
    $('tbody#venue_shifts_tbody tr').css('background-color', 'transparent');
    $('#' + id).css('background-color', '#48a3d8');
    console.log(id);
    console.log(stafflist);
    //return false;
    var myJsonData = { start_time: start_time, end_time: end_time, client_id: client_id, tr: tr, stafflist: stafflist, runtime_selected_staff: runtime_selected_staff,venue_start_date:$('#venue_start_date').val(),venue_end_date:$('#venue_end_date').val()};

    //console.log(myJsonData);return false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/getAvailableStaffList',
        data: myJsonData,
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            $('#staffList_new').html(data.staffList);
            $('#add_staff_shiift-btn').css('display', 'block');
            $('.staff_type_radio').removeAttr('checked');
            $('.staff_type_labels').removeClass('active');
            $('#staff_type_1').click();
            $('#staff_type_label-1').addClass(' active');
        }
    });


    return false;
}

function abbb(id) {
    if ($('#staffList_new li#li-' + id + '').hasClass('selected')) {
        $('#staffList_new li#li-' + id + '').removeClass('selected');
    } else {
        $('#staffList_new li').removeClass('selected');
        $('#staffList_new li#li-' + id + '').addClass('selected');

    }
    //$('#staffList_new li#li-'+id+'').css('display','none');
}
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
    </script>
    @endsection