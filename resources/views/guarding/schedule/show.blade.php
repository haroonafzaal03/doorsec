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

    tr.zt>th {
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
</style>


<section class="content bg-white">



    <div class="row">
        <div class="col-md-12">
        </div>
        <div class="col-md-12">
            <div id="guarding_calendar_block" class="">
                <table id="guarding_schedule_table" class="table  table-bordered table-sm venue_calendar_table">
                    <thead class="zt-head"></thead>
                    <tbody class="zt-body"></tbody>
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

    function adjustColumnHeight() {
        console.log('adasd' + $('tr').length);
        for (var n = 0; n <= $('tr').length; n++) {
            var row_height = $('table#guarding_schedule_table tr').eq(n).height();
            console.log(row_height);
            if (row_height <= 45) {
                row_height = 50;
            } else {
                row_height = row_height;
            }

            $('table#guarding_schedule_table tr').eq(n).children('td.headcol').css('height', row_height);
            $('table#guarding_schedule_table tr').eq(n).children('td.fixed_name_col').css('height', row_height);
        }

    }

    $(function() {
        $('#start_date').datepicker({
            autoclose: true
        });

        var new_date = new Date();
        var current_month = ("0" + (new_date.getMonth() + 1)).slice(-2);

        var columns = moment("2019-" + current_month, "YYYY-MM").daysInMonth();
        var ar = getMonths('2019-' + current_month, "YYYY-MM");
        console.log(ar);
        renderSchedule(ar, columns, new_date);
    });

    function refreshSchedule() {
        var start_date = $('#start_date').val();
        if (start_date == '') {
            alert('Please set date!');
            return false;
        }
        var week = $('input:radio[name=week]:checked').val();
        if (typeof(week) == 'undefined') {
            alert('Please select week!');
            return false;
        }
        var ar = duration(start_date, week);
        var start_date = moment(start_date);
        var end_date = moment(start_date).add(week, 'week');
        //console.log(start_date.format('D-ddd-YYYY')+' '+end_date.format('D-ddd-YYYY'));
        end_date = end_date.subtract(1, "days");
        //console.log(start_date.format('MMM D')+' - '+end_date.format('MMM D'));
        $('#date_duration').text(start_date.format('MMM D') + ' - ' + end_date.format('MMM D, YYYY'));
        var columns = ar.length;
        //console.log(ar);
        renderSchedule(ar, columns);
    }

    function renderSchedule(ar, columns, current_date) {
        var total_days = ar.length;
        var current_date = moment(current_date);
        var topRow = $("<tr  class='row1'>");
        var title_row = null;
        title_row += '<th width="100px" class="headcol heading " colspan="2">REVISION DATE </th>';
        title_row += '<th width="100px" class="text-center heading" colspan="' + total_days + '">' + current_date.format('MMMM YYYY') + '</th>';

        topRow.append(title_row);
        $('.zt-head').html(topRow);

        var shifts = 0;
        var total_hours = 0;

        var newRow = $("<tr  class='row2'>");
        var cols = "";
        cols += '<th width="100px" class="headcol fixed  col1">' + current_date.format('dddd, MMMM d, YYYY') + ' </th>';
        cols += '<th width="100px" class="fixed_th  col2">   <i class="fa fa-angle-right bold hide"></i> </th>';

        for (var i = 0; i < ar.length; i++) {
            cols += '<th width="100px" class="" data-date="' + ar[i]['format4'] + '"><span>' + ar[i]['format4'] + '</span></th>';
        }

        cols += '<th rowspan="2" class="absent_col abs bg-reds vertical-align-middle">ABS</th>';
        cols += '<th rowspan="2" class="overtime_col abs  bg-blues s vertical-align-middle">Over <br/> Time</th>';
        cols += '<th rowspan="2" class="off_leave_col abs bg-greenss vertical-align-middle">SL</th>';
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
            cols += '<td width="100px"  class="headcol bold bg-grey  col1" colspan="2">' + staff[j].siraType + '</td>';
            cols += '<td width=""  class="fixed_name_col bold  bg-grey  col2" colspan="">' + staff[j].name + '</td>';
            newRow.append(cols);
            for (var k = 1; k <= columns; k++) {
                var cols = "";
                cols += '<td width="100px"  id="' + staff[j].id + '">'+
                    '<select style="width:60px" type="text" class="form-control guarding_sch_inputs day_'+k+'">'+
                        '<option value=""> </option>'+
                        '<option value="day">D</option>'+
                        '<option value="night">N</option>'+
                        '<option value="afternoon">A</option>'+
                        '<option value="late_day">LD</option>'+
                        '<option value="evening">EL</option>'+
                        '<option value="absent">ABS</option>'+
                        '<option value="sick_leave">SL</option>'+
                        '<option value="annula_leave">AL</option>'+
                        '<option value="emergency_leave">EL</option>'+
                        '<option value="day_off">OFF</option>'+
                        '<option value="off_working_night">OWN</option>'+
                        '<option value="off_working_day">OWD</option>'+
                        '<option value="trainig">T</option>'+
                        '<option value="overtime">OT</option>'+
                        '<option value="event_day">ED</option>'+
                        '<option value="public_holiday">PH</option>'+
                    '</select></td>';
                newRow.append(cols);
            }
            cols = "";
            cols += '<td width="75px" class="absent_input_abs" id="' + staff[j].id + '"><input class=" absent_input abs text-center bg-transparent" style="width:100%" value="0" type="text" class="form-control"/></td>';
            cols += '<td width="75px" class="overtime_input_abs" id="' + staff[j].id + '"><input class=" overtime_input abs text-center bg-transparent" style="width:100%" value="0" type="text" class="form-control"/></td>';
            cols += '<td width="75px" class="off_leave_input_abs" id="' + staff[j].id + '"><input class=" off_leave_input abs text-center bg-transparent" style="width:100%" value="0" type="text" class="form-control"/></td>';

            newRow.append(cols);
            newRow.appendTo($('.zt-body'));
        }

        var newRow_zt1 = $('<tr class="row2 zt">');
        var cols = "";
        cols += '<th width="100px" class="headcol fixed  col1" style="text-align:left !important;background:#7030a1;color:white;">Day Shift: 7:00 To 19:00 Hrs<span class="pull-right">12Hrs</span></th>';
        cols += '<th width="100px" class="fixed_th  col2">   <i class="fa fa-angle-right bold hide"></i> </th>';
        for (var i = 0; i < ar.length; i++) {
            cols += '<th width="100px" class="" data-date="' + ar[i]['format4'] + '"><span>' + ar[i]['format4'] + '</span></th>';
        }
        cols += '<th class="off_leave_col abs bg-greenss vertical-align-middle" style="width: 198px;height: 14.5em;"></th>';

        newRow_zt1.append(cols);
        newRow_zt1.appendTo($('.zt-body'));


        var newRow_zt2 = $('<tr class="row3 zt">');
        var Ncolss = "";
        Ncolss += '<th width="100px" class="headcol fixed  col1" style="text-align:left !important;width: 24.25em !important;background:#7030a1;color:white;">Day Shift: 10:00 To 07:00 Hrs<span class="pull-right">12Hrs</span></th>';
        for (var i = 0; i < ar.length; i++) {
            Ncolss += '<th width="100px" class="" data-date="' + ar[i]['format5'] + '"><span>' + ar[i]['format5'] + '</span></th>';
        }

        newRow_zt2.append(Ncolss);
        newRow_zt2.appendTo($('.zt-body'));


        var newRow_zt3 = $('<tr class="row4">');
        var Ncolss3 = "";
        Ncolss3 += '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: center;padding: 35px; height: 115px !important;">Total</td>';
        Ncolss3 += '<td width="" class="fixed_name_col bold  bg-grey  col2" colspan="" style="height: 58px;">DAY SHIFT - 06</td>';
        newRow_zt3.append(Ncolss3);
        for (var k = 1; k <= columns; k++) {
            var Ncolss3 = "";
            Ncolss3 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt3.append(Ncolss3);
        }
        Ncolss3 = "";

        newRow_zt3.append(Ncolss3);
        newRow_zt3.appendTo($('.zt-body'));

        var newRow_zt4 = $('<tr class="row4">');
        var Ncolss4 = "";
        Ncolss4 = '<td width="" class="headcol_zt fixed_name_col bold  bg-grey  col2" colspan="" style="height: 58px;">VARIANCE / SHORTAGES</td>';
        newRow_zt4.append(Ncolss4);
        for (var k = 1; k <= columns; k++) {
            var Ncolss4 = "";
            Ncolss4 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt4.append(Ncolss4);
        }
        Ncolss4 = "";

        newRow_zt4.append(Ncolss4);
        newRow_zt4.appendTo($('.zt-body'));



        var newRow_zt5 = $('<tr class="row4">');
        var Ncolss5 = "";
        Ncolss5 += '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: center;padding: 35px; height: 115px !important;">Total</td>';
        Ncolss5 += '<td width="" class="fixed_name_col bold  bg-grey  col2" colspan="" style="height: 58px;">NIGHT SHIFT - 04</td>';
        newRow_zt5.append(Ncolss5);
        for (var k = 1; k <= columns; k++) {
            var Ncolss5 = "";
            Ncolss5 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt5.append(Ncolss5);
        }
        Ncolss5 = "";

        Ncolss5 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">Annual Leaves</th>';

        newRow_zt5.append(Ncolss5);
        newRow_zt5.appendTo($('.zt-body'));

        var newRow_zt6 = $('<tr class="row4">');
        var Ncolss6 = "";
        Ncolss6 = '<td width="" class="headcol_zt fixed_name_col bold  bg-grey  col2" colspan="" style="height: 58px;">VARIANCE / SHORTAGES</td>';
        newRow_zt6.append(Ncolss6);
        for (var k = 1; k <= columns; k++) {
            var Ncolss6 = "";
            Ncolss6 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt6.append(Ncolss6);
        }
        Ncolss6 = "";
        Ncolss6 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: black !important;text-align: center;"></th>';
        newRow_zt6.append(Ncolss6);
        newRow_zt6.appendTo($('.zt-body'));



        var newRow_zt7 = $('<tr class="row4">');
        var Ncolss7 = "";
        Ncolss7 = '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">Total day off per day</td>';
        newRow_zt7.append(Ncolss7);
        for (var k = 1; k <= columns; k++) {
            var Ncolss7 = "";
            Ncolss7 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt7.append(Ncolss7);
        }
        Ncolss7 = "";
        Ncolss7 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">Total Absents</th>';
        newRow_zt7.append(Ncolss7);
        newRow_zt7.appendTo($('.zt-body'));

        var newRow_zt8 = $('<tr class="row4">');
        var Ncolss8 = "";
        Ncolss8 = '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">Total Sick leave per day</td>';
        newRow_zt8.append(Ncolss8);
        for (var k = 1; k <= columns; k++) {
            var Ncolss8 = "";
            Ncolss8 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt8.append(Ncolss8);
        }
        Ncolss8 = "";
        Ncolss8 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: black !important;text-align: center;"></th>';
        newRow_zt8.append(Ncolss8);
        newRow_zt8.appendTo($('.zt-body'));


        var newRow_zt9 = $('<tr class="row4">');
        var Ncolss9 = "";
        Ncolss9 = '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">Total Absent per day</td>';
        newRow_zt9.append(Ncolss9);
        for (var k = 1; k <= columns; k++) {
            var Ncolss9 = "";
            Ncolss9 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt9.append(Ncolss9);
        }
        Ncolss9 = "";
        Ncolss9 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">O.T Days</th>';
        newRow_zt9.append(Ncolss9);
        newRow_zt9.appendTo($('.zt-body'));


        var newRow_zt10 = $('<tr class="row4">');
        var Ncolss10 = "";
        Ncolss10 = '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">Total of security officers both shifts</td>';
        newRow_zt10.append(Ncolss10);
        for (var k = 1; k <= columns; k++) {
            var Ncolss10 = "";
            Ncolss10 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt10.append(Ncolss10);
        }
        Ncolss10 = "";
        Ncolss10 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: black !important;text-align: center;"></th>';
        newRow_zt10.append(Ncolss10);
        newRow_zt10.appendTo($('.zt-body'));


        var newRow_zt11 = $('<tr class="row4">');
        var Ncolss11 = "";
        Ncolss11 = '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">Total of Male security officers both shifts</td>';
        newRow_zt11.append(Ncolss11);
        for (var k = 1; k <= columns; k++) {
            var Ncolss11 = "";
            Ncolss11 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt11.append(Ncolss11);
        }
        Ncolss11 = "";
        Ncolss11 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #8fb4e0 !IMPORTANT;color: black !important;text-align: center;">Total Trainings</th>';
        newRow_zt11.append(Ncolss11);
        newRow_zt11.appendTo($('.zt-body'));


        var newRow_zt12 = $('<tr class="row4">');
        var Ncolss12 = "";
        Ncolss12 = '<td width="100px" class="headcol_zt bg-grey" rowspan="2" colspan="2" style="text-align: left;/* padding: 35px; *//* height: 115px !important; */width: 24.24em;height: 58px;">Total of Female security officers both shifts</td>';
        newRow_zt12.append(Ncolss12);
        for (var k = 1; k <= columns; k++) {
            var Ncolss12 = "";
            Ncolss12 += '<td width="100px"><input style="width:44px" type="text" class="form-control guarding_sch_inputs"/></td>';
            newRow_zt12.append(Ncolss12);
        }
        Ncolss12 = "";
        Ncolss12 += '<th class="off_leave_col abs" style="width: 198px;height: 4.5em;BACKGROUND: #0f2442 !IMPORTANT;color: black !important;text-align: center;"></th>';
        newRow_zt12.append(Ncolss12);
        newRow_zt12.appendTo($('.zt-body'));



        console.log(events);
        for (var e = 0; e < events.length; e++) {
            var client_id = events[e].client_id;
            var start_date = events[e].start_date;
            var end_date = events[e].end_date;
            var staff_name = events[e].name;
            var asset_path = "{{image_base()}}";
            var staff_image = (events[e].picture == null) ? " {{  asset('avatar.jpg') }}" : asset_path + '/' + events[e].picture;
            var event_start_time = moment(events[e].start_time, 'hh:mm A').format('hh:mm A');
            var event_end_time = moment(events[e].end_time, 'hh:mm A').format('hh:mm A');

            //var staff = (events[e].name == null) ? "No Staff" : events[e].name;
            var event_card_background = (events[e].status == 'booked') ? 'bg-green' : 'bg-red';
            var event_card_border = (events[e].status == 'booked') ? 'card_border_green' : 'card_border_red';
            start_date = moment(start_date);
            end_date = moment(end_date);

            if (moment(start_date).isSame(end_date)) {
                console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY') + " same");
                var td_id = client_id + '-' + start_date.format('D-ddd-MMM-YYYY');
                if ($('#' + td_id).length > 0) {
                    shifts = shifts + 1;
                    total_hours = total_hours + getHours(events[e].start_time, events[e].end_time);
                }


                var event_card = '<div class="event_card ' + event_card_background + ' ' + event_card_border + '"><div class="shift_staff_icons"><img src="' + staff_image + '" class="user-image" alt="User Image"><p>' + staff_name + '</p><div class="timings">' + event_start_time + ' - ' + event_end_time + '</div><div><div class="view_details_icons hide"><i class="fa fa-eye"></i><i class="fa fa-user-plus"></i></div></div>';
                $('#' + td_id).append(event_card);
            } else {
                var dates_array = getDates(start_date, end_date);
                console.log(client_id + '-' + start_date.format('D-ddd-YYYY') + " " + client_id + '-' + end_date.format('D-ddd-YYYY'));
                //console.log(dates_array);
                for (var d = 0; d < dates_array.length; d++) {
                    //console.log(client_id + '-' + dates_array[d]['format1']);

                    var td_id = client_id + '-' + dates_array[d]['format1'];
                    if ($('#' + td_id).length > 0) {
                        shifts = shifts + 1;
                        total_hours = total_hours + getHours(events[e].start_time, events[e].end_time);
                        //console.log(total_hours);
                    }

                    var event_card = '<div class="event_card ' + event_card_background + ' ' + event_card_border + '"><div class="timings">' + event_start_time + ' - ' + event_end_time + '</div><div class="view_details_icons"></div><div class="shift_staff_icons"><img src="' + staff_image + '" class="user-image" alt="User Image"><p>' + staff_name + '</p><div><div class="view_details_icons hide"><i class="fa fa-eye"></i><i class="fa fa-user-plus"></i></div></div>';
                    $('#' + td_id).append(event_card);
                }
            }
        }
        adjustColumnHeight();
        $('#shifts').text(shifts);
        $('#total_hours').text(total_hours);
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>

@endsection