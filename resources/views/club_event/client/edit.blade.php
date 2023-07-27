@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
        Clients
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('client')}}">Clients</a></li>
        <li>Edit Client</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Edit Client</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <form role="form" id="edit_client_form" action="{{ route('client_update',$client['id'] )}}" method="POST" enctype="multipart/form-data">
                @csrf
            <!-- BEGIN Form -->
            <div class="row">
              <div class="col-md-6">

                  <div class="row">
                      <div class="form-group  col-md-6 mb-2">

                      <label for=""  class="">Select Client Type</label>
                        <select type="text" class="form-control" id="client_type_id" name="client_type_id" placeholder="">
                        <option value=""> Select</option>
                          @if($client_type)
                          @foreach($client_type as $obj)
                            <option value="{{$obj->id}}" {{ ($obj->id == $client['client_type_id']) ? "selected"  : "" }} >{{$obj->type}} </option>
                          @endforeach
                          @endif
                        </select>

                        </div>

                        <div class="form-group  col-md-6 mb-2">
                            <label for="property_name"  class="">Property Name</label>
                            <input type="text" class="form-control" id="property_name"  name="property_name" placeholder="Property Name" autocomplete="off"  value= "{{$client['property_name']}}" />
                        </div>
                    </div>

                  <div class="form-group">
                    <label for="client_logo">Company Logo</label>
                    <input type="file" class=" form-control upload" id="client_logo" name="client_logo"/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1"> Name on Trade License</label>
                    <input type="text" class="form-control" id="property_lice_name" value= "{{$client['property_lice_name']}}" name="property_lice_name" placeholder="" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="tarde_lice">Trade License Attachment</label>
                    <input type="file" class=" form-control upload" id="tarde_lice" value= "{{$client['tarde_lice']}}" name="tarde_lice"/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Trade License Expiry Date</label>
                    <input type="text" readonly class="form-control datepicker" id="property_lice_expiry_date" value= " {{  ($client['property_lice_expiry_date']) ?
                    \Carbon\Carbon::parse($client['property_lice_expiry_date'])->format('m/d/Y') : '' }} " name="property_lice_expiry_date" placeholder="" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Trade License Number</label>
                    <input type="number" class="form-control" id="property_lice_number" value= "{{$client['property_lice_number']}}" name="property_lice_number" placeholder="" autocomplete="off" />
                  </div>
                  <div class="form-group">
                    <label for="property_tax_regis_num">Tax Registration Number</label>
                    <input type="number" class="form-control" id="property_tax_regis_num" value= "{{$client['property_tax_regis_num']}}" name="property_tax_regis_num" placeholder="" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="venue_manager_name">Venue Manager Name</label>
                    <input type="text" class="form-control" value= "{{$client['venue_manager_name']}}" name="venue_manager_name" id="venue_manager_name" placeholder="" autocomplete="off">
                  </div>

              </div>
               <div class="col-md-6">
                 <div class="form-group">
                    <label for="venue_manager_number"> Venue Manager Contact Number</label>
                    <input type="" class="form-control" id="venue_manager_number" value= "{{$client['venue_manager_number']}}" name="venue_manager_number" placeholder="" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="venue_manager_email">Venue manager's Email ID</label>
                    <input type="email" id="venue_manager_email" value= "{{$client['venue_manager_email']}}" name="venue_manager_email" class="form-control"  placeholder="" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="property_signatory_id">Signatory's ID Attachment</label>
                    <input type="file" class="upload form-control" value= "{{$client['property_signatory_id']}}" name="property_signatory_id" id="property_signatory_id"/>
                  </div>
                  <div class="form-group row">
                    <label for="property_contract_start" class="col-md-6">Contract Start</label>
                    <label for="property_contract_start " class="col-md-6">Contract End</label>
                    <div class="col-md-6">
                      <input type="text" readonly value= "{{ ($client['property_contract_start']) ? \Carbon\Carbon::parse($client['property_contract_start'])->format('m/d/Y') : '' }} " name="property_contract_start" id="property_contract_start" class="form-control datepicker"  placeholder="" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                      <input type="text" readonly id="property_contract_end" value= "{{ ($client['property_contract_end']) ?
                    \Carbon\Carbon::parse($client['property_contract_end'])->format('m/d/Y') : '' }} " name="property_contract_end" class="form-control datepicker"  placeholder="" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="account_manager_name">Accounts Manager's Name</label>
                    <input type="text" id="account_manager_name" value= "{{$client['account_manager_name']}}" name="account_manager_name" class="form-control"  placeholder="" autocomplete="off">
                  </div>
                  <div class="form-group">
                    <label for="account_manager_email">Accounts manager's Email ID</label>
                    <input type="email" id="account_manager_email" value= "{{$client['account_manager_email']}}" name="account_manager_email" class="form-control"  placeholder="" autocomplete="off">
                  </div>

                  <div class="form-group">
                    <label for="account_manager_num">Accounts manager's Number</label>
                    <input type="" id="account_manager_num" value= "{{$client['account_manager_num']}}" name="account_manager_num" class="form-control"  placeholder="">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1 ">Client Address</label>

                    <div class="input-group" style="width: 100%;">
                      <input class="form-control" name="client_address" id="keyword" data-type="default" data-element="text" placeholder="Enter Venue Location" type="text" autocomplete="off" value="{{$client['client_address']}}" />
                    </div>
                    <div class="input-group typeahead-dropdown" id="keyword_result_dropdown" style="margin-bottom: 0px;display:none;">
                      <ul class="typeahead-dropdown-menu" style="max-height: 270px !important;overflow-y: scroll;">

                      </ul>
                    </div>
                  </div>


              </div>
            </div>
            <!-- END Form -->
				<div class="pull-right ">
					<button type="submit" class="btn btn-success">Update</button>
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
<script>
  $(function () {


    $('#edit_client_form').bootstrapValidator({
      message: 'This value is not valid',
        feedbackIcons: {
            valid: '',
            invalid: '',
            validating: 'glyphicon glyphicon-refresh222'
        },
        fields: {
          client_type_id: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Client Type'
                    }
                }
            },
          property_name: {
                validators: {
                    notEmpty: {
                        message: 'Propety name is required and cannot be empty'
                    }
                }
            },
            venue_manager_name: {
                validators: {
                    notEmpty: {
                        message: 'The Venue Manager Name is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z ]+$/,
                        message: 'The Venue Manager Name can only consist of alphabets'
                    }
                }
            },
            venue_manager_number: {
                validators: {
                    notEmpty: {
                        message: 'The Venue Manager No. is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: ' Venue Manager No. can only consist of numbers'
                    }

                }
            },
            property_lice_number: {
                validators: {
                  regexp: {
                        regexp: /^[0-9]+$/,
                        message: ' Trade License No. can only consist of numbers'
                    }
                }
            },
            property_tax_regis_num: {
                validators: {
                  regexp: {
                        regexp: /^[0-9]+$/,
                        message: ' Tax Registration No.can only consist of numbers'
                    }
                }
            },
            account_manager_num: {
                validators: {
                  regexp: {
                        regexp: /^[0-9]+$/,
                        message: ' Account Manager No. can only consist of numbers'
                    }
                }
            },
            venue_manager_email: {
                validators: {
                    emailAddress: {
                        message: 'The is not a valid email address'
                    }
                }
            }

        }
     });

    $("#property_contract_start").datepicker({
        todayBtn:  1,
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#property_contract_end').datepicker('setStartDate', minDate);
    });

    $("#property_contract_end").datepicker()
        .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#property_contract_start').datepicker('setEndDate', maxDate);
    });




