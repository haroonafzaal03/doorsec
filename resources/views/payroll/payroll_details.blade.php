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
          <div class="col-xs-10 col-xs-offset-1">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Mange payroll</h3>
                </div>
            <!-- /.box-header -->
                    <div class="box-body">
                      @php
                            $staff = \App\Staff::all();
                        @endphp
                    <div class="row">
                        <div class="col-md-5">
                        <label for="staff_name" class=""> Staff </label>
                            <select name="staff_id" id="staff_id" class="form-control image_select2 custom_css" style="width:100%" onChange="change_staff(this.value);">
                                    <option value=""> Select</option>
                                        @if($staff)
                                            @foreach($staff as $staff)
                                            <option value="{{$staff->id}}" {{($staff->id == $payroll_data->id)?'selected':''}} data-image="{{img($staff->picture)}}">{{$staff->name}} </option>
                                            @endforeach
                                        @endif
                            </select>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>





      <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
          <div class="box hide">
            <div class="box-header">
              <h3 class="box-title">Advance Fileter</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" id="payroll_form" action="#" method="GET" onSubmit="return getPayrollDetails();" enctype="multipart/form-data">
                    @csrf
                    <!-- BEGIN Form -->
                    <div class="row">

                        <!---- /.col-md-2 ---->


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="staff_name" class="">  Client Type </label>
                                <select name="client_type_id" id="client_type_id" class="form-control" onChange="getClientsLists(this.value)">
                                    <option value=""> Select</option>
                                    @if($client_types)
                                    @foreach($client_types as $obj)
                                    <option value="{{$obj->id}}">{{$obj->type}} </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div id="datepicker_hide" class="col-md-3 hide">
                            <div class="form-group">
                                <label for="staff_type_id" class=""> Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control  month_year" id="datepicker" autocomplete="new-password" name="venue_date" value="{{date('m-d-Y')}}" readonly />
                                </div>
                            </div>
                        </div>
                        <!---- /.col-md-2 ---->

                        <input type="hidden" id="s_id" name="s_id" value="{{$payroll_data->id }}"/>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="staff_name" class=""> Client </label>
                                <select name="event_venue_id" id="event_venue_id" class="form-control select2 custom_css" style="width:100%">
                                    <option value="">Select</option>
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
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 col-md-offset-1">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                    <a href="#" class="block img_lightbox ">
                        <img class="profile-user-img img-responsive img-circle" src="{{img($payroll_data->picture)}}" alt="Staff profile picture">
                    </a>
                    <h3 class="profile-username text-center active_font bold"> {{ (!empty($payroll_data->name ) ? $payroll_data->name: '-') }}</h3>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                        <b>Contact Number</b> <a class="pull-right"> {{ (!empty($payroll_data->contact_number ) ? $payroll_data->contact_number: '-') }} </a>
                        </li>
                        <li class="list-group-item">
                        <b>Sira Number</b> <a class="pull-right">{{ (!empty($payroll_data->sira_id_number ) ? $payroll_data->sira_id_number: '-') }} </a>
                        </li>
                        <li class="list-group-item ">
                        <b>Total Paid</b> <a class="pull-right">{{$payroll_data->payroll->sum('total_amount')}}</a>
                        </li>
                        <li class="list-group-item ">
                        <b>Total Arrears</b> <a class="pull-right">{{$payroll_data->payroll->sum('pending_amount')}}</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block hide"><b>Message</b></a>
                    </div>
            <!-- /.box-body -->
                </div>
        </div>
            <div class="col-md-7">
            <!-- Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Edit Payroll</h2>
                <br>
                <br>
            </div>
                <!-- /.box-header -->
                <!-- form start -->
                    <div class="box-body">
                        <form class="form-horizontal" id="payRollEditForm" method="POST" action="{{ route('payroll_update')}}" onsubmit="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="details_payment"class="table table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Event/Venue Name</th>
                                                <th> Start-End Date</th>
                                                <th> Start-End Time</th>
                                                <th> Rate/hr</th>
                                                <th> Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Pending Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- payroll_info --}}
                                            @php
                                                $total_amount= 0;
                                                $pending_amount= 0;
                                            @endphp
                                            @if($payroll_info)
                                                @foreach ($payroll_info as $pi )
                                                    <tr>
                                                        <td><input type="checkbox" name="payroll_id[]" value="{{$pi->staff_sch_id}}"/></td>
                                                        @if($pi->event)
                                                            <td>{{$pi->event->event_name}}</td>
                                                            <td>{{$pi->event->start_date}} To {{$pi->event->end_date}}</td>
                                                        @else
                                                            <td>{{$pi->venue->client->property_name}}</td>
                                                            <td>{{$pi->venue->start_date}} To {{$pi->venue->end_date}}</td>
                                                        @endif
                                                        <td>{{$pi->start_time}} To {{$pi->end_time}}</td>
                                                        <td>{{$pi->rate_per_hour}}</td>
                                                        <td>{{$pi->total_amount}}</td>
                                                        <td>{{$pi->paid_amount}}</td>
                                                        <td>{{$pi->pending_amount}}</td>
                                                        @php
                                                        $total_amount =$pi->total_amount +$total_amount;
                                                        $pending_amount =$pi->pending_amount +$pending_amount;
                                                        @endphp
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="form-group row m-b-0">
                                <!-- <div class="col-md-4"> -->
                                    <label class="control-label dp-inline"> Total Amount: </label>


                                    <input type="hidden" class="form-control" value="{{$total_amount}}" readonly id="total_amount" name="total_amount" required="required" />
                                    <span class="total_amount label bg-blue label-lg " id="total_amounts">{{$total_amount}} </span>
                                <!-- </div> -->
                                        <div class="col-md-hide">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row m-b-0">
                                <!-- <div class="col-md-4"> -->
                                    <label class="control-label dp-inline"> Paid Amount: </label>

                                    <input type="hidden" class="form-control" value="{{$total_amount -$pending_amount }}" readonly id="amount_paid_payroll" name="amount_paid_payroll" required="required" />
                                    <span class="paid_amount label label-lg bg-green " id="paid_amounts">{{$total_amount -$pending_amount }} </span>
                                <!-- </div> -->
                                        <div class="col-md-hide">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row m-b-0">
                                <!-- <div class="col-md-4"> -->
                                    <label class="control-label dp-inline"> Pending Amount: </label>

                                    <input type="hidden" class="form-control" value="{{$pending_amount}}" readonly id="amount_pending_payroll" name="amount_pending_payroll" required="required" />
                                    <span class="pending_amount label bg-yellow label-lg " id="pending_amounts">{{$pending_amount}}</span>
                                <!-- </div> -->
                                        <div class="col-md-hide">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                        </div><!-- row-->
                        <hr>
                        <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"> Pay Amount </label>

                        <div class="col-sm-10">
                            <input class="form-control number_only" type="number" id="paid_amount" name="paid_amount" required="required"  max=""/>
                            <span class="paid_amt_response_message block bold text-red" > &nbsp; </span>
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-sm-2 control-label"> Hold Amount </label>

                        <div class="col-sm-10">
                            <input  type="checkbox"  id="hold_payment" name="hold_payment" value="hold"  />

                        </div>
                        </div>
                                        <!-- /.box-body -->
                    <div class="box-footer">

                        <button type="button" class="btn btn-success pull-right" id="submitBtn" style="margin-left: 5px;"> Update </button>
                        <button type="button" class="btn btn-default pull-right">Cancel</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
          <!-- /.box -->
            </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    <!-- <div class="col-md-6"></div> -->
        <div class="col-md-10 col-md-offset-1">
          <div class="box box-info row">
            <div class="box-header with-border">
              <h3 class="box-title">View Payment Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <!-- <form class="form-horizontal"> -->
              <div class="box-body">
              <div class="col-md-12">
                <table id="payment_logs_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event / Venue Name</th>
                            <th>Payment Date</th>
                            <th>Paid Amount</th>
                            <th>Pending Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($payroll_data->payroll as $index => $obj)
                        @foreach($obj->payroll_log as $key=>  $data)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{($obj->event_id)?$obj->event->event_name:$obj->venue->client->property_name}}</td>
                            <td>{{$data->payment_date}}</td>
                            <td>{{$data->paid_amount}}</td>
                            <td>{{$data->pending_amount}}</td>
                            <td>{{$data->payment_status}}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>

            </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="print_slip_trigger" onClick="PrintPDF();" style="margin-left: 5px;">Print Slip</button>
                <button type="submit" class="btn btn-default pull-right">Cancel</button>
              </div>
              <!-- /.box-footer -->
            <!-- </form> -->
          </div>

        </div>
    </div>
