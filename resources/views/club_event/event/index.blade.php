@extends('layouts.master')

@section('content')

<section class="content-header">
    <h1>
        Event
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('event')}}">Events</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List of All Events</h3>
                    @hasrole('add.new.event')
                    <a href="{{route('event_create')}}" class="btn btn-info pull-right">
                        Add New Event
                    </a>
                    @endhasrole
                    <a href="#" type="button" class="btn btn-warning pull-right hide" style="margin-right:1%">
                        View Event Calendar
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Event Name</th>
                                <th>Date of Event</th>
                                <th>Event Timing</th>
                                <th>Event Type</th>
                                <th>Location</th>
                                <th>Security Required</th>
                                <th>Staff Schdeuled</th>
                                @hasrole('event.status')
                                <th>Status</th>
                                @endhasrole
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // dd($schedule)
                            @endphp
                            @if($schedule)
                            @foreach($schedule as $key => $sch)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ ($sch->client)?$sch->client->property_name:'No Name'}}</td>
                                <td><a href="{{route('event_details',$sch->id)}}">{{ $sch->event_name}}</a></td>
                                <td>{{\Carbon\Carbon::parse($sch->start_date)->format('M d ,Y').' TO '.\Carbon\Carbon::parse($sch->end_date)->format('M d ,Y')}}
                                </td>
                                <td><span class="">{{\Carbon\Carbon::parse($sch->start_time)->format('H:i a')}}</span>
                                    TO <span class="">{{\Carbon\Carbon::parse($sch->end_time)->format('H:i a')}}</span>
                                </td>
                                <td>{{$sch->event_type}} </td>
                                <td>{{$sch->location}}</td>
                                <td>{{$sch->total_staff}}</td>
                                <td>{{count($sch->staffschedule)}}</td>
                                @hasrole('event.status')
                                <td><span
                                        class="label {{ get_label_class_by_key($sch->status) }} ">{{ get_status_name_by_key($sch->status)}}</span>
                                </td>
                                @endhasrole
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            @hasrole('event.edit')
                                            <li><a href="{{route('edit_event',$sch->id)}}">Edit</a></li>
                                            @endhasrole

                                            @hasrole('event.remove')
                                            <li><a data-toggle="" class="removeDataAnchor" data-id="{{$sch->id}}"
                                                    data-target="#removeDataPopup" href="#">Remove</a></li>
                                            @endhasrole
                                            <li><a href="{{route('duplicate_event',$sch->id)}}"><i
                                                        class="fa fa-copy"></i>Duplicate for next Event</a></li>
                                        </ul>
                                    </div>

                                    @if($sch->status != 'closed')
                                    <button class="close_event_btn btn btn-danger closeEventAnchor-off hide"
                                        data-target="#close_event_popup" data-id="{{$sch->id}}"
                                        data-event_name="{{ $sch->event_name }}"> Close Event </button>
                                    @endif


                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection
@section('content_js')
<script>
function RemoveSchedule() {
    var formData = $("#passDataForm").serialize();
    var myJsonData = {
        formData: formData
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('/DeleteEvent', myJsonData, function(response) {
        var obj = JSON.parse(response);
        $(".response_message").text(obj.message);

        if (obj.status) {

            setTimeout(function() {
                location.reload(true);
            }, 1500);

            $(".response_message").addClass('text-green');
            $(".response_message").removeClass('text-red');

        } else {

            $(".response_message").addClass('text-red');
            $(".response_message").removeClass('text-green');

        }

    });

}

// Close Event function
function CloseEvent() {
    //return false;

    var formData = $("#CloseEventForm").serialize();
    var myJsonData = {
        formData: formData
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('/update_event_status', myJsonData, function(response) {
        var obj = JSON.parse(response);
        $(".response_message").text(obj.message);

        if (obj.status) {
            setTimeout(function() {
                location.reload(true);
            }, 850);

            $(".response_message").addClass('text-green');
            $(".response_message").removeClass('text-red');

        } else {

            $(".response_message").addClass('text-red');
            $(".response_message").removeClass('text-green');

        }

    });

}



$(function() {

    // REMOVE Event Trigger
    $(".removeDataAnchor").click(function() {

        $(".response_message").removeClass('text-red,text-green');
        $("#passDataForm #input_password").val('');

        var data_id = $(this).attr('data-id');
        var target_modal = $(this).attr('data-target');
        var action_on = $(this).attr('data-action');

        if (target_modal) {
            $(".response_message").text('');
            $(".module_title").text('Remove Schedule');
            $("#delete_data_id").val(data_id);
            $("#action_on").val(action_on);
            $(target_modal + " .deleteBtnTrigger").attr('onClick', 'RemoveSchedule()');
            $(target_modal).modal();
        }

        return false;
    });

    // Close Event Trigger
    $(".closeEventAnchor").click(function() {

        $("#CloseEventForm input[name='input_password'").val('');

        var event_id = $(this).attr('data-id');
        var target_modal = $(this).attr('data-target');
        var event_name = $(this).attr('data-event_name');

        $(".event_name").text(event_name);

        if (target_modal) {
            $("#CloseEventForm input[name='event_id']").val(event_id);
            $(target_modal + " .apply_now").attr('onClick', 'CloseEvent()');
            $(target_modal).modal();
        }

        return false;

    });

    $('#example1').DataTable({});

}); // jQuery End
</script>
@endsection