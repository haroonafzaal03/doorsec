<!---- SMS POPUP ---->

<div id="SendSMSPopup" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="send_sms_form" action="#" onsubmit="return false">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send SMS</span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="control-label dp-inline bold"> Staff </label>
                                </div>
                                <div class="col-md-12">
                                    <!-- <input class="form-control" type="text" id="recipient_no" name="recipient_no"   /> -->
                                    <input type="text" readonly="readonly" id="phone_number" name="phone_number"
                                        class="form-control" value="" data-role="tagsinput" />
                                    <input type="hidden" readonly="readonly" name="staff_ids" class="form-control"
                                        value="" data-role="" data-hidden="false" />
                                </div>
                                <div class="col-md-12 bulk_staff_temp_list sms">

                                </div>

                            </div>
                            <!--- /.row --->
                            <div id="appsss">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label class="">Arriving Time:</label>
                                        <div class="input-group">
                                            <input type="text" v-model="arrving_time" class="form-control" value=""
                                                id="arrving_time" name="arrving_time" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="">Briefing Time:</label>
                                        <div class="input-group">
                                            <input type="text" v-model="briefing_time" class="form-control" value=""
                                                id="briefing_time" name="briefing_time" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="">Location guide:</label>
                                        <div class="input-group">
                                            <input type="text" v-model="localtionGuide" class="form-control"
                                                name="event_loc_guide" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="">Dress Code:</label>
                                        <div class="input-group ">
                                            <textarea type="text" v-model="dressCode" class="form-control"
                                                id="event_dress_code" name="event_dress_code"
                                                autocomplete="off"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label class="">Siging Area/Meeting point:</label>
                                        <div class="input-group">
                                            <input type="text" v-model="signMeetPt" class="form-control" value=""
                                                id="signMeetPt" name="signMeetPt" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="">Start Time:</label>
                                        <div class="input-group">
                                            <input type="text" v-model="strtTimeE" class="form-control" value=""
                                                id="strtTimeE" name="strtTimeE" autocomplete="off">
                                            <small>formate: HH:MM i.e 12:00 or 02:59</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label dp-inline"> Message </label>
                                    </div>

                                    <div class="col-md-12">
                                        @php
                                        $mytime = Carbon\Carbon::now();
                                        @endphp

                                        <textarea class="form-control message_body" readonly rows="23" id="message_body"
                                            name="message_body">
DoorSec is covering {{ isset($schedule_data)?$schedule_data['event_name']:'' }} Live at {{ isset($schedule_data)?$schedule_data['location']:'' }} Location Guide @{{localtionGuide}}

Siging Area / Meeting point: @{{signMeetPt}}

Date: {{ isset($schedule_data)?$schedule_data['start_date'] :'' }} To {{ isset($schedule_data)?$schedule_data['end_date'] :'' }}

Reporting time: @{{arrving_time}}
Briefing: @{{briefing_time}}
Work start: @{{strtTimeE}}

Dress code: @{{dressCode}}

Please note that the briefing is very important and vital as this is our first major event at this new venue.

Please be there on time.

Please select this message and reply "YES" OR "NO" for confirm/deny this message at the earliest.

Thank you

DoorSec Management</textarea>
                                        <span class="text-red response_message bold"> </span>
                                    </div>
                                </div>
                            </div>
                            <!--- /.row --->
                            <div class="form-group row hide">
                                <div class="col-md-12">
                                    <label class="control-label dp-inline"> Status </label>
                                    <label class="control-label dp-inline m-l-20 muted_text label bg-yellow">
                                        Pending</label>
                                </div>
                                <!--- /.row --->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success send_sms_submit"> <i class="fa fa-send mr-"></i>
                            Send</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!----- /. SMS POPP ---->



<!-- Active & Deactivate Staff Modal -->

<div class="modal fade" id="staff_status_popup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="StaffStatusForm" action="#" method="POST" onsubmit="return false">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title module_title"> Update Status </h4>
                </div>
                <div class="modal-body">
                    <p class="bold action_message"></p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="control-label dp-inline"> Status </label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" id="staff_status" name="staff_status"
                                        required="required">
                                        <option value=""> Select</option>
                                        <option value="active">Active</option>
                                        <option value="deactivate">Deactivate</option>
                                    </select>
                                    <span class="response_message block bold " style="margin-top:10px"> &nbsp; </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 reason hide">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="control-label dp-inline"> Reason </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <textarea class="textarea form-control" name="reason" col="6" row="4"></textarea>
                                    <span class="response_message block bold " style="margin-top:10px"> &nbsp; </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning TriggerActionBtn"> Update </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="form-control" type="hidden" id="staffid" name="data_id" readonly="readonly" />
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Images lightbox Modal -->

