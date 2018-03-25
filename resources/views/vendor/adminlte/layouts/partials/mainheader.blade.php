<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>VR</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="https://www.vicroads.vic.gov.au/~/media/vicroads/header/vicroadslogo.png" alt="vicroads logo" style="width:140px; margin-right:15%; margin-bottom:8px;">
        </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('adminlte_lang::message.togglenav') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown dasboard" id="dashboard">
                    <a href="{{ url('/home') }}" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ trans('adminlte_lang::message.dashboard') }}"><i class="fa fa-dashboard"></i>&nbsp;&nbsp;{{ trans('adminlte_lang::message.dashboard') }}</a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}" id="logout"
                       onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        {{ trans('adminlte_lang::message.signout') }}
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        <input type="submit" value="logout" style="display: none;">
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</header>
