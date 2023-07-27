<header class="main-header">
            <!-- Logo -->
            <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src="{{asset('img/min-logo.png')}}"/></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="{{asset('img/doorsec.png')}}"/></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only"> </span>
            </a>
            <h3  class="dashboard_heading" id=""> {{session()->get('dash_display')}}  </h3>
            <div class="navbar-custom-menu ">
                <ul class="nav navbar-nav">
                    @hasrole('guarding')
                    <li class="">
                        <form class="" id="switchBoardForm" action="{{ Route('switch_board')}}" METHOD="POST">
                            @csrf
                            <input type="hidden" value="{{ Request::url() }}" name="redirect_url"/>
                            @if(session()->get('dashboard') == "guarding" )
                                <button type="submit" name="club_events"  value="1"  class="btn btn-danger btn-lg" style="background:#dd4b39 !important;font-weight:bold !Important;letter-spacing:1px;padding: 12px;border-color: #00a65a;+" id=""> Switch to Club & Events  </button>
                            @else
                                <button type="submit" name="guarding"  value="1" class="btn btn-danger btn-lg" style="background:#00a65a !important;font-weight:bold !Important;letter-spacing:1px;padding: 12px;border-color: #00a65a;" id=""> Switch to Guarding  </button>
                            @endif

                        </form>
                     </li>
                     @endhasrole
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                    <span class="hidden-xs"> {{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{ asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                        <p>
                        {{ Auth::user()->name }}
                        <small>Member since Nov. 2019</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-body hide">
                        <div class="row">
                        <div class="col-xs-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-xs-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-xs-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                        </div>
                        <!-- /.row -->
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left hide">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div>

                        </div>
                        <div class="pull-right">
                            @hasrole('staff')
                                <a href="{{ route('userrights') }}" style="margin: 0px 0px 0px 0px"><button class="dropdown-item btn btn-primary ">User</button></a>
                                <a href="{{ route('rolesrights') }}" style="margin: 0px 60px 0px 0px"><button class="dropdown-item btn btn-warning ">Roles</button></a>
                             @endhasrole
                        <a class="dropdown-item  btn btn-default" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                        </div>
                    </li>
                    </ul>
                </li>
                </ul>
            </div>
            </nav>
        </header>