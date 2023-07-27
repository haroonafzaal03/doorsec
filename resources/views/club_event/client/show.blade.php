@extends('layouts.master')

@section('content')
<style>
.label{
  font-size:100%
}
</style>
<section class="content-header">
      <h1>
        Client Profile
      </h1>
      <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('client')}}">Client</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{img($client['client_logo'])}}" alt="User profile picture">

              <h3 class="profile-username text-center">{{$client['property_name']}}</h3>

              <p class="text-muted text-center">Client</p>

              {{--<ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Salary</b> <a class="pull-right">1,322</a>
                </li>
                <li class="list-group-item">
                  <b>Total Balance</b> <a class="pull-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Arrears</b> <a class="pull-right">13,287</a>
                </li>
              </ul>

              <a href="mailbox.html" class="btn btn-primary btn-block"><b>Message</b></a>--}}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- Document Section -->

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Documents </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Trade License</strong>

              <p class="text-muted">
               <img src="{{ img($client['tarde_lice']) }}" style="width:35%;height:10%"/>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Signatory ID </strong>

              <p class="text-muted">
                <a href="{{ (!empty($client['property_signatory_id'])) ? img_click($client['property_signatory_id'])  : '#' }}" target="_blank">
                  <img src="{{ img($client['property_signatory_id']) }}" style="width:35%;height:10%"/>
                </a>
                </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- document section end -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Client</h3>
              <a class="pull-right btn btn-sm btn-info" href="{{url('/client/edit',$id)}}">Edit</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Property</strong>
                <table class="table">
                    <tr>
                        <th>Name </th>
                        <td><p class="text-muted">{{ $client['property_name']}}</p></td>
                    </tr>
                    <tr>
                        <th>License Number </th>
                        <td><p class="text-muted">{{ $client['property_lice_number']}}</p></td>
                    </tr>
                    <tr>
                        <th>Expiry Date </th>
                        <td><p class="text-muted">{{ $client['property_lice_expiry_date']}}</p></td>
                    </tr>
                    <tr>
                        <th>Tax Registartion Number </th>
                        <td><p class="text-muted">{{ $client['property_tax_regis_num']}}</p></td>
                    </tr>
                    <tr>
                        <th>Contract Start  </th>
                        <td><p class="text-muted">{{ $client['property_contract_start']}}</p></td>
                    </tr>
                    <tr>
                        <th>Contract END </th>
                           <td> <p class="text-muted">{{ $client['property_contract_end']}}</p></td>
                    </tr>
                </table>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Venue</strong>

              <p class="text-muted">{{$client['client_address']?$client['client_address'] :'No location provided'}}</p>

              <strong><i class="fa fa-phone margin-r-5"></i> Point Of Contact</strong>

              <p class="text-muted "><i class="fa fa-user"></i>  {{$client['venue_manager_name']?$client['venue_manager_name'] :'No Name provided'}}</p>


              <p class="text-muted label label-info"><i class="fa fa-whatsapp"></i>  {{$client['venue_manager_email']?$client['venue_manager_email'] :'No Email provided'}}</p>
              <p class="text-muted label label-primary "><i class=" fa fa-phone"></i>  {{$client['venue_manager_number']?$client['venue_manager_number'] :'No Contact Number provided'}}</p>


              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Account Manager</strong>
              <p class="text-muted "><i class="fa fa-user"></i>  {{$client['account_manager_name']?$client['account_manager_name'] :'No Name provided'}}</p>
              <p class="text-muted label label-info"><i class="fa fa-whatsapp"></i>  {{$client['account_manager_email']?$client['account_manager_email'] :'No Email provided'}}</p>
              <p class="text-muted label label-primary "><i class=" fa fa-phone"></i>  {{$client['account_manager_num']?$client['account_manager_num'] :'No Contact Number provided'}}</p>
              <p>

              </p>

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

    $('#example1').DataTable({
    });

    $('.datepicker').datepicker({
    });

  })
</script>
@endsection