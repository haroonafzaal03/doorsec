<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap 3.3.7 -->

    <link rel="stylesheet" href="{{ asset('js/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('js/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('js/bower_components/Ionicons/css/ionicons.min.css') }}">

    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('js/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{ asset('js/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <!--   <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    -->  <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css')}}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{asset('js/bower_components/morris.js/morris.css')}}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{asset('js/bower_components/jvectormap/jquery-jvectormap.css')}}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{asset('js/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('js/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{asset('js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('js/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    <!-- Custom Style.css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/venue_calendar.css') }}">
    <!-- Custom List Box Css -->
    <link rel="stylesheet" href="{{ asset('css/custom_listbox.css') }}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('js/plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <!-- Tags Input -->
    <link rel="stylesheet" href="{{asset('js/plugins/tagsinput/bootstrap-tagsinput.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('js/plugins/iCheck/all.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('js/bower_components/select2/dist/css/select2.min.css')}}">
    <!-- bootstrapValidator -->
    <link rel="stylesheet" href="{{ asset('js/bower_components/bootstrap_validator/css/bootstrapValidator.min.css')}}">
    <!--- izitoast -->
    <link href="{{ asset('css/iziToast.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300,400,600,700,300italic,400italic,600italic">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts
    <link rel="dns-prefet ch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
-->
    <!-- Styles  default
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
</head>
<!-- STYLE FOR Loader -->
@if(session()->get('dashboard') == "guarding")
    <body class="hold-transition skin-green  sidebar-mini {{ Route::current()->getName() }}  sidebar-collapse">
@else
    <body class="hold-transition skin-red sidebar-mini {{ Route::current()->getName() }}  {{ (Route::current()->getName() == 'venue' || Route::current()->getName() == 'event_schedule') ? 'venue_screen  ' : '' }}" style="">
@endif
    <div id="loader"></div>
    <div class="wrapper">

    <!-- Header -->
        @include('layouts.header')
        <!-- header -->
    <!-- Left side column. contains the logo and sidebar -->
        <!-- aside nav-->
            @include('layouts.aside')
        <!-- aside end -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        @include('common.notify')
        <!-- Main content -->
        <div id="app">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
        <b>Version</b> 0.00
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="#">DoorSec</a>.</strong> All rights
        reserved.
    </footer>

        <!--- ALL MODAL COMMON FILE -->
            @include('common.modals')


    <!-- Add the sidebar's background. This div must be placed
        immediately after the control sidebar -->
    </div>
<!-- jQuery 3 -->
<script src="{{asset('js/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('js/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('js/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
<!-- DEMO JS -->
<script src="{{asset('js/demo.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('js/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap  -->
<script src="{{asset('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('js/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('js/bower_components/chart.js/Chart.js')}}"></script>
    <!-- DataTables -->
<script src="{{ asset('js/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('js/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('js/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('js/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{ asset('js/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Custom - listBox -->
<script src="{{ asset('js/jquerylistbox.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!-- TimePicket App -->
<script src="{{asset('js/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!-- TagsInput -->
<script src="{{asset('js/plugins/tagsinput/bootstrap-tagsinput.js')}}"></script>
<!-- iCheck -->
<script src="{{ asset('js/plugins/iCheck/icheck.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('js/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- bootstrapValidator -->
<script src="{{asset('js/bower_components/bootstrap_validator/js/bootstrapValidator.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<!-- izitoast -->
 <script src="{{ asset('js/iziToast.js') }}"></script>
 @include('vendor.lara-izitoast.toast')
<script>

$(document).on({
    ajaxStart: function() {
        loader_open();
       },
     ajaxStop: function() {
        loader_close();
        }

});
    function loader_open(){
        $('.wrapper').addClass('background-blur');
            $('#loader').css('display','block');
    }
    function loader_close(){
        $('.wrapper').removeClass('background-blur');
            $('#loader').css('display','none');
    }
    function loadTimePicker()
    {
        $('.timepicker').timepicker({
        defaultTime: 'value',
        showInputs:true,
        minuteStep: 1,
        disableFocus: true,
        template: 'dropdown',
        showMeridian:false,
        showSeconds: false
        });

        return false;
    }
    setTimeout(() => {
        $(".alert").fadeOut();
    }, 2000);

    $(".number_only").keyup(function(){
        this.value = this.value.replace(/[^0-9\.]/g,'');
        this.value = this.value.replace(/^[0|\.]*/,'');;
    });


    // IMAGE LIGHT BOX  //
    var $lightbox = $('#lightbox');

    $('.img_lightbox').on('click', function(event) {
      $lightbox.find('.modal-dialog').removeAttr('style');
        var $img = $(this).find('img'),
            src = $img.attr('src'),
            alt = $img.attr('alt'),
            css = {
                'maxWidth': '100%',
                'maxHeight': $(window).height() - 100
            };

        $lightbox.find('.close').addClass('hidden');
        $lightbox.find('img').attr('src', src);
        $lightbox.find('img').attr('alt', alt);
        $lightbox.find('img').css(css);


          $lightbox.modal();

    });

    $lightbox.on('shown.bs.modal', function (e) {
        var $img = $lightbox.find('img');

        $lightbox.find('.modal-dialog').css({'width': $img.width()});
        $lightbox.find('.close').removeClass('hidden');
    });




    $(".datepicker").datepicker();

    $(document).on('focus click tap','input, textarea',function(){
        $(this).attr("autocomplete",'off');
    });




