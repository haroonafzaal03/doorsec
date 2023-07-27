@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1>
            Attendance
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('attendance')}}">Attendance</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Attendance</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" id="attendance_filter_form" action="#" method="GET" onSubmit="return getAttendanceDetails();" enctype="multipart/form-data">
                    @csrf
                    <!-- BEGIN Form -->
                    <div class="row">

                        <div class="col-md-2 hide">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Staff Type</label>
                                <select name="staff_type_id" id=""staff_type_id class="form-control" onChange="getStaffList(this.value)">
                                    <option value="">Select</option>
                                    @if($staff_type)
                                    @foreach($staff_type as $obj)
                                    <option value="{{$obj->id}}">{{$obj->type}} </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-2 hide">
                            <div class="form-group">
                                <label for="staff_name" class=""> Staff </label>
                                <select name="staff_id" id="staff_id" class="form-control image_select2 custom_css" style="width:100%">
                                    <option value="">Select</option>
                                    @if($staffs)
                                    @foreach($staffs as $obj)
                                    <!-- <option value="{{$obj->id}}" data-image="{{img($obj->picture)}}">{{$obj->name}} </option> -->
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_name" class=""> Client Type </label>
                                <select name="client_type_id" id="client_type_id" class="form-control" onChange="getClientsList(this.value);SwitchDatepicker(this.value)" >
                                    <option value=""> Select</option>
                                    @if($client_types)
                                    @foreach($client_types as $obj)
                                    <option value="{{$obj->id}}">{{$obj->type}} </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_name" class=""> Event / Venue </label>
                                <select name="event_venue_id" id="event_venue_id" class="form-control select2 custom_css" style="width:100%">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> From</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="day" autocomplete="new-password" name="day" value="" disabled />
                                </div>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->

                        <div class="col-md-2 hide">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> To</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="to_date"   name=" " value="" disabled />
                                </div>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->

                        <div class="col-md-1">
                            <div class="form-group row">
                                <div class=" col-md-12 text-right">
                                    <label for="staff_type_id" class="">  &nbsp;   </label>
                                    <button type="submit" class="btn btn-info" style="width:100%">GO</button>
                                </div>
                            </div>
                        </div>
                        <!---- /.col-md-12 ---->

                        <div class="col-md-1">
                            <div class="form-group row">
                                <div class=" col-md-12 text-right">
                                    <label for="staff_type_id" class="">  &nbsp;   </label>
                                    <button type="button" id="resetFilter" class="btn btn-default" style="width:100%">Reset</button>
                                </div>
                            </div>
                        </div>
                        <!---- /.col-md-12 ---->


                        </div>
                        <!-- /.row -->
                    </form>
                    <!-- END Form -->
              </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->




      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Staff Attendance Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                    @csrf
                    <!-- BEGIN Form -->
                    <div class="row">
                        <div class="col-md-12">
                            <form id="mark_attendance_form" method="POST" onSubmit="return false;" class="">
                            @csrf
                                <span class="response_message m-b-20 block"></span>
                                <table id="attendance_table" class="table table-bordered table-striped table-responsive datatable">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Event / Venue </th>
                                        <th>Event Date </th>
                                        <th width="20%"><input type="checkbox" id="checkAll" name="" class="checkAll" style="transform: scale(1.5);margin-right: 20px;margin-top: 0px;vertical-align: middle;" disabled />  Mark Attendance  </th>
                                        <th class="hide">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <!---- /.col-md-2 ---->


                        </div>
                        <!-- /.row -->
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



@section('content_js')
<script>

function getAttendanceDetails()
{
    valid = true;

    $("#client_type_id").parent().find('.response_message').remove();
    $("#event_venue_id").parent().find('.response_message').remove();
    $(".checkAll").prop('disabled',true);

    if(!$("#client_type_id").val())
    {
        $("#client_type_id").parent().append('<span class="text-red  response_message" > Select Client Type </span>');
        valid = false;
    }

    if(!$("#event_venue_id").val())
    {
        $("#event_venue_id").parent().append('<span class="text-red  response_message" > Select Event / Venue </span>');
        valid = false;
    }

    if(valid)
    {
        var formData = $("form#attendance_filter_form").serialize();
        var myJsonData = {formData: formData};


        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.post('/getStaffAttendance', myJsonData, function(response) {
        var obj = JSON.parse(response);
        $(".response_message").text(obj.message);

        $("#attendance_table").dataTable().fnDestroy();

        if(obj.status)
        {
            $("#attendance_table tbody").empty();
            $("#attendance_table tbody").html(obj.data);
            $("#attendance_table").DataTable();


            $('#attendance_table_wrapper .col-sm-6 .dataTables_length label').css('float','left');
            $('#attendance_table_wrapper .col-sm-6 .dataTables_length').append('<input type="button" id="markAttendanceBtn" name="" value="Save Attendance" class="btn btn-success m-b-10 m-l-20 pull-left" onClick="MarkAttendance()" />');

            getTotalActiveCheckBoxes();

            $(".checkAll").prop('disabled',false);
        }
        else
        {
                $("#attendance_table tbody").empty();
                $(".checkAll").prop('disabled',true);
        }


        });

    }


    return false;

}

  $(function () {

    $("#attendance_table").dataTable();

    $("#resetFilter").click(function(){
        $("#attendance_filter_form")[0].reset();
        getClientsList(0);
    });

    $("#payroll_table").dataTable();

  }); // jQuery Ends

  function SwitchDatepicker(val)
  {
    $("input.datepicker").prop('disabled',false);
    if(val == 2)
    {
        $("input.datepicker").val('');
        $("input.datepicker").prop('disabled',true);
    }

    return false;
  }


$("#checkAll").change(function(){
    $('input.attendance_box:checkbox').not(this).prop('checked', this.checked);
    getTotalActiveCheckBoxes();
});



function MarkAttendance()
{
    $("form#mark_attendance_form").find('.response_message').remove();
    var formData = $("form#mark_attendance_form").serialize();
    var myJsonData = {formData: formData};
    console.log(myJsonData);

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $.post('/saveAttendance', myJsonData, function(response) {
        var obj = JSON.parse(response);

        if(obj.status)
        {
            //$("form#mark_attendance_form").prepend('<span class="text-green  response_message block m-b-20 bold " > Attendance successfully marked </span>');
            iziToast.show({
                        title: 'Marked',
                        color: 'green', // blue, red, green, yellow
                        message: 'Attendance successfully marked!!!'
                    });


            setTimeout(function(){
                $("form#mark_attendance_form").find('.response_message').remove();
            },1000)
        }
        else
        {
            $("form#mark_attendance_form").prepend('<span class="text-red  response_message block m-b-20 bold " > Refresh Page and Try Again </span>');
            setTimeout(function(){
                $("form#mark_attendance_form").find('.response_message').remove();
            },500)
        }
    });



    return false;
}

function getTotalActiveCheckBoxes()
{
    $("#markAttendanceBtn").prop('disabled',false);

    var total = $("#attendance_table").find('input.attendance_box:checked').length;
    if(total == 0)
    {
        $("#markAttendanceBtn").prop('disabled',true);
    }
}






</script>
@endsection