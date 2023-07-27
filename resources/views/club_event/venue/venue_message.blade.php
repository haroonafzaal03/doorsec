@extends('layouts.master')

@section('content')

<section class="content-header">
    <h1>
        Venue
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> venue</a></li>
        <li><a href="{{route('venue_detail')}}">Venues</a></li>
        <li><a href="{{route('venue_sendsms',$schedule->id)}}">Venue Message</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Venue Info</h3>
                    <i class="fa fa-plus info_highligt pull-right"></i>
                </div>

                <!---- SMS POPUP ---->

                <div class="container-fluid venue_detail_info hide">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <hr>
                                <div class="col-md-3 form-group">
                                    <label>Client Name</label>
                                    <input class="form-control"
                                        value="{{($schedule->venues?$schedule->venues[0]->client->property_name:'')}}"
                                        type="text" readonly />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Start Date</label>
                                    <input class="form-control" value="{{$schedule->start_date}}" type="text"
                                        readonly />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>End Date</label>
                                    <input class="form-control" value="{{$schedule->end_date}}" type="text" readonly />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Start Time</label>
                                    <input class="form-control" value="{{$schedule->start_time}}" type="text"
                                        readonly />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>End Time</label>
                                    <input class="form-control" value="{{$schedule->end_time}}" type="text" readonly />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Address</label>
                                    <input class="form-control"
                                        value="{{$schedule->venues?$schedule->venues[0]->client->client_address:''}}"
                                        type="text" readonly />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="send_sms_form" action="{{route('venue_whatsapp',$schedule->id)}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">List of Staffs</h3>

                    </div>
                    <!-- /.box-header -->
                    <!---- SMS POPUP ---->

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <div class="col-md-12">
                                        <label class="control-label dp-inline bold">
                                            Staffs </label>
                                    </div>

                                    <div class="col-md-12 bulk_staff_temp_list sms">
                                        @php $staff_list=array();@endphp
                                        @if($schedule->venues)
                                        @foreach($schedule->venues as $vdv)
                                        @foreach ($vdv->satff_schdedules as $vdvss)
                                        @php
                                        $staff_list[$vdvss->staff->id]=array($vdvss->staff->id,$vdvss->staff->name,$vdvss->staff->contact_number);
                                        @endphp
                                        @endforeach
                                        @endforeach
                                        @foreach($staff_list as $key => $staff )
                                        <span class="staff_tags_temp ">
                                            <span class="close">x</span>
                                            <span class="username">{{$staff[1] }}
                                            </span>
                                            <br>
                                            <span class="contact_number">{{$staff[2] }}
                                            </span>
                                            <input name="staff[{{$staff[0]}}]" value="{{$staff[2]}}" type="hidden"
                                                class="form-control" />
                                        </span>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">whatsapp Message</h3>

                    </div>
                    <!-- /.box-header -->
                    <!---- SMS POPUP ---->

                    <di v class="container-fluid">
                        <div class="box-body">
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
                                        <label class="">Siging Area/Meeting
                                            point:</label>
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
                                            <small>formate: HH:MM i.e 12:00 or
                                                02:59</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label dp-inline"> Message
                                        </label>
                                    </div>

                                    <div class="col-md-12">
                                        @php
                                        $mytime = Carbon\Carbon::now();
                                        @endphp

                                        <textarea class="form-control message_body" readonly rows="23" id="message_body"
                                            name="message_body">
DoorSec is covering {{ isset($schedule)?$schedule->client->property_name:'' }} Live at {{ isset($schedule)?$schedule->client->client_address:'' }} Location Guide @{{localtionGuide}}

Siging Area / Meeting point: @{{signMeetPt}}

Date: {{ isset($schedule)?$schedule->start_date:'' }} To {{ isset($schedule)?$schedule->end_date :'' }}

Reporting time: @{{arrving_time}}
Briefing: @{{briefing_time}}
Work start: @{{strtTimeE}}

Dress code: @{{dressCode}}

Please note that the briefing is very important and vital as this is our first major event at this new venue.

Please be there on time.

Please select this message and reply "YES" OR "NO" for confirm/deny this message at the earliest.

Thank you

DoorSec Management</textarea>
                                        <span class="text-red response_message bold">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--- /.row --->
                            <div class="form-group row hide">
                                <div class="col-md-12">
                                    <label class="control-label dp-inline"> Status
                                    </label>
                                    <label class="control-label dp-inline m-l-20 muted_text label bg-yellow">
                                        Pending</label>
                                </div>
                                <!--- /.row --->
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success pull-right"> <i class="fa fa-send mr-"></i>
                                Send</button>
                        </div>
                </div>

            </div>
            <!----- /. SMS POPP ---->

            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        </div>
        <!-- /.col -->
    </form>

    <!-- /.row -->
</section>
<!-- /.content -->
@endsection
@section('content_js')
<script>
let vm = new Vue({
    el: "#appsss",
    data: {
        localtionGuide: '',
        dressCode: '',
        briefing_time: '',
        arrving_time: '',
        signMeetPt: '',
        strtTimeE: '',
    },

});
$('.close').on('click', function() {
    var r = confirm("Are you sure!");
    if (r == true) {
        txt = "You pressed OK!";
        $(this).parent().remove();
    } else {
        txt = "You pressed Cancel!";
    }

    //$(this).parent().remove();
});
$('.info_highligt').on('click', function() {
    // venue_detail_info
    if ($(this).hasClass('fa-minus')) {
        $(this).removeClass('fa-minus');
        $(".venue_detail_info").addClass('hide');
        $(this).addClass('fa-plus');


    } else {
        $(this).addClass('fa-minus');
        $(".venue_detail_info").removeClass('hide');
        $(this).removeClass('fa-plus');
    }
});
</script>
@endsection