</div>

<form id="payment_logs_print_form" class="pyment_logs_print_form hide" action="{{route('print_slip')}}" method="POST">
            @csrf
                <input type="" id="" name="frm_staff_id" value="{{$payroll_data->id }}" readonly/>
</form>
</section>
@endsection
@section('content_js')
<script>
     $(document).ready(function(){
         $("#details_payment").dataTable();
     })
    $("#paid_amount").on('keyup', function(){
        $("form#payRollEditForm .paid_amt_response_message").removeClass('text-green');
        $("form#payRollEditForm .paid_amt_response_message").removeClass('text-red');

        var paid_amount = $(this).val();
        if(paid_amount == "" || paid_amount == " " || paid_amount == null )
            paid_amount = 0;

        var total_amount = $("#total_amount").val();
        var amount_paid_payroll = $("#amount_paid_payroll").val();
        var check_amount = $("#amount_pending_payroll").val();

        if(check_amount <= 0)
        {
            check_amount = total_amount;
        }
        $("#submitBtn").removeAttr('disabled');
        if(parseInt(paid_amount) > 0)
        {
            if(parseInt(paid_amount) <= parseInt(check_amount))
            {
                $('.paid_amt_response_message').html(' &nbsp; ');
            }
            else
            {
                $('.paid_amt_response_message').text('Paid Amount must  be less than Pending Amount');
                $("#submitBtn").attr('disabled','disabled');
            }

        }

    });

        $("#hold_payment").change(function(){
            var r = confirm("Press a button!");
                if(r == true){
                    txt = "You pressed OK!";
                    if($(this).prop('checked')){
                        $("#hold_payment").prop('checked',true);
                        $("#paid_amount").attr('disabled',true);
                    }else{
                        $("#hold_payment").prop('checked',false);
                        $("#paid_amount").attr('disabled',false);
                    }
                } else {
                    console.log('inn');
                    txt = "You pressed Cancel!";
                    if($(this).prop('checked')){
                            $("#hold_payment").prop('checked',false);
                        }else{
                            $("hold_payment").prop('checked',true);
                        }
                }
                console.log(txt);

        })
        $("#submitBtn").on('click',function(){
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
                    getPayrollDetails();
                    $('#paid_amount').val('')
                    $('.paid_amt_response_message').removeClass('text-red')
                    $('.paid_amt_response_message').addClass('text-green')
                    $('.paid_amt_response_message').text('Paid Amount has been added')
                    location.reload();
                }
                else
                {
                        getPayrollDetails();
                        $('.paid_amt_response_message').removeClass('text-green')
                        $('.paid_amt_response_message').addClass('text-red')
                        $(".payment_logs_body").empty();
                        $('.paid_amt_response_message').text('Paid Amount Required')
                }
                });

                return false;



            }); // update Functionality
    function PrintPDF()
        {
            var a = $("#payment_logs_print_form").submit();
            return false;
        }
        $(function() {
            $('#datepicker').datepicker().on('changeDate', function (ev) {
                getClientsLists(1);
            });

});
    function getClientsLists(client_type_id){
    if(client_type_id > 0)
    {
        var st_id = $('#s_id').val();
        var myJsonData = '';
        var venue_date  = '';
        if(client_type_id==1){
            $('#datepicker').removeClass('disabled');
            $('#datepicker_hide').removeClass('hide');
            $('#datepicker').datepicker({dateFormat: 'yyyy-mm-dd'});
            venue_date = $('#datepicker').val();

        }else{
            $('#datepicker').addClass('disabled');
            $('#datepicker_hide').addClass('hide');
            //$('#datepicker').val("");
            $('#datepicker').datepicker( "destroy" );
             venue_date = false;
        }
        myJsonData = {type_id: client_type_id , is_option : true,staff_id:st_id, venue_date:venue_date};
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.post('/get_event_venue_lists', myJsonData, function(response) {
        var obj = JSON.parse(response);

            if(obj.response)
            {

                    $("select#event_venue_id").html(obj.response);
                    $("select#event_venue_id").select2();
            }
        });
    }
    else
    {
        $("select#event_venue_id").empty();
        $("select#event_venue_id").html('<option value=""> Select </option>');
    }

    return false;
}
$(function(){
    $("#resetFilter").click(function(){
        getClientsLists(0);
        $('#payroll_form')[0].reset();
    })
});

