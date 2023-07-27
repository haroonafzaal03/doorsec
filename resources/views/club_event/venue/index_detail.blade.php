@extends('layouts.master')

@section('content')

<section class="content-header">
    <h1>
        Venue
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> venue</a></li>
        <li><a href="{{route('event')}}">Venues</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List of All Venues</h3>
                    <a href="#" type="button" class="btn btn-warning pull-right hide" style="margin-right:1%">
                        View venue Calendar
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Date of Event</th>
                                <th>Event Timing</th>
                                <th># of shift </th>
                                <th>Total Staff </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if($schedule)
                            @foreach($schedule as $key => $sch)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $sch->client->property_name}}</td>
                                <td>{{\Carbon\Carbon::parse($sch->start_date)->format('M d ,Y').' TO '.\Carbon\Carbon::parse($sch->end_date)->format('M d ,Y')}}
                                </td>
                                <td><span class="">{{\Carbon\Carbon::parse($sch->start_time)->format('H:i a')}}</span>
                                    TO <span class="">{{\Carbon\Carbon::parse($sch->end_time)->format('H:i a')}}</span>
                                </td>
                                <td>{{count($sch->venues)}}</td>
                                <td>
                                @php
                                    $count=array();
                                @endphp
                                @foreach($sch->venues as $schv)
                                   @php if($schv->satff_schdedules){
                                       foreach($schv->satff_schdedules as $sssh ){
                                        $count[$sssh->staff_id]=$sssh->staff->name;
                                       }
                                   } @endphp
                                @endforeach
                                   <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="{{implode(' , ',$count)}}">{{count($count)}}</button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{url('/venue_sendsms/'.$sch->id)}}">Send Bluk Message to all</a></li>
                                        </ul>
                                    </div>
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
            $("#data_id").val(data_id);
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