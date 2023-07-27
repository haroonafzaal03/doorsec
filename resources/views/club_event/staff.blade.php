@extends('layouts.master')

@section('content')

<section class="content-header">
      <h1>
        Staff
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Staff</a></li>
      </ol>
</section>

<!-- Main content -->
<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of All Staff</h3>
              <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addPopup">
                Add New Staff
              </button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Address</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td><a href="profile.html">Staff A</profile></td>
                  <td>demo@test.com</td>
                  <td>78765778744</td>
                  <td>XYZ</td>
                  <td>Temporary ( Hourly Rate )</td>
                  <td>
					<div class="btn-group">
					  <button type="button" class="btn btn-info">Action</button>
					  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#">Edit</a></li>
						<li><a href="#">Remove</a></li>
					  </ul>
					</div>

				  </td>
                </tr>


                <tr>
                  <td><a href="profile.html">Staff B</profile></td>
                  <td>demo@test.com</td>
                  <td>78765778744</td>
                  <td>XYZ</td>
                  <td>Temporary ( Hourly Rate )</td>
                  <td>
					<div class="btn-group">
					  <button type="button" class="btn btn-info">Action</button>
					  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#">Edit</a></li>
						<li><a href="#">Remove</a></li>
					  </ul>
					</div>

				  </td>
                </tr>


                <tr>
                  <td><a href="profile.html">Staff C</profile></td>
                  <td>demo@test.com</td>
                  <td>78765778744</td>
                  <td>XYZ</td>
                  <td>Temporary ( Hourly Rate )</td>
                  <td>
					<div class="btn-group">
					  <button type="button" class="btn btn-info">Action</button>
					  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#">Edit</a></li>
						<li><a href="#" >Remove</a></li>
					  </ul>
					</div>

				  </td>
                </tr>

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

	<!--- ADD NEW Staff POPUP -->
	<div class="modal fade" id="addPopup">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Add New Staff</h4>
		  </div>
            <form role="form">
				<div class="modal-body">
				<!-- BEGIN Form -->
            <div class="row">
              <div class="col-md-6">

                  <div class="form-group">
                    <label for="exampleInputPassword1">Staff Name</label>
                    <input type="text" class="form-control" id="" placeholder=""/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Staff Contact No.</label>
                    <input type="text" class="form-control" id="" placeholder=""/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Staff Picture</label>
                    <input type="file" class=""/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Staff Contact No. ( Home Country )</label>
                    <input type="text" class="form-control" id="" placeholder=""/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Staff Passport No.</label>
                    <input type="text" class="form-control" id="" placeholder="" />
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Passport Copy.</label>
                    <input type="file" class=""/>
                  </div>
                  <div class="form-group">
                    <label for="taxregistrationnumber">Staff Emirates ID No.</label>
                    <input type="text" class="form-control" id="" placeholder=""/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Staff UID No.</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>

              </div>
               <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputFile">Staff Category</label>
                    <select type="text" class="form-control" id="exampleInputFile" placeholder="">
                      <option> Select </option>>
                    </select>
                  </div>
                 <div class="form-group">
                    <label for="exampleInputFile"> Staff SIRA ID No.</label>
                    <input type="text" class="form-control" id="exampleInputFile" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Staff SIRA ID Attachment </label>
                    <input type="file" class=""/>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Passport Expiry date</label>
                    <input type="text" class="form-control datepicker" id="" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Visa Expiry Date</label>
                    <input type="text" class="form-control datepicker" id="" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Nationality</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Height</label>
                    <input type="text" class="form-control" id="exampleInputFile" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Weight</label>
                    <input type="text" class="form-control" id="exampleInputFile" placeholder="">
                  </div>

              </div>
            </div>
				<!-- END Form -->

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success">Save</button>
				</div>
            </form>
		</div>
		<!-- /.modal-content -->
	  </div>
	  <!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
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