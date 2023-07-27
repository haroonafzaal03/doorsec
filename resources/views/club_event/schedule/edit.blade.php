@extends('layouts.master')
@section('content')

@php
$sch_status_list =  get_schedule_status_list();
$staff_sch_status_list =  get_staff_schedule_status_list();
$sms_status_list =  get_sms_status_list();
@endphp

<section class="content-header">
   <h1>
      Schedule
   </h1>
   <ol class="breadcrumb">
       <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('schedule')}}">Schedule</a></li>
      <li><a href="{{route('schedule_create')}}">Edit schedule</a></li>
   </ol>
</section>
<!-- Main content -->
<section class="content">
   <div class="row">
   <div class="col-xs-12">
   <div class="box collapsed-box-off collapsed-box">
      <div class="box-header">
         <h3 class="box-title">Edit Schedule</h3>
         <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
         </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body" style="display:none">
         <form role="form" id="edit_schedule_form" action="{{route('schedule_update',$schedule_data['id'])}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!--  BEGIN Form-->
            <div class="row">
               <div class="col-md-12">
                  <div class="col-md-6">
                     <div clas="row">
                        <div class="col-md-6 p-l-0">
                           <div class="form-group">
                              <label for="Company">To </label>
                              <input type="text"  class="form-control" readonly name="schedule_to" value="{{$schedule_data['schedule_to']}}"/>
                           </div>


                        </div>


                           <div class="col-md-6 p-l-0">
                              <div class="form-group">
                              <label for="Company">From </label>
                              <input type="text"  class="form-control" readonly  name="schedule_from" value="{{$schedule_data['schedule_from']}}"/>
                           </div>

                     </div>
<!--- /.row -->
                  </div>
