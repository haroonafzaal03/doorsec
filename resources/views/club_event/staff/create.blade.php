@extends('layouts.master')

@section('content')

<section class="content-header">
  <h1>
    Staff
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{route('staff')}}">Staffs</a></li>
    <li><a href="{{route('staff_create')}}">Add Staff</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Add new Staff</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <form role="form" id="add_staff_form" action="{{ route('staff_store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputFile">Staff Category</label>
                  <select type="text" class="form-control" id="staff_type_id" name="staff_type_id" placeholder="" onChange="EnableDisableBasicSalary(this.value)">
                    <option value=""> Select</option>
                    @if($staff_type)
                    @foreach($staff_type as $obj)
                    <option value="{{$obj->id}}">{{$obj->type}} </option>
                    @endforeach
                    @endif
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Staff Name</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="" />
                </div>
                <div class="form-group">
                  <label for="">Staff Contact No.</label>
                  <input type="text" class="form-control number_only" id="contact_number" name="contact_number" placeholder="" />
                </div>
                <div class="form-group">
                  <label for="">Staff Picture</label>
                  <input type="file" class="upload form-control" name="picture" />
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Staff Contact No. ( Home Country )</label>
                      <input type="text" class="form-control number_only" id="contact_number_home" name="contact_number_home" placeholder="" />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">

                      <label for="weight">Other Contact Number</label>
                      <input type="text" class="form-control number_only" id="other_contact_number" name="other_contact_number" placeholder="">

                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">

                      <label for="weight">Email Address</label>
                      <input type="text" class="form-control email" id="email" name="email" placeholder="" required>

                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">

                      <label for="weight">Secondary Email Address</label>
                      <input type="text" class="form-control secondary_email" id="secondary_email" name="secondary_email" placeholder="">

                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-7 p-r-0">
                    <div class="form-group">
                      <label for=" ">Nationality</label>
                      <input type="text" class="form-control" id="nationality" name="nationality" placeholder="">
                    </div>
                  </div>

                  <div class="col-md-5 p-l-5">
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

              </div>
              <div class="col-md-6">


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sira_id_number"> Staff SIRA ID No.</label>
                      <input type="text" class="form-control" id="sira_id_number" name="sira_id_number" placeholder="">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="siraType">SIRA Type </label>
                      <select type="text" class="form-control" id="sira_type_id" name="sira_type_id">
                        <option value="">Select</option>
                        @if(isset($siraTypes))
                        @foreach($siraTypes as $obj)
                        <option value="{{$obj->id}}">{{$obj->type}} </option>
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
                      <input type="file" class="upload form-control" id="sira_id_attach" name="sira_id_attach" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Sira Expiry Date</label>
                      <input type="text" readonly class="form-control datepicker" id="sira_expiry" name="sira_expiry" placeholder="">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="uid_number">Staff UID No.</label>
                      <input type="text" class="form-control" id="uid_number" name="uid_number" placeholder="">
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="taxregistrationnumber">Staff Emirates ID No.</label>
                      <input type="text" class="form-control" id="emitrates_id" name="emitrates_id" required placeholder="123-1234-1234567-1" pattern="[0-9]{3}-[0-9]{4}-[0-9]{7}-[0-9]{1}" />
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Emirates Expiry Date</label>
                      <input type="text" readonly class="form-control datepicker" id="emirates_expiry" name="emirates_expiry" placeholder="">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="eid_attach">EID Attachment </label>
                      <input type="file" class="form-control" id="emirated_id_attach" name="emirated_id_attach" placeholder="">
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">NOC Expiry </label>
                      <input type="text" readonly class="form-control datepicker" id="noc_expiry" name="noc_expiry" placeholder="">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">NOC</label>
                      <input type="file" class="form-control" id="noc_attach" name="noc_attach">
                    </div>
                  </div>
                </div>


                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="exampleInputFile">Height</label>
                    <input type="text" class="form-control" id="height" name="height" placeholder="">
                  </div>
                  <div class="col-md-6">
                    <label for="weight">Weight</label>
                    <input type="text" class="form-control" id="weight" name="weight" placeholder="">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="exampleInputFile">Date of Birth</label>
                    <input type="text" readonly class="form-control datepicker" id="date_of_birth" name="date_of_birth" placeholder="">
                  </div>
                </div>


              </div>
              <!-- /.col-md-4 --->

              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputPassword1">Staff Passport No.</label>
                  <input type="text" class="form-control" id="passport_number" name="passport_number" placeholder="" />
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Passport Copy.</label>
                  <input type="file" class="upload form-control" name="passport_attach" />
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Passport Issue Date</label>
                  <input type="text" readonly class="form-control datepicker" id="passport_issue" name="passport_issue" placeholder="">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Passport Expiry Date</label>
                  <input type="text" readonly class="form-control datepicker" id="passport_expiry" name="passport_expiry" placeholder="">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Visa Expiry Date</label>
                  <input type="text" readonly class="form-control datepicker" name="visa_expiry" id="visa_expiry" placeholder="">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Visa Attachment </label>
                  <input type="file" class="upload form-control" name="visa_attach" id="visa_attach" />
                </div>

                <div class="form-group disabled">
                  <label for="exampleInputPassword1">Staff Basic Salary </label>
                  <input type="text" class="form-control" id="basic_salary" value="" disabled name="basic_salary" placeholder="" />
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Staff Certificate </label>
                  <div class="row">
                    <div class="col-md-12">
                      <input type="file" class="upload form inline" style="width: 86%;" name="staff_certificate[]" id="staff_certificate" multiple />
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">General Note </label>
                  <div class="row">
                    <div class="col-md-12">
                      <textarea type="textarea" class="form-control" style="width: 86%;" name="general_note" id="general_note">  </textarea>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.col-md-4  --->

            </div>
            <!-- END Form -->
            <div class="row">
              <h3 class="title col-md-12   m-t-10 m-b-20">Next of Kin <small class="active_font">( optional ) </small> </h3>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputPassword1">Name</label>
                  <input type="text" class="form-control" name="nk_name" id="nk_name" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleInputPassword1">Relation</label>
                  <input type="text" class="form-control" name="nk_relation" id="nk_relation" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Phone</label>
                  <input type="text" class="form-control" name="nk_phone" id="nk_phone" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Address</label>
                  <input type="text" class="form-control" name="nk_address" id="nk_address" placeholder="" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Nationality</label>
                  <input type="text" class="form-control" name="nk_nationality" id="nk_nationality" placeholder="" />
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


@endsection
@section('content_js')
<script>
  $(function() {

    $('#add_staff_form').bootstrapValidator({

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
        gender: {
          validators: {
            notEmpty: {
              message: 'Gender Required!'
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
        other_contact_number: {
          validators: {
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'This Field can only consist of numbers'
            }
          }
        },
        basic_salaryss: {
          validators: {
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'Salary must be in digits'
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
        emitrates_id:{
          validators: {
            notEmpty: {
              message: 'The Emirates ID is required and cannot be empty'
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
      dateFormat: 'yyyy-mm-dd'
    });

  });

  function EnableDisableBasicSalary(value) {
    if (value == 2) {
      $("#basic_salary").attr('disabled', 'disabled');
      $("#basic_salary").parent('.form-group').addClass('disabled');
      $("#basic_salary").attr('title', 'Basic Salary is disabled for Freelancers Staff');
    } else {
      $("#basic_salary").removeAttr('disabled');
      $("#basic_salary").parent('.form-group').removeClass('disabled');
      $("#basic_salary").removeAttr('title');
    }
    return false;
  }
</script>
@endsection