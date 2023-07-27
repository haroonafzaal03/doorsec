@extends('layouts.master')

@section('content')
 <section class="content-header">
      <h1>
        Users
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
              <h3 class="box-title">List of All Users</h3>
               <a href="{{route('register')}}">
             <button type="submit"class="btn btn-success pull-right btn-flat">Add New User</button>
            </a>
              <!-- <a type="button" class="btn btn-info pull-right" href="{{route('client_create')}}" >Add New Us</a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped table-responsive">
                <thead>
                <tr>
                  <th>UserName</th>
                  <th>UserEmail</th>
                  <th>Roles</th>
                  <th>Action</th>

                </tr>
                </thead>
                <tbody>
                @if($user)
                @foreach($user as $user)
                <tr>
                  <td>{{$user->name}}</td>
                  <td>{{$user->email}}</td>
                  <td>{{implode(',',$user->roles()->get()->pluck('name')->toArray()) }}</td>
                  <td> <a href="{{ Route('userright_edit',$user->id)}}">
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