<!--- /.col-md-6 -->
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="Company">Status</label>
                        <select class="form-control" id="status" name="status" >
                        <option value="">Select</option>
                        @if($sch_status_list)
                           @foreach($sch_status_list as $key =>  $arr)
                              <option value="{{$key}}" {{ ($schedule_data['status'] == $key ) ? 'selected' : '' }}>{{$arr}}</option>
                           @endforeach
                        @endIf
                        </select>
                     </div>

                  </div>

                  <div class="col-md-6">
                     <div clas="row">
                        <div class="col-md-4 p-l-0">

                           <div class="form-group">
                              <label for="Company">Client</label>
                              <select class="form-control" id="client_id" name="client_id" onChange="getClientData(this.value)" >
                              <option value="">Select</option>
                              @if($client)
                                 @foreach($client as $obj)
                                 <option value="{{ $obj->id }}" {{ ($obj->id == $schedule_data['client_id']) ? 'selected' : '' }} >{{ $obj->property_name }}</option>
                                 @endforeach
                                 @endif
                              </select>
                           </div>

                        </div>

                        <div class="col-md-4 p-r-0">

                           <div class="form-group">
                              <label for="Company">Contact Person</label>
                              <input type="text" class="form-control alphabets_only" value="{{$schedule_data['contact_person']}}" id="client_contact_person" name="contact_person"  />
                           </div>
                        </div>

                        <div class="col-md-4 p-r-0">

                           <div class="form-group">
                              <label for="Company">Contact No.</label>
                              <input type="text" class="form-control number_only" id="client_contact_no" name="contact_no" value="{{$schedule_data['contact_no']}}"  />
                           </div>
                        </div>
                     </div>
                  </div>



                  <div class="col-md-6">
                     <div clas="row">
                        <div class="col-md-6 p-l-0">
                           <div class="form-group">
                              <label for="Company">Event Name</label>
                              <input type="text" class="form-control" value="{{ $schedule_data['event_name'] }}" id="event_name" name="event_name" >
                           </div>
                        </div>
                        <div class="col-md-6 p-r-0">
                           <div class="form-group">
                              <label for="Company">Event type</label>
                              <input type="text" class="form-control" id="event_type" name="event_type" value="{{ $schedule_data['event_type'] }}">
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Date picker -->
                  <div class="col-md-6" >
                     <div class="bootstrap-timepicker row">
                        <label class="col-md-12 d-block">Event Date </label>
                        <div class="form-group col-md-6">
                           <small><strong>Start : </strong></small>
                           <div class="input-group">
                              <input type="text" readonly  value="{{ (!empty($schedule_data['start_date']) && ($schedule_data['start_date'] != '0000-00-00')) ?  date('m/d/Y',strtotime($schedule_data['start_date'])) : '' }}" name="start_date" class="form-control datepicker start_date">
                              <div class="input-group-addon">
                                 <i class="fa fa-calendar-o"></i>
                              </div>
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <small><strong>End : </strong></small>
                           <div class="input-group">
                              <input type="text" readonly value="{{ (!empty($schedule_data['end_date']) && ($schedule_data['end_date'] != '0000-00-00')) ?  date('m/d/Y',strtotime($schedule_data['end_date'])) : '' }}" name="end_date" class="form-control datepicker end_date">
                              <div class="input-group-addon">
                                 <i class="fa fa-calendar-o"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- time Picker -->
                  <div class="col-md-6" >
                     <div class="bootstrap-timepicker row">
                        <label class="col-md-12 d-block">Event Timing </label>
                        <div class="form-group col-md-6">
                           <small><strong>From : </strong></small>
                           <div class="input-group">
                              <input type="text" readonly value="{{$schedule_data['start_time']}}" name="start_time" class="form-control timepicker">
                              <div class="input-group-addon">
                                 <i class="fa fa-clock-o"></i>
                              </div>
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <small><strong>To : </strong></small>
                           <div class="input-group">
                              <input type="text" readonly value="{{$schedule_data['end_time']}}" name="end_time" class="form-control timepicker">
                              <div class="input-group-addon">
                                 <i class="fa fa-clock-o"></i>
                              </div>
                           </div>
                        </div>
                        <!-- /.form group -->
                     </div>
                  </div>
                  <div class="col-md-6 ">
                     <div class="form-group">
                        <label for="">Security Required </label>
                        <input type="number" class="form-control" name="total_staff" value="{{$schedule_data['total_staff']}}" min="1"/>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text"  class="form-control" name="location" value="{{$schedule_data['location']}}"/>
                     </div>
                  </div>
                  <div class="col-md-12 ">
                     <div class="pull-right">
                        <button type="submit" class="btn btn-info">Update</button>
                     </div>
                  </div>
         </form>
         </div>
         <!-- /.box-body -->
         </div>
         <!-- /.box -->
      </div>
      <!-- /.col -->
      </div>
      <!-- /.box -->


      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Add Staff Schedule </h3>
            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body">
            <div class="row">
               <div class="col-md-6">
                  <h4 class="heading bold text-green underline">{{ $schedule_data['event_name'] }} </h4>
               </div>
               <div class="col-md-6  pull-right mb-3 text-center">
               @if($staff_types)
               @foreach($staff_types as $index =>$st)

               <label class="control-label dp-inlines staff_type_labels  {{ ($st->id == 1) ? 'active' : '' }} "  id="staff_type_label-{{$loop->iteration}}">
                  <input type="radio" {{ ($st->id == 1) ? "checked" : "" }}  data-typeid="{{$st->id}}" id="staff_type_{{$st->id}}" name="staff_type_radio" class=" staff_type_radio">
                  {{$st->type}}</label>
               @endforeach
               @endif
               </div>
            </div>



            <div class="row">

               <div class="col-md-4 col-md-offset-0">
                  <div class="col-md-12 customlist">
                     <ul id="staffList_filled" class="users_multi_listbox">
                     </ul>
                  </div>
               </div>

               <div class="col-md-4 " style="text-align: center;">
                  <a class="btn btn-danger disabled confrimStaffScheduleRemove"  data-target="#RemoveStaffSchConfirmation" id="staffList_filled-move"><i class="fa fa-arrow-right ml-1" > </i>  Remove</a>
                  <a class="btn btn-warning " data-toggle="modal" data-target="#SendSMSPopup" id="send_sms_btn" ><i class="fa fa-whatsapp mr-1" > </i>Send SMS </a>

                  <a class="btn btn-success staffList_new-move" id="add_staff_btn" class="" ><i class="fa fa-arrow-left mr-1" > </i>ADD </a>
               </div>


               <div class="col-md-4">
                  <div class="col-md-12 customlist">
                     <ul id="staffList_new" class="users_multi_listbox">
                     </ul>
                  </div>
               </div>

            </div>





         </div>
         <!-- /.box-body -->


    </div>
   <!-- /.BOX -->




   <div class="box">
         <div class="box-header">
            <h3 class="box-title"> Staff Schedule Details </h3>
            <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body">


            <div class="row">
               <div class="col-md-12 table-responsive">
                  <form role="form" id="add_staff_schedule_form" onSubmit="return CheckValidation(this.id);" action="{{ route('staff_schedule_update',$id)}}" method="POST" >
                     @csrf
                     <table id="staff_schedule_table" class="table table-bordered table-striped table-responsive">
                        <thead>
                           <tr>
                              <th style="width:15%">Staff Name</th>
                              <th>SIRA NO.</th>
                              <th>Cont. No.</th>
                              <th style="width:9%">Start Time</th>
                              <th style="width:9%">End Time</th>
                              <th style="width:9%">Hours</th>
                              <th style="width:10%">Rate per hour</th>
                              <th>Staff Status</th>
                              <th>SMS Status</th>
                           </tr>
                        </thead>
                        <tbody>
                          @if($staff_schedule_details)
                          @foreach($staff_schedule_details as $index =>$obj)
                          <tr id="row-{{$obj->staff->id}}" class="{{ ($obj->status == 'dropout' ) ? 'row_disabled' : ''}} ">
                          <td class="" ><img src="{{img($obj->staff->picture)}}" class="img-circle user_image" /> <span class="username" > {{ $obj->staff->name }}  </span> </td>
                          <td class="" >{{ $obj->staff->sira_id_number }}</td>
                          <td class="" >{{ $obj->staff->contact_number }}</td>
                          <td class="" ><input type="" readonly onChange="calculateStaffHours({{$obj->staff->id}})" class="form-control timepicker start_time_input"  id="start_time_input-{{$obj->staff->id}}" name="array_staff[{{$index}}][start_time]" value="{{ $obj->start_time }}" data-validation='true' /></td>
                          <td class="" ><input type="" readonly onChange="calculateStaffHours({{$obj->staff->id}})"  class="form-control timepicker end_time_input"  id="end_time_input-{{$obj->staff->id}}" name="array_staff[{{$index}}][end_time]" value="{{ $obj->end_time }}" data-validation='true'/></td>

                          @php

                           $start = \Carbon\Carbon::parse($obj->start_time);
                           $end =   \Carbon\Carbon::parse($obj->end_time);
                           $minutes = $end->diffInMinutes($start);
                           $value = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60);
                           $parts = explode(':', $value);
                           $hours = $parts[0] + floor(($parts[1]/60)*100) / 100 . PHP_EOL;

                           @endphp

                          <td class="" ><input type="" class="form-control"  id="staff_hours-{{$obj->staff->id}}" name="array_staff[{{$index}}][hours]" value="{{ $hours }}" readonly data-validation='true' /></td>


                          <td class="" ><input type="hidden" class="form-control"  id="" name="array_staff[{{$index}}][staff_id]" value="{{ $obj->staff->id }}" readonly />
                          <input type="" class="form-control number_only"  id="rph-{{$obj->staff->id}}" name="array_staff[{{$index}}][rate_per_hour]" value="{{ $obj->rate_per_hour }}" data-validation='true' />
                          </td>
                          <td class="" id="td_staff_status-{{$obj->staff->id}}" >
                              <select class="form-control" id="staff_status" name="array_staff[{{$index}}][status]" >
                              <option value="" disabled>Select</option>
                              @if($staff_sch_status_list)
                                 @foreach($staff_sch_status_list as $key =>  $arr)
                                    <option value="{{$key}}" {{ ($obj->status == $key ) ? "selected" : "" }}>{{$arr}}</option>
                                 @endforeach
                              @endIf
                              </select>
                          </td>
                          <td class="" >
                              <select class="form-control sms_status" id="sms_status" name="array_staff[{{$index}}][sms_status]" >
                              <option value="" disabled>Select</option>
                              @if($sms_status_list)
                                 @foreach($sms_status_list as $key =>  $arr)
                                    <option value="{{$key}}" {{ ($obj->sms_status == $key ) ? "selected" : "" }}>{{$arr}}</option>
                                 @endforeach
                              @endIf
                              </select>
                          </td>
                          </tr>
                          @endforeach
                          @endif
                        </tbody>
                        <tfoot>
                           <tr>
                             <td colspan="9"><button type="submit" class=" {{ (sizeof($staff_schedule_details) > 0 ) ? '' : 'hide'}} btn btn-success mt-3  add_staff_schedule_btn">Update Staff Schedule</button></td>
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
   <!-- /.BOX -->




