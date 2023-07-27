@extends('layouts.master')
@section('content')
<style>


        table { border-collapse:separate; border-top: 3px solid grey; }
        td, th {
            margin:0;
            border-top-width:0px;
            white-space:nowrap;
        }

</style>


<section class="content bg-white">



<div class="row">
    <div class="col-md-12">
    </div>
    <div class="col-md-12">
        <div id="guarding_calendar_block" class="">
            <table id="guarding_schedule_table" class="table  table-bordered table-sm venue_calendar_table">
                <thead class="zt-head"></thead>
                <tbody  class="zt-body"></tbody>
            </table>
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

<script>
var events = events_ptj;
var staff = scheduler_staff_ptj;
var clients_ids = scheduler_clients_ids;
var rows = staff.length;

function adjustColumnHeight()
{
    console.log('adasd'+ $('tr').length);
    for(var n=0;n <= $('tr').length;n++)
    {
        var row_height =   $('table#guarding_schedule_table tr').eq(n).height();
        console.log(row_height);
        if(row_height <=45)
        {
            row_height = 50;
        }else{
            row_height =  row_height;
        }

        $('table#guarding_schedule_table tr').eq(n).children('td.headcol').css('height',row_height);
        $('table#guarding_schedule_table tr').eq(n).children('td.fixed_name_col').css('height',row_height);
    }

}

