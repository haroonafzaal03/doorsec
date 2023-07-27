@extends('layouts.master')

@section('content')

<section class="content-header">
  <h1>
    Staff
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{route('staff')}}">Staffs</a></li>
    <li>Edit Staff</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Edit Staff</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

          <form role="form" id="edit_staff_form" action="{{ route('staff_update',$staff->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputFile">Staff Category</label>
                  <select type="text" class="form-control" id="staff_type_id" name="staff_type_id" placeholder="" onChange="EnableDisableBasicSalary(this.value)">
                    <option value=""> Select</option>
                    @if($staff_type)
                    @foreach($staff_type as $obj)
                    <option value="{{$obj->id}}" {{ ($staff->staff_type_id == $obj->id) ? 'Selected':'' }}>{{$obj->type}} </option>
                    @endforeach
                    @endif
                  </select>
                </div>

                <div class="form-group">
                  <label for="exampleInputPassword1">Staff Name</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{$staff->name}}" />
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Staff Contact No.</label>
                  <input type="text" class="form-control number_only" id="contact_number" name="contact_number" placeholder="" value="{{$staff->contact_number}}" />
                </div>
                <div class="form-group">
                  <label class="d-block" for="exampleInputPassword1">Staff Picture</label>
                  <div class="row">
                    <div class="col-md-12">
                    <input type="file" class="upload form inline" name="picture" />
                    @if($staff->picture)
                      <a href="{{asset('storage/'.$staff->picture)}}" download="" class="float-right  fs-20 inline " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                    @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleInputPassword1">Staff Contact No. ( Home Country )</label>
                      <input type="text" class="form-control number_only" id="contact_number_home" name="contact_number_home" placeholder="" value="{{$staff->contact_number_home}}" />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">

                      <label for="weight">Other Contact Number</label>
                      <input type="text" class="form-control number_only" id="other_contact_number" name="other_contact_number" placeholder="" value="{{$staff->other_contact_number}}" />

                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">

                      <label for="weight">Email Addres</label>
                      <input type="text" class="form-control email" id="email" name="email" placeholder="" value="{{$staff->email}}">

                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">

                      <label for="weight">Secondary Email Address</label>
                      <input type="text" class="form-control secondary_email" id="secondary_email" value="{{$staff->secondary_email}}" name="secondary_email" placeholder="">

                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-7 p-r-0">
                    <div class="form-group">
                      <label for=" ">Nationality</label>
                      <input type="text" class="form-control" id="nationality" name="nationality" placeholder="" value="{{$staff->nationality}}" />
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="form-group">
                      <label for=" ">Gender </label>
                      <select type="text" class="form-control" id="gender" value="" name="gender">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                      </select>
                    </div>
                  </div>
                </div>


                <div class="form-group">
                </div>

                <div class="form-group">
                </div>

              </div>
              <div class="col-md-6">


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sira_id_number"> Staff SIRA ID No.</label>
                      <input type="text" class="form-control" id="sira_id_number" name="sira_id_number" placeholder="" value="{{$staff->sira_id_number}}" />
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="siraType">SIRA Type </label>
                      <select type="text" class="form-control" id="sira_type_id" name="sira_type_id">
                        <option value="">Select</option>
                        @if(isset($siraTypes))
                        @foreach($siraTypes as $obj)
                        <option value="{{$obj->id}}" @if ($obj->id == $staff->sira_type_id) selected @endif >{{$obj->type}} </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Staff SIRA ID Attachment </label>

                      <div class="row">
                        <div class="col-md-12">
                          <input type="file" class="upload form inline" id="sira_id_attach" name="sira_id_attach" style="    width: 86%;" />
                          @if($staff->sira_id_attach)
                          <a href="{{ asset('storage/'.$staff->sira_id_attach)}}" download class="float-right  fs-20 {{ (empty($staff->sira_id_attach)) ? 'hide' : 'inline' }} " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                          @endif
                        </div>

                      </div>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Sira Expiry Date</label>
                      <input type="text" readonly class="form-control datepicker" id="sira_expiry" name="sira_expiry" placeholder="" value="{{\Carbon\Carbon::parse($staff->sira_expiry)->format('m/d/Y')}}" >
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="uid_number">Staff UID No.</label>
                      <input type="text" class="form-control" id="uid_number" name="uid_number" placeholder="" value="{{$staff->uid_number}}" />
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="taxregistrationnumber">Staff Emirates ID No.</label>
                      <input type="text" class="form-control"  id="emitrates_id" name="emitrates_id" placeholder="123-1234-1234567-1" pattern="[0-9]{3}-[0-9]{4}-[0-9]{7}-[0-9]{1}" value="{{$staff->emitrates_id}}" />
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Emirates Expiry Date</label>
                      <input type="text" readonly class="form-control datepicker" id="emirates_expiry" name="emirates_expiry" value="{{\Carbon\Carbon::parse($staff->emirates_expiry)->format('m/d/Y')}}" />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="eid_attach">EID Attachment </label>
                      <div class="row">
                        <div class="col-md-12">
                          <input type="file" class="form inline" id="emirated_id_attach" name="emirated_id_attach" value="{{ $staff->emirated_id_attach}}">
                          @if($staff->emirated_id_attach)
                          <a href="{{ asset('storage'.$staff->emirated_id_attach)}}" download class="float-right  fs-20 {{ (empty($staff->emirated_id_attach)) ? 'hide' : 'inline' }} " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                          @endif
                        </div>

                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">NOC Expiry </label>
                      <input type="text" readonly class="form-control datepicker" id="noc_expiry" name="noc_expiry" placeholder="" value="{{\Carbon\Carbon::parse($staff->noc_expiry)->format('m/d/Y')}}" />
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">NOC</label>

                      <div class="row">
                        <div class="col-md-12">
                          <input type="file" class="form inline-block" style="width: 86%;" id="noc_attach" name="noc_attach">
                          @if($staff->noc_attach)
                          <a href="{{ asset('storage/'.$staff->noc_attach)}}" download class="float-right  fs-20 {{ (empty($staff->noc_attach)) ? 'hide' : 'inline' }} " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                          @endif
                        </div>

                      </div>



                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="exampleInputFile">Height</label>
                    <input type="text" class="form-control" id="height" name="height" placeholder="" value="{{$staff->height}}" />
                  </div>
                  <div class="col-md-6">
                    <label for="weight">Weight</label>
                    <input type="text" class="form-control" id="weight" name="weight" placeholder="" value="{{$staff->weight}}" />
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="weight">Date of Birth</label>
                    <input type="text" readonly class="form-control datepicker" value="{{ (!empty($staff->date_of_birth)) ? \Carbon\Carbon::parse($staff->date_of_birth)->format('m/d/Y'):'' }}"id="date_of_birth" name="date_of_birth" placeholder="">
                  </div>
                </div>


              </div>
              <!-- /.col-md-4 --->

              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputPassword1">Staff Passport No.</label>
                  <input type="text" class="form-control" id="passport_number" name="passport_number" placeholder="" value="{{$staff->passport_number}}" />
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Passport Copy.</label>

                  <div class="row">
                    <div class="col-md-12">
                      <input type="file" class="upload inline" style="width: 86%;" name="passport_attach" />
                      @if($staff->passport_attach)
                      <a href="{{ asset('storage/'.$staff->passport_attach)}}" download class="float-right inline  fs-20 {{ (empty($staff->passport_attach)) ? 'hide' : 'inline' }} " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                      @endif
                    </div>

                  </div>


                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Passport Issue Date</label>
                  <input type="text" readonly class="form-control datepicker" id="passport_issue" name="passport_issue" placeholder="" value="{{ (!empty($staff->passport_issue)) ? \Carbon\Carbon::parse($staff->passport_issue)->format('m/d/Y'):'' }}" />
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Passport Expiry Date</label>
                  <input type="text" readonly class="form-control datepicker" id="passport_expiry" name="passport_expiry" placeholder="" value="{{ (!empty($staff->passport_expiry)) ? \Carbon\Carbon::parse($staff->passport_expiry)->format('m/d/Y'):'' }}" />
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Visa Expiry Date</label>
                  <input type="text" readonly class="form-control datepicker" name="visa_expiry" id="visa_expiry" placeholder="" value="{{ (!empty($staff->visa_expiry)) ? \Carbon\Carbon::parse($staff->visa_expiry)->format('m/d/Y'):'' }}" />
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Visa Attachment </label>

                  <div class="row">
                    <div class="col-md-12">
                      <input type="file" class="upload form inline" style="width: 86%;" name="visa_attach" id="visa_attach" />
                      @if($staff->visa_attach)
                      <a href="{{ asset('storage/'.$staff->visa_attach)}}" download class="float-right inline  fs-20 {{ (empty($staff->visa_attach)) ? 'hide' : 'inline' }} " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                      @endif
                    </div>

                  </div>


                </div>

                <div class="form-group {{ ($staff->staff_type_id != 1) ? 'disabled' : '' }}">
                  <label for="exampleInputPassword1">Staff Basic Salary </label>
                  <input type="text" class="form-control" id="basic_salary" value="" name="basic_salary" value="{{$staff->basic_salary}}" {{ ($staff->staff_type_id != 1) ? 'disabled' : '' }} />
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Staff Certificate </label>

                  <div class="row">
                    <div class="col-md-12">
                      {{-- @dd($staff->staff_certificate->pluck('document_type')->toArray()) --}}
                      <input type="file" class="upload form inline" style="width: 86%;" name="staff_certificate[]" id="staff_certificate" multiple />
                      @if($staff->staff_certificate && count($staff->staff_certificate)>0)
                      <a href="javascript:void(0)" onclick="downloadMultipleAttachments(`{{json_encode($staff->staff_certificate->pluck('document_type')->toArray())}}`)" download="" class="float-right  fs-20 inline " title="Download Attachment"><i class="fa fa-file-text"></i> </a>
                      @endif
                    </div>

                  </div>


                </div>
                <div class="form-group">
                  <label for="exampleInputFile">General Note </label>
                  <div class="row">
                    <div class="col-md-12">
                      <textarea type="textarea" class="form-control" style="width: 86%;" name="general_note" id="general_note">{{$staff->general_note}} </textarea>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.col-md-4 --->

            </div>
            <!-- END Form -->

            <div class="row">
              <h3 class="title col-md-12   m-t-10 m-b-20">Next of Kin <small class="active_font">( optional ) </small> </h3>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputPassword1">Name</label>
                  <input type="text" value="{{$staff->nk_name}}" class="form-control" name="nk_name" id="nk_name" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputPassword1">Relation</label>
                  <input type="text" value="{{$staff->nk_relation}}" class="form-control" name="nk_relation" id="nk_relation" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Phone</label>
                  <input type="text" value="{{$staff->nk_phone}}" class="form-control" name="nk_phone" id="nk_phone" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Address</label>
                  <input type="text" value="{{$staff->nk_address}}" class="form-control" name="nk_address" id="nk_address" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Nationality</label>
                  <input type="text" value="{{$staff->nk_nationality}}" class="form-control" name="nk_nationality" id="nk_nationality" placeholder="" />
                </div>
              </div>

              <div class="pull-right col-md-3">
                <div class="form-group text-right">
                  <label for="" class="block visibility-hidden ">Nationality</label>
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
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


