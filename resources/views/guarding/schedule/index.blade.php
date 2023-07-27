@extends('layouts.master')

@section('content')

    <section class="content-header">
      <h1>
        Guarding
      </h1>
      <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('guarding')}}">Guarding</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of All Guards Schedules</h3>
              <a href="{{route('guarding_create')}}" class="btn btn-info pull-right" >
                Add New Schedule
              </a>
              <a href="#" type="button" class="btn btn-warning pull-right hide" style="margin-right:1%" >
                View Event Calendar
              </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Client</th>
                  <th>Date of Schedule</th>
                  <th>total staff</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if($schedule)
                   @foreach($schedule as $key => $sch)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td><a href="{{route('guarding_schedule',$sch->id)}}">{{$sch->client->property_name}}</a></td>
                    <td>{{\Carbon\Carbon::parse($sch->start_date)->format('M d ,Y').' TO '.\Carbon\Carbon::parse($sch->end_date)->format('M d ,Y')}}</td>
                    <td>{{count($sch->staffschedule)}}</td>
                    <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                          <li><a href="{{route('guarding_schedule',$sch->id)}}"><i class="fa fa-eye"></i>View</a></li>
                          <li><a href="{{route('guarding_edit',$sch->id)}}"><i class="fa fa-pencil"></i>Edit</a></li>
                          <li><a href="{{route('export_guardingSchedule',$sch->id)}}"><i class="fa fa-book"></i>Export</a></li>
                          <li><a href="{{route('guarding_duplicate',$sch->id)}}"><i class="fa fa-copy"></i>Duplicate for next month</a></li>
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
@endsection
@section('content_js')
<script>

$(function () {

    $('#example1').DataTable({
    });

  }); // jQuery End

</script>
@endsection