<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" class="img-responsive" alt="" />
            </div>
        </div>
    </div>
</div>

<!-- Close Event Modal -->

<div class="modal fade" id="close_event_popup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="CloseEventForm" method="POST" action="#" onsubmit="return false">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Close Event</h4>
                </div>
                <div class="modal-body">
                    <p class="bold">If you want to close Event <span class="event_name text-blue"> </span> ,Please Enter
                        Your Password!</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="control-label dp-inline"> Password </label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="password" id="" name="input_password"
                                        required="required" />
                                    <span class="response_message block bold " style="margin-top:10px"> &nbsp; </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning apply_now"> Apply</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="form-control" type="hidden" id="" name="event_id" readonly="readonly" />
                </div>
            </form>
        </div>
    </div>
</div>

<!--- Schedule Venue Popup --->

<div class="modal fade" id="schedule_venue_modal" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="venue_schedule_form" action="{{route('venue_store')}}" onsubmit="return CheckValidation();"
                method="POST" novalidate="novalidate">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Schdeule Venue</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-xl-7">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label class="control-label dp-inline"> Select Client </label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <select name="client_id" id="venue_client_id" data-validation="true"
                                                        class="form-control image_select2 custom_css"
                                                        style="width:100%">
                                                        <option value=""> Select</option>
                                                        @if(isset($clients))
                                                        @foreach($clients as $obj)
                                                        <option value="{{$obj->id}}"
                                                            data-image="{{img($obj->client_logo)}}">
                                                            {{$obj->property_name}} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="bootstrap-timepicker row">
                                            <!--<label class="col-md-12 d-block">Event Dates </label>-->
                                            <div class="form-group col-md-6">
                                                <small><strong>Start : </strong></small>
                                                <div class="input-group">
                                                    <input type="text" readonly name="start_date" id="venue_start_date"
                                                        class="form-control datepicker" value="{{ date('m/d/Y') }}">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <small><strong>End : </strong></small>
                                                <div class="input-group">
                                                    <input type="text" readonly name="end_date" id="venue_end_date"
                                                        class="form-control datepicker" value="{{ date('m/d/Y') }}">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <small><strong>Number of Shifts </strong></small>
                                                <div class="input-group">
                                                    <input type="text" name="number_shifts" id="number_shifts"
                                                        class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2 col-lg-2 col-xl-3">
                                                <small><strong>From </strong></small>
                                                <div class="input-group">
                                                    <input type="text" readonly name="" id="outer_from"
                                                        class="form-control timepicker"
                                                        onchange="calculateStaffHours();">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                                <small><strong>To </strong></small>
                                                <div class="input-group">
                                                    <input type="text" readonly name="" id="outer_to"
                                                        class="form-control timepicker"
                                                        onchange="calculateStaffHours();">
                                                    <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                                <small><strong>Rate Per Hour </strong></small>
                                                <div class="input-group">
                                                    <input type="" class="form-control number_only" id="rate_per_hour"
                                                        name="" value="" data-name="rate_per_hour" autocomplete="off">
                                                </div>
                                            </div>
                                            <input type="hidden" id="hours-hidden">
                                            <div class="form-group col-md-2">
                                                <button class="btn btn-primary" id="add_shift"
                                                    style="margin-top: 16px;">Add Shift</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- time Picker -->
                                    <div class="col-md-12">
                                        <div class="bootstrap-timepicker row" id="venue_shift_section"
                                            style="overflow-y: scroll;height: calc(100vh - 270px);border-top: 3px solid grey;">
                                            <table class="table" id="table-venue_shifts">
                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th>Day</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Hours</th>
                                                        <th>Rate Per Hour</th>
                                                        <th>Staff</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="venue_shifts_tbody">

                                                    <!--<tr id="tr-proceed_staff_btn">
                                 <td></td>
                                 <td></td>
                                 <td>
                                    <button class="btn btn-success pull-right">Proceed To Staff</button></td>
                                 </tr>-->
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1" style="height: calc(100vh - 120px);">
                                    <div class="col-md-12 add_staff_to_shiift" style="margin-top: 300px;padding:0px;">
                                        <button class="btn btn-success pull-right" id="add_staff_shiift-btn"><i
                                                class="fa fa-arrow-left mr-1"> </i>ADD </button>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-4 vs_popup-staff-section">

                                    <div class="col-md-12 m-b-20 text-center staff_types">
                                        <label class="control-label inline-block staff_type_labels active"
                                            id="staff_type_label-1">
                                            <input type="radio" checked="" data-typeid="1" id="staff_type_1"
                                                name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                                            DoorSec Staff</label>

                                        <label class="control-label inline-block staff_type_labels"
                                            id="staff_type_label-2">
                                            <input type="radio" data-typeid="2" id="staff_type_2"
                                                name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                                            Freelancer</label>

                                        <label class="control-label inline-block staff_type_labels"
                                            id="staff_type_label-3">
                                            <input type="radio" data-typeid="3" id="staff_type_3"
                                                name="staff_type_radio" class=" staff_type_radio" autocomplete="off">
                                            Guard</label>
                                    </div>

                                    <div class="col-md-12 ">
                                        <label>Search </label>
                                        <input type="text" class="form-control" id="search"
                                            placeholder="Search by name">
                                    </div>
                                    <div class="col-md-12 customlist venue_calendar">
                                        <ul id="staffList_new" class="users_multi_listbox">
                                        </ul>
                                    </div>

                                    <!--<ol id="selected_staff">

								</ol>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" id="btnnn">Schedule</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Schedule Venue Modal -->


