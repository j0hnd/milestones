<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Gravatar::get($user->email) }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p> <a href="javascript:void(0)" class="toggle-user-profile">{{ Auth::user()->name }}</a> <br/> </p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('adminlte_lang::message.online') }}</a>
                </div>
            </div>
        @endif

        <!-- search form (Optional) -->
        <!--
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('adminlte_lang::message.search') }}..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        -->
        <!-- /.search form -->

        @php
            $current_page = explode('/', url()->current());
            $last = end($current_page);
            $key = key($current_page);
            $page = $current_page[$key]
        @endphp

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item header ">{{ trans('adminlte_lang::message.mainnavheader') }}</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="sidebar-menu-item {{ $page == 'home' ? 'active' : '' }}"><a href="{{ url('home') }}"><i class='fa fa-dashboard'></i> <span>{{ trans('adminlte_lang::message.dashboard') }}</span></a></li>
            <li class="sidebar-menu-item {{ $page == 'projects' ? 'active' : '' }}"><a href="{{ url('projects') }}"><i class='fa fa-folder'></i> <span>{{ trans('adminlte_lang::message.projects') }}</span></a></li>
            <li class="sidebar-menu-item {{ $page == 'myteam' ? 'active' : '' }}"><a href="{{ url('myteam') }}"><i class='fa fa-users'></i> <span>{{ trans('adminlte_lang::message.myteam1') }}</span></a></li>

            @if (session('is_admin'))
            <li class="sidebar-menu-item header"><i class="fa fa-gears"></i>&nbsp;&nbsp;{{ trans('adminlte_lang::message.settingsheader') }}</li>
            <li class="sidebar-menu-item {{ $page == 'roles' ? 'active' : '' }}"><a href="{{ url('/user/roles') }}"><i class='fa fa-gear'></i> <span>{{ trans('adminlte_lang::message.userroles') }}</span></a></li>
            <li class="sidebar-menu-item {{ $page == 'types' ? 'active' : '' }}"><a href="{{ url('/project/types') }}"><i class='fa fa-gear'></i> <span>{{ trans('adminlte_lang::message.projecttypes') }}</span></a></li>
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