//LOCATION SEARCH FUNCTIONALITY
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('#keyword');

//on keyup, start the countdown
$input.on('keyup focus', function (ev) {
 // console.log(ev.event)
clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$input.on('keydown', function () {
clearTimeout(typingTimer);
});

  function doneTyping ()
  {

      if($('#keyword').val() != '')
      {
        var icon;
        $('#keyword_result_dropdown').css('display','none');
        $('#keyword_result_dropdown ul').html('');


          $.ajaxSetup({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });


          var myJsonData = {keyword : $('#keyword').val()};


          $.post('/getLocations', myJsonData, function(response) {
              var obj = JSON.parse(response);

              if(obj.length != 0)
                  {
                      $('#keyword_result_dropdown').css('display','block');
                      $('#keyword_result_dropdown ul').html('');

                      $.each(obj,function(i,v){
                        icon = 'fa-map-marker';
                          $('#keyword_result_dropdown ul').append('<li  class="" onclick="selectOption(\''+v.location_name+'\');"><span class="fa '+ icon +'"></span><div  class="typeahead-value ">'+ v.location_name +'</div></li>');
                      });
            }


          });

      } // END IF


  } // fUNCTION doneTyping CLOSED

  $(".select2").select2();
 // jQuery Ends...



function reInitializeSelect2(id)
{
  $("#"+id).select2('destroy');
  $("#"+id).select2('');
}


function goBack() {
  window.history.back();
}

function selectOption(option_value){
    $('#keyword').val(option_value);
    $('#keyword_result_dropdown').css('display','none');
}


function getStaffList(staff_type_id)
{
    if(staff_type_id > 0)
    {
        var myJsonData = {staff_type_id: staff_type_id , is_option : true};

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.post('/getStaffByJSON', myJsonData, function(response) {
        var obj = JSON.parse(response);

            if(obj.response)
            {
                    $("select#staff_id").html(obj.response);
                    $(".image_select2").select2({
                        templateResult: tmpResultFormat,
                        templateSelection: tmpSelectionFormat
                    });

            }
        });
    }
    else
    {
        $("select#staff_id").empty();
        $("select#staff_id").html('<option value=""> Select </option>');
    }

    return false;
}


function getClientsList(client_type_id)
{
    if(client_type_id > 0)
    {
        var myJsonData = {type_id: client_type_id , is_option : true};

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.post('/get_event_venue_list', myJsonData, function(response) {
        var obj = JSON.parse(response);

            if(obj.response)
            {
                    $("select#event_venue_id").html(obj.response);
                    $("select#event_venue_id").select2();
            }
        });
    }
    else
    {
        $("select#event_venue_id").empty();
        $("select#event_venue_id").html('<option value=""> Select </option>');
    }

    return false;
}



function tmpResultFormat(opt)
{
    if(!opt.id)
    {
        return opt.text;
    }

    var option_image = $(opt.element).attr('data-image');
    if(!option_image)
    {
        return opt.text;
    }
    else
    {
        var $opt = $(
           '<span><img class="" width="50px" src="' + option_image + '"  /> ' + opt.text + '</span>'
        );
        return $opt;

    }
}

function tmpSelectionFormat(opt){
    if(!opt.id)
    {
        return opt.text;
    }

    var option_image = $(opt.element).attr('data-image');
    if(!option_image)
    {
        return opt.text;
    }
    else
    {
        var $opt = $(
           '<span><img class="" width="25px" style="height:25px;vertical-align:top" src="' + option_image + '"  /> ' + opt.text.toUpperCase() + '</span>'
        );
        return $opt;

    }
}

</script>

@yield('content_js')
</body>
</html>