<!--- Edit Venue shift  Popup --->

<div class="modal fade" id="edit_venue_shift_modal" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close edit_venue_shift_modal-closebtn"
                    data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Venue Shift Detail</h4>
            </div>
            <a class="btn btn-warning pull-right send_message" id=""><i class="fa fa-whatsapp m-r-10"></i>Send Bulk SMS for Whole Venue Schedule</a>
            <!-- Edit Venue Body -->
            <div class="modal-body" id="edit_venue_schedule-body">
                <form id="edit_venue_shift_form" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <input id="venue_id_popup" type="hidden">
                                <input id="client_id_popup" type="hidden">
                                {{--<input id="shift_end_time-hidden" type="hidden">
                           <input id="shift_start_time-hidden" type="hidden">--}}
                                <div class="form-group row hide">
                                    <label class="control-label dp-inline">Start Time</label>
                                    &nbsp;<span id="shift_start_time"></span>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label dp-inline">Date</label>
                                    &nbsp;<span id="shift_date"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row hide">
                                    <label class="control-label dp-inline">End Time</label>
                                    &nbsp;<span id="shift_end_time"></span>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label dp-inline"># Staff Schedule</label>
                                    &nbsp; <span id="number_staff_scheduled"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    {{--<div class="col-md-3">
                              <label class="control-label dp-inline">Add Staff</label>
                           </div>
                           <div class="col-md-9">
                              <select name="" id="staff_list" class="form-control image_select2 custom_css" style="width:100%">
                              </select>
                           </div>--}}

                                    <div class="col-md-4">
                                        <label class="control-label dp-inline">Add Staff</label>&nbsp;<input
                                            type="checkbox" id="add_staff-checkbox" autocomplete="off">
                                    </div>
                                    <div id="add_staff_section">
                                        <div class="form-group col-md-4">
                                            <small><strong>From </strong></small>
                                            <div class="input-group">
                                                <input type="text" readonly="" name="" id="shift_start_time-hidden"
                                                    class="form-control timepicker" autocomplete="off">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <small><strong>To</strong></small>
                                            <div class="input-group">
                                                <input type="text" readonly="" name="" id="shift_end_time-hidden"
                                                    class="form-control timepicker" autocomplete="off">
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">&nbsp;</div>
                                        <div class="col-md-8 m-b-20 text-center staff_types">
                                            <label class="control-label inline-block staff_type_labels"
                                                id="staff_type_label-1">
                                                <input type="radio" data-typeid="1" id="staff_type_1"
                                                    name="staff_type_radio" class=" staff_type_labels_evp"
                                                    autocomplete="off">
                                                DoorSec Staff</label>

                                            <label class="control-label inline-block staff_type_labels"
                                                id="staff_type_label-2">
                                                <input type="radio" data-typeid="2" id="staff_type_2"
                                                    name="staff_type_radio" class=" staff_type_labels_evp"
                                                    autocomplete="off">
                                                Freelancer</label>

                                            <label class="control-label inline-block staff_type_labels"
                                                id="staff_type_label-3">
                                                <input type="radio" data-typeid="3" id="staff_type_3"
                                                    name="staff_type_radio" class=" staff_type_labels_evp"
                                                    autocomplete="off">
                                                Guard</label>
                                        </div>
                                        <div class="col-md-4">&nbsp;</div>
                                        <div class="col-md-8">
                                            <select name="" id="staff_list"
                                                class="form-control image_select2 custom_css" style="width:100%">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table id="shiftstaffDataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="" class="selectAll big_checkbox" name="" /></th>
                                        <!--<th>ID</th>-->
                                        <th>Name</th>
                                        <th>Contact Number</th>
                                        <!--<th>Nationality</th>-->
                                        <th>Staff Type</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Hours</th>
                                        <th>Rate per hour</th>
                                        <th>Status</th>
                                        <th>Message Response</th>
                                        <th>Availability</th>
                                        <th>SMS Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="staffDataTable_body">

                                </tbody>
                                <!--</tfoot>
                     <tr id="add_staff_row">
                           <td>&nbsp;</td>
                           <td colspan="7"><button type="button" class="btn btn-sucess" onclick="addNewStaffRow()" >Add Staff</button></td>
                        </tr>
                     </tfoot>-->
                            </table>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left hide" id="deleteShifBtn"> Delete
                            Shift</button>
                        <button type="button" value="1" name="send_bulk_sms" class="btn btn-warning pull-left send_message"
                            > <i class="fa fa-whatsapp m-r-10"></i>Send Bulk SMS for Whole Venue
                            Schedule</button>
                        <button type="button" value="1" name="send_payroll" onclick=""
                            class=" send_payroll_btn  btn btn-success pull-left"> <i class="fa fa-money m-r-10"></i>Send
                            Payroll</button>
                        <a class="btn btn-primary pull-left send_message hide" id="send_message">Bulk Message for whole venue
                            Schedule</a>
                        <button type="button" class="btn btn-warning" id="updateShiftStaffBtn">Update</button>
                        <button type="button" class="btn btn-default edit_venue_shift_modal-closebtn"
                            data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>



            <!-- Send Sms Body -->
            <div class="modal-body" id="send_sms_modal-body">
                <form id="send_sms_form-venue" action="#" onsubmit="return false">
                    @csrf
                    <div id="appss">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label class="">Arriving Time:</label>
                                <div class="input-group">
                                    <input type="text" v-model="arrving_timeV" class="form-control" value=""
                                        id="arrving_timeV" name="arrving_timeV" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="">Briefing Time:</label>
                                <div class="input-group">
                                    <input type="text" v-model="briefing_timeV" class="form-control" value=""
                                        id="briefing_timeV" name="briefing_timeV" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="">Location guide:</label>
                                <div class="input-group">
                                    <input type="text" v-model="localtionGuideV" class="form-control"
                                        name="localtionGuideV" id="localtionGuideV" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="">Siging Area/Meeting point:</label>
                                <div class="input-group">
                                    <input type="text" v-model="signMeetPt" class="form-control" value=""
                                        id="signMeetPt" name="signMeetPt" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="">Dress Code:</label>
                                <div class="input-group ">
                                    <textarea type="text" v-model="dressCodeV" class="form-control" id="dressCodeV"
                                        name="dressCodeV" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label dp-inline bold"> Staff </label>
                                    </div>
                                    <div class="col-md-12">
                                        <!-- <input class="form-control" type="text" id="recipient_no" name="recipient_no"   /> -->
                                        <input type="text" readonly="readonly" id="phone_number" name="phone_number"
                                            class="form-control hide" value="" data-role="tagsinput" />
                                        <input type="hidden" readonly="readonly" name="staff_ids" id="staff_ids"
                                            class="form-control" value="" data-role="" data-hidden="false" />
                                        <input type="hidden" readonly="readonly" name="client_ids" id="client_ids"
                                            class="form-control" value="" data-role="" data-hidden="false" />
                                        <input type="hidden" readonly="readonly" name="venue_ids" id="venue_ids"
                                            class="form-control" value="" data-role="" data-hidden="false" />
                                        <input type="hidden" readonly="readonly" name="clientName" id="clientName"
                                            class="form-control" value="" data-role="" data-hidden="false" />
                                        <input type="hidden" readonly="readonly" name="venueCurrentDate"
                                            id="venueCurrentDate" class="form-control"
                                            value="{{ $mytime->toDateString() }}" data-role="" data-hidden="false" />
                                    </div>
                                    <div class="col-md-12 bulk_staff_temp_list sms">

                                    </div>

                                </div>
                                <!--- /.row --->
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label dp-inline"> Message </label>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea class="form-control" readonly rows="23" id="message_body_evp"
                                            data-validation="true" name="message_body">
