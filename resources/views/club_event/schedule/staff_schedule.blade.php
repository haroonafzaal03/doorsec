@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
      Staff Schedule
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Staff Schedule</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Event Details </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">

            <table id="schedule_details_table" class="table table-bordered m-b-30">
                <tr>
                    <th class="bg-grey border-1" width="30%">To</th>
                    <th class="border-1 text-muted">SIRA</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Client</th>
                    <th class="  text border-1 text-muted"> {{$schedule_data->client->property_name}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">From</th>
                    <th class="  text border-1 text-muted"> {{$schedule_data->schedule_from}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Event Name</th>
                    <th class="  text border-1 text-muted"> {{$schedule_data->event_name}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Date of Event</th>
                    <th class="  text border-1 text-muted"> {{\Carbon\Carbon::parse($schedule_data->start_date)->format('M d ,Y').' To '.\Carbon\Carbon::parse($schedule_data->end_date)->format('M d ,Y')}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Type of Event</th>
                    <th class="  text border-1 text-muted"">  {{$schedule_data->event_type}}    </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Location</th>
                    <th class="  text border-1 text-muted"">{{$schedule_data->location}} </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Security Required</th>
                    <th class="  text border-1 text-muted"">{{$schedule_data->total_staff}} </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Timings</th>
                    <th class="  text border-1 text-muted"">{{$schedule_data->start_time}}  To   {{$schedule_data->end_time}} </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Contact Person</th>
                    <th class="  text border-1 text-muted"">{{$schedule_data->contact_person}} </th>
                    </tr>
                </table>
                    <!---- /. Schedule Details Div close --->





            <div class="row">
              <div class="col-md-4  mb-3 hide">
                  <div class="form-group row">
                      <label for="Company inline" class="col-md-4">Event </label>
                      <div class="col-md-8">
                        <input type="text"  class="form-control muted_fields" name="scheduled_client" data-id=""  value="{{ $schedule_data->client->property_name }}" readonly />
                      </div>
                  </div>
                </div>
            <!-- /.col-md-3 -->

                <div class="col-md-4 hide">
                  <div class="form-group row">
                      <label for="Company inline" class="col-md-4">Event Type </label>
                      <div class="col-md-8">
                        <input type="text"  class="form-control muted_fields" name="scheduled_client" data-id="" value="Event Type A" readonly />
                      </div>
                  </div>
              </div>
            <!-- /.col-md-3 -->

            </div>






            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Staff Schedule </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <div class="row">
                  <div class="col-md-4 col-md-offset-0">
                      <div class="col-md-12 customlist">
                          <ul id="staffList_filled" class="users_multi_listbox">


                          </ul>
                      </div>
                  </div>
                    <div class="col-md-4 " style="text-align: center;">
                        <a class="btn btn-danger disabled" id="staffList_filled-move"><i class="fa fa-arrow-right ml-1" > </i>  REMOVE</a>
                        <a class="btn btn-success staffList_new-move" id="add_staff_btn" class="" ><i class="fa fa-arrow-left mr-1" > </i>ADD </a>
                        <br/>
                        <br/>
                        <a class="btn btn-warning " data-toggle="" data-target="" id="send_sms_btn" ><i class="fa fa-whatsapp mr-1" > </i>Send SMS </a>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 customlist">
                            <ul id="staffList_new" class="users_multi_listbox">
                            </ul>
                        </div>
                    </div>
              </div>

              <div class="row">
                  <div class="col-md-12">
                    <h4 class="mt-3 mb-3">
                      Staff Details
                    </h4>
                  </div>


                  <div class="col-md-12 table-responsive">
                  <form role="form" action="{{ route('staff_schedule_store')}}" method="POST" >
                    @csrf
                    <table id="staff_schedule_table" class="table table-bordered table-striped table-responsive">
                      <thead>
                        <tr>
                           <th style="width:15%">Staff Name</th>
                          <th>SIRA NO.</th>
                          <th>Contact No.</th>
                          <th>Start Time</th>
                          <th>End Time</th>
                          <th>Hours</th>
                          <th>Rate per hour</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                      <tfoot>
                      <tr>
                        <td colspan="7"><button type="submiut"class="btn btn-success mt-3 hide add_staff_schedule_btn">Save Staff Schedule</button></td>
                      </tr>
                     </tfoot>
                    </table>
                  </form>

                </div>

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
<script>
  $(function () {

    // custom list box
    var staffList = $('#staffList_new').html();

      $('#staffList_new').jqListbox({
            targetInput: false,
            initialValues: [
              @if($staff)
              @foreach($staff as $index => $obj)
                  '<li class=""><img src="{{img($obj->picture)}}" class="custom staff_list_view img-circle" data-id="index-{{$loop->iteration}}" data-id="{{$obj->id}}" data-sira="{{$obj->sira_id_number}}" data-staffid="{{ $obj->id}}" data-contact="{{$obj->contact_number}}" data-start_time="" data-end_time="" /> <span class="list_staff_name ">{{$obj->name}}</span></li>',
              @endforeach
              @endif
            ],
            onBeforeRender: function (listbox) {
                if (listbox.countSelected() == 0) {
                    $('#example4-copy,.staffList_new-move,#send_sms_btn').addClass('disabled');
                } else {
                    $('#example4-copy,.staffList_new-move,#send_sms_btn').removeClass('disabled');
                }
            }
        });
        $('#staffList_filled').jqListbox({
            targetInput: false,
            initialValues: [

            ],
            onBeforeRender: function (listbox) {
                if (listbox.countSelected() == 0) {
                    $('#staffList_filled-copy,#staffList_filled-move').addClass('disabled');
                } else {
                    $('#staffList_filled-copy,#staffList_filled-move,#send_sms_btn').removeClass('disabled');
                }
            }
        });

        $('.staffList_new-move').click(function(e){
         $(".add_staff_schedule_btn").removeClass('hide');
          $('#staffList_new li.selected').each(function(index,value){
            var row_no = index;
            var data_id = $(this).children('img').attr('data-id');
            var data_staffid = $(this).children('img').attr('data-staffid');
            var data_image = $(this).children('img').attr('src');
            var data_sira = $(this).children('img').attr('data-sira');
            var data_contact = $(this).children('img').attr('data-contact');
            var data_start_time = $(this).children('img').attr('data-start_time');
            var data_end_time = $(this).children('img').attr('data-end_time');
            var text = $(this).text();



            $("#staff_schedule_table tbody").append('<tr id="row-'+data_id+'"><td><img src="'+data_image+'" class="staff_list_view img-circle"/> <span class="list_staff_name" >'+text+'</span><input type ="hidden" value="{{$schedule_data->id}}"name="array_staff['+row_no+'][event_id]"  readonly /><input type ="hidden" value="'+data_staffid+'"name="array_staff['+row_no+'][staff_id]"  readonly /></td><td><span>'+data_sira+'<span></td><td><span >'+data_contact+'</span></td><td><input class="form-control timepicker"  type="" id="start_time" name="array_staff['+row_no+'][start_time]"  value="'+data_start_time+'"  /></td><td><input class="form-control timepicker"  type="" id="end_time" name="array_staff['+row_no+'][end_time]"     value="'+data_end_time+'" /></td><td><input class="form-control " type="number" id="hours" name="array_staff['+row_no+'][hours]" /></td><td><input class="form-control "  type="number" id="rate_per_hour" name="array_staff['+row_no+'][rate_per_hour] value="" /></td></tr>')

            });

            $('#staffList_new').jqListbox('transferSelectedTo', $('#staffList_filled'));
            e.preventDefault();

            loadTimePicker();
            return false;
        });

        $('#staffList_filled-move').click(function(e){



          $('#staffList_filled li.selected').each(function(index,value){
              var data_id = $(this).children('img').attr('data-id');
              $('#staff_schedule_table tbody tr#row-'+data_id).remove();
          });


          if($("#staff_schedule_table tbody tr").length == 0)
           {
             $(".add_staff_schedule_btn").addClass('hide');
           }



          $('#staffList_filled').jqListbox('transferSelectedTo', $('#staffList_new'));
            e.preventDefault();
            return false;
        });

            /** SMS POPUP CODE **/
        $("#send_sms_btn").click(function(ev){

          $('#staffList_new li.selected').each(function(ev){
           // alert($(this).children('img').attr('data-contact'));
            var data_contact = $(this).children('img').attr('data-contact');
              $('input#phone_number').tagsinput('add', data_contact);

          });
          $("#SendSMSPopup").modal();

        });




  })
</script>
@endsection