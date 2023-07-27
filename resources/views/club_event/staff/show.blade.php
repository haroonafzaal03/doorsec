@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1>
            Staff Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('staff') }}">Staff</a></li>
            <li class="active">Staff Profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <a href="#" class="block img_lightbox ">
                            <img class="profile-user-img img-responsive img-circle" src="{{ img($staff['picture']) }}"
                                alt="Staff profile picture">
                        </a>
                        <h3 class="profile-username text-center active_font bold"> {{ $staff['name'] }}</h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item  {{ empty($staff->basic_salary) ? 'hide' : '' }}  ">
                                <b>Salary</b> <a class="pull-right"> {{ $staff->basic_salary ? $staff->basic_salary : '' }}
                                </a>
                            </li>
                            <li class="list-group-item  hide ">
                                <b>Total Balance</b> <a class="pull-right"> </a>
                            </li>
                            <li class="list-group-item hide">
                                <b>Arrears</b> <a class="pull-right">0</a>
                            </li>
                        </ul>

                        <a href="#" class="btn btn-primary btn-block hide"><b>Message</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->


                <!-- Profile Image -->
                @if (sizeof($BlockClientsList) > 0)
                    <div class="box box-danger">
                        <div class="box-body box-profile">
                            <p class=" text-left text-red bold p-t-0 m-t-0" style="font-size:18px;"> Blocked For Clients</p>
                            <ul class="list-group list-group-unbordered blocked_clients_list">

                                @foreach ($BlockClientsList as $obj)
                                    <li class="list-group-item">
                                        <b>{{ $obj['property_name'] }}</b>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                @endif

                <!-- StaffDisciplinary -->

                <div class="box box-warning">
                    <div class="box-body box-profile">
                        <p class=" text-left text-orange bold p-t-0 m-t-0" style="font-size:18px;"> Disciplinary <a
                                class="btn btn-sm btn-primary pull-right" id="add_disciplinary"><i
                                    class="fa fa-plus-circle"></i>Add </a></p>
                        <div class="col-md-12" style="height:350px;overflow:scroll;">
                            <ul class="list-group list-group-unbordered blocked_clients_list">
                                @if (count($staff->disciplinary) > 0)
                                    @foreach ($staff->disciplinary as $stdis)
                                        <li class="list-group-item">
                                            <input type="hidden" id="disciplinary_data_{{$stdis->id}}" value="{{json_encode($stdis)}}">
                                            <a class=" pull-right btn-sm cursor-pointer removeDataAnchor"
                                            data-id="{{$stdis->id}}"
                                            {{-- data-href="{{ route('disciplinaries_delete', $stdis->id) }}" --}}
                                                href="#" data-target="#removeDataPopup"><i
                                                    class="fa fa-trash "></i></a>
                                                    <a class=" pull-right btn-sm cursor-pointer editDataAnchor padding-right-0"
                                            data-id="{{$stdis->id}}" data-action="{{route('disciplinaries_update',$stdis->id)}}"
                                            {{-- data-href="{{ route('disciplinaries_delete', $stdis->id) }}" --}}
                                                href="#" data-target="#modal_staff_disciplinaries_update"><i
                                                    class="fa fa-edit "></i></a>
                                            <small
                                                class="label label-warning pull-right">{{ \carbon\carbon::createFromTimeStamp(strtotime($stdis->created_at))->diffForHumans() }}</small>
                                            @if ($stdis->letter_type)
                                                <p class="text-muted bold">{{ $stdis->letter_type }}</p>
                                            @endif
                                            @if ($stdis->document_path)

                                                <p class="text-muted"><img
                                                        src="{{ !empty($stdis->document_path) ? img($stdis->document_path) : asset('img/document.png') }}"
                                                        style="width:35%;height:10%" /></p>

                                            @endif
                                            @if ($stdis->admin_notes)

                                                <span class="bold">Admin notes</span>
                                                <p class="text-muted">{{ $stdis->admin_notes }}</p>

                                            @endif

                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->




            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="bold active_font inline"><i class="fa fa-user margin-r-5 m-b-10"></i><span> About
                                {{ $staff['name'] }} </span>
                            <label
                                class="label m-l-10 f-sm {{ get_label_class_by_key($staff['status']) }}">{{ $staff['status'] }}</label>
                        </h3>
                        <a class="pull-right btn btn-sm btn-info" href="{{ route('staff_edit', $id) }}">Edit</a>
                        <br>
                        <label>Reason: </label>
                        <small>{{ $staff['reason'] ?? '' }}</small>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table">
                            <tr>
                                <th>Staff Contact No. </th>
                                <td>
                                    <p class="text-muted">{{ $staff['contact_number'] }}</p>
                                </td>
                            </tr>

                            <tr>
                                <th>Staff Category </th>
                                <td>
                                    <p class="text-muted text-green bold">{{ $staff->stafftypes['type'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Staff SIRA ID No. </th>
                                <td>
                                    <p class="text-muted">{{ $staff['sira_id_number'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Nationality</th>
                                <td>
                                    <p class="text-muted"> {{ $staff['nationality'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td>
                                    <p class="text-muted"> {{ $staff['height'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td>
                                    <p class="text-muted"> {{ $staff['weight'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Staff UID No.</th>
                                <td>
                                    <p class="text-muted">{{ $staff['uid_number'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Staff Emirates ID No.</th>
                                <td>
                                    <p class="text-muted">{{ phone_number_format($staff['emitrates_id']) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Passport No.</th>
                                <td>
                                    <p class="text-muted"> {{ $staff['passport_number'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Passport Expiry date</th>
                                <td>
                                    <p class="text-muted">
                                        {{ \Carbon\Carbon::parse($staff['passport_expiry'])->format('d M, Y') }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>Visa Expiry Date</th>
                                <td>
                                    <p class="text-muted">
                                        {{ \Carbon\Carbon::parse($staff['visa_expiry'])->format('d M, Y') }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>Staff Contact No. ( Home Country ) </th>
                                <td>
                                    <p class="text-muted">{{ $staff['contact_number_home'] }}</p>
                                </td>
                            </tr>

                            <tr>
                                <th>Other Contact No. </th>
                                <td>
                                    <p class="text-muted">{{ $staff['other_contact_number'] }}</p>
                                </td>
                            </tr>
                            <tr>
                                <th>General Note. </th>
                                <td>
                                    <p class="text-muted">{{ $staff['general_note'] }}</p>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-3">
                <!-- Document Section -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Certificate Details</h3>
                        <a class="btn btn-primary pull-right" id="add_certificate">Add<i class="fa fa-plus"></i></a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Docment Name</th>
                                <th>download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($staff->staff_certificate)
                                @foreach ( $staff->staff_certificate as  $sc)
                                    <tr>
                                        <td>{{$sc->document_name}}</td>
                                        <td><a href="{{ !empty($sc->document_type) ? img_click($sc->document_type) : '#' }}"target="_blank">
                                                <i class="fa fa-download"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-3">
                <!-- Document Section -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Documents Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-book margin-r-5"></i> Passport Copy</strong>

                        <p class="text-muted">
                            <a href="{{ !empty($staff['passport_attach']) ? img_click($staff['passport_attach']) : '#' }}"
                                target="_blank">
                                <img src="{{ !empty($staff['passport_attach']) ? img($staff['passport_attach']) : asset('img/document.png') }}"
                                    style="width:35%;height:10%" />
                            </a>
                        </p>
                        <p class="text-muted"> Passport No : {{ $staff['passport_number'] }}</p>
                        <p class="text-muted text-red bold"> Passport Expiry Date :
                            {{ \Carbon\Carbon::parse($staff['passport_expiry'])->format('d M, Y') }} </p>
                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Staff SIRA Details </strong>

                        <p class="text-muted">
                            <a href="{{ !empty($staff['sira_id_attach']) ? img_click($staff['sira_id_attach']) : '#' }}"
                                target="_blank">
                                <img src="{{ !empty($staff['sira_id_attach']) ? img($staff['sira_id_attach']) : asset('img/document.png') }}"
                                    style="width:35%;height:10%" />
                            </a>
                        </p>
                        <p class="text-muted text-blue bold"> SIRA ID No. : {{ $staff['sira_id_number'] }}</p>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Visa Details </strong>

                        <p class="text-muted">
                            <a href="{{ !empty($staff['visa_attach']) ? img_click($staff['visa_attach']) : '#' }}"
                                target="_blank">
                                <img src="{{ !empty($staff['visa_attach']) ? img($staff['visa_attach']) : asset('img/document.png') }}"
                                    style="width:35%;height:10%" />
                            </a>
                        </p>
                        <p class="text-muted text-red bold"> Visa Expiry Date :
                            {{ \Carbon\Carbon::parse($staff['visa_expiry'])->format('d M, Y') }} </p>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Staff Emirates Detils </strong>

                        <p class="text-muted">
                            <a href="{{ !empty($staff['emirated_id_attach']) ? img_click($staff['emirated_id_attach']) : '#' }}"
                                target="_blank">
                                <img src="{{ !empty($staff['emirated_id_attach']) ? img($staff['emirated_id_attach']) : asset('img/document.png') }}"
                                    style="width:35%;height:10%" />
                            </a>
                        </p>
                        <p class="text-muted text-blue bold"> Emirates Id :
                            {{ !empty($staff['emitrates_id']) ? phone_number_format($staff['emitrates_id']) : '----' }} </p>
                        <p class="text-muted text-red bold"> Visa Expiry Date :
                            {{ \Carbon\Carbon::parse($staff['emirates_expiry'])->format('d M, Y') }} </p>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- document section end -->
            </div>
        </div>
        <!-- /.row -->


    </section>


<div id="sira-expiry-alert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-danger">
      <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
      <div class="modal-content">
            <div class="modal-body p-5">
              <h3 class="modal-title module_title text-center my-5">Sira will Expire within {{$alert}} days</h3>
            </div>
            {{-- <div class="modal-footer text-center">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> --}}

      </div>
   </div>
</div>
    <!-- /.content -->
@endsection
@section('content_js')
    <script>
        $(function() {

            $('#example1').DataTable({});

            $('.datepicker').datepicker({});

        })

        $('#add_disciplinary').click(function() {
            $('#modal_staff_disciplinaries').modal('show');
        });

         $('#edit_disciplinary').click(function() {
            $('#modal_staff_disciplinaries_update').modal('show');

        });
         $('#add_certificate').click(function() {
           $('#modal_staff_certificate').modal('show');
        });

        $(document).ready(function(){

            $('.editDataAnchor').click(function(){
                var data_id =  $(this).attr('data-id');
                var target_modal = $(this).attr('data-target');
                var action_on = $(this).attr('data-action');

                if(target_modal)
                {
                    var disciplinary=$('#disciplinary_data_'+data_id).val();
                    disciplinary=JSON.parse(disciplinary);


                    //for LetterType
                    $('select[name=letter_type] > option').each(function(index,option){
                        if($(option).val()==disciplinary.letter_type)
                        {
                            $(option).prop('selected',true);
                        }
                    });

                    $('#download_attachment').attr('href',window.location.origin+'/storage/'+disciplinary.document_path);
                    $('#download_attachment').html(disciplinary.document_name);


                    $('#admin_notes').val(disciplinary.admin_notes);
                    // console.log(disciplinary);
                    // $("#passDataForm .response_message").text('');
                    // $(target_modal + " .modal-title").text('Remove Staff');
                    // $(target_modal + " .action_message").text('If You Want to Remove Disciplinary, Please Enter Your Password!');
                    // $("#passDataForm #delete_data_id").val(data_id);
                    // $("#passDataForm #action_on").val(action_on);
                    $(target_modal + " .submit_staff_disciplinaries").attr('onClick','UpdateDisciplinary('+data_id+')');
                    $(target_modal).modal();
                }
            });

            $(".removeDataAnchor").click(function(){

      $(".response_message").removeClass('text-red,text-green');
      $("#passDataForm #input_password").val('');

      var data_id =  $(this).attr('data-id');
      var target_modal = $(this).attr('data-target');
      var action_on = $(this).attr('data-action');

      if(target_modal)
      {
        $("#passDataForm .response_message").text('');
        $(target_modal + " .modal-title").text('Remove Staff');
        $(target_modal + " .action_message").text('If You Want to Remove Disciplinary, Please Enter Your Password!');
        $("#passDataForm #delete_data_id").val(data_id);
        $("#passDataForm #action_on").val(action_on);
        $(target_modal + " .deleteBtnTrigger").attr('onClick','RemoveDisciplinary('+data_id+')');
        $(target_modal).modal();
      }

      return false;
    });

    @if ($alert> 0 && $alert<=30)
    $('#sira-expiry-alert').modal('show');
    @endif
  })


//   function


function UpdateDisciplinary(discipline_id)
{
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var form=document.getElementById('form_staff_disciplinaries_update');

    $.ajax({
        type:"POST",
        url:'/disciplinaries/'+discipline_id+'/update',
        data:new FormData(form),
        processData: false,
        contentType: false,
        success:function(response)
        {
        $(".response_message").text(response.message);

       if(response.status)
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
        },
        error:function(error)
        {

        }
    });


}

  function RemoveDisciplinary(discipline_id)
  {
    var formData =  $("#passDataForm").serialize();
    var myJsonData = {formData: formData};

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

     $.post('/disciplinaries/'+discipline_id+'/delete', myJsonData, function(response) {
    //    var obj = JSON.parse(response);
      $(".response_message").text(response.message);

       if(response.status)
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

    </script>
@endsection
