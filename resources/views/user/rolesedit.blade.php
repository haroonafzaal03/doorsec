@extends('layouts.master')

@section('content')
<style>
.checkbox_style{
  content: '';
  display: inline-block;
  height: 16px;
  width: 16px;
  border: 1px solid;
}
</style>
<style type="text/css">
  .box{
    margin-bottom: 0px;
  }
  .col-md-4
  {
    display: contents;
  }
</style>
 <section class="content-header">
      <h1>
       Edit Roles
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <form action="{{Action('RoleController@Update',['role'=>$role->id]) }}" method="POST">
           @csrf
          <div class="box">

            <!-- /.box-header -->
            <div class="col-md-4">
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Attendance</h3>
              <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Attendance')

                  <div class="form-check">
                    <input class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
              <h3 class="box-title">Client</h3>
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Client')

                  <div class="form-check">
                    <input class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>


  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
              <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Event</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Event')

                  <div class="form-check">
                    <input class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Guarding</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Guarding')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

 <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Payroll</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Payroll')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Reports</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Reports')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Role</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Role')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Schedule</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Schedule')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>


<div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Staff</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Staff')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">User</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'User')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>

  <div class="box">
        <div class="col-md-4">
          <div class="box box-primary">
           <div class="box-header with-border">
            <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
              <h3 class="box-title">Venue</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($permissions as $permission)
                  @if($permission->model ==  'Venue')

                  <div class="form-check">
                    <input  class="checkbox_style" type="checkbox" name="permissions[]" value="{{$permission->id}}"
                    {{$role->hasAnyPermission($permission->name)? 'checked' :''}}>
                    <label>{{$permission->name}}</label>
                  </div>
                @endif
                @endforeach
              <hr>
            </div>
        </div>
        </div>
        <!-- /.col -->
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>

  </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

@endsection