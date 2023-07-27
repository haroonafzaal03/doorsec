@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
        Clients
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Clients</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of All Clients</h3>
              @hasrole('add.new.client')
              <a type="button" class="btn btn-info pull-right" href="{{route('client_create')}}" >Add New Client</a>
              @endhasrole
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Client Type</th>
                  <th>Property Name</th>
                  <th>Venue Manager Name</th>
                  <th>Venue Manager Contact No.</th>
                  <th>Accounts Manager's Name</th>
                  <th>Accounts Manager's No.</th>
                  <th>Contract Start</th>
                  <th>Contract End</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if($client)
                @foreach($client as $cl)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$cl->client_type['type']}}</td>
                  <td><a href="{{url('/client/'.$cl->id)}}">{{$cl->property_name}}</a></td>
                  <td>{{$cl->venue_manager_name}}</td>
                  <td>{{$cl->venue_manager_number}}</td>
                  <td>{{$cl->account_manager_name}}</td>
                  <td>{{$cl->account_manager_num}}</td>
                  <td>{{$cl->property_contract_start}}</td>
                  <td>{{$cl->property_contract_end}}</td>
                  <td>
                      <div class="btn-group">

                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu"   role="menu">
                        <li><a href="{{url('/client/'.$cl->id)}}">View</a></li>
                        @hasrole('edit.client.profile')
                        <li><a href="{{url('/client/edit/'.$cl->id)}}">Edit</a></li>
                        @endhasrole
                        <!-- <li><a href="{{route('client_remove',$cl->id)}}">Remove</a></li> -->
                        @hasrole('delete.client')
                        <li><a  class="removeDataAnchor" data-id ="{{$cl->id}}" data-target="#removeDataPopup"  href="#">Remove</a></li>
                        @endhasrole                        </ul>
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

function RemoveClient()
  {
    var formData =  $("#passDataForm").serialize();
    var myJsonData = {formData: formData};

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

     $.post('/RemoveClient', myJsonData, function(response) {
       var obj = JSON.parse(response);
      $(".response_message").text(obj.message);

       if(obj.status)
       {

        setTimeout(function(){
          location.reload(true);
        },1000);

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

  $(function () {

    $('#example1').DataTable({
    });

    // REMOVE DATA
    $(".removeDataAnchor").click(function(){

    $(".response_message").removeClass('text-red,text-green');
    $("#passDataForm #input_password").val('');

    var data_id =  $(this).attr('data-id');
    var target_modal = $(this).attr('data-target');
    var action_on = $(this).attr('data-action');

    if(target_modal)
    {
      $("#passDataForm .response_message").text('');
      $(target_modal + " .modal-title").text('Remove Client');
      $(target_modal + " .action_message").text('If You Want to Remove Client, Please Enter Your Password!');
      $("#passDataForm #delete_data_id").val(data_id);
      $("#passDataForm #action_on").val(action_on);
      $(target_modal + " .deleteBtnTrigger").attr('onClick','RemoveClient()');
      $(target_modal).modal();
    }

    return false;
    });

  })
</script>
@endsection