//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#keyword');

//on keyup, start the countdown
$input.on('keyup focus', function (ev) {
  console.log(ev.event)
clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$input.on('keydown', function () {
clearTimeout(typingTimer);
});

  function doneTyping ()
  {

      if($('#keyword').val() != '')
      {
        var icon;
        $('#keyword_result_dropdown').css('display','none');
        $('#keyword_result_dropdown ul').html('');


          $.ajaxSetup({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });


          var myJsonData = {keyword : $('#keyword').val()};


          $.post('/getLocations', myJsonData, function(response) {
              var obj = JSON.parse(response);

              if(obj.length != 0)
                  {
                      $('#keyword_result_dropdown').css('display','block');
                      $('#keyword_result_dropdown ul').html('');

                      $.each(obj,function(i,v){
                        icon = 'fa-map-marker';
                          $('#keyword_result_dropdown ul').append('<li  class="" onclick="selectOption(\''+v.location_name+'\');"><span class="fa '+ icon +'"></span><div  class="typeahead-value ">'+ v.location_name +'</div></li>');
                      });
            }


          });

      } // END IF


  } // fUNCTION CLOSED



  }); // jQuery End


  function selectOption(option_value){
    $('#keyword').val(option_value);
    $('#keyword_result_dropdown').css('display','none');
  }


</script>
@endsection