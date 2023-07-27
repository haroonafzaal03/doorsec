@extends('layouts.master')

@section('content')

    <section class="content-header">
        <h1>
            Payroll
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{route('payroll')}}">Payroll</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Payroll</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" id="payroll_form" action="#" method="GET" onSubmit="return getPayrollDetails();" enctype="multipart/form-data">
                    @csrf
                    <!-- BEGIN Form -->
                    <div class="row">


                    <div class="col-md-3">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Start Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control  month_year" id="datepicker" autocomplete="new-password" name="start_date" value="{{ \Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> End Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control  month_year" id="datepicker" autocomplete="new-password" name="end_date" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Staff Type</label>
                                <select name="staff_type_id" id=""staff_type_id class="form-control" onChange="getStaffList(this.value)">
                                    <option value="">Select</option>
                                    @if($staff_type)
                                    @foreach($staff_type as $obj)
                                    <option value="{{$obj->id}}">{{$obj->type}} </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="staff_name" class=""> Staff </label>
                                <select name="staff_id" id="staff_id" class="form-control image_select2 custom_css" style="width:100%">
                                    <option value="">Select</option>
                                    @if($staffs)
                                    @foreach($staffs as $obj)
                                    <!-- <option value="{{$obj->id}}" data-image="{{img($obj->picture)}}">{{$obj->name}} </option> -->
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_name" class="">  Type </label>
                                <select name="client_type_id" id="client_type_id" class="form-control" onChange="getClientsList(this.value)">
                                    <option value=""> Select</option>
                                    @if($client_types)
                                    @foreach($client_types as $obj)
                                    <option value="{{$obj->id}}">{{$obj->type}} </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_name" class=""> Event / Venue </label>
                                <select name="event_venue_id" id="event_venue_id" class="form-control select2 custom_css" style="width:100%">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->

                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Select Month</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control  month_year" id="datepicker" autocomplete="new-password" name="payment_date" value="{{ date('Y-m') }}" />
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Payment Status</label>
                                    <select id="payment_status" name="payment_status" class="form-control" >
                                    <option value="">Select</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="hold">Hold</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Payment Status</label>
                                    <select id="payment_status" name="payment_status" class="form-control" >
                                    <option value="">Select</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="hold">Hold</option>
                                    </select>
                            </div>
                        </div>

                        <!---- /.col-md-2 ---->


                        <div class="col-md-1">
                            <div class="form-group row">
                                <div class=" col-md-12 text-right">
                                    <label for="staff_type_id" class="">  &nbsp;   </label>
                                    <button type="submit" class="btn btn-info" style="width:100%">GO</button>
                                </div>
                            </div>
                        </div>
                        <!---- /.col-md-12 ---->

                        <div class="col-md-1">
                            <div class="form-group row">
                                <div class=" col-md-12 text-right">
                                    <label for="staff_type_id" class="">  &nbsp;   </label>
                                    <button type="button" id="resetFilter" class="btn btn-default" style="width:100%">Reset</button>
                                </div>
                            </div>
                        </div>
                        <!---- /.col-md-12 ---->


                        </div>
                        <!-- /.row -->
                    </form>
                    <!-- END Form -->
              </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->




      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Staff Payment Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                    @csrf
                    <!-- BEGIN Form -->
                    <div class="row">
                        <div class="col-md-12">

                            <table id="payroll_table" class="table table-bordered table-striped table-responsive">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <!-- <th>Event / Venue </th> -->
                                    <!-- <th>Payment Date</th> -->
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Pending Amount</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>



                        </div>
                        <!---- /.col-md-2 ---->


                        </div>
                        <!-- /.row -->
              </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->




<!--- Edit Payroll Popup ---->

<div id="editPayrollPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
      <div class="modal-content">
         <form id="payRollEditForm" method="POST" action="{{ route('payroll_update')}}" onsubmit="">
            @csrf
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title module_title">Edit Payroll </h4>
            </div>
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group row m-b-0">
                                <div class="col-md-12">
                                    <label class="control-label dp-inline"> Total Amount: </label>

                                    <input type="hidden" class="form-control" readonly id="total_amount" name="total_amount" required="required" />
                                    <span class="total_amount label bg-blue label-lg"> </span>
                                </div>
                                <div class="col-md-hide">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">

                            <div class="form-group row m-b-0">
                                <div class="col-md-12">
                                    <label class="control-label dp-inline"> Paid Amount: </label>

                                    <input type="hidden" class="form-control number_only" id=" " name="" required="required" />
                                    <span class="paid_amount label label-lg bg-green"> </span>
                                </div>
                                <div class="col-md-4 hide">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">

                            <div class="form-group row m-b-0">
                                <div class="col-md-12">
                                    <label class="control-label dp-inline"> Pending Amount: </label>

                                    <input type="hidden" class="form-control" id="pending_amount" name="pending_amount" required="required" />
                                    <span class="pending_amount label bg-yellow label-lg"> </span>
                                </div>
                                <div class="col-md-3 hide">
                                </div>
                            </div>


                        </div>
                        <!-- /.COL -->
                    </div>
                     <!-- /.row -->

                    <div class="form-group row m-t-20">
                        <div class="col-md-3">
                        <label class="control-label dp-inline"> Pay Amount </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control number_only" type="number" id="paid_amount" name="paid_amount" required="required"  max=""/>
                            <span class="paid_amt_response_message block bold text-red" > &nbsp; </span>
                        </div>
                    </div>
                    <div class="form-group row m-t-20">
                        <div class="col-md-3">
                        <label class="control-label dp-inline"> Hold Amount </label>
                        </div>
                        <div class="col-md-8">
                            <input  type="checkbox"  id="hold_payment" name="hold_payment" value="hold"  />

                        </div>
                    </div>
                    <!-- /.row -->


                  </div>
               </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="payroll_id" name="payroll_id" value="" />
               <button type="button" class="btn btn-success" id="submitBtn" > Update </button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>



<!--- payrollDetailsPopup  ---->

<div id="paymentDetailsPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
      <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title module_title">View Payment Details </h4>
            </div>
            <div class="modal-body payment_logs_body">

            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" id="print_slip_trigger" onClick="PrintPDF();" class="btn btn-success" >Print Slip</a>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <form id="payment_logs_print_form" class="pyment_logs_print_form hide" action="{{route('print_slip')}}" method="POST">
            @csrf
                <input type="" id="" name="frm_type_id" value="" readonly />
                <input type="" id="" name="frm_event_venue_id" value=""   readonly/>
                <input type="" id="" name="frm_payroll_id" value=""   readonly/>
                <input type="" id="" name="frm_staff_id" readonly/>
            </form>
      </div>
   </div>
</div>







    </section>
    <!-- /.content -->



@endsection
@section('content_js')
<script>


function getPayrollDetails()
{
    var formData = $("form#payroll_form").serialize();
    var myJsonData = {formData: formData};


    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.post('/getPayrollDetails', myJsonData, function(response) {
       var obj = JSON.parse(response);
      $(".response_message").text(obj.message);

       $("#payroll_table").dataTable().fnDestroy();

       if(obj.status)
       {
           $("#payroll_table tbody").empty();
           $("#payroll_table tbody").html(obj.data);
       }
       else
       {
            $("#payroll_table tbody").empty();
       }

       $("#payroll_table").dataTable();

     });



    return false;
}

function editPayroll(id)
{
    // window.location.href = '{{route("payroll_details", 2)}}';
    // return;
    var data_id =    $("#"+id).attr('data-id');
    var data_total_amount =    $("#"+id).attr('data-total_amount');
    var data_pending_amount =    $("#"+id).attr('data-pending_amount');
    var data_paid_amount =    $("#"+id).attr('data-paid_amount');
    var data_status =    $("#"+id).attr('data-status');

    if(data_status == 'hold'){
        $("form#payRollEditForm #hold_payment").attr('checked','checked');
        $("form#payRollEditForm #paid_amount").attr('disabled',true);
        $("form#payRollEditForm #submitBtn").attr('disabled',true);
    }
    else
    {
        $("form#payRollEditForm #submitBtn").attr('disabled',false);
    }

    $("form#payRollEditForm #payroll_id").val(data_id);
    $("form#payRollEditForm #total_amount").val(data_total_amount);
    $("form#payRollEditForm .total_amount").text(data_total_amount);
    $("form#payRollEditForm #pending_amount").val(data_pending_amount);
    $("form#payRollEditForm .pending_amount").text(data_pending_amount);
    $("form#payRollEditForm .paid_amount").text(data_paid_amount);
    $("form#payRollEditForm #payment_status").val(data_status);
    $("#editPayrollPopup").modal();
    return false;
}


function ViewPaymentLogs(payroll_id,event_venue_id,staff_id,type) // type is string
{
    $("form#payment_logs_print_form")[0].reset();
    var myJsonData =  {event_venue_id : event_venue_id , staff_id : staff_id , type : type };

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.post('/get_payment_logs_json/'+payroll_id, myJsonData, function(response) {
       var obj = JSON.parse(response);
        console.log(obj);
       if(obj)
       {
           $('form#payment_logs_print_form input[name="frm_payroll_id"]').val(payroll_id);
           $('form#payment_logs_print_form input[name="frm_event_venue_id"]').val(event_venue_id);
           $('form#payment_logs_print_form input[name="frm_staff_id"]').val(staff_id);
           $('form#payment_logs_print_form input[name="frm_type_id"]').val(type);


            $("#paymentDetailsPopup").modal();
            $("#paymentDetailsPopup .payment_logs_body").html(obj.data);
       }
       else
       {
            $("#paymentDetailsPopup .payment_logs_body").empty();
       }
     });

    return false;
}

    function PrintPDF()
    {
        $("form#payment_logs_print_form").submit();
        return false;
    }

    $(function () {

            getPayrollDetails();

            $("#resetFilter").click(function(){
                $("#payroll_form")[0].reset();
                getStaffList(0);
                getClientsList(0);
                getPayrollDetails();
            });



            $("#paid_amount").on('keyup',function(ev){
            $('form#payRollEditForm .paid_amt_response_message').removeClass('text-green')
            $('form#payRollEditForm .paid_amt_response_message').addClass('text-red')

                var paid_amount = $(this).val();

                if(paid_amount == "" || paid_amount == " " || paid_amount == null )
                    paid_amount = 0;


                    var total_amount = $("#total_amount").val();
                    var check_amount = $("#pending_amount").val();
                    if(check_amount <= 0)
                    {
                        check_amount = total_amount;
                    }

                $("form#payRollEditForm #submitBtn").removeAttr('disabled');
                if(parseInt(paid_amount) > 0)
                {
                    if(parseInt(paid_amount) <= parseInt(check_amount))
                    {
                        $('.paid_amt_response_message').html(' &nbsp; ');
                    }
                    else
                    {
                        $('.paid_amt_response_message').text('Paid Amount must  be less than Pending Amount');
                        $("form#payRollEditForm #submitBtn").attr('disabled','disabled');
                    }

                }
                else
                {
                // exception
                }

            });

            $(".month_year").datepicker(
                {
                    format: "yyyy-mm-dd"
                }
            );

            $("#payroll_table").dataTable();


             $('#hold_payment').change(function(){
                var r =   confirm("Press a button!");
                        if (r == true) {
                        txt = "You pressed OK!";
                            if($(this).prop('checked')){
                                $("form#payRollEditForm #hold_payment").prop('checked',true);
                                $("form#payRollEditForm #paid_amount").attr('disabled',true);
                            }else{
                                $("form#payRollEditForm #hold_payment").prop('checked',false);
                                $("form#payRollEditForm #paid_amount").attr('disabled',false);
                            }
                        } else {
                                console.log('inn');
                            txt = "You pressed Cancel!";
                            if($(this).prop('checked')){
                                    $("form#payRollEditForm #hold_payment").prop('checked',false);
                                }else{
                                    $("form#payRollEditForm #hold_payment").prop('checked',true);
                                }
                        }
                        console.log(txt);
             });


            $("form#payRollEditForm #submitBtn").on('click',function(){
                $('form#payRollEditForm .paid_amt_response_message').text('');
                var paid_amount = $('#paid_amount').val();


                var myJsonData =  $("form#payRollEditForm").serialize();

                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });

                $.post('/payroll_update', myJsonData, function(response) {
                var obj = JSON.parse(response);
                    console.log(obj);
                if(obj.response == 'OK')
                {
                    $('#paid_amount').val('')
                    $('form#payRollEditForm .paid_amt_response_message').removeClass('text-red')
                    $('form#payRollEditForm .paid_amt_response_message').addClass('text-green')
                    $('form#payRollEditForm .paid_amt_response_message').text('Paid Amount has been added')
                }
                else
                {
                        $('form#payRollEditForm .paid_amt_response_message').removeClass('text-green')
                        $('form#payRollEditForm .paid_amt_response_message').addClass('text-red')
                        $("#paymentDetailsPopup .payment_logs_body").empty();
                        $('form#payRollEditForm .paid_amt_response_message').text('Paid Amount Required')
                }
                });

                return false;



            }); // update Functionality



    }); // JquERY END



</script>
@endsection