$(function () {
    $('#start_date').datepicker({
        autoclose: true
    });

    var new_date = new Date();
    var current_month = ("0" + (new_date.getMonth() + 1)).slice(-2);

    var columns = moment("2019-"+current_month, "YYYY-MM").daysInMonth();
    var ar = getMonths('2019-'+current_month, "YYYY-MM");
    console.log(ar);
    renderSchedule(ar,columns);
});

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
            //console.log(start_date.format('MMM D')+' - '+end_date.format('MMM D'));
            $('#date_duration').text(start_date.format('MMM D')+' - '+end_date.format('MMM D, YYYY'));
            var columns = ar.length;
            //console.log(ar);
            renderSchedule(ar,columns);
        }

    function renderSchedule(ar,columns)
    {
            var total_days = ar.length;

            var topRow = $("<tr  class='row1'>");
            var title_row = null;
            title_row += '<th width="100px" class="headcol heading " colspan="2">REVISION DATE </th>';
            title_row += '<th width="100px" class="text-center heading" colspan="'+total_days+'">SEPTEMBER 2019 </th>';

            topRow.append(title_row);
            $('.zt-head').html(topRow);

            var shifts = 0;
            var total_hours = 0;

            var newRow = $("<tr  class='row2'>");
            var cols = "";
            cols += '<th width="100px" class="headcol fixed  col1">Thursday, September 5, 2019 </th>';
            cols += '<th width="100px" class="fixed_th  col2">   <i class="fa fa-angle-right bold hide"></i> </th>';

            for (var i = 0; i < ar.length; i++) {
                cols += '<th width="100px" class="" data-date="' + ar[i]['format4'] + '"><span>' + ar[i]['format4'] + '</span></th>';
            }

            cols += '<th rowspan="2" class="absent_col abs bg-reds vertical-align-middle">ABS</th>';
            cols += '<th rowspan="2" class="overtime_col abs  bg-blues s vertical-align-middle">Over <br/> Time</th>';
            cols += '<th rowspan="2" class="off_leave_col abs bg-greenss vertical-align-middle">OFF</th>';

            newRow.append(cols);
            $('.zt-head').append(newRow);

            var Ncols = "";
            Ncols = '<th width="100px" class="headcol fixed  col1"> Staff Type</th>';
            Ncols += '<th width="100px" class="fixed_th  col2"> Name</th>';

            for (var i = 0; i < ar.length; i++) {
                Ncols += '<th width="100px" class="" data-date="' + ar[i]['format5'] + '"><span>' + ar[i]['format5'] + '</span></th>';
            }


            var newRow = $("<tr class='row3' >");
            newRow.append(Ncols);

            $('.zt-head').append(newRow);

            $('.zt-body').empty();

            for (var j = 0; j < rows; j++) {
                var newRow = $('<tr class="row4">');
                var cols = "";
                cols += '<td width="100px"  class="headcol bold bg-grey  col1" colspan="2">' + staff[j].type + '</td>';
                cols += '<td width=""  class="fixed_name_col bold  bg-grey  col2" colspan="">' + staff[j].name + '</td>';
                newRow.append(cols);
                for (var k = 1; k <= columns; k++) {
                    var cols = "";
                    cols += '<td width="100px"  id="' + staff[j].id +'"><input style="width:75px" type="text" class="form-control guarding_sch_inputs"/></td>';
                   newRow.append(cols);
                }
                cols ="";
                cols += '<td width="75px" class="absent_input_abs" id="' + staff[j].id +'"><input class=" absent_input abs text-center bg-transparent" style="width:100%" value="0" type="text" class="form-control"/></td>';
                cols += '<td width="75px" class="overtime_input_abs" id="' + staff[j].id +'"><input class=" overtime_input abs text-center bg-transparent" style="width:100%" value="0" type="text" class="form-control"/></td>';
                cols += '<td width="75px" class="off_leave_input_abs" id="' + staff[j].id +'"><input class=" off_leave_input abs text-center bg-transparent" style="width:100%" value="0" type="text" class="form-control"/></td>';

                newRow.append(cols);
                newRow.appendTo($('.zt-body'));
            }
            console.log( events);
            for (var e = 0; e < events.length; e++) {
                var client_id = events[e].client_id;
                var start_date = events[e].start_date;
                var end_date = events[e].end_date;
                var staff_name = events[e].name;
                var asset_path = "{{image_base()}}";
                var staff_image = (events[e].picture == null) ? " {{  asset('avatar.jpg') }}" : asset_path+'/'+events[e].picture;
                var event_start_time =  moment(events[e].start_time, 'hh:mm A').format('hh:mm A');
                var event_end_time = moment(events[e].end_time, 'hh:mm A').format('hh:mm A');

                //var staff = (events[e].name == null) ? "No Staff" : events[e].name;
                var event_card_background = (events[e].status == 'booked') ? 'bg-green' : 'bg-red';
                var event_card_border = (events[e].status == 'booked') ? 'card_border_green' : 'card_border_red';
                start_date = moment(start_date);
                end_date = moment(end_date);

                if (moment(start_date).isSame(end_date)) {
                    console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY') + " same");
                    var td_id = client_id + '-' + start_date.format('D-ddd-MMM-YYYY');
                    if($('#'+td_id).length > 0){
                    shifts = shifts + 1;
                    total_hours = total_hours + getHours(events[e].start_time,events[e].end_time);
                    }


                    var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'"><div class="shift_staff_icons"><img src="'+staff_image+'" class="user-image" alt="User Image"><p>'+staff_name+'</p><div class="timings">'+event_start_time+' - '+event_end_time+'</div><div><div class="view_details_icons hide"><i class="fa fa-eye"></i><i class="fa fa-user-plus"></i></div></div>';
                    $('#' + td_id).append(event_card);
                } else {
                    var dates_array = getDates(start_date, end_date);
                    console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY'));
                    //console.log(dates_array);
                    for (var d = 0; d < dates_array.length; d++) {
                        //console.log(client_id + '-' + dates_array[d]['format1']);

                        var td_id = client_id + '-' + dates_array[d]['format1'];
                        if($('#'+td_id).length > 0){
                            shifts = shifts + 1;
                            total_hours = total_hours + getHours(events[e].start_time,events[e].end_time);
                            //console.log(total_hours);
                        }

                        var event_card = '<div class="event_card '+event_card_background+' '+event_card_border+'"><div class="timings">'+event_start_time+' - '+event_end_time+'</div><div class="view_details_icons"></div><div class="shift_staff_icons"><img src="'+staff_image+'" class="user-image" alt="User Image"><p>'+staff_name+'</p><div><div class="view_details_icons hide"><i class="fa fa-eye"></i><i class="fa fa-user-plus"></i></div></div>';
                        $('#' + td_id).append(event_card);
                    }
                }
            }
            adjustColumnHeight();
            $('#shifts').text(shifts);
            $('#total_hours').text(total_hours);
        }




    </script>


@endsection