@endsection
@section('content_js')
<script>


  $(function() {

    $('#edit_staff_form').bootstrapValidator({
      message: 'This value is not valid',
      feedbackIcons: {
        valid: '',
        invalid: '',
        validating: 'glyphicon glyphicon-refresh2'
      },
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Staff name is required and cannot be empty'
            },
            regexp: {
              regexp: /^[a-zA-Z ]+$/,
              message: 'Staff name can only consist of alphabets'
            }
          }
        },
        passport_number: {
          validators: {
            notEmpty: {
              message: 'The Passport No. is required and cannot be empty'
            }
          }
        },
        contact_number: {
          validators: {
            notEmpty: {
              message: 'The Contact No. is required and cannot be empty'
            },
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'Contact No. can only consist of numbers'
            }
          }
        },
        contact_number_home: {
          validators: {
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'Contact No. (Home) can only consist of numbers'
            }
          }
        },
        staff_type_id: {
          validators: {
            notEmpty: {
              message: 'The Staff Type is required and cannot be empty'
            }
          }
        },
        sira_id_number: {
          validators: {
            notEmpty: {
              message: 'The SIRA ID No. is required and cannot be empty'
            }
          }
        },
        other_contact_number: {
          validators: {
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'This Field can only consist of numbers'
            }
          }
        },
        passport_number: {
          validators: {
            notEmpty: {
              message: 'The Passport No. is required and cannot be empty'
            }
          }
        },
        email: {
          validators: {
            emailAddress: {
              message: 'The input is not a valid email address'
            }
          }
        },
        secondary_email:{
          validators: {
            emailAddress: {
              message: 'The input is not a valid email address'
            }
          }
        },
        basic_salary: {
          validators: {
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'Salary must be in digits'
            }
          }
        },
        sira_type_id: {
          validators: {
            notEmpty: {
              message: 'The Sira type. is required and cannot be empty'
            }
          }
        }

      }
    });



    $('.datepicker').datepicker({
        // "setDate": new Date(),
        // "autoclose": true,
        dateFormat: 'yyyy-mm-dd'
    });

    $('#sira_expiry').datepicker("setDate",$('#sira_expiry').val());


  });

  function downloadMultipleAttachments(attachments)
  {
    attachments=JSON.parse(attachments);
    // console.log(attachments);

    var link = document.createElement('a');

      link.style.display = 'none';
      document.body.appendChild(link);
      attachments.forEach(attachment=>{
        // var fileName=attachment.split('.');
        link.setAttribute('download', attachment);
        attachment=window.location.origin+'/storage/'+attachment;
        console.log(attachment);
        link.setAttribute('href', attachment);
        link.click();
      })
    document.body.removeChild(link);


  }

  function EnableDisableBasicSalary(value) {
    if (value == 2) {
      $("#basic_salary").attr('disabled', 'disabled');
      $("#basic_salary").attr('title', 'Basic Salary is disabled for Freelancers Staff');
    } else {
      $("#basic_salary").removeAttr('disabled');
      $("#basic_salary").removeAttr('title');
    }
    return false;
  }
  $(document).ready(function(){
    @if ($alert> 0 && $alert<=30)
    $('#sira-expiry-alert').modal('show');
    @endif
  })
</script>
@endsection