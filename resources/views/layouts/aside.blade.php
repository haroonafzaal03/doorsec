 <aside class="main-sidebar">
     <!-- sidebar: style can be found in sidebar.less -->
     <section class="sidebar">
         <!-- Sidebar user panel -->
         <div class="user-panel">
             <div class="pull-left image">
                 <img src="{{asset('img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
             </div>
             <div class="pull-left info">
                 <p> {{ Auth::user()->name }}</p>
                 <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
             </div>
         </div>
         <!-- sidebar menu: : style can be found in sidebar.less -->
         <ul class="sidebar-menu" data-widget="tree">
             @php
             $active_view = Route::current()->getName();
             @endphp

             @if($active_view == 'home')
             <li class="active">
                 @else
             <li class="">
                 @endif
                 @if(session()->get('dashboard') == "guarding")
                 <a href="{{ Route('guarding')}}">
                     @else
                     <a href="{{ Route('club_event')}}">
                         @endif
                         <i class="fa fa-dashboard"></i> <span class=""
                             style="text-transform:Capitalize;">{{session()->get('dashboard')}} Dashboard</span>
                         <span class="pull-right-container">
                         </span>
                     </a>
             </li>
             @if($active_view == "client" || $active_view == "client_create" || $active_view == "client_store"||
             $active_view == "client_show"|| $active_view == "event" || $active_view == "event_create" || $active_view
             == "edit_event" || $active_view == "event_show" || $active_view == "event_edit" || $active_view ==
             "staff_event_schedule" || $active_view == "schedule_event_details")
             <li class="treeview menu-open active hide ">
                 @else
             <li class="treeview hide">
                 @endif
                 <a href="#">
                     <i class="fa fa-users"></i> <span>Customers</span>
                     <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                     </span>
                 </a>
                 <ul class="treeview-menu">
                     @if($active_view == "client" || $active_view == "client_create" || $active_view == "client_store"||
                     $active_view == "client_show")
                     <li class="active">
                         @else
                     <li class="">
                         @endif
                         <a href="{{url('/client')}}">
                             <i class="fa fa-users"></i> <span>Client</span>
                             <span class="pull-right-container">
                             </span>
                         </a>
                     </li>


                     @if($active_view == "event" || $active_view == "event_create" || $active_view == "edit_event" ||
                     $active_view == "event_show" || $active_view == "event_edit" || $active_view ==
                     "staff_event_schedule" || $active_view == "schedule_event_details")
                     <li class="active">
                         @else
                     <li class="">
                         @endif
                         <a href="{{route('event')}}"><i class="fa fa-circle-o"></i> Events</a>
                     </li>


                     @if($active_view == "venue" || $active_view == "venue_create" || $active_view == "edit_venue" ||
                     $active_view == "venue_show" || $active_view == "venue_edit" || $active_view ==
                     "staff_venue_schedule" || $active_view == "schedule_venue_details")
                     <li class="active hide ">
                         @else
                     <li class=" hide ">
                         @endif
                         <a href="{{route('venue')}}"><i class="fa fa-circle-o"></i> Venue</a></li>
                 </ul>
             </li>
             {{--@if($active_view == "client" || $active_view == "client_create" || $active_view == "client_store"|| $active_view == "client_show")
        <li class="active">
        @else
        <li class="">
        @endif
            <a href="{{url('/client')}}">
             <i class="fa fa-users"></i> <span>Client</span>
             <span class="pull-right-container">
             </span>
             </a>
             </li>--}}

             @hasrole('staff')
             @if($active_view == "staff" || $active_view == "staff_create" || $active_view == "staff_store" ||
             $active_view == "staff_show")
             <li class="active">
                 @else
             <li class="">
                 @endif
                 <a href="{{route('staff')}}">
                     <i class="fa fa-users"></i> <span>Staff</span>
                     <span class="pull-right-container">
                     </span>
                 </a>
             </li>
             @endhasrole
             @if($active_view == "client" || $active_view == "client_create" || $active_view == "client_store"||
             $active_view == "client_show")
             <li class="active">
                 @else
             <li class="">
                 @endif
                 <a href="{{url('/client')}}">
                     <i class="fa fa-users"></i> <span>Client</span>
                     <span class="pull-right-container">
                     </span>
                 </a>
             </li>
             @if(session()->get('dashboard') == 'club_events')

             @if($active_view == "event" || $active_view == "venue" || $active_view == "event_schedule" || $active_view
             == "event" || $active_view == "event_create" || $active_view == "edit_event" || $active_view ==
             "event_details" || $active_view == "event_edit" || $active_view == "event_schedule" || $active_view ==
             "event_logs" || $active_view =="venue_detail" || $active_view == "venue_sendsms")
             <li class="treeview menu-open active">
                 @else
             <li class="treeview">
                 @endif
                 <a href="#">
                     <i class="fa fa-calendar"></i> <span>Schedule</span>
                     <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                     </span>
                 </a>
                 <ul class="treeview-menu">

                     @if($active_view == "event" || $active_view == "event_create" || $active_view == "edit_event" ||
                     $active_view == "event_details" || $active_view == "event_edit" || $active_view == "event_schedule"
                     || $active_view == "event_logs" )
                     <li class="active treeview">
                         @else
                     <li class="treeview">
                         @endif
                         <a href="#"><i class="fa fa-calendar-o"></i> Events
                             <span class="pull-right-container">
                                 <i class="fa fa-angle-left pull-right"></i>
                             </span>
                         </a>
                         <ul class="treeview-menu">
                             @if($active_view == "event" || $active_view == "event_create" || $active_view ==
                             "edit_event" || $active_view == "event_details" || $active_view == "event_edit" )
                             <li class="active">
                                 @else
                             <li class="">
                                 @endif
                                 <a href="{{route('event')}}"><i class="fa fa-circle-o"></i> Add Events Details </a>
                             </li>
                             @hasrole('schedule.event.view')
                             @if($active_view == "event_schedule")
                             <li class="active">
                                 @else
                             <li class="">
                                 @endif
                                 <a href="{{route('event_schedule')}}"><i class="fa fa-circle-o"></i> View Events
                                     Schedule</a>
                             </li>
                             @endhasrole
                         </ul>
                     </li>



                     @hasrole('schedule.venue.view')
                     @if($active_view == "venue" || $active_view =="venue_detail" || $active_view == "venue_sendsms")
                     <li class=" treeview active">
                         @else
                     <li class="treeview ">
                         @endif
                         <a href="#"><i class="fa fa-calendar-o"></i> Venue
                             <span class="pull-right-container">
                                 <i class="fa fa-angle-left pull-right"></i>
                             </span>
                         </a>
                         <ul class="treeview-menu">
                             @if($active_view == "venue")
                             <li class="active">
                                 @else
                             <li class="">
                                 @endif
                                 <a href="{{route('venue')}}"><i class="fa fa-circle-o"></i> Venue</a>
                             </li>
                             @if($active_view == "venue_detail" || $active_view == "venue_sendsms")
                             <li class="active">
                                 @else
                             <li class="">
                                 @endif
                                 <a href="{{route('venue_detail')}}"><i class="fa fa-circle-o"></i>View Venues
                                     Schedule</a>
                             </li>
                         </ul>
                     </li>
             </li>
             @endhasrole
         </ul>
         </li>
         @else

         @if($active_view == "guarding_create" || $active_view == "guarding_schedule" || $active_view ==
         "guarding_view")
         <li class="active treeview">
             @else
         <li class="treeview">
             @endif
             <a href="">
                 <i class="fa fa-calendar"></i> <span>Schedule</span>
                 <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i>
                 </span>


             </a>
             <ul class="treeview-menu">
                 @hasrole('guarding')

                 @if($active_view == "event" || $active_view == "guarding_create" || $active_view == "event_details"
                 || $active_view == "event_edit" )
                 <li class="active">
                     @else
                 <li class="">
                     @endif

                     <a href="{{route('guarding_create')}}"><i class="fa fa-circle-o"></i> Add Guarding Details </a>
                 </li>
                 @endhasrole
                 @hasrole('guard.schedule')
                 @if($active_view == "guarding_schedule")
                 <li class="active">
                     @else
                 <li class="">
                     @endif
                     <a href="{{route('guarding_view')}}"><i class="fa fa-circle-o"></i> View Guarding Schedule</a>
                 </li>
                 @endhasrole
             </ul>
         </li>

         @endif
         <!--- Dashboard Check --->





         @if($active_view == "payroll")
         <li class="active">
             @else
         <li class="">
             @endif
             <a href="{{route('payroll')}}">
                 <i class="fa fa-money"></i> <span>Pay Roll</span>
                 <span class="pull-right-container">
                 </span>
             </a>
         </li>

         @if($active_view == "attendance")
         <li class="active">
             @else
         <li class="">
             @endif
             <a href="{{route('attendance')}}">
                 <i class="fa fa-clock-o"></i> <span>Attendance</span>
                 <span class="pull-right-container">
                 </span>
             </a>
         </li>
         @hasrole('generate.pdf')
         <li class="">
             <a href="#">
                 <i class="fa  fa-file-excel-o"></i> <span>Reports</span>
             </a>
         </li>
         <!--- Dashboard Check --->
         @endhasrole
         @if($active_view == "whatsapp_module")
         <li class="active">
             @else
         <li class="">
             @endif
             <a href="{{route('whatsapp_module')}}">
                 <i class="fa  fa-whatsapp"></i> <span>Whatsapp Messages </span>
             </a>
         </li>
         </ul>
     </section>
     <!-- /.sidebar -->
 </aside>