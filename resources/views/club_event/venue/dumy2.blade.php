@extends('layouts.master')
@section('content')
<style>
/******** FIXED **********/


        table { border-collapse:separate; border-top: 3px solid grey; }
        td, th {
            margin:0;

            border-top-width:0px;
            white-space:nowrap;
        }
        table#scheduler tbody tr{
            height:100px;
        }
        div#venue_calendar_block {
            overflow-x:scroll;
            margin-left:5em;
            overflow-y:visible;
            padding-bottom:1px;
        }
        .headcol {
            position:absolute;
            width:5em;
            left:0;
            top:auto;
            border-right: 0px none black;
            border-top-width:3px; /*only relevant for first row*/
            margin-top:-3px; /*compensate for top border*/
            white-space: normal;
            height: 100px;
        }
        .long { background:yellow; letter-spacing:1em; }
        #selected_staff li.abbc.label.badge:nth-child(odd) {
            background-color: #00a65a;
            margin:10px;
            padding: 10px 30px 10px 10px;
        }
        #selected_staff li.abbc.label.badge:nth-child(even) {
            background-color: gray;
            margin:10px;
        }
        .user-image {
            float: left;
            /*width: 25px;
            height: 25px;*/
            width: 50px;
            height: auto;
            border-radius: 50%;
            margin-right: -9px;
            margin-top: -2px;
        }
        .view_more {
            margin-left: 8px;
            text-decoration: underline;
            color: burlywood;
            cursor: pointer;
            display: block;
            text-align: right;
         }
         .view_details_icons {
            float: right;
            width: 40%;
            margin-bottom: 14px;
         }
         .view_details_icons>i {
            float: right;
            padding: 0 3px;
        }
        .timings {
            font-family: Raleway;
            float: left;
            width: 60%;
        }
        .shift_staff_icons {
            width: 100%;
            float: left;
        }

        .event_card{
            cursor: auto;
            min-width:250px;
        }

        .products-list .product-img img{
            border-radius: 50%;
        }

        .staffListUl{
            height: 400px;
            overflow-y: scroll;
        }
        .modal-title.staffListModalTitle{
            color: rgb(88, 144, 255);
    text-decoration-line: underline;
        }



        .staffListUl::-webkit-scrollbar-thumb:vertical {
        height: 30px;
        background-color: #6d7886;
        }

        .staffListUl::-webkit-scrollbar-track-piece {
            background-color: #b4bcc7;
        }
        .staffListUl::-webkit-scrollbar {
            width: 9px;
            height: 15px;
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
                            <input type="text" readonly class="form-control pull-right" id="start_date">
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
                            <input type="radio" name="week" value="2">
                            &nbsp;2 Week
                        </label>
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
</div>
<!-- /.row -->


<div class="row">
    <div class="col-md-12">
    <a class="btn btn-primary pull-right" onClick="schedule_now()">Schedule Now</a>
    </div>
    <div class="col-md-12">
        <div id="venue_calendar_block" class="">
            <table id="scheduler" class="table  table-bordered table-sm venue_calendar_table">
                <thead class="zt-head">
                    <tr>
                    <th class="headcol">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </th>
                        <th>Client 1</th>
                        <th>Client 2</th>
                        <th>Client 3</th>
                        <th>Client 4</th>
                        <th>Client 5</th>
                        <th>Client 6</th>
                        <th>Client 7</th>
                        <th>Client 8</th>
                    </tr>
                </thead>
                <tbody  class="zt-body">
                <tr>
                    <td class="headcol">Sept 1</td>
                    <td>
                         {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                         {!! venueCard('01:00 AM - 02:00 AM','Norman','user8-128x128.jpg')!!}
                         {!! venueCard('01:00 AM - 02:00 AM','Jane','user7-128x128.jpg')!!}
                         {!! venueCard('03:00 AM - 04:00 AM','Nora','user4-128x128.jpg')!!}
                         {!! venueCard('03:00 AM - 04:00 AM','Jane','user7-128x128.jpg')!!}
                         {!! venueCard('03:00 AM - 04:00 AM','Norman','user4-128x128.jpg')!!}

                    </td>
                    <td> {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                    {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}</td>
                    <td> {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                    {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}</td>
                    <td> {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                    {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}</td>
                    <td> {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                    {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}</td>
                    <td> {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                    {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}</td>
                    <td> {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                    {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}</td>
                    <td>
                        {!! venueCard('01:00 AM - 02:00 AM','Alexander Pierce','user1-128x128.jpg')!!}
                        {!! venueCard('01:00 AM - 02:00 AM','Nora','user4-128x128.jpg')!!}
                    </td>
                </tr>
                <tr>
                    <td class="headcol">Sept 2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="headcol">Sept 3</td>
                    <td>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="headcol">Sept 4</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="headcol">Sept 5</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="headcol">Sept 6</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-2 staff-list hide">
        <div class="col-md-12 customlist venue_calendar">
            <ul id="staffList_new" class="users_multi_listbox">
            </ul>
        </div>
    </div>

</div>
<!--- /.row ---->

<!-- Main row -->

<!-- /.row -->
</section>
@endsection
@section('content_js')
<script>
$(function () {
    $('.view_more').click(function(){
        $('#modal-shiftStaffList').modal('show');
    });
    adjustColumnHeight();
});

function adjustColumnHeight()
{
    for(var n=0;n <= $('tr').length;n++)
    {
        var row_height =   $('table#scheduler tr').eq(n).height();
        if(row_height <=45)
        {
            row_height = 50;
        }
        $('table#scheduler tr').eq(n).children('td.headcol').css('height',row_height);
    }

}
</script>
@endsection