DoorSec is covering {{ isset($schedule_data)?$schedule_data['venue_name']:'' }} Live at {{ isset($schedule_data)?$schedule_data['location']:'' }} Location Guide @{{localtionGuideV}}

Siging Area / Meeting point: @{{signMeetPt}}

Date: {{ isset($schedule_data)?$schedule_data['start_date'] :'' }}

Reporting time: @{{arrving_timeV}}
Briefing: @{{briefing_timeV}}
Work start: {{ isset($schedule_data)?$schedule_data['start_time'] :'' }}

Dress code: @{{dressCodeV}}

Please note that the briefing is very important and vital as this is our first major event at this new venue.

Please be there on time.

Please select this message and reply "YES" OR "NO" for confirm/deny this message at the earliest.

Thank you

DoorSec Management</textarea>
                                        <span class="text-red response_message bold"> </span>
                                    </div>
                                </div>
                                <!--- /.row --->
                                <div class="form-group row hide">
                                    <div class="col-md-12">
                                        <label class="control-label dp-inline"> Status </label>
                                        <label class="control-label dp-inline m-l-20 muted_text label bg-yellow">
                                            Pending</label>
                                    </div>
                                    <!--- /.row --->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success send_sms_submit"> <i class="fa fa-send mr-"></i>
                            Send</button>
                        <button type="button" class="btn btn-default" id="send_sms_close-btn">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Venue shift Modal -->


