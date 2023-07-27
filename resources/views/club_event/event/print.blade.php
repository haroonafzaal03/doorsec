<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
    {{--
    <link rel="stylesheet" href="{{ public_path('css/AdminLTE.min.css') }}"> --}}
    {{--
    <link rel="stylesheet" href="{{ public_path('css/style.css') }}"> --}}
    {{--
    <link rel="stylesheet" href="{{ public_path('css/venue_calendar.css') }}"> --}}
    <style>
        @page {
            size: 35cm 60cm landscape;
        }

        .venue_screen .content-wrapper {
            font-size: 12px !important;
        }

        div#venue_calendar_block {
            overflow-x: scroll;
            margin-left: 5em;
            overflow-y: visible;
            padding-bottom: 1px;
        }

        table#scheduler {
            background-color: #eee !important;
        }

        .table-bordered {
            border: 1px solid #f4f4f4;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            border-collapse: separate;
        }

        table.venue_calendar_table tbody tr {
            height: 100px;
        }

        img {
            border: 0;
        }

        img {
            vertical-align: middle;
        }

        .user-image {
            /* float: left; */
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 25px;
            margin-right: -9px;
            margin-top: -2px;
        }

        .headcol {
            background: #43a2d2;
            color: #fff;
            padding-left: 22px;
        }

        .card_pending {
            color: BLACK;
            background: white !important;
            border-left: 10px solid #948c8c;
        }

        .event_card {
            /* background: #dd4b39; */
            /* color: #f5f5fd; */
            font-weight: 600 !important;
            padding: 20px 7px;
            margin: 2px;
            width: 190px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            /* border-top-left-radius: 10px; */
            /* border-bottom-left-radius: 10px; */
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            /* cursor: pointer; */
            font-size: 13px;
        }

        .event_card {
            cursor: auto;
            min-width: 250px;
        }


        .shift_staff_icons {
            width: 100%;
            height: 50px;
            position: relative;
        }

        .shift_staff_icons p {
            margin-left: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0px;
            position: absolute;
            top: -5;
        }

        .timings {
            font-family: Raleway;
            /* float: left; */
            width: 60%;
            margin-left: 60px;
            position: absolute;
            top: 20;
        }

        thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: 1px solid #f4f4f4;
        }

        /* #scheduler > thead > tr > th.headcol{
            background: transparent;
        } */
        .zt-head {
            background: #d6d0d0;
        }

    </style>
</head>

<body>
    <div class="row">
        <div id="venue_calendar_block" class="">
            <table id="scheduler" class="table  table-bordered table-sm venue_calendar_table">
                <thead class="zt-head">
                    <tr>
                        <th class="headcol">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            &nbsp; &nbsp; &nbsp; </th>
                        @if ($clients)
                            @foreach ($clients as $cl)
                                <th class="" data-date=""><span data-id="2">{{ $cl->property_name }}</span></th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody class="zt-body">
                    @php
                        $today = \Carbon\Carbon::parse('2020-10-06');
                    @endphp
                    @for($i=1;$i<=7;$i++)
                        <tr>
                            <td class="headcol" style="height: 144px;">{{$today->format('d M Y')}}</td>
                            @if ($clients)
                                @foreach ($clients as $cl)
                                    @if($events)
                                        @foreach($events as $ev)
                                            @if($ev->client_id == $cl->id)
                                                @if($today->format('Y-m-d') == $ev->start_date)
                                                <td class="venue_shift_schedule_section clientIdz-2" data-cdates="2020-07-9"
                                                    data-clname="alhamra" id="2-9-Thu-Jul-2020"
                                                    style="background-color: rgb(238, 238, 238); cursor: auto; box-shadow: none;">
                                                    <div class="event_card card_pending ">
                                                        <div class="shift_staff_icons"><img
                                                                src="http://uta.cybussolutions.com/storage/staff/a0ZKXc9iqkciHc41VpRpUKC1rBtlz6IGNR8WE9Oq.jpeg"
                                                                class="user-image" alt="User Image">
                                                            <p>{{$ev->name }}</p>
                                                            <div class="timings">{{$ev->start_time}} - {{$ev->end_time}}</div>
                                                            <div>
                                                                <div class="view_details_icons hide"><i class="fa fa-eye"></i><i
                                                                        class="fa fa-user-plus"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                @endif
                                            @else

                                            @endif
                                        @endforeach
                                    @endif
                                    <td class="venue_shift_schedule_section clientIdz-13"
                                        data-cdates="2020-07-9" data-clname="Mr. Miyagis - Media One Hotel"
                                        id="13-9-Thu-Jul-2020" ></td>

                                @endforeach
                            @endif
                            </td>
                        </tr>
                        @php
                            $today->add('1','Days');
                        @endphp
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
