@extends('layouts.master')

@section('content')
    <section class="content-header">
      <h1>
      Sschedule
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Schedule</a></li>
        <li>Add Schedule</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add New Schedule</h3>
              <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <form role="form" id="add_schedule_form" action="{{ route('schedule_store')}}" method="POST" enctype="multipart/form-data">
                @csrf
           <!--  BEGIN Form-->
            <div class="row">
            <div class="col-md-12">
            <div class="col-md-6">

                <div class="form-group">
                  <label for="Company">To </label>
                  <input type="text"  class="form-control" name="schedule_to" value="SIRA" readonly/>
                </div>
              </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label for="Company">From </label>
                  <input type="text"  class="form-control" name="schedule_from" value="DOORSEC SECURITY SERVICES LLC" readonly/>
                </div>
            </div>
            <div class="col-md-6">
                <div clas="row">
                    <div class="col-md-4 p-l-0">

                      <div class="form-group">
                        <label for="Company">Client</label>
                        <select type="number" class="form-control" id="client_id" name="client_id" onChange="getClientData(this.value)" >
                          <option value="">Select</option>
                          @if($client)
                          @foreach($client as $obj)
                          <option value="{{ $obj->id }}">{{ $obj->property_name }}</option>
                          @endforeach
                          @endif
                        </select>
                       </div>

                    </div>

                    <div class="col-md-4 p-r-0">

                      <div class="form-group">
                        <label for="Company">Contact Person</label>
                        <input type="text" class="form-control alphabets_only" id="client_contact_person" name="contact_person"  />
                      </div>
                    </div>

                    <div class="col-md-4 p-r-0">

                      <div class="form-group">
                        <label for="Company">Contact No.</label>
                        <input type="text" class="form-control number_only" id="client_contact_no" name="contact_no"  />
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div clas="row">
                    <div class="col-md-6 p-l-0">

                      <div class="form-group">
                        <label for="Company">Event Name</label>
                        <input type="text" class="form-control" id="event_name" name="event_name" >
                       </div>

                    </div>

                    <div class="col-md-6 p-r-0">

                      <div class="form-group">
                         <label for="Company">Event type</label>
                         <input type="text" class="form-control" id="event_type" name="event_type" >
                       </div>
                    </div>
                </div>
            </div>

                  <!-- Date picker -->
            <div class="col-md-6" >
                <div class="bootstrap-timepicker row">
                    <label class="col-md-12 d-block">Event Dates </label>
                    <div class="form-group col-md-6">
                     <small><strong>Start : </strong></small>
                        <div class="input-group">
                            <input type="text" readonly name="start_date" id="start_date" class="form-control datepicker" value="{{ date('m/d/Y') }}">
                            <div class="input-group-addon">
                            <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
					<div class="form-group col-md-6">
					    <small><strong>End : </strong></small>
                        <div class="input-group">
                                <input type="text" readonly name="end_date" id="end_date" class="form-control datepicker"value="{{ date('m/d/Y') }}">
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
					<label class="col-md-12 d-block">Event Timings </label>
					<div class="form-group col-md-6">
					  <small><strong>From : </strong></small>
					    <div class="input-group">
                            <input type="text" readonly name="start_time" class="form-control timepicker">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
					    </div>
					</div>
					<div class="form-group col-md-6">
					    <small><strong>To : </strong></small>
                        <div class="input-group">
                            <input type="text" readonly name="end_time" class="form-control timepicker">
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
                    <label for="contact_person">Security Required </label>
                    <input type="" class="form-control" name="total_staff" value=""/>
                    </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <label for="location">Location</label>
                  <input type="text"  class="form-control" name="location" value=""/>
                </div>
            </div>


                    <div class="col-md-12 ">
                        <div class="pull-right">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('staff_schedule',1) }}" class="btn btn-warning hide">Save & Continue</a>
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
      <!-- /.row -->
    </section>
    <!-- /.content -->


@endsection
@section('content_js')
<!-- TimePicket App -->
<script>

// GET DATA OF CLIENT BY ID //
function getClientData(id)
{
  //var myJsonData = {formData: formData};

  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  if(id)
  {
  $.get('/get_client_data_json/'+id, function(response) {
       var obj = JSON.parse(response);
       if(obj != null || obj != "")
       {
        $("#client_contact_person,#client_contact_no").removeAttr('value');

        $("#client_contact_person").val(obj.venue_manager_name);
        // $("#client_contact_person").parent('.form-group').removeClass('has-error');
        // $("#client_contact_person").parent('.form-group').addClass('has-success');
        // $("#client_contact_person").next('.help-block[data-bv-validator="notEmpty"]').attr('data-bv-result','VALID');
        // $("#client_contact_person").next('.help-block[data-bv-validator="notEmpty"]').css('display','none');

        $("#client_contact_no").val(obj.venue_manager_number);
      //   $("#client_contact_no").parent('.form-group').removeClass('has-error');
      //   $("#client_contact_no").parent('.form-group').addClass('has-success');
      //   $("#client_contact_no").next('.help-block[data-bv-validator="notEmpty"]').attr('data-bv-result','VALID');
      //   $("#client_contact_no").next('.help-block[data-bv-validator="notEmpty"]').css('display','none');

      //  $("form#add_schedule_form button[type='submit']").removeAttr('disabled');
       }
       else
       {
        $("#client_contact_person").val('');
        $("#client_contact_no").val('');
       }

     });

    }
    else{
      $("#client_contact_person").val('');
      $("#client_contact_no").val('');
    }


}


  $(function() {
    $('#add_schedule_form').bootstrapValidator({

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


  $("#start_date").datepicker({
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });

    $("#end_date").datepicker()
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#start_date').datepicker('setEndDate', maxDate);
    });

      //Timepicker
      loadTimePicker();


  })
</script>
@endsection
