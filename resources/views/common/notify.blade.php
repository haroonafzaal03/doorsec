@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Session::has('flash_error'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('flash_error') }}
    </div>
@endif


@if(Session::has('flash_success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('flash_success') }}
    </div>
@endif


<div class="row text-right goBackDiv">
    <div class="col-md-12 p-r-30">
		<button class="btn btn-primary btn-sm m-t-20 m-l-10 " id="screen_back_btn" onclick="goBack()"> <i class="fa fa-arrow-left m-r-10"></i>Go Back</button>
	</div>
</div>

