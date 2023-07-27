@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="login-box">
    <div class="login-logo logo-lg">
     <img  style="width:50%;height:30%" src="{{asset('img/Logo.jpg')}}"/>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group has-feedback">
         <input id="email" type="email"  placeholder="Email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
          <span class="glyphicon glyphicon-whatsapp form-control-feedback"></span>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"  placeholder="password"  name="password" required autocomplete="current-password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="row">
          <div class="col-xs-6">
            <div class="checkbox">
              <label>
              <input class="form-check-input" type="checkbox"  name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-6 hide">
            <div class="checkbox">
              <a href="#" type="button" class="">
                Guarding / Club Event
              </a>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-12">
            <button type="submit"class="btn btn-primary btn-block btn-flat">Sign In</button>

          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

    </div>
</div>

<div class="modal fade transparent" id="main_screen_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg full_screen">
      <div class="modal-content transparent full_screen">
        <div class="modal-header transparent">
          <button type="button" class="close xl" data-dismiss="modal" aria-label="Close">
            <span class="visibility-hidden pointer_event-disabled" aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"> </h4>
        </div>
        <div class="modal-body">
          <div class="row row-eq-height radio main_category_boxes_row">
            <label data-url="club_event/index.html" class="col-md-offset-1 col-md-4 club_event category_boxes"
              id="club_event_lbl" for="club_event_id">
              <img src="{{ asset('img/club_event.svg')}}" id="" class="" />
              <span class="title">Club Events</span>
              <input type="radio" id="club_event_id" name="category_checkbox" class="club_event_radio_btn	hide" />
            </label>
            <label data-url="guarding/index.html" class="col-md-4 col-md-offset-2 club_event category_boxes"
              id=" lbl" for=" id">
              <img src="{{asset('img/guarding.svg')}}" id="" class="" />
              <span class="title">Guarding </span>
              <input type="radio" id=" id" name="category_checkbox" class=" radio_btn	hide" />
            </label>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
@endsection