<div class="modal modal-default fade" id="modal-success">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Success</h4>
            </div>
            <div class="modal-body">
                <p class="success_message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal modal-default fade" id="modal-shiftStaffList">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius: 4px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title staffListModalTitle">All 13</h4>
            </div>
            <div class="modal-body">
                <ul class="products-list product-list-in-box staffListUl">
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user4-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Samsung TV</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum.
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user3-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Bicycle</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum.
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user1-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Xbox One</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum.
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user6-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user7-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user8-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user3-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user5-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user4-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user3-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user6-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                    <!-- /.item -->
                    <li class="item">
                        <div class="product-img">
                            <img src="https://adminlte.io/themes/AdminLTE/dist/img/user6-128x128.jpg"
                                alt="Product Image">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">PlayStation 4</a>
                            <span class="product-description">
                                Loreum ipsum Loreum ipsum
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
                </ul>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- /.modal -->

<!--- Delete with Password Popup ---->

<div id="removeDataPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <form id="passDataForm" action="#" onsubmit="return false">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title module_title">Remove Staff </h4>
                </div>
                <div class="modal-body">
                    <p class="bold action_message">If You Want to Remove Record, Please Enter Your Password!</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="control-label dp-inline"> Password </label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="password" id="input_password"
                                        name="input_password" required="required" />
                                    <span class="response_message block bold " style="margin-top:10px"> &nbsp; </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger deleteBtnTrigger"> Delete </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="form-control" type="hidden" id="delete_data_id" name="data_id" readonly="readonly" />
                    <input class="form-control" type="hidden" id="action_on" name="action_on" readonly="readonly" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Staff From Schedule Confirmation Modal -->

