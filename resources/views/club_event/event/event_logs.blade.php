@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
        Event Logs
      </h1>
      <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('event')}}">Event</a></li>
        <li>Event Logs</li>
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
                    <h3 class="box-title">Event Details  <span class="label m-l-20 {{ get_label_class_by_key($event_details->status) }} " >{{ get_status_name_by_key($event_details->status)}}</span></h3>
                  </div>
                    <div class="col-md-6">
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
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
                            <th class="bg-grey  text border-1">Organizer</th>
                            <th class="  text border-1 text-muted"> {{$event_details->client->property_name}}</th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">From</th>
                            <th class="  text border-1 text-muted"> {{$event_details['schedule_from']}}</th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">Event Name</th>
                            <th class="  text border-1 text-muted"> {{$event_details['event_name']}}</th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">Date of Event</th>
                            <th class="  text border-1 text-muted"> {{\Carbon\Carbon::parse($event_details['start_date'])->format('M d ,Y').' To '.\Carbon\Carbon::parse($event_details['end_date'])->format('M d ,Y')}}</th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">Timings</th>
                            <th class="  text border-1 text-muted">{{$event_details['start_time']}}  To   {{$event_details['end_time']}} </th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">Venue</th>
                            <th class="  text border-1 text-muted">{{$event_details['location']}} </th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">Security Required</th>
                            <th class="  text border-1 text-muted">{{$event_details['total_staff']}} </th>
                            </tr>
                            <tr>
                            <th class="bg-grey  text border-1">Contact Person</th>
                            <th class="  text border-1 text-muted">{{$event_details['contact_person']}} </th>
                            </tr>
                        </table>
                            <!---- /. Schedule Details Div close --->

                    </div>
            <!-- /.box-body -->
          </div>


          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Event Logs Details
              </h3>

              <a href="{{route('export_event_logs',$id)}}" id="export_event_logs_btn" type="button" class="btn btn-success pull-right" style="margin-right:1%" >
                Print Event Logs
              </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <input type="hidden" name="event_id" value="{{$id}}"/>
            <table id="event_logs_table" class="table table-bordered m-b-30">
              <thead>
                  <tr>
                    <th class="bg-grey border-1" width="10%">TIME.</th>
                    <th class="bg-grey border-1" width="10%">Sender</th>
                    <th class="bg-grey border-1" width="">To.</th>
                    <th class="bg-grey border-1" width="">Message</th>
                    <th class="bg-grey border-1" width="">Responded BY</th>
                    <th class="bg-grey border-1" width="">Action Taken</th>
                    <th class="bg-grey border-1" width="">Status</th>
                    <th class="bg-grey border-1" width="8%">Action</th>
                  </tr>
              </thead>
              <tbody>
                    @php
                     $last_index = sizeof($event_logs_list);
                    @endphp
                    <tr id="event_logs_tr-{{ $last_index }}" class="event_logs_tr"  data-iteration="{{ $last_index }}" >
                        <td class="bold" >
                            <span class="readonly time_span " ></span>
                            <input type="hidden"  data-name="time" class="readonly readonly_field form-control  border-none" resize="none" value="{{ Carbon\Carbon::now()->format('H:i:s a') }}" /></td>
                        <td class="bold" ><textarea  data-id="" data-name="name" class="form-control access_fields">  </textarea> </td>
                        <td class="bold" ><textarea  data-id="" data-name="to" class="form-control  access_fields"></textarea> </td>
                        <td class="bold" ><textarea  data-id="" data-name="action" class="form-control  access_fields"></textarea> </td>
                        <td class="bold" ><textarea  data-id="" data-name="responded_by_in" class="form-control access_fields"></textarea> </td>
                        <td class="bold" ><textarea  data-id="" data-name="action_taken" class="form-control access_fields"></textarea> </td>
                        <td class="bold status_column" >
                        <label class="control-label status_label block red_label">
                            <input type="radio"  data-id="red" data-name="status" data-color_code="e21313"  name="status"  value="red" class=" access_fields" />
                          </label>
                          <label class="control-label status_label block yellow_label">
                            <input type="radio" checked data-id="yellow" data-name="status" data-color_code="eae116"  name="status"   value="yellow" class=" access_fields" />
                          </label>
                          <label class="control-label status_label block green_label">
                            <input type="radio" data-id="green" data-name="status" data-color_code="40e298" name="status"  value="green"  class=" access_fields" />
                          </label>
                          <label class="control-label status_label block pink_label">
                            <input type="radio" data-id="pink" data-name="status" data-color_code="e46ac9" name="status"  value="pink"  class=" access_fields" />
                          </label>
                          <label class="control-label status_label block blue_label">
                            <input type="radio" data-id="blue" data-name="status" data-color_code="37b0e6" name="status"  value="blue"  class=" access_fields" />
                          </label>
                       </td>
                        <td class="bold" >
                            <button type="button" id="saveEventLogBtn-{{ $last_index }}" class="btn btn-success addMoreEventLogsBtn" data-iteration="{{ $last_index }}" style="width:100%" >Save</button>
                        </td>
                    </tr>
                @if(sizeof($event_logs_list) > 0)
                @foreach($event_logs_list as $index => $obj)
                        <tr id="event_logs_tr-{{ $index }}" class="event_logs_tr {{ $obj->status }}"  data-iteration="{{ $index }}" >
                            <td class="bold" ><span  id=""  class="time_span border-none"> {{ Carbon\Carbon::parse($obj->time)->format('H:i:s a') }}</span>

                            <input type="hidden"  data-name="" class="readonly readonly_field transparent    border-none" resize="none" value="{{ Carbon\Carbon::parse($obj->time)->format('H:i:s a') }}" /></td>                        </td>
                            <td class="bold" ><textarea readonly data-id="" data-name="name" class="form-control readonly_field transparent "> {{ $obj->name }} </textarea> </td>
                            <td class="bold" ><textarea  readonly data-id="" data-name="to" class="form-control readonly_field transparent">{{ $obj->to }} </textarea> </td>
                            <td class="bold" ><textarea  readonly data-id="" data-name="action" class="form-control readonly_field transparent">{{ $obj->action }} </textarea> </td>
                            <td class="bold" ><textarea readonly data-id="" data-name="responded_by_in" class="form-control readonly_field transparent">{{ $obj->responded_by_in }} </textarea> </td>
                            <td class="bold" ><textarea readonly data-id="" data-name="action_taken" class="form-control readonly_field transparent">{{ $obj->action_taken }} </textarea> </td>
                            <td class="bold status_column {{ $obj->status }}" >

                            </td>
                            <td class="bold" >
                            </td>
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

    $(document).delegate('.addMoreEventLogsBtn','click',function (){
        var iteration = $(this).attr('data-iteration');
        var btn_id  = $(this).attr('id');
        var serialize = "";
        $("tr#event_logs_tr-"+iteration+" td .access_fields").each(function(ev){
            if($(this).attr('data-name') != "")
            {
              var data_name =  $(this).attr("data-name");
              if(data_name ==  'status')
              {
                if($(this).prop('checked') == true)
                {
                  var data_val =  $(this).val();
                  var color_code =  $(this).attr('data-color_code');
                    serialize  += data_name + "=" + data_val+"&";
                    serialize += 'color_code='+color_code+"&";
                }
              }
              else
              {
                var data_val =  $(this).val();
                serialize  += data_name + "=" + data_val+"&";
              }
            }


        });

        serialize += "event_id="+$("input[name='event_id']").val();//;serialize.replace(/^&|&$/g,'');
        var myJsonData = {formData: serialize};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
                type:'POST',
                url:'/saveEventLogs',
                data:myJsonData,
                dataType: 'JSON',
                success:function(obj){
                    if(obj.response == 200)
                    {
                        iteration++;
                        var $tr    = $('#'+btn_id).closest('tr');
                        var $clone = $tr.clone();
                        $tr.find('.status_column').empty();
                        $tr.find('.status_column').addClass(obj.data.status);
                        $tr.addClass(obj.data.status);
                        $tr.find('textarea').prop('readonly',true);
                        $tr.find('textarea').addClass('transparent');
                        $tr.find('.form-control').addClass('readonly_field');
                        $tr.find('.time_span').text(obj.data.time);
                        $clone.prop('id','event_logs_tr-'+iteration);
                        $clone.find('input:text,select,textarea').val('');
                        $clone.find('textarea').prop('readonly',false);

                        $tr.before($clone); // set Clone Element before or After

                        $clone.find('.addMoreEventLogsBtn').prop('id','saveEventLogBtn-'+iteration);
                        $clone.find('.addMoreEventLogsBtn').removeAttr('data-iteration');
                        $clone.find('.addMoreEventLogsBtn').attr('data-iteration',iteration);
                        $("#"+btn_id).remove();
                        $clone.find('textarea[data-name="name"]').focus();
                    }
                    else
                    {
                        alert("Error!!");
                    }
                }
        }); // ejax ends

    });

    $('input.icheck').iCheck({
      checkboxClass: 'icheckbox_square-red',
      radioClass: 'iradio_square-red',
      increaseArea: '20%' /* optional */
    });


  });
</script>
@endsection