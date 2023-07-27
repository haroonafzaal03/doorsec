@extends('layouts.master')
@section('content')
<style>
    /******** FIXED **********/


    table {
        border-collapse: separate;
    }

    td,
    th {
        margin: 0;

        border-top-width: 0px;
        white-space: nowrap;
    }

    /* table#scheduler tbody tr{
            min-height:100px;
        } */
    div#venue_calendar_block {
        overflow-x: scroll;
        margin-left: 5em;
        overflow-y: visible;
        padding-bottom: 1px;
    }

    .headcol {
        position: absolute;
        width: 5em;
        left: 0;
        top: auto;
        border-right: 0px none black;
        border-top-width: 3px;
        /*only relevant for first row*/
        margin-top: -3px;
        /*compensate for top border*/
        white-space: normal;
    }

    .long {
        background: yellow;
        letter-spacing: 1em;
    }

    #selected_staff li.abbc.label.badge:nth-child(odd) {
        background-color: #00a65a;
        margin: 10px;
        padding: 10px 30px 10px 10px;
    }

    #selected_staff li.abbc.label.badge:nth-child(even) {
        background-color: gray;
        margin: 10px;
    }

    .add_staff_to_shiift button {
        margin-bottom: 10px;
    }

    table#shiftstaffDataTable {
        font-size: 13px;
    }
</style>


<section class="content">


    <div class="row hide">
        <div class="col-md-12">
            <div class="box" style="border-bottom: 1px solid #d2d6de;border-top: none;background: none">

                <!-- /.box-header -->
                <div class="box-body" style="padding: 0">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <select class="form-control pull-right">
                                    <option>Account/Position/option>
                                </select>

                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-home"></i>
                                </div>
                                <select class="form-control pull-right">
                                    <option>Demo South</option>
                                </select>

                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Type filter by account">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Type filter by user">

                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-1">
                            <button type="submit" class="btn btn-default">Reset</button>
                        </div>
                        <div class="form-group col-md-1">
                            <button type="submit" onclick="refreshSchedule();" class="btn btn-default">Reload</button>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->

                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box" style="border-bottom: 1px solid #d2d6de;border-top: none;background: none">

                <!-- /.box-header -->
                <div class="box-body" style="padding: 0">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" readonly class="form-control pull-right" id="start_date" value="{{\Carbon\Carbon::now()->format('m/d/Y')}}">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2" style="width: auto;">
                            <label class="">
                                <input type="radio" name="week" value="1">
                                &nbsp;1 Week
                            </label>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2" style="width: auto;">
                            <label class="">
                                <input type="radio" checked name="week" value="2">
                                &nbsp;2 Week
                            </label>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2">
                                <lable></lable>
                                <input type="text" class="form-control " id="search_client" placeholder="Search venue by name">
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-1 hide">
                            <button type="submit" class="btn btn-default">Reset</button>
                        </div>
                        <div class="form-group col-md-1">
                            <button type="submit" onclick="refreshSchedule();" class="btn btn-default">Reload</button>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2 hide" style="width: auto;">
                            <label class="">
                                <input type="checkbox">
                                &nbsp;Include Shifts Overlapping Perlod
                            </label>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2 hide" style="width: auto;">
                            <label class="">
                                <input type="checkbox">
                                &nbsp;Vacant Only
                            </label>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2 hide" style="width: auto;">
                            <label class="">
                                <input type="checkbox">
                                &nbsp;Not Published Only
                            </label>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-md-2 hide" style="width: auto;">
                            <label class="">
                                <input type="checkbox">
                                &nbsp;Not Acknowledge Only
                            </label>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->

                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>

    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12 info-box-common">
            <div class="info-box custom">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-clock"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Hours</span>
                    <span class="info-box-number" id="total_hours">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12 info-box-common">
            <div class="info-box custom">
                <span class="info-box-icon bg-red"><i class="fa fa-tasks"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Shift</span>
                    <span class="info-box-number" id="shifts">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-2 col-sm-6 col-xs-12 info-box-common">
            <div class="info-box custom">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Freelancer</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12 info-box-common">
            <div class="info-box custom">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">DoorSec Staff</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-2 col-sm-6 col-xs-12 info-box-common">
            <div class="info-box custom">
                <span class="info-box-icon bg-teal"><i class="ion ion-ios-home"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Vacant Shifts</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12 info-box-common">
            <div class="info-box custom">
                <span class="info-box-icon bg-olive"><i class="ion ion-ios-cloud-upload"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Unpublished Shifts</span>
                    <span class="info-box-number">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <h3 class="heading bold m-t-10 text-center" id="date_duration">{{ date("M,Y") }}</h3>
        </div>
        <!--<div class="col-md-4  m-b-20 text-center">
        @if($staff_types)
            @foreach($staff_types as $index =>$st)

                <label class="control-label inline-block staff_type_labels  {{ ($st->id == 1) ? 'active' : '' }} "  id="staff_type_label-{{$loop->iteration}}">
                    <input type="radio" {{ ($st->id == 1) ? "checked" : "" }}  data-typeid="{{$st->id}}" id="staff_type_{{$st->id}}" name="staff_type_radio" class=" staff_type_radio">
                    {{$st->type}}</label>
            @endforeach
        @endif
    </div>-->
    </div>
    <!-- /.row -->


    <div class="row">
        @hasrole('schedule.now')
        <div class="col-md-12">
            <a class="btn btn-primary pull-right" onClick="schedule_now()">Schedule Now</a>
        </div>
        @endhasrole
        <div class="col-md-12">
            <div id="venue_calendar_block" class="">
                <table id="scheduler" class="table  table-bordered table-sm venue_calendar_table">
                    <thead class="zt-head"></thead>
                    <tbody class="zt-body"></tbody>
                </table>
            </div>
        </div>

        <!--<div class="col-md-2 staff-list">
        <div class="col-md-12 customlist venue_calendar">
            <ul id="staffList_new" class="users_multi_listbox">
            </ul>
        </div>
    </div>-->

    </div>
    <!--- /.row ---->

    <!-- Main row -->

    <!-- /.row -->
</section>

@endsection
@section('content_js')

<script>
    var default_img = "{{ asset('avatar.jpg ') }}";
    var asset_path = "{{image_base()}}";
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script src="{{ asset('js/zt-scheduler.js')}}"></script>
<script src="{{ asset('js/venue_schedule.js')}}"></script>
<script>
$('document').ready(function(){
        refreshSchedule();
    });
   // const { jsPDF } = window.jspdf;
</script>
@endsection