function getPayrollDetails(){
    var form_data = $('#payroll_form').serialize();
    var myJsonData = {form_data:form_data};
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.post('/get_pay_roll', myJsonData, function(response) {
        if(response == 'record_not_found')
        return false;
        var obj = JSON.parse(response);

        $("form#payRollEditForm #paid_amounts").text(obj[0].paid_amount);
        $("form#payRollEditForm #paid_amounts").removeClass('hide');
        $("form#payRollEditForm #total_amounts").text(obj[0].total_amount);
        $("form#payRollEditForm #total_amounts").removeClass('hide');
        $("form#payRollEditForm #pending_amounts").text(obj[0].pending_amount);
        $("form#payRollEditForm #pending_amounts").removeClass('hide');
        //For Hidden Inputs
        $("form#payRollEditForm #total_amount").val(obj[0].total_amount);
        $("form#payRollEditForm #amount_paid_payroll").val(obj[0].paid_amount);
        $("form#payRollEditForm #amount_pending_payroll").val(obj[0].pending_amount);



        $("#frm_event_venue_id").val(obj[0].event_id);
        $("#frm_payroll_id").val(obj[0].id);
        $("#payroll_id").val(obj[0].id);
            console.log(response);
        });
        return false;
}

        $(".image_select2").select2({
            templateResult: tmpResultFormat,
            templateSelection: tmpSelectionFormat
        });
        function change_staff(id){
            window.location = "/payroll/edit/"+ id;
        }
</script>
@endsection