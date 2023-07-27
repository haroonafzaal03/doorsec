@extends('layouts.master')
@section('content')
    <style>
        table {
            border-collapse: separate;
            border-top: 3px solid grey;
        }

        td,
        th {
            margin: 0;
            border-top-width: 0px;
            white-space: nowrap;
        }

        tr.zt {
            height: 32px !important;
        }

        tr.zt > th {
            background: #eeeeee;
            color: #000;
        }

        td,
        th {
            font-size: 13px;
        }

        select.form-control.guarding_sch_inputs {
            -webkit-appearance: none;
            cursor: pointer;
        }

        option {
            font-weight: bold;
        }

    </style>


    <section class="content bg-white">

        @php
            $schedule_start = Carbon\Carbon::parse($data->start_date);
            $schedule_end = Carbon\Carbon::parse($data->end_date);
            $total_days = $schedule_end->diffInDays($schedule_start);
        @endphp
        <div class="row" style="padding: 0px 15px">
            <div class="box box-success collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Guardings Details</h3>
                    <a class="btn btn-primary" href="{{route('export_guardingSchedule',$data->id)}}"> Export</a>
                    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right m-r-5 m-t-5">
                        <select name="" id="" class="form-control">
                            <option value="" Selected>Legends</option>
                            <option value="">D: Day</option>
                            <option value="">N: Night</option>
                            <option value="">A: Afternoon</option>
                            <option value="">LD: Late Day</option>
                            <option value="">E: Evening</option>
                            <option value="">ABS: Absent</option>
                            <option value="">SL: Sick Leave</option>
                            <option value="">AL: Annual Leave</option>
                            <option value="">EL: Emergency Leave</option>
                            <option value="">UL: Unpaid Leave</option>
                            <option value="">OFF: Day Off</option>
                            <option value="">OWD: Off Working Day</option>
                            <option value="">OWN: Off Working Night</option>
                            <option value="">T: Training</option>
                            <option value="">OT: Overtime</option>
                            <option value="">ED: Event Duty</option>
                            <option value="">PH: Public Holiday</option>
                        </select>
                    </div>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label>Client Name : </label>
                            <small>{{$data->client->property_name}}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Start Date : </label>
                            <small>{{($data) ?\Carbon\Carbon::parse($data->start_date)->format('m/d/Y') : '' }}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>End Date : </label>
                            <small>{{($data) ?\Carbon\Carbon::parse($data->end_date)->format('m/d/Y') : '' }}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Day Shift From :</label>
                            <small>{{$data->day_start_time}}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Day Shift To :</label>
                            <small>{{$data->day_end_time}}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Require Staff Day :</label>
                            <small>{{$data->require_staff_day}}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Night Shift From :</label>
                            <small>{{$data->night_start_time}}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Night Shift To :</label>
                            <small>{{$data->night_end_time}}</small>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>Require Staff Night :</label>
                            <small>{{$data->require_staff_night}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div>
            <div class="col-md-12">
                <div id="guarding_calendar_block" class="">
                    <form enctype="multipart/form-data" id="guarding_schedule_form" name="guarding_schedule_form">
                        <input type="hidden" name="guarding_id" id="guarding_id" value="{{$data->id}}">
                        @csrf
                        <table id="guarding_schedule_table" class="table  table-bordered table-sm venue_calendar_table">
                            <thead class="zt-head">
                            <tr class="row1">
                                <th width="100px" class="headcol heading " colspan="2">REVISION DATE</th>
                                <th width="100px" class="text-center heading" colspan="{{$total_days+1}}">
                                    {{Carbon\Carbon::parse($data->start_date)->format('F, Y')}}</th>
                            </tr>
                            <tr class="row2">
                                <th width="100px" class="headcol fixed  col1">
                                    {{Carbon\Carbon::parse($data->start_date)->format('l F d, Y')}}
                                </th>
                                <th width="100px" class="fixed_th  col2"><i class="fa fa-angle-right bold hide"></i>
                                </th>

                                @if($total_days)
                                    @for($i = 0;$i<=$total_days;$i++)
                                        <th width="100px" class=""
                                            data-day="{{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('D')}}">
                                    <span>
                                        {{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('D')}}
                                    </span>
                                        </th>
                                    @endfor
                                @endif
                                <th rowspan="2" class="absent_col abs bg-reds vertical-align-middle">ABS</th>
                                <th rowspan="2" class="overtime_col abs  bg-blues s vertical-align-middle">Over <br>
                                    Time
                                </th>
                                <th rowspan="2" class="sick_leave_col abs bg-greenss vertical-align-middle">SL</th>
                                <th rowspan="2" class="off_leave_col abs bg-greenss vertical-align-middle">OFF</th>
                            </tr>
                            <tr class="row3">
                                <th width="100px" class="headcol fixed  col1"> Staff Type</th>
                                <th width="100px" class="fixed_th  col2"> Name</th>
                                @if($total_days)
                                    @for($i = 0;$i<=$total_days;$i++)
                                        <th width="100px" class="month_dates"
                                            data-date="{{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('d-m-Y')}}">
                                    <span>
                                        {{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('d')}}
                                    </span>
                                        </th>
                                    @endfor
                                @endif

                            </tr>
                            </thead>

                            <tbody class="zt-body">
                            @if($data->guarding_schedule)
                                <input type="hidden" id="count_total_staff" value="{{count($data->guarding_schedule)}}">
                                @foreach($data->guarding_schedule as $gss)
                                    @if($gss)
                                        <tr class="row4">
                                            <td width="100px" class="headcol bold bg-grey  col1" colspan="2"
                                                style="height: 58px;">
                                                {{$gss->staffschedule->sira_type->type}}</td>
                                            <td width=""
                                                class="fixed_name_col bold  bg-grey  col2 shift_type_{{$gss->staffschedule->shift_type}}"
                                                data-shift-type="{{$gss->staffschedule->shift_type}}"
                                                data-gender="{{$gss->staffschedule->staff->gender}}" colspan=""
                                                style="height: 58px;">
                                                G-00{{$gss->staffschedule->staff->id}}
                                                -- {{$gss->staffschedule->staff->name}}
                                                <input type="hidden" name="staff_id[]"
                                                       value="{{$gss->staffschedule->staff->id}}">
                                                <input type="hidden" id="variance_day_night"
                                                       data-day-variance="{{$data->require_staff_day}}"
                                                       data-night-variance="{{$data->require_staff_night}}">
                                            </td>
                                            @if($total_days)

                                            @php

                                            $day=$gss->day?json_decode($gss->day):[];
                                            $night=$gss->night?json_decode($gss->night):[];
                                            $afternoon=$gss->afternoon?json_decode($gss->afternoon):[];
                                            $late_day=$gss->late_day?json_decode($gss->late_day):[];
                                            $evening=$gss->evening?json_decode($gss->evening):[];
                                            $absent=$gss->absent?json_decode($gss->absent):[];
                                            $sick_leave=$gss->sick_leave?json_decode($gss->sick_leave):[];
                                            $annual_leave=$gss->annual_leave?json_decode($gss->annual_leave):[];
                                            $emergency_leave=$gss->emergency_leave?json_decode($gss->emergency_leave):[];
                                            $unpaid_leave=$gss->unpaid_leave?json_decode($gss->unpaid_leave):[];
                                            $day_off=$gss->day_off?json_decode($gss->day_off):[];
                                            $off_working_night=$gss->off_working_night?json_decode($gss->off_working_night):[];
                                            $off_working_day=$gss->off_working_day?json_decode($gss->off_working_day):[];
                                            $training=$gss->training?json_decode($gss->training):[];
                                            $overtime=$gss->overtime?json_decode($gss->overtime):[];
                                            $event_day=$gss->event_day?json_decode($gss->event_day):[];
                                            $public_holiday=$gss->public_holiday?json_decode($gss->public_holiday):[];
                                           if(count($day))
                                           {
                                            //    dd($day);
                                           }
                                            @endphp
                                                @for($i = 0;$i<=$total_days;$i++)

                                                    @php
                                                        $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y');

                                                            $legends_ = '';


                                                                if(in_array($dayss,$day))
                                                                            {
                                                                            $daysSelected= 'selected=selected';

                                                                        }else
                                                                        {
                                                                            $daysSelected='';
                                                                        }

                                                                 if(in_array($dayss,$night))
                                                                            {
                                                                            $nightSelected= 'selected=selected';

                                                                        }else
                                                                        {
                                                                            $nightSelected='';
                                                                        }

                                                                if(in_array($dayss,$afternoon))
                                                                            {

                                                                            $afternoonSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $afternoonSelected='';
                                                                        }

                                                                if(in_array($dayss,$late_day))
                                                                            {
                                                                            $late_daySelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $late_daySelected='';
                                                                        }
                                                                if(in_array($dayss,$evening))
                                                                            {
                                                                            $eveningSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $eveningSelected='';
                                                                        }


                                                                        $absentSelected = '0987650';
                                                                if(in_array($dayss,$absent))
                                                                            {
                                                                            $absentSelected= 'selected=selected';
                                                                        }



                                                                if(in_array($dayss,$sick_leave))
                                                                            {
                                                                            $sick_leaveSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $sick_leaveSelected='';
                                                                        }


                                                                if(in_array($dayss,$annual_leave))
                                                                            {
                                                                            $annual_leaveSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $annual_leaveSelected='';
                                                                        }


                                                                if(in_array($dayss,$emergency_leave))
                                                                            {
                                                                            $emergency_leaveSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $emergency_leaveSelected='';
                                                                        }



                                                                if(in_array($dayss,$unpaid_leave))
                                                                            {
                                                                            $unpaid_leaveSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $unpaid_leaveSelected='';
                                                                        }



                                                                if(in_array($dayss,$day_off))
                                                                            {
                                                                            $day_offSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $day_offSelected='';
                                                                        }



                                                                if(in_array($dayss,$off_working_night))
                                                                            {
                                                                            $off_working_nightSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $off_working_nightSelected='';
                                                                        }



                                                                if(in_array($dayss,$off_working_day))
                                                                            {
                                                                            $off_working_daySelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $off_working_daySelected='';
                                                                        }


                                                                if(in_array($dayss,$training))
                                                                            {
                                                                            $trainingSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $trainingSelected='';
                                                                        }


                                                                if(in_array($dayss,$overtime))
                                                                            {
                                                                            $overtimeSelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $overtimeSelected='';
                                                                        }


                                                                if(in_array($dayss,$event_day))
                                                                            {
                                                                            $event_daySelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $event_daySelected='';
                                                                        }


                                                                if(in_array($dayss,$public_holiday))
                                                                            {
                                                                            $public_holidaySelected= 'selected=selected';
                                                                        }else
                                                                        {
                                                                            $public_holidaySelected='';
                                                                        }

                                                    @endphp
                                                    <td width="100px" id="137">
                                                        <input type="hidden" class="legends_value" name=""
                                                               data-staff-id="{{$gss->staffschedule->staff->id}}"
                                                               value="{{$dayss}}">
                                                        <select style="width:60px" type="text"
                                                                class="form-control total_attendance guarding_sch_inputs guarding_date_{{$dayss}} {{$gss->staffschedule->shift_type}}_1 lgends_off_{{$gss->staffschedule->staff->id}} {{$dayss}}_staff_type_{{$gss->staffschedule->sira_type->id}} gender_{{$gss->staffschedule->staff->gender}}_{{$dayss}}"
                                                                selected="selected">
                                                            <option value="" selected disabled></option>
                                                            <option data-name="day_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="day" {{$daysSelected??''}}>
                                                                D
                                                            </option>

                                                            <option data-name="night_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="night" {{$nightSelected??''}}>
                                                                N
                                                            </option>

                                                            <option data-name="special_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="afternoon" {{$afternoonSelected??''}}>
                                                                A
                                                            </option>
                                                            <option data-name="day_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="late_day" {{$late_daySelected??''}}>
                                                                LD
                                                            </option>
                                                            <option data-name="night_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="evening" {{$eveningSelected??''}}>
                                                                E
                                                            </option>
                                                            <option data-name="{{$gss->absent}}" data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="absent" {{$absentSelected??''}}>
                                                                ABS
                                                            </option>
                                                            <option data-name="{{$gss->sick_leave}}" data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="sick_leave" {{$sick_leaveSelected??''}}>
                                                                SL
                                                            </option>
                                                            <option data-name="{{$gss->annula_leave}}" data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="annula_leave" {{$annual_leaveSelected??''}}>
                                                                AL
                                                            </option>
                                                            <option data-name="unpaid_leave_shifts" data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="emergency_leave" {{$emergency_leaveSelected??''}}>
                                                                EL
                                                            </option>
                                                            <option data-name="unpaid_leave_shifts" data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="unpaid_leave" {{$unpaid_leaveSelected??''}}>
                                                                UL
                                                            </option>
                                                            <option data-name="{{$gss->day_off}}" data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="day_off" {{$day_offSelected??''}}>
                                                                OFF
                                                            </option>
                                                            <option data-name="night_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="off_working_night" {{$off_working_nightSelected??''}}>
                                                                OWN
                                                            </option>
                                                            <option data-name="day_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="off_working_day" {{$off_working_daySelected??''}}>
                                                                OWD
                                                            </option>
                                                            <option data-name="{{$gss->trainig}}" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="trainig"{{$trainigSelected??''}}>
                                                                T
                                                            </option>
                                                            <option data-name="{{$gss->overtime}}" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="overtime" {{$overtimeSelected??''}}>
                                                                OT
                                                            </option>
                                                            <option data-name="special_shifts" data-present="yes"
                                                                    data-value="{{$dayss}}"
                                                                    value="event_day" {{$event_daySelected??''}}>
                                                                ED
                                                            </option>
                                                            <option data-name="{{$gss->public_holiday}}"
                                                                    data-present="no"
                                                                    data-value="{{$dayss}}"
                                                                    value="public_holiday" {{$public_holidaySelected??''}}>
                                                                PH
                                                            </option>
                                                        </select>
                                                    </td>

                                                @endfor
                                            @endif

                                            <td width="55px" class="absent_input_abs" id="137">
                                                <input
                                                    class=" absent_input_{{$gss->staffschedule->staff->id}} abs text-center bg-transparent"
                                                    style="width:100%" value="" type="text" autocomplete="off"/>
                                            </td>
                                            <td width="55px" class="overtime_input_abs" id="137">
                                                <input
                                                    class=" overtime_input_{{$gss->staffschedule->staff->id}} abs text-center bg-transparent"
                                                    style="width:100%" value="" type="text" autocomplete="off"/>
                                            </td>
                                            <td width="55px" class="sick_leave_input_abs" id="137">
                                                <input
                                                    class=" sick_leave_input_{{$gss->staffschedule->staff->id}} abs text-center bg-transparent"
                                                    style="width:100%" value="" type="text" autocomplete="off"/>
                                            </td>
                                            <td width="55px" class="off_leave_input_abs" id="137">
                                                <input
                                                    class=" off_leave_input_{{$gss->staffschedule->staff->id}} abs text-center bg-transparent"
                                                    style="width:100%" value="" type="text" autocomplete="off"/>
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr class="row2 zt">
                                <th width="100px" class="headcol fixed  col1"
                                    style="text-align:left !important;background:#7030a1;color:white;">Day Shift:
                                    {{Carbon\Carbon::parse($data->day_start_time)->format('H:i a')}} To
                                    {{Carbon\Carbon::parse($data->day_end_time)->format('H:i a')}}
                                    <span class="pull-right">{{gmdate('H.I ', Carbon\Carbon::parse($data->day_end_time)->diffInSeconds(Carbon\Carbon::parse($data->day_start_time)))}}
                                        Hrs</span>
                                </th>
                                <th width="100px" class="fixed_th  col2"><i class="fa fa-angle-right bold hide"></i>
                                </th>
                                @if($total_days)
                                    @for($i = 0;$i<=$total_days;$i++)
                                        <th width="100px" class=""
                                            data-day="{{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('D')}}">
                                    <span>
                                        {{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('D')}}
                                    </span>
                                        </th>
                                    @endfor
                                @endif

                                <th class="off_leave_col abs"
                                    style="width: 215px;height: 3em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;padding-top: 0.7em;">
                                    Unpaid/Emergency Leaves
                                </th>
                            </tr>
                            <tr class="row3 zt">
                                <th width="100px" class="headcol fixed  col1"
                                    style="text-align:left !important;width: 24.25em !important;background:#7030a1;color:white;">
                                    Night Shift:{{Carbon\Carbon::parse($data->night_start_time)->format('H:i a')}} To
                                    {{Carbon\Carbon::parse($data->night_end_time)->format('H:i a')}}
                                    <span class="pull-right">{{gmdate('H.I ', Carbon\Carbon::parse($data->night_end_time)->diffInSeconds(Carbon\Carbon::parse($data->night_start_time)))}}
                                        Hrs</span>
                                </th>
                                @if($total_days)
                                    @for($i = 0;$i<=$total_days;$i++)
                                        <th width="100px" class=""
                                            data-date="{{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('d')}}">
                                    <span>
                                        {{Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('d')}}
                                    </span>
                                        </th>
                                    @endfor
                                @endif
                                <th class="off_leave_col abs" id="total_unpaid_leave_shifts"
                                    style="width: 215px;height: 3em;BACKGROUND: #0f2442 !IMPORTANT;color: white !important;padding-top: 0.7em;">
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: center;padding: 35px; height: 115px !important;">Total
                                </td>
                                <td width="" class="fixed_name_col bold  bg-grey  col2" colspan=""
                                    style="height: 58px;">DAY
                                    SHIFT - <span id="count_day_shift">{{$data->require_staff_day}}</span></td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs day_shift_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">
                                    Special Shifts
                                </th>

                            </tr>
                            <tr class="row4">
                                <td width="" class="headcol_zt fixed_name_col bold  bg-grey  col2" colspan=""
                                    style="height: 58px;">
                                    VARIANCE / SHORTAGES
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs day_variance_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs" id="total_special_shifts"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: white !important;text-align: center;">
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: center;padding: 35px; height: 115px !important;">Total
                                </td>
                                <td width="" class="fixed_name_col bold  bg-grey  col2" colspan=""
                                    style="height: 58px;">
                                    NIGHT SHIFT - <span id="count_night_shift">{{$data->require_staff_night}}</span>
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs night_shift_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">
                                    Annual Leaves
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="" class="headcol_zt fixed_name_col bold  bg-grey  col2" colspan=""
                                    style="height: 58px;">
                                    VARIANCE / SHORTAGES
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs  night_variance_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs" id="total_annual_leaves"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: white !important;text-align: center;">
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">
                                    Total day off per day
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs off_shift_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">
                                    Total Absents
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">
                                    Total Sick leave per day
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs sickleave_shift_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs" id="total_absents"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: white !important;text-align: center;">
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">
                                    Total Absent per day
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs absent_shift_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">
                                    O.T Days
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">
                                    Total of security officers both shifts
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs total_security_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs" id="total_overtime"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: white !important;text-align: center;">
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">
                                    Total of Male security officers both shifts
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs male_security_{{$dayss}}"
                                                   readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">
                                    Total Trainings
                                </th>
                            </tr>
                            <tr class="row4">
                                <td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2"
                                    style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">
                                    Total of Female security officers both shifts
                                </td>
                                @if($total_days)
                                    @if(isset($gss))
                                        @for($i = 0;$i<=$total_days;$i++) @php $dayss=Carbon\Carbon::parse($gss->staffschedule->start_date)->add($i,'days')->format('d-m-Y'); @endphp
                                        <td width="100px">
                                            <input style="width:44px" type="text"
                                                   class="form-control guarding_sch_inputs female_security_{{$dayss}}"
                                                   value="" readonly disabled>
                                        </td>
                                        @endfor
                                    @endif
                                @endif
                                <th class="off_leave_col abs" id="total_training"
                                    style="width: 215px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: white !important;text-align: center;">
                                </th>
                            </tr>
                            </tbody>

                        </table>

                    </form>
                </div>
            </div>
        </div>
        <!--- /.row ---->

        <!-- Main row -->

        <!-- /.row -->
    </section>

@endsection


@section('content_js')
    <script src="{{ asset('js/zt-scheduler.js')}}"></script>
    <script >

        var summaryCalculations=[]

        //window.onload = function(){   }
        loader_open();
        $(document).ready(function () {

            var day1=document.getElementsByClassName("Day_1");
            var day1Length=day1.length;
            for(i=0;i<day1Length;i++)
            {
                var value=day1[i].value;
                if (value == "" || value == 0 || value == null) {
                    $(day1[i]).find('option[value=day]').attr('selected', true);

                }
            }

            var night1=document.getElementsByClassName("Night_1");

            var night1Length=night1.length
            for(i=0;i<night1Length;i++)
            {
                var value=night1[i].value;
                if (value == "" || value == 0 || value == null) {
                    $(night1[i]).find('option[value=night]').attr('selected', true);

                }
            }

            var legends_value=document.getElementsByClassName("legends_value");
            var legends_valueLength=legends_value.length
            for(i=0;i<legends_valueLength;i++)
            {
                var name = $(legends_value[i]).next('.guarding_sch_inputs').val();
                var staff_id = $(legends_value[i]).attr('data-staff-id');
                var col_dates = $(legends_value[i]).val();
                if (name != "" && name != 0 && name != null) {
                    $(legends_value[i]).attr('name', name + '_' + staff_id + '[]');
                }
                summaryCalculations.push({
                    staff_id:staff_id,
                    col_dates:col_dates
                });

            }


            $('.guarding_sch_inputs').on('change', function () {
                var name = $(this).val();
                var staff_id = $(this).prev('.legends_value').attr('data-staff-id');
                var col_dates = $(this).prev('.legends_value').val();
                if (name != "" && name != 0 && name != null) {
                    $(this).prev('.legends_value').attr('name', name + '_' + staff_id + '[]');
                }
                $('#guarding_schedule_form').submit();
                summary_calculate(staff_id, col_dates);
            });

            $('#count_day_shift').text($('.shift_type_Day').length);
            $('#count_night_shift').text($('.shift_type_Night').length);
            loader_close();
            setTimeout(function () {
                loader_close();

            }, 1000);

            var timeOut=setTimeout(function(){
                summaryCalculations.forEach((calculation,index)=>{
                    summary_calculate(calculation.staff_id,calculation.col_dates);

                })
            },1000);
            // window.clearTimeout(timeOut);
        });
        $('#guarding_schedule_form').submit(function () {
            var guard_id = $('#guarding_id').val();
            var formData = $("form#guarding_schedule_form").serialize();
            //return false;
            var myJsonData = {
                formData: formData
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('/guarding_scheduleupdate/' + guard_id, myJsonData, function (response) {
                if (response == "true") {
                    // alert("Changes Save Successfully !");
                    iziToast.show({
                        title: 'Congrats!',
                        color: 'green', // blue, red, green, yellow
                        message: 'Changes Save Successfully!'
                    });
                } else {
                    iziToast.show({
                        title: 'Sorry!',
                        color: 'red', // blue, red, green, yellow
                        message: 'SomeThing went wrong!'
                    });
                }
            });
            return false;
        })

        var index=0;


         function summary_calculate(staff_id, date) {
            /* rows summary */
            setTimeout(function(){

            var absent = $('.lgends_off_' + staff_id + ' option:selected[value=absent]').length;
            var overtime = $('.lgends_off_' + staff_id + ' option:selected[value=overtime]').length;
            var owd = $('.lgends_off_' + staff_id + ' option:selected[value=off_working_day]').length;
            var own = $('.lgends_off_' + staff_id + ' option:selected[value=off_working_night]').length;
            var off = $('.lgends_off_' + staff_id + ' option:selected[value=day_off]').length;
            var sick_leave = $('.lgends_off_' + staff_id + ' option:selected[value=sick_leave]').length;
            $('.absent_input_' + staff_id).val(absent);
            $('.overtime_input_' + staff_id).val(overtime + owd + own);
            $('.off_leave_input_' + staff_id).val(off);
            $('.sick_leave_input_' + staff_id).val(sick_leave);

            /* cols summary */
            var total_days = $('.guarding_date_' + date + ' option:selected[data-name=day_shifts]').length;
            var total_night = $('.guarding_date_' + date + ' option:selected[data-name=night_shifts]').length;
            var total_day_off = $('.guarding_date_' + date + ' option:selected[value=day_off]').length;
            var total_sickleave = $('.guarding_date_' + date + ' option:selected[value=sick_leave]').length;
            var total_absent = $('.guarding_date_' + date + ' option:selected[value=absent]').length;
            var day_variance = $('#variance_day_night').attr('data-day-variance');
            var night_variance = $('#variance_day_night').attr('data-night-variance');
            var total_officer = $('#count_total_staff').val();
            var total_female = $('.' + date + '_staff_type_6').length;
            $('.day_variance_' + date).val(total_days - day_variance);
            $('.night_variance_' + date).val(total_night - night_variance);
            $('.day_shift_' + date).val(total_days);
            $('.night_shift_' + date).val(total_night);
            $('.off_shift_' + date).val(total_day_off);
            $('.sickleave_shift_' + date).val(total_sickleave);
            $('.absent_shift_' + date).val(total_absent);
            $('.total_security_' + date).val(total_officer);
            $('.male_security_' + date).val(total_officer - total_female);
            $('.female_security_' + date).val(total_female);


            /*  var total_officer = $('.' + date + '_staff_type_4 option:selected[data-present=yes]').length;
            var total_male = $('.gender_male_' + date + ' option:selected[data-present=yes]').length;
            var total_female = $('.gender_female_' + date + ' option:selected[data-present=yes]').length;
            $('.day_variance_' + date).val(($('.guarding_date_' + date).length) - total_days);
            $('.night_variance_' + date).val(($('.guarding_date_' + date).length) - total_night); */
            console.log(++index);
            if(index===summaryCalculations.length)
            {
                total_summary();
            }

            // console.log(++index);
        },1);
        }

        function total_summary()
        {
            /* totals summary */
            var unpaid_leave_shifts_total = $('.total_attendance option:selected[data-name=unpaid_leave_shifts]').length;
            var special_shifts_total = $('.total_attendance option:selected[data-name=special_shifts]').length;
            var annual_leaves_total = $('.total_attendance option:selected[value=annula_leave]').length;
            var absent_total = $('.total_attendance option:selected[value=absent]').length;
            var overtime_total = $('.total_attendance option:selected[value=overtime]').length;
            var own_total = $('.total_attendance option:selected[value=off_working_night]').length;
            var owd_total = $('.total_attendance option:selected[value=off_working_day]').length;
            var training_total = $('.total_attendance option:selected[value=trainig]').length;

            $('#total_unpaid_leave_shifts').text(unpaid_leave_shifts_total);
            $('#total_special_shifts').text(special_shifts_total);
            $('#total_annual_leaves').text(annual_leaves_total);
            $('#total_absents').text(absent_total);
            $('#total_overtime').text(overtime_total + own_total + owd_total);
            $('#total_training').text(training_total);
        }
    </script>
@endsection
