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
</style>
<div class="row p-t-10">
    <div class="col-md-12">
        <div class="">
            <form action="{{ route('guarding_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-8 col-lg-8 col-xl-7">
                    <div class="">
                        <div class="form-group col-md-6">
                            <small><strong>Select Client * : </strong></small>
                            <select name="client_id" id="venue_client_id" data-validation="true" class="form-control image_select2 custom_css" style="width:100%">
                                <option value=""> Select</option>
                                @if(isset($clients))
                                @foreach($clients as $obj)
                                <option value="{{$obj->id}}" data-image="{{img($obj->client_logo)}}">{{$obj->property_name}} </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <small><strong>Start Date * : </strong></small>
                            <div class="input-group">
                                <input type="text" readonly="" name="start_date" id="venue_start_date" class="form-control datepicker" value="" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <small><strong>End Date * : </strong></small>
                            <div class="input-group">
                                <input type="text" readonly="" name="end_date" id="venue_end_date" class="form-control datepicker" value="" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bootstrap-timepicker row">
                            <!--<label class="col-md-12 d-block">Event Dates </label>-->
                            <!-- <div class="form-group col-md-3">
                            <small><strong>Number of Shifts </strong></small>
                            <div class="input-group">
                                <input type="text" name="number_shifts" id="number_shifts" class="form-control" value="" autocomplete="off">
                            </div>
                        </div> -->
                            <!-- <div class="form-group col-md-3">
                            <small><strong>Shift Type</strong></small>

                            <select data-validation="true" class="form-control custom_css" style="width:100%" id="shift_type">
                                <option value="" disabled=""> Select</option>
                                <option value="Day">Day</option>
                                <option value="Night">Night</option>
                            </select>

                        </div> -->
                            <div class="form-group col-md-3 col-lg-2 col-xl-3" style="padding: 0px 3px">
                                <small><strong>Day Shift From * </strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" name="day_outer_from" id="day_outer_from" class="form-control timepicker" onchange="calculateStaffHours();" autocomplete="off">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-lg-2 col-xl-3" style="padding: 0px 3px">
                                <small><strong>Day Shift To *</strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" name="day_outer_to" id="day_outer_to" class="form-control timepicker" onchange="calculateStaffHours();">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

							<div class="form-group col-md-3 col-lg-2 col-xl-3" style="padding: 0px 3px">
                                <small><strong>Require Staff Day *</strong></small>
                                <div class="input-group">
                                    <input type="text" name="require_staff_day" id="require_staff_day" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-lg-2 col-xl-3" style="padding: 0px 3px">
                                <small><strong>Night Shift From *</strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" name="night_outer_from" id="night_outer_from" class="form-control timepicker" onchange="calculateStaffHours();" autocomplete="off">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-lg-2 col-xl-3" style="padding: 0px 3px">
                                <small><strong>Night Shift To *</strong></small>
                                <div class="input-group">
                                    <input type="text" readonly="" name="night_outer_to" id="night_outer_to" class="form-control timepicker" onchange="calculateStaffHours();">
                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

							<div class="form-group col-md-3 col-lg-2 col-xl-3" style="padding: 0px 3px">
                                <small><strong>Require Staff Night *</strong></small>
                                <div class="input-group">
                                    <input type="text" name="require_staff_night" id="require_staff_night" class="form-control">
                                </div>
                            </div>
                            <label class="col-md-12 d-block">Assignment Types</label>
                            <input type="hidden" id="hours-hidden">
                            @if(isset($siraTypes))
                            @foreach($siraTypes as $obj)
							@if($obj->id != 5)
                            <div class="form-group col-md-2">
                                <small><strong>{{$obj->type}} </strong></small>
                                <div class="checkbox-inline">
                                    <input type="checkbox" id="atc_{{$obj->id}}" class="assignment_type_checkbox" onclick="assignment_type_checkbox('{{$obj->id}}');">
                                    <input type="text" id="at_count_{{$obj->id}}" data-id="{{$obj->id}}" data-type="{{$obj->type}}" class="at_count" style="/* width: 91px; */width: 100%;" disabled>
                                </div>
                            </div>
							@endif
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
                                    <!-- <tr id="tr_1">
                                    <td class="remove_staff-td"><i class="fa fa-minus-circle"></i></td>
                                    <td><label class="col-md-12 d-block">Day</label>
                                    </td>
                                    <td style="width:22%">
                                        <div class="form-group col-md-12">
                                            <div class="input-group"><input type="text" readonly="" class="form-control timepicker" value="7:00">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:22%">
                                        <div class="form-group col-md-12">
                                            <div class="input-group"><input type="text" readonly="" class="form-control timepicker" value="19:00">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                        </div>
                                    </td>


                                    <td><label class="col-md-12 d-block">Supervisor</label></td>
                                    <td class="staff_image_temp"><img src="http://127.0.0.1:8000/avatar.jpg" class="img-circle user_image"></td>

                                    <td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff('tr_1')">Select Staff</a><br> <input data-validation="true" type="hidden" id="selected_staff-1" name="selected_staff[]"></td>
                                </tr>

                                <tr id="tr_1">
                                    <td class="remove_staff-td"><i class="fa fa-minus-circle"></i></td>
                                    <td><label class="col-md-12 d-block">Night</label>
                                    </td>
                                    <td style="width:22%">
                                        <div class="form-group col-md-12">
                                            <div class="input-group"><input type="text" readonly="" class="form-control timepicker" value="10:00">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:22%">
                                        <div class="form-group col-md-12">
                                            <div class="input-group"><input type="text" readonly="" class="form-control timepicker" value="7:00">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                        </div>
                                    </td>


                                    <td><label class="col-md-12 d-block">Lady Officer</label></td>
                                    <td class="staff_image_temp"><img src="http://127.0.0.1:8000/avatar.jpg" class="img-circle user_image"></td>

                                    <td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff('tr_1')">Select Staff</a><br> <input data-validation="true" type="hidden" id="selected_staff-1" name="selected_staff[]"></td>
                                </tr> -->
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
                        <label class="control-label inline-block staff_type_labels " id="staff_type_label-1">
                            <input type="radio" data-typeid="1" id="staff_type_1" name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                            DoorSec Staff</label>

                        <label class="control-label inline-block staff_type_labels" id="staff_type_label-2">
                            <input type="radio" data-typeid="2" id="staff_type_2" name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                            Freelancer</label>

                        <label class="control-label inline-block staff_type_labels active" id="staff_type_label-3">
                            <input type="radio" checked=""  data-typeid="3" id="staff_type_3" name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
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
                            Schedule</button>
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

            // $("#venue_start_date").datepicker({
            //     format: "mm-yyyy",
            //     viewMode: "months",
            //     minViewMode: "months"
            // });

            $(".image_select2").select2();
            var count = 1;
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
                                            var cols = '<td class="remove_staff-td"><i class="fa fa-minus-circle"></i></td>';
                                            cols += '<td><label class="col-md-12 d-block"><select onchange="select_shift_type(\'' + 'tr_' + count + '\',this.value)" data-validation="true" class="form-control custom_css" style="width:100%" name="shift_type[]" id="shift_type"><option value="" selected> Select</option><option value="Day">Day</option><option value="Night">Night</option></select></label><input type="hidden" name="" value=""></td>';
                                            cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="start_time[]" class="form-control timepicker" value="" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                            cols += '<td style="width:22%"><div class="form-group col-md-12"><div class="input-group"><input type="text" readonly name="end_time[]" class="form-control timepicker" value="" onchange="calculateStaffHours(\'' + 'tr_' + count + '\');"> <div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></div></td>';
                                            cols += '<td class="">' + type + '<input type="hidden" name="assignment_type[]" value="' + assignment_type_id + '"></td>';
                                            cols += '<td class="staff_image_temp"><label class="label bg-red">No Staff</label></td>';
                                            cols += '<td><a type="button" href="javascript:;" class="btn btn-default select_staff" onclick="select_staff(\'' + 'tr_' + count + '\')">Select Staff</a><br> <input data-validation="true" required type="hidden" id="selected_staff-' + count + '" name="selected_staff[]" /></td>';


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
                                var cols = '<td class="remove_staff-td"><i class="fa fa-minus-circle"></i></td>';
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
                        title: 'Hey',
                        color: 'green', // blue, red, green, yellow
                        message: 'Staff added to shift successfully!'
                    });
                } else {
                    $('#' + $('#tr_shift_id').val()).find('td:nth-child(6)').html('<label class="label bg-red">No Staff</label>');
                    $('#' + $('#tr_shift_id').val()).find('td:nth-child(7)').find('input[name="selected_staff[]"]').val('');
                    //CheckValidation();
                }
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
                $(this).closest('tr').remove();
                if ($('table#table-venue_shifts tbody#venue_shifts_tbody tr').length == 0) {
                    $('table#table-venue_shifts').css('display', 'none');
                    $('#btnnn').css('display', 'none');
                    $('#add_staff_shiift-btn').css('display', 'none');
                    $('#venue_shift_section').css('display', 'none');

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
    var stafflist = $('#' + id).closest('tr').find('td:nth-child(7)').find('input[name="selected_staff[]"]').val();
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
    //console.log(stafflist);
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
            $('.staff_type_radio').removeAttr('checked');
            $('.staff_type_labels').removeClass('active');
            $('#staff_type_3').trigger('change');
            $('#staff_type_3').trigger('click');
            $('#staff_type_label-3').addClass(' active');
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