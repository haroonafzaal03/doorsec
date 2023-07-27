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
            min-height:100px;
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
        }
        .long { background:yellow; letter-spacing:1em; }
        .detailss {
            display: grid;
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
                    <div class="form-group col-md-2">
                                <lable></lable>
                                <input type="text" class="form-control " id="search_client" placeholder="Search event by name">
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
    <div class="col-md-12">
        <div id="venue_calendar_block" class="">
            <table id="scheduler" class="table  table-bordered table-sm venue_calendar_table">
                <thead class="zt-head"></thead>
                <tbody  class="zt-body"></tbody>
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
<script src="{{ asset('js/zt-scheduler.js')}}"></script>

<script>
var events = events_ptj;
var clients = scheduler_clients_ptj;
var clients_ids = scheduler_clients_ids;
var rows = clients.length;

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

$(function () {
            $('#start_date').datepicker({
                autoclose: true
            })

            //var clients = ['Anthony', 'George', 'Zeeshan', 'Herry', 'Robin'];
            console.log(clients);
            //console.log(events_ptj);

            //Date for the calendar events (dummy data)
            /*var events = [
                {
                    title: 'Long Event',
                    client: 1,
                    start: '2019-07-05',
                    end: '2019-07-08',
                    timings: '9AM - 10PM',
                    backgroundColor: '#f39c12', //yellow
                    borderColor: '#f39c12' //yellow
                },
                {
                    title: 'Meeting',
                    client: 1,
                    start: '2019-07-09',
                    end: '2019-07-09',
                    timings: '9AM - 10PM',
                    allDay: false,
                    backgroundColor: '#0073b7', //Blue
                    borderColor: '#0073b7' //Blue
                },
                {
                    title: 'Lunch',
                    client: 3,
                    start: '2019-07-09',
                    end: '2019-07-09',
                    timings: '9AM - 10PM',
                    allDay: false,
                    backgroundColor: '#00c0ef', //Info (aqua)
                    borderColor: '#00c0ef' //Info (aqua)
                },
                {
                    title: 'Birthday Party',
                    client: 3,
                    start: '2019-07-10',
                    end: '2019-07-10',
                    timings: '9AM - 10PM',
                    allDay: false,
                    backgroundColor: '#00a65a', //Success (green)
                    borderColor: '#00a65a' //Success (green)
                },
                {
                    title: 'Click for Google',
                    client: 4,
                    start: '2019-07-06',
                    end: '2019-07-07',
                    timings: '9AM - 10PM',
                    url: 'http://google.com/',
                    backgroundColor: '#3c8dbc', //Primary (light-blue)
                    borderColor: '#3c8dbc' //Primary (light-blue)
                },
                {
                    title: 'Click for Google',
                    client: 1,
                    start: '2019-07-05',
                    end: '2019-07-05',
                    timings: '10AM - 1PM',
                    url: 'http://google.com/',
                    backgroundColor: '#3c8dbc', //Primary (light-blue)
                    borderColor: '#3c8dbc' //Primary (light-blue)
                }
            ];*/
            //console.log(events);

            var new_date = new Date();
            var current_month = ("0" + (new_date.getMonth() + 1)).slice(-2);

            var columns = moment("2019-"+current_month, "YYYY-MM").daysInMonth();
            var ar = getMonths('2019-'+current_month, "YYYY-MM");
            console.log(ar);
            renderSchedule(ar, columns);

           /*  var staffList = $('#staffList_new').html();

            $('#staffList_new').jqListbox({
                targetInput: false,
                //selectedClass: 'selected-item',
                initialValues: [
                    @if($staff)
                            @foreach($staff as $obj)
                        '<li data-id="" data-view="{{ ($obj->stafftypes->id == 1) ? "on" : "off" }}" data-stafftype="{{$obj->stafftypes->id}}" data-exist="false" data-dropout="false" class="abbc"><img src="{{img($obj->picture)}}" data-exist ="false" class="custom staff_list_view img-circle" data-id="{{$loop->iteration}}"  data-sira="{{$obj->sira_id_number}}" data-staffid="{{ $obj->id}}" data-contact="{{$obj->contact_number}}" data-start_time="" data-end_time="" data-rph=""   data-hours="" data-status="" data-sms_status=""   /> <span class="list_staff_name ">{{$obj->name}}</span> </li>',
                    @endforeach
                    @endif
                ]
            }); */

            /*  STAFF TYPE SELECTION RADIO BUTTON  */

            /*$("input.staff_type_radio[type='radio']").change(function(){

                $('.staff_type_labels').removeClass('active');
                $(this).parent('.staff_type_labels').addClass('active');
                var staff_type_id  =  $(this).attr('data-typeid');
                $("ul#staffList_new li").attr('data-view','off');
                if(staff_type_id)
                {
                    $("ul#staffList_new li[data-stafftype="+staff_type_id+"]").attr('data-view','on');
                }

            });*/

        })

    function refreshSchedule(){
        var start_date = $('#start_date').val();
        if(start_date == ''){
            alert('Please set date!');
            return false;
        }
        var week = $('input:radio[name=week]:checked').val();
        if(typeof(week) == 'undefined'){
            alert('Please select week!');
            return false;
        }
        var ar = duration(start_date,week);
        var start_date = moment(start_date);
        var end_date = moment(start_date).add(week, 'week');
        //console.log(start_date.format('D-ddd-YYYY')+' '+end_date.format('D-ddd-YYYY'));
        end_date =  end_date.subtract(1, "days");
        console.log(start_date.format('MMM D')+' - '+end_date.format('MMM D'));
        $('#date_duration').text(start_date.format('MMM D')+' - '+end_date.format('MMM D, YYYY'));
        var columns = ar.length;
        console.log(ar);
        renderSchedule(ar,columns);
    }

    function renderSchedule(ar,columns){

        var shifts = 0;
        var total_hours = 0;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<th class="headcol">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </th>';
        for (var i = 0; i < rows; i++) {
            cols += '<th class="" data-date=""><span data-id="'+clients[i].id+'">' + clients[i].property_name + '</span></th>';
        }
        newRow.append(cols);
        $('.zt-head').html(newRow);
        $('.zt-body').empty();

        for (var j = 0; j < ar.length; j++) {
                var newRow = $('<tr>');
                var cols = "";
                cols += '<td class="headcol">' + ar[j]['format3'] + '</td>';
                newRow.append(cols);
                for (var k = 0; k < clients.length; k++) {
                    var cols = "";
                    cols += '<td class="clientIdz-' + clients[k].id + '"id="' + clients[k].id + '-' + ar[j]['format1'] + '"></td>';
                   newRow.append(cols);
                }
                //$('.zt-body').append(newRow);
                newRow.appendTo($('.zt-body'));
        }
        for (var e = 0; e < events.length; e++) {
            console.log(events[e]);
            var client_id = events[e].client_id;
            var start_date = events[e].start_date;
            var end_date = events[e].end_date;
            var event_start_time =  moment(events[e].start_time, 'hh:mm A').format('hh:mm A');
            var event_end_time = moment(events[e].end_time, 'hh:mm A').format('hh:mm A');

            var staff = (events[e].name == null) ? "No Staff" : events[e].name;
            var staff_image = (events[e].picture == null) ? " {{  asset('avatar.jpg') }}" : events[e].picture;
            var event_card_background = (events[e].status == 'booked') ? 'bg-green' : 'bg-red';
            var event_card_border = (events[e].status == 'booked') ? 'card_border_green' : 'card_border_red';
            start_date = moment(start_date);
            end_date = moment(end_date);
            var total_hours = 101;

            if (moment(start_date).isSame(end_date)) {
                //console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY') + " same");
                var td_id = client_id + '-' + start_date.format('D-ddd-MMM-YYYY');
                console.log("id ");
                console.log(td_id);
                if($('#'+td_id).length > 0){
                   shifts = shifts + 1;
                   total_hours = total_hours + getHours(events[e].start_time,events[e].end_time);
                }

                var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'"><div class="shift_staff_icons"><img src="'+staff_image+'" class="user-image" alt="User Image"><div class="detailss" style=display: grid;"><span style="margin-left: 20px;">'+ staff +'</span><div class="timings">'+event_start_time+' - '+event_end_time+'</div><div style="margin-left: 20px;">'+events[e].event_name+'</div></div><div><div class="view_details_icons event_schedule_card hide"><a href="/event_detail/'+events[e].id+'" target="_blank"><i class="fa fa-eye"></i></a><a href="/edit_event/'+events[e].id+'" target="_blank"><i class="fa fa-edit"></i></a></div></div></div>';
                $('#' + td_id).append(event_card);
            } else {
                var dates_array = getDates(start_date, end_date);
                for (var d = 0; d < dates_array.length; d++) {
                    //console.log(client_id + '-' + dates_array[d]);

                    var td_id = client_id + '-' + dates_array[d]['format1'];
                    if($('#'+td_id).length > 0){
                        shifts = shifts + 1;
                        total_hours = total_hours + getHours(events[e].start_time,events[e].end_time);
                    }
                    var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'"><div class="shift_staff_icons"><img src="https://adminlte.io/themes/AdminLTE/dist/img/user1-128x128.jpg" class="user-image" alt="User Image"><div class="detailss" style=display: grid;"><span style="margin-left: 20px;">'+ staff +'</span><div class="timings">'+   +' - '+event_end_time+'</div><div style="margin-left: 20px;">'+events[e].event_name+'</div></div><div><div class="view_details_icons event_schedule_card hide"><a href="/event_detail/'+events[e].id+'" target="_blank"><i class="fa fa-eye"></i></a><a href="/edit_event/'+events[e].id+'" target="_blank"><i class="fa fa-edit"></i></a></div></div></div>';
                    $('#' + td_id).append(event_card);
                }
            }
        }
        adjustColumnHeight();
        $('#shifts').text(shifts);
        $('#total_hours').text(total_hours);
    }
    jQuery("#search_client").keyup(function () {

        var filter = jQuery(this).val();
        jQuery("table#scheduler th span").each(function () {

            var id = jQuery(this).attr('data-id');
            if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                jQuery(this).parent().hide();
                jQuery('.clientIdz-' + id).addClass('hide');
            } else {
                jQuery(this).parent().show();
                jQuery('.clientIdz-' + id).removeClass('hide');
            }

        });
    });
    </script>


@endsection

