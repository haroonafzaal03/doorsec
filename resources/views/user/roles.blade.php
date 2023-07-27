@extends('layouts.master')

@section('content')
<style type="text/css">
  .ellipsis {
    max-width: 40px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}
</style>
 <section class="content-header">
      <h1>
        Roles
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <!--Add new Role -->
            <!-- /.box-header -->
            <div class="col-md-12">
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add New Role</h3>
              <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                    </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <form action="{{ route('role_create') }}" method="POST">
                @csrf
                  <div class="row" style="display: flex; width: 90%;margin-left: 50px; ">
                    <input type="text" class="form-control" name="name" placeholder="Role Name" >
                    <input type="text" class="form-control"  name="description" placeholder="Description"style="margin-left: 10px;">
                    <input type="submit" class="btn btn-primary" value="Add" style="margin-left: 10px;"> 
                  </div>
            </form>
            </div>

            </div>
            <!-- /.box-body -->
          </div>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of All Roles</h3>

            </div>
            <!-- /.box-header -->
           
            <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>Role</th>        
                  <th>Roles</th>
                  <th>Action</th> 
                </tr>
                </thead>
                <tbody>
                @if($roles)
                @foreach($roles as $role)
                <tr>
                  <td>{{$role->name}}</td>
                  <td class="ellipsis" >{{implode(',',$role->permissions()->get()->pluck('name')->toArray()) }}</td>
                  <td> <a href="{{ Route('roles_edit',$role->id)}}">
                  	<button type="button" class="btn btn-primary btn-sm">Edit</button>
                  </a> </td>
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