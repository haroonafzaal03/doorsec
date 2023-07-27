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
 <section class="content-header">
      <h1>
       Edit Users
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of All Roles</h3>
              <!-- <a type="button" class="btn btn-info pull-right" href="{{route('client_create')}}" >Add New Us</a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body ">
              <form action="{{Action('UserRightsController@userRoleUpdate',['user'=>$user->id]) }}" method="POST">
                @csrf
                <!-- {{ method_field('PUT') }} -->

              @foreach($roles as $role)
              <div class="form-check">
                <input class="checkbox_style" type="checkbox" name="roles[]" value="{{$role->id}}"
                {{$user->hasAnyRole($role->name)? 'checked' :''}}>
                <label>{{$role->name}}</label>
              </div>
              @endforeach

  <hr>
                <div class="form-group ">
                <label>dashboard</label>
                  <select class="form-control" name="dashboard">
                    <option value="guarding" {{($user->dashboard=='guarding')?'selected':''}}> guarding</option>
                    <option value="event_club" {{($user->dashboard=='event_club')?'selected':''}}> Event & Club</option>
                  </select>
                </div>
                <hr>
               <button type="submit" class="btn btn-primary">Update</button>
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