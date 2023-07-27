@extends('layouts.master')
@section('content')

    <section class="content-header">
      <h1>
        WhatsApp Messages
      </h1>
      <ol class="breadcrumb">
       <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('whatsapp_module')}}">WhatsApp Messages</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Recent Messages Recived</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body ">
				<div class="row">
					<div class="col-md-12 table-responsive">
						<table id="messages" class="table table-bordered datatable table-striped ">
							<thead>
								<tr>
									<th>Date/Time</th>
									<th>Name</th>
									<th>Message</th>
									<th>Message Type</th>
								</tr>
							</thead>
							<tbody>
								@if($data)
									@foreach($data as $cl)
										@if($cl->contact_number)
											<tr id="tr-{{$cl->id}}">
												<td>{{Carbon\Carbon::parse($cl->created_at)->format('D,m,y H:i:ss')}}<br>
													<label class="label label-primary pull-right">
														{{Carbon\Carbon::parse($cl->created_at)->diffForHumans()}}
													</label>
												</td>
												<td>{{ get_staff_by_number($cl->contact_number)}}</td>
												<td>
													<pre>{{$cl->message}}</pre>
												</td>
												<td>
													<label class="label label-{{($cl->message_type=='normal')?'warning':'success'}} pull-right">{{$cl->message_type}}</label>
												</td>
											</tr>
										@endif
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
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
<!--- /#BlockStaffPopup  ---->
@endsection
@section('content_js')
<script>
$('#messages').DataTable({
	"ordering": false
});
</script>

@endsection