<div class="modal fade" id="RemoveStaffSchConfirmation" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="removeStaffScheduleForm" action="#" onsubmit="return false">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Remove Staff Schedule</h4>
                </div>
                <div class="modal-body">
                    <p class="bold">If you want to Delete Selected Staff from Schedule,Please Enter Your Password!</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="control-label dp-inline"> Password </label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="password" id="input_password"
                                        name="input_password" required="required" />
                                    <span class="response_message block bold " style="margin-top:10px"> &nbsp; </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger deleteBtnTrigger"> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="form-control" type="hidden" id="" name="data_id" readonly="readonly" />
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Remove Staff From Schedule Confirmation Modal -->

<div class="modal fade" id="sendToPayroll" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="sendToPayrollform" action="#" onsubmit="return false">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sent Staff to PayRoll</h4>
                </div>
                <div class="modal-body">
                    <p class="bold">If you want to Send Shown Staff to Payroll,Please Enter Your Password!</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="control-label dp-inline"> Password </label>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" type="password" id="input_password"
                                        name="input_password" required="required" />
                                    <span class="response_message_send_to_payroll block bold " style="margin-top:10px">
                                        &nbsp; </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success submit_password"> Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input class="form-control" type="hidden" id="" name="data_id" readonly="readonly" />
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_staff_disciplinaries" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_staff_disciplinaries" action="{{route('disciplinaries_store')}}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Staff Disciplinaries</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 row">
                                <div class="form-group">
                                    <label class="control-label dp-inline"> staff name: </label>
                                    <span id="staff_name">{{ (isset($staff->name) ?$staff->name:'')}}</span>
                                    <input class="form-control" type="hidden" id="staff_id" name="staff_id"
                                        value="{{ (isset($staff->id) ?$staff->id:'')}}" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Letter type</label>
                                    <select class="form-control" name="letter_type">
                                        <option value=" ">Select</option>
                                        <option value="Letter of Appreciation">Letter of Appreciation</option>
                                        <option value="Statement">Statement</option>
                                        <option value="Disciplinary Records">Disciplinary Records</option>
                                        <option value="Warning letters">Warning letters</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Attach document</label>
                                    <input class="form-control" type="file" id="document_name" name="document_name" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Admin notes</label>
                                    <textarea class="form-control" id="admin_notes" name="admin_notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success submit_staff_disciplinaries"> Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_staff_certificate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_staff_certificate" action="{{route('staff_certificate_add')}}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Staff Certificate</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 row">
                                <div class="form-group">
                                    <label class="control-label dp-inline"> staff name: </label>
                                    <span id="staff_name">{{ (isset($staff->name) ?$staff->name:'')}}</span>
                                    <input class="form-control" type="hidden" id="staff_id" name="staff_id"
                                        value="{{ (isset($staff->id) ?$staff->id:'')}}" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Certificate Name</label>
                                    <select class="form-control" name="document_name">
                                        <option value=" ">Select</option>
                                        <option value="Fire Fighter">Fire Fighter</option>
                                        <option value="POD">POD</option>
                                        <option value="First Aid">First Aid</option>
                                        <option value="sira">Sira</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Attach document</label>
                                    <input class="form-control" type="file" id="staff_certificate" name="staff_certificate[]" multiple/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success submit_staff_disciplinaries"> Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_staff_disciplinaries_update" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_staff_disciplinaries_update" action="" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Staff Disciplinaries</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 row">
                                <div class="form-group">
                                    <label class="control-label dp-inline"> staff name: </label>
                                    <span id="staff_name">{{ (isset($staff->name) ?$staff->name:'')}}</span>
                                    <input class="form-control" type="hidden" id="staff_id" name="staff_id"
                                        value="{{ (isset($staff->id) ?$staff->id:'')}}" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Letter type</label>
                                    <select class="form-control" name="letter_type">
                                        <option value=" ">Select</option>
                                        <option value="Letter of Appreciation">Letter of Appreciation</option>
                                        <option value="Statement">Statement</option>
                                        <option value="Disciplinary Records">Disciplinary Records</option>
                                        <option value="Warning letters">Warning letters</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Attach document</label>
                                    <input class="form-control" type="file" id="document_name" name="document_name" />
                                    <a id="download_attachment" href="" download></a>
                                </div>
                                <div class="form-group">
                                    <label class="control-label dp-inline"> Admin notes</label>
                                    <textarea class="form-control" id="admin_notes" name="admin_notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success submit_staff_disciplinaries"> Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>