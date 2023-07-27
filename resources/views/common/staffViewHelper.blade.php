@php
$sch_status_list = get_schedule_status_list();
$staff_sch_status_list = get_staff_schedule_status_list();
$sms_status_list = get_sms_status_list();
@endphp

@if($result)
@php
$indexx = 0;
@endphp
@foreach($result as $key => $cl)
<tr id="tr-{{$cl->ss_id}}" data-start_time="{{$cl->start_time}}" data-venue_id="{{ $cl->venue_id }}"
    data-index="{{ ($row_index) ? $row_index + 1 :  $key }}" data-ii="{{$row_index}}">
    <input type="hidden" name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][id]" value="{{$cl->ss_id}}" />
    <input type="hidden" class="venue_detail_id"
        name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][venue_detail_id]"
        value="{{$cl->venue->venue_detail_id}}" />
    <input type="hidden" name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][venue_id]"
        value="{{$cl->venue_id}}" />
    <input type="hidden" name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][staff_sch_id]"
        value="{{$cl->ss_id}}" />
    <input type="hidden" name="" id="bulk_sms_data_{{$cl->id}}"
        data-venue_name="{{ $cl->venue->client->property_name }} " data-start_time="{{$cl->start_time}}"
        data-end_time="{{$cl->end_time}}" data-venue_date="{{$cl->ss_date}}" />
    <td><input type="checkbox" id="staff_checkbox-{{$cl->ss_id}}" class="staff_sch_check big_checkbox" name=""
            data-contact="{{ $cl->contact_number }} " data-venue_id="{{ $cl->venue_id }}"
            data-staff_status="{{ $cl->ss_status }}" data-staff_id="{{ $cl->id }}" />

    </td>

    <td class="staff_image_temp"><img src="{{img($cl->picture)}}" class="img-circle user_image" /> <span
            class="username"> {{$cl->name}} </span>
        <input type="hidden" name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][staff_id]" value="{{ $cl->id }}">
    </td>
    <td>{{$cl->contact_number}}</td>
    <td>{{$cl->type}}</td>
    <td>
        <div class="input-group">
            <input type="text" readonly value="{{$cl->start_time}}"
                name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][start_time]"
                class="form-control timepicker shift_start_time" onchange="calculateStaffHours('tr-{{$cl->ss_id}}',1);">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="text" readonly value="{{$cl->end_time}}"
                name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][end_time]"
                class="form-control timepicker shift_end_time" onchange="calculateStaffHours('tr-{{$cl->ss_id}}',1);">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
        </div>
    </td>
    <td class=""><input type="" style="width:100%;" class="form-control hours" id=""
            name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][hours]" data-name="hours" value="{{ $cl->hours }}"
            autocomplete="off"></td>
    <td><input type="" style="width:100%;" class="form-control number_only" id="rph-{{$cl->ss_id}}"
            name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][rate_per_hour]" value="{{ $cl->rate_per_hour }}"
            data-name="rate_per_hour" {{ ($cl->availability == 0) ? 'readonly="readonly"' : '' }} />
    </td>
    <td class="" id="td_staff_status-{{$cl->staff->id}}">
        <select class="form-control staff_status_select" id=""
            name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][staff_sch_status]" data-name="status">
            <option value="" disabled>Select</option>
            @if($staff_sch_status_list)
            @foreach($staff_sch_status_list as $keys => $arr)
            <option value="{{$keys}}" {{ ($cl->ss_status == $keys ) ? "selected" : "" }}>{{$arr}}</option>
            @endforeach
            @endIf
        </select>
    </td>

    <td>
        @if(!empty($cl->wa_response))
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#WA_Response-{{$cl->staff->id}}">
            <i class="fa fa-whatsapp"></i>
        </button>
        @endif
        <div class="modal fade" id="WA_Response-{{$cl->staff->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        Message : <span class="bold">{{ (!empty($cl->wa_response)) ? $cl->wa_response : '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td class="">
        <select id="" class="form-control allow_check" data-id="{{$cl->ss_id}}" id="availability-{{$cl->id}}"
            name="arrayC[{{ ($row_index) ? $row_index + 1 :  $key }}][availability]">
            <option value="1" {{ ( ($cl->availability == 1) ) ? "selected" : ""}}> shown </option>
            <option value="0" {{ ( ($cl->availability == 0) ) ? "selected" : ""}}> not shown</option>
        </select>
        <label class="availability_label hide"> <input type="checkbox" {{ ($cl->availability) ? 'checked' : 0 }}
                class="allow_check-off" /></label>
    </td>
    <td class="td-ss-sms-status" width="10%"><label
            class="label {{    get_label_class_by_key($cl->ss_sms_status)}}">{{ get_status_name_by_key ($cl->ss_sms_status,'sms')}}</label>
        @if($cl->ss_sms_status == "not_sent")
        @if($cl->ss_sms_status != "dropout")
        <button type="button" data-toggle="modal" data-target="" data-staff_id="{{$cl->id}}"
            data-contact="{{$cl->contact_number}}" data-staff_name="{{$cl->name}}" id="sms_status_btn-{{$cl->ss_id}}"
            class="staff_sms_btn btn btn-sm btn-warning pull-right" onclick="initializeSmsTrigger(this.id)"><i
                class="fa fa-whatsapp"></i> </button>
        @endif
        @endif

    </td>
    <td>
        <div class="btn-group">

            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" style="background-color:#fff" role="menu">
                <!--<li><a href="{{route('staff_show',$cl->id)}}">View Details</a></li>
                        <li><a href="{{route('staff_edit',$cl->id)}}">Edit</a></li>
                        <li><a href="#" class="updateStatusAnchor" data-id =" {{ $cl->id }} "  data-target="#staff_status_popup" data-toggle="" data-status="{{ $cl->status }}" > Update Status</a></li>-->
                <li><a href="javascript:;" class="blockStaffFromShiftAnchor" data-staff-id="{{$cl->id}}"
                        data-ss-id="{{$cl->ss_id}}" data-val="{{$cl->ss_status}}" data-toggle=""
                        data-val="{{$cl->block_for_clients}}">Block / Un Block Staff From Shift</a>
                </li>
                <li>
                    <a href="javascript:;" class="removeDataAnchor" data-id="{{$cl->ss_id}}"
                        data-target="#removeDataPopup" href="#" onclick="//removeStaffFromShift({{$cl->ss_id}});">Remove
                        staff</a>
                </li>
            </ul>
        </div>
    </td>
</tr>
@endforeach
@endif