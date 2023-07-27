@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
        Schedule
      </h1>
      <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('schedule')}}">Schedule</a></li>
        <li>View Schedule Details</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box collapsed-box ">
            <div class="box-header">
				<div class="row">
					<div class="col-md-6">
					  <h3 class="box-title">Event Schedule Details  <span class="label m-l-20 {{ get_label_class_by_key($schedule->status) }} " >{{ get_status_name_by_key($schedule->status)}}</span></h3>
					</div>
				    <div class="col-md-6">
					  <div class="box-tools pull-right">
						  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						  </button>
					  </div>
					  <a href="{{route('edit_schedule',$id)}}" class="btn btn-info pull-right m-r-30">Edit Schedule</a>
					  <a href="" data-toggle="modal" data-target="#exportSchedulePopup" class="btn btn-success  pull-right mr-1">Export Schedule Details</a>
					</div>
				</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive" style="display:none">
            <table id="schedule_details_table" class="table table-bordered m-b-30">
                <tr>
                    <th class="bg-grey border-1" width="30%">To</th>
                    <th class="border-1 text-muted">SIRA</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Client</th>
                    <th class="  text border-1 text-muted"> {{$schedule->client->property_name}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">From</th>
                    <th class="  text border-1 text-muted"> {{$schedule['schedule_from']}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Event Name</th>
                    <th class="  text border-1 text-muted"> {{$schedule['event_name']}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Date of Event</th>
                    <th class="  text border-1 text-muted"> {{\Carbon\Carbon::parse($schedule['start_date'])->format('M d ,Y').' To '.\Carbon\Carbon::parse($schedule['end_date'])->format('M d ,Y')}}</th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Type of Event</th>
                    <th class="  text border-1 text-muted"">  {{$schedule['event_type']}}    </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Location</th>
                    <th class="  text border-1 text-muted"">{{$schedule['location']}} </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Security Required</th>
                    <th class="  text border-1 text-muted"">{{$schedule['total_staff']}} </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Timings</th>
                    <th class="  text border-1 text-muted"">{{$schedule['start_time']}}  To   {{$schedule['end_time']}} </th>
                    </tr>
                    <tr>
                    <th class="bg-grey  text border-1">Contact Person</th>
                    <th class="  text border-1 text-muted"">{{$schedule['contact_person']}} </th>
                    </tr>
                </table>
                    <!---- /. Schedule Details Div close --->

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Staff Schedule Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
            <table id="staff_schedule_details_table" class="table table-bordered m-b-30">
            <thead>
                <tr>
                  <th class="bg-grey border-1" width="">No.</th>
                  <th class="bg-grey border-1" width="20%">Name</th>
                  <th class="bg-grey border-1" width="">SIRA No.</th>
                  <th class="bg-grey border-1" width="">Contact No.</th>
                  <th class="bg-grey border-1" width="">Start Time</th>
                  <th class="bg-grey border-1" width="">End Time</th>
                  <th class="bg-grey border-1" width="">Hours</th>
                  <th class="bg-grey border-1" width="">Rate Per Hour</th>
                  <th class="bg-grey border-1" width="">Status</th>
                  <th class="bg-grey border-1" width="">SMS Status</th>
                </tr>
            </thead>
            <tbody>
              @if($staff_schedule_details)
              @foreach($staff_schedule_details as $obj)
                <tr>
                <td class="bold" >{{ $loop->iteration }}</td>
                <td class="" ><img src="{{img($obj->staff->picture)}}" class="img-circle user_image" /> <span class="username" > {{ $obj->staff->name }}  </span> </td>
                <td class="" >{{ $obj->staff->sira_id_number }}</td>
                <td class="" >{{ $obj->staff->contact_number }}</td>
                <td class="" >{{ $obj->start_time }}</td>
                <td class="" >{{ $obj->end_time }}</td>
                <td class="" >{{ $obj->hours }}</td>
                <td class="" >{{ $obj->rate_per_hour }}</td>
                <td class="" ><span class="label  {{ get_label_class_by_key($obj->status) }}"> {{ get_status_name_by_key($obj->status) }}</span></td>
                <td class="" ><span class="label  {{ get_label_class_by_key($obj->sms_status) }}"> {{ get_status_name_by_key($obj->sms_status) }}</span></td>
                </tr>
              @endforeach
              @endif
            </tbody>

            </table>
                    <!---- /. Schedule Details Div close --->

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->




<!--  Export Schedule Details Modal -->

<div class="modal fade" id="exportSchedulePopup" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
      <form id="" action="{{route('exportSchedule',$id)}}"  method="POST" >
               @csrf
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Export Schedule Details</h4>
            </div>
            <div class="modal-body abc">
               <p class="bold"></p>
               <div class="row">
                     <div class="col-md-12">
                        <div classs="form-group row">
                             @if($columns)
                              @foreach($columns as $index => $arr)
                              <div class="col-md-6">
                              <label class="control-label block"> <input type="checkbox" class="icheck" id="" name="form_elements[{{ $arr }}]"  /> {{ getStaffLabels($arr) }} </label>
                              </div>
                              @endforeach
                             @endif

                           <div class="col-md-6">
                           </div>
                        </div>
                     </div>
                  </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success deleteBtnTrigger"> Export </button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <input class="form-control" type="hidden" id="" name=""  readonly="readonly" />
            </div>
         </form>
      </div>
   </div>
</div>
<!--  /.--- Export Schedule Details Modal CLOSE -->



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

    $('input.icheck').iCheck({
      checkboxClass: 'icheckbox_square-red',
      radioClass: 'iradio_square-red',
      increaseArea: '20%' /* optional */
    });


  })
</script>
@endsection