</section>
<!-- /.content -->
@endsection
@section('content_js')
<script>


   function calculateStaffHours(id)
   {
      var start =moment.utc( $('#start_time_input-'+id).val(),"HH:mm");
      var end = moment.utc($('#end_time_input-'+id).val(),"HH:mm");
      var hour = moment.utc(end.diff(start)).format("HH");
      var minute = moment.utc(end.diff(start)).format("mm");
      var duration = parseFloat(minute/60) + parseFloat(hour) || 0;

      $('#staff_hours-'+id).val(duration.toFixed(2));


   }


   function RemoveSelectedStaffFromList()
   {
      $('#staffList_filled li.selected').each(function(index,value){
      var data_id = $(this).children('img').attr('data-id');
      var data_staffid = $(this).children('img').attr('data-staffid');
         $('#staff_schedule_table tbody tr#row-'+data_staffid).remove();
      });


      if($("#staff_schedule_table tbody tr").length == 0)
      {
         $(".add_staff_schedule_btn").addClass('hide');
      }



      $('#staffList_filled').jqListbox('transferSelectedTo', $('#staffList_new'));
      return false;
   }

   // Remove Staff Schedule
   function RemoveStaffchedule()
   {
      var formData =  $("#removeStaffScheduleForm").serialize();
      var myJsonData = {formData: formData};

      $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });

      $.post('/DeleteStaffSchedule/{{$id}}', myJsonData, function(response) {
         var obj = JSON.parse(response);
         $(".response_message").text(obj.message);

         if(obj.status)
         {
            setTimeout(function(){
            location.reload(true);
            },850);


         RemoveSelectedStaffFromList();
         $(".response_message").addClass('text-green');
         $(".response_message").removeClass('text-red');

         }
         else
         {

         $(".response_message").addClass('text-red');
         $(".response_message").removeClass('text-green');

         }

      });

   }

   // GET DATA OF CLIENT BY ID //
   function getClientData(id)
   {

      $.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
      });

      $.get('/get_client_data_json/'+id, function(response) {
            var obj = JSON.parse(response);
            if(obj != null || obj != "")
            {
            $("#client_contact_person").val(obj.venue_manager_name);
            $("#client_contact_no").val(obj.venue_manager_number);
            }
            else
            {
            }

         });
   }






   $(function() {

      $('#edit_schedule_form').bootstrapValidator({

            message: 'This value is not valid',
            feedbackIcons: {
                  valid: '',
                  invalid: '',
                  validating: 'glyphicon glyphicon-refresh2'
            },
            fields: {
               client_id: {
                     validators: {
                        notEmpty: {
                              message: 'Select Client from list'
                        }
                     }
                  },
                  event_name: {
                     validators: {
                        notEmpty: {
                              message: 'Event Name is required'
                        }
                     }
                  },
                  event_type: {
                     validators: {
                        notEmpty: {
                              message: 'Event Type is required'
                        }
                     }
                  },
                  total_staff: {
                     validators: {
                        notEmpty: {
                              message: 'Minimum Staff Required'
                        },
                        regexp: {
                              regexp: /^[0-9]+$/,
                              message: 'Enter total no. staff in digits'
                        }
                     }
                  },
                  start_time: {
                     validators: {
                        notEmpty: {
                              message: 'End Date is required'
                        }
                     }
                  },
                  end_time: {
                     validators: {
                        notEmpty: {
                              message: 'End Date is required'
                        }
                     }
                  },
                  location: {
                     validators: {
                        notEmpty: {
                              message: 'Location is required'
                        }
                     }
                  }
            }
         });




$('.number_only').keyup(function () {
this.value = this.value.replace(/[^0-9\.]/g,'');
});

$('.alphabets_only').keyup(function () {
this.value = this.value.replace(/[^a-zA-Z\.]/g,'');
});
























      loadTimePicker();

    // custom list box
    var staffList = $('#staffList_new').html();

      $('#staffList_new').jqListbox({
            targetInput: false,
            //selectedClass: 'selected-item',
            initialValues: [
               @if($remainingStaff)
              @foreach($remainingStaff as $obj)
                  '<li data-pos="{{$loop->iteration}}" data-move="true" data-id="" data-view="{{ ($obj->stafftypes->id == 1) ? "on" : "off" }}" data-stafftype="{{$obj->stafftypes->id}}" data-exist="false" data-dropout="false" class="abbc"><span class="li_position_no bold">   </span>   <img src="{{img($obj->picture)}}" data-exist ="false" class="custom staff_list_view img-circle" data-id="{{$loop->iteration}}"  data-sira="{{$obj->sira_id_number}}" data-staffid="{{ $obj->id}}" data-contact="{{$obj->contact_number}}" data-start_time="" data-end_time="" data-rph=""   data-hours="" data-status="" data-sms_status=""   /> <span class="list_staff_name ">{{$obj->name}} </span> - <span class="list_staff_contact_no"> {{$obj->contact_number}} </span> </li>',
              @endforeach
              @endif
            ],
            onBeforeRender: function (listbox) {
                if (listbox.countSelected() == 0) {
                    $('#example4-copy,.staffList_new-move,#send_sms_btn').addClass('disabled');
                } else {
                  $('#example4-copy,.staffList_new-move').removeClass('disabled');
                }
            },
            onAfterRender: function (listbox) {

                }
         });



        $('#staffList_filled').jqListbox({
            targetInput: false,
            initialValues: [
              @if($staff_schedule_details)
              @foreach($staff_schedule_details as $obj)
                  '<li data-pos="{{$loop->iteration}}" data-id="" data-view="on" data-stafftype="{{$obj->staff->stafftypes->id}}" data-exist="true" data-dropout="{{ ($obj->status == "dropout") ? "true" : "false" }}"><span class="li_position_no bold">{{$loop->iteration}} - </span>  <img src="{{img($obj->staff->picture)}}" data-exist ="true" class="custom staff_list_view img-circle" data-id="{{$loop->iteration}}"  data-sira="{{$obj->staff->sira_id_number}}" data-staffid="{{ $obj->staff->id}}" data-contact="{{$obj->staff->contact_number}}" data-start_time="{{$obj->start_time}}" data-end_time="{{$obj->end_time}}" data-hours="{{$obj->hours}}" data-rph="{{$obj->rate_per_hour}}" data-status="{{$obj->status}}" data-sms_status="{{$obj->sms_status}}"  /> <span class="list_staff_name "> {{$obj->staff->name}} </span> - <span class="list_staff_contact_no"> {{$obj->staff->contact_number}} </span></li>',
              @endforeach
              @endif

            ],
            onBeforeRender: function (listbox) {
                if (listbox.countSelected() == 0) {
                    $('#staffList_filled-copy,#staffList_filled-move').addClass('disabled');
                } else {
                    $('#staffList_filled-copy,#staffList_filled-move,#send_sms_btn').removeClass('disabled');
                }
            },
            onAfterRender: function (listbox) {
                  GenerateIndexing();
                }
           });

        $('.staffList_new-move').click(function(e){
         $(".add_staff_schedule_btn").removeClass('hide');
         $('#staffList_new').jqListbox('transferSelectedTo', $('#staffList_filled'));


            e.preventDefault();
            $("#staff_schedule_table tbody").empty();
            $('#staffList_filled li').each(function(index,value){
            var row_no = index;

            var data_id = $(this).children('img').attr('data-id');
            var data_staffid = $(this).children('img').attr('data-staffid');
            var data_image = $(this).children('img').attr('src');
            var data_sira = $(this).children('img').attr('data-sira');
            var data_contact = $(this).children('img').attr('data-contact');
            var data_rph = $(this).children('img').attr('data-rph');
            var data_hours = $(this).children('img').attr('data-hours');
            var data_start_time = $(this).children('img').attr('data-start_time');
            var data_end_time =  $(this).children('img').attr('data-end_time');
            var data_status =  $(this).children('img').attr('data-status');
            var data_sms_status =  $(this).children('img').attr('data-sms_status');
            var text = $(this).find('span.list_staff_name ').text();
            var data_dropout = $(this).attr('data-dropout');

            var schedule_table_start_time = $("form#edit_schedule_form input[name='start_time']").val();
            var schedule_table_end_time = $("form#edit_schedule_form input[name='end_time']").val();


            if(data_start_time === "" || data_start_time === null || data_start_time ==="0:00" )
            {
               data_start_time = schedule_table_start_time;
            }

            if(data_end_time === "" || data_end_time === null || data_end_time === "0:00" )
            {
               data_end_time = schedule_table_end_time;
            }

            var  staff_status_opt = '<select id="" class="form-control" name="array_staff['+row_no+'][status]" >';
                staff_status_opt += '<option value="" disabled>Select</option>';
                @foreach($staff_sch_status_list as $key => $arr)
                  staff_status_opt += '<option value="{{$key}}" >{{$arr}}</option>';
               @endforeach
            staff_status_opt += '</select>';


            var  sms_status_opt = '<select id="" class="form-control" name="array_staff['+row_no+'][sms_status]" >';
            sms_status_opt += '<option value="" disabled>Select</option>';
                @foreach($sms_status_list as $key => $arr)
                sms_status_opt += '<option value="{{$key}}">{{$arr}}</option>';
               @endforeach
               sms_status_opt += '</select>';




            $("#staff_schedule_table tbody").append('<tr id="row-'+data_staffid+'"><td><img src="'+data_image+'" class="staff_list_view img-circle"/> <span class="list_staff_name" >'+text+'</span><input type ="hidden" value="{{$schedule_data->id}}"name="array_staff['+row_no+'][schedule_id]"  readonly /><input type ="hidden" value="'+data_staffid+'"name="array_staff['+row_no+'][staff_id]"  readonly /></td><td><span>'+data_sira+'<span></td><td><span >'+data_contact+'</span></td><td><input class="form-control timepicker start_time_input"  type="" readonly id="start_time_input-'+data_staffid+'" name="array_staff['+row_no+'][start_time]" data-validation="true"  value="'+data_start_time+'" onChange="calculateStaffHours(\''+data_staffid+'\')" /></td><td><input class="form-control timepicker end_time_input" type="" readonly id="end_time_input-'+data_staffid+'" name="array_staff['+row_no+'][end_time]"  data-validation="true"   value="'+data_end_time+'"   onChange="calculateStaffHours(\''+data_staffid+'\')" /></td><td><input class="form-control " type="" id="staff_hours-'+data_staffid+'"  value="'+data_hours+'" data-validation="true" name="array_staff['+row_no+'][hours]" readonly /></td><td><input class="form-control number_only"  type="" value="'+data_rph+'" data-validation="true"  id="rate_per_hour-'+data_staffid+'" name="array_staff['+row_no+'][rate_per_hour] value="" /></td><td id="staff_status_td">'+staff_status_opt+'</td><td id="sms_status_td">'+sms_status_opt+'</td></tr>')

            $('select[name="array_staff['+row_no+'][status]"]').val(data_status);
            $('select[name="array_staff['+row_no+'][sms_status]"]').val(data_sms_status);

         });


            $('.number_only').keyup(function () {
            this.value = this.value.replace(/[^0-9\.]/g,'');
            });
            //Timepicker
            loadTimePicker();

            return false;
        });

        $('#staffList_filled-move-off').click(function(e){




        });

            /** SMS POPUP CODE **/
        $("#send_sms_btn").click(function(ev){

          $('#staffList_new li.selected,#staffList_filled li.selected').each(function(ev){
            var data_contact = $(this).children('img').attr('data-contact');
              $('input#phone_number').tagsinput('add', data_contact);

          });

          $("#SendSMSPopup").modal();

        });


            /** Staff Schedule Confirmation **/
        $(".confrimStaffScheduleRemove").click(function(ev){
            var data_staffid = "";
            var comma_string = "";
            var target_modal = $(this).attr('data-target');
            var data_exist = "";

            $(".response_message").text('');
            $("form#removeStaffScheduleForm")[0].reset();

            $('#staffList_filled li.selected').each(function(index,value){
               if(index > 0)
               {
                  comma_string = ",";
               }
               data_exist = $(this).children('img').attr('data-exist');

               if( data_exist ==  "true")
               {
                  data_staffid  += comma_string + $(this).children('img').attr('data-staffid');
               }

               $(this).children('img').attr('data-exist','false');
               $(this).attr('data-exist','false');
            });

            if(data_staffid)
            {
               $(""+target_modal+" input[name='data_id']").val(data_staffid);
               $(target_modal + " .deleteBtnTrigger").attr('onClick','RemoveStaffchedule()');
               $(target_modal).modal();
            }
            else
            {
               RemoveSelectedStaffFromList();
            }


        });


        /*  STAFF TYPE SELECTION RADIO BUTTON  */

         $("input.staff_type_radio[type='radio']").change(function(){

            $('.staff_type_labels').removeClass('active');
            $(this).parent('.staff_type_labels').addClass('active');
            var staff_type_id  =  $(this).attr('data-typeid');
            $("ul#staffList_new li").attr('data-view','off');
            if(staff_type_id)
            {
              $("ul#staffList_new li[data-stafftype="+staff_type_id+"]").attr('data-view','on');
            }

         });

   // DATE PICKER

   $('.number_only').keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g,'');
  });

  $('.alphabets_only').keyup(function () {
    this.value = this.value.replace(/[^a-zA-Z\.]/g,'');
  });


  $(".start_date").datepicker({
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('.end_date').datepicker('setStartDate', minDate);
    });

    $(".end_date").datepicker()
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('.start_date').datepicker('setEndDate', maxDate);
    });


   });


function GenerateIndexing()
{

	$('#staffList_filled li').each(function(index,value){

			var index = $(this).index() + 1;
      $(this).find('.li_position_no').text(index + " - ")

    }); // filled boxx

}

function CheckValidation(form_id)
{
   var validation = true;
   $("#"+form_id + " input[data-validation='true']").each(function(e){
         console.log(this.id);
         if(this.value == null || this.value == '' || this.value == " " || this.value == "0:00")
         {
               $("#"+this.id).addClass('error');
               validation = false;
         }
   });
   return validation;
}

</script>
@endsection