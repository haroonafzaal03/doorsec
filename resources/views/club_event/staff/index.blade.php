@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
        Staff
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Staff</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of All Staff</h3>
              @hasrole('add.new.staff')
              <a type="button" class="btn btn-info pull-right" href="{{route('staff_create')}}" >Add New Staff</a>
              @endhasrole
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="staffDataTable" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Contact Number</th>
                  <th>Nationality</th>
                  <th>Staff Type</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @if(isset($data) && $data)
                @foreach($data as $cl)
                <tr id="tr-{{$cl->id}}">
                  <td>{{($cl->staff_type_id) ? mb_substr($cl->stafftypes['type'],0,1).'-00'.$cl->id : '' }}</td>
                  <td>{!!staff_image($cl->id,$cl->name,$cl->picture)!!}</td>
                  <td>{{$cl->contact_number}}</td>
                  <td>{{$cl->nationality}}</td>
                  <td>{{$cl->stafftypes->type}}</td>
                  <td>
                      <label class="label {{    get_label_class_by_key($cl->status)}}">{{ $cl->status }}</label>
                      <br>
                      <small>{{ $cl->reason??'' }}</small>
                  </td>
                  <td>
                      <div class="btn-group">

                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu"  style="background-color:#fff"role="menu">
                        <li><a href="{{route('staff_show',$cl->id)}}">View Details</a></li>
                        @hasrole('edit.staff.profile')
                        <li><a href="{{route('staff_edit',$cl->id)}}">Edit</a></li>
                        @endhasrole
                        <li><a href="#" class="updateStatusAnchor" data-id =" {{ $cl->id }} "  data-target="#staff_status_popup" data-toggle="" data-status="{{ $cl->status }}" > Update Status</a></li>
                        @hasrole('block.staff')
                        <li><a href="#" class="blockStaffAnchor" data-id ="{{$cl->id}}"   data-target="#BlockStaffPopup" data-toggle="" data-val="{{$cl->block_for_clients}}">Block / Un Block Staff</a></li>
                        @endhasrole
                        @hasrole('delete.staff')
                        <li><a  class="removeDataAnchor" data-id ="{{$cl->id}}" data-target="#removeDataPopup"  href="#">Remove</a></li>@endhasrole
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


<!--- Block Staff with Password Popup ---->

<div id="BlockStaffPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
      <div class="modal-content">
         <form id="blockStaffForm"   autocomplete="off" action="#" method="POST" onsubmit="return false;">
            @csrf
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title module_title">Block / Un Block Staff </h4>
            </div>
            <div class="modal-body">
               <p class="bold action_message hide">Select Clients & Enter Password to Block Staff</p>
               <div class="row">
                  <div class="col-md-12">
                  <div class="form-group row">
                        <div class="col-md-2">
                           <label class="control-label dp-inline"> Clients </label>
                        </div>
                        <div class="col-md-8">
                        <select class="form-control select2" id="clients_list" name="clients_list[]" multiple="multiple" data-placeholder="Select Clients"  style="width: 100%;"  autocomplete="off">
                          <option value="-1" disabled="disabled"> Select</option>
                          @if($clients)
                          @foreach($clients as $obj)
                            <option value="{{$obj->id}}"> {{$obj->property_name}} </option>
                          @endforeach
                          @endif
                           </select>
                           <span class="response_message block bold " style="margin-top:10px"> &nbsp; </span>
                        </div>
                     </div>
                     <!--- /.form-group --->
                     <div class="form-group row hide ">
                        <div class="col-md-2">
                           <label class="control-label dp-inline"> Password </label>
                        </div>
                        <div class="col-md-6">
                           <input class="form-control" type="password" id="input_password" name="input_password" autocomplete = "off" />
                        </div>
                     </div>
                     <!--- /.form-group --->
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-warning blockBtnTrigger"  > Apply Changes </button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <input class="form-control" type="hidden" id="data_id" name="data_id"  readonly="readonly" />
            </div>
         </form>
      </div>
   </div>
</div>


<!--- /#BlockStaffPopup  ---->










@endsection
@section('content_js')
<script>

function BlockStaffByPassVerification()
{
  if($("#blockStaffForm #clients_list").val() )
  {

    var formData =  $("#blockStaffForm").serialize();
    var myJsonData = {formData: formData};

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

     $.post('/BlockStaff', myJsonData, function(response) {
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

    return false;
  }

  function updateStaffStatus()
{
    var formData =  $("#StaffStatusForm").serialize();
    var myJsonData = {formData: formData};

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

     $.post('/update_staff_status', myJsonData, function(response) {
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


  function RemoveStaff()
  {
    var formData =  $("#passDataForm").serialize();
    var myJsonData = {formData: formData};

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

     $.post('/RemoveStaffDetails', myJsonData, function(response) {
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




    // BLOCK Staff For Client  ( Single & Multiple )

    $(document).on('click',".blockStaffAnchor",function(){

      $("form#blockStaffForm")[0].reset();
      reInitializeSelect2('clients_list') // id

      $(".response_message").removeClass('text-red,text-green');
      $("#blockStaffForm #input_password").val('');

      var data_id =  $(this).attr('data-id');
      var target_modal = $(this).attr('data-target');
      var client_ids = $(this).attr('data-val');

      if(target_modal)
      {
        $("#blockStaffForm .response_message").text('');
        $("#blockStaffForm #data_id").val(data_id);

        if(client_ids)
        {
          $.each(client_ids.split(","), function(i,e){
          $("#clients_list.select2 option[value='" + e + "']").prop("selected", true);
        });
          $("#clients_list.select2").select2();
        }

        $(target_modal + " .blockBtnTrigger").attr('onClick','BlockStaffByPassVerification()');
        $(target_modal).modal('show');
      }

      return false;
    });

    // REMOVE DATA
    $(document).on('click',".removeDataAnchor",function(){

      $(".response_message").removeClass('text-red,text-green');
      $("#passDataForm #input_password").val('');

      var data_id =  $(this).attr('data-id');
      var target_modal = $(this).attr('data-target');
      var action_on = $(this).attr('data-action');

      if(target_modal)
      {
        $("#passDataForm .response_message").text('');
        $(target_modal + " .modal-title").text('Remove Staff');
        $(target_modal + " .action_message").text('If You Want to Remove Staff, Please Enter Your Password!');
        $("#passDataForm #delete_data_id").val(data_id);
        $("#passDataForm #action_on").val(action_on);
        $(target_modal + " .deleteBtnTrigger").attr('onClick','RemoveStaff()');
        $(target_modal).modal();
      }

      return false;
    });





    // Activate & De-Activate Staff

    $(document).on('click',".updateStatusAnchor",function(){

      $(".response_message").removeClass('text-red,text-green');
      $("#passDataForm #input_password").val('');

      var data_id =  $(this).attr('data-id');
      var data_status =  $(this).attr('data-status');
      var target_modal = $(this).attr('data-target');
      var action_on = $(this).attr('data-action');

      if(target_modal)
      {
        $(".response_message").text('');
        $(target_modal + " #staffid").val(data_id);
        $(target_modal + " #staff_status").val(data_status);
        $("#action_on").val(action_on);
        $(target_modal + " .TriggerActionBtn").attr('onClick','updateStaffStatus()');
        $(target_modal).modal('show');
      }

      return false;
});




    $('#staffDataTable').DataTable(
      {

                processing: true,
                serverSide: true,
                searching: true,
                ordering: true,
                info: false,
                autoWidth:false,
                bLengthChange: false,
                bSort: true,
                // order: [[ 8, "asc" ]],
                // columnDefs: [{
                // orderable: false,
                // className: 'select-checkbox',
                // targets: 0
                // }],

        ajax: {
            url:"{{ route('staff') }}",
            // data:{
            //     'brand_id':$('input[name=brand_id]').val(),
            //     'status':$('input[name=status]').val(),
            //     'tags':tags_checked,
            //     'locations':locations_checked,
            //     'couriers':couriers_checked,
            //     'order_date':$('input[name=order_date]').val(),
            //     'start_date':$('input[name=start_date]').val(),
            //     'end_date':$('input[name=end_date]').val(),
            // }
        },
        columns: [
            {data: 'staff_type_id', name: 'staff_type_id', orderable: true, searchable: true},
            {data: 'staff_image', name: 'staff_image', orderable: true, searchable: true},
            {data: 'contact_number', name: 'contact_number', orderable: true, searchable: true},
            {data: 'nationality', name: 'nationality', orderable: true, searchable: true},
            {data: 'staff_type', name: 'staff_type', orderable: true, searchable: true},
            {data: 'reason', name: 'reason', orderable: true, searchable: true},
            // {data: 'shipping_city', name: 'shipping_city', orderable: true, searchable: true},
            // {data: 'fulfillment_status', name: 'fulfillment_status', orderable: true, searchable: true},
            // //{data: 'tags', name: 'tags', orderable: true, searchable: true},
            // {data: 'created_at', name: 'created_at', orderable: true, searchable: true},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],

      }
      );


    $('.select2').select2()
    $('#staff_status').change(function(){
      var selected = $('#staff_status').val();
      if(selected == "deactivate"){
        $('.reason').removeClass('hide');
        $('.reason').show();
      }else{
        $('.reason').hide();
      }
    });

  }) // Jquery End

</script>
@endsection