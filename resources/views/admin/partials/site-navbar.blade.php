<div class="main-menu menu-fixed menu-custom menu-dark menu-accordion menu-shadow expanded" data-scroll-to-active="true">
    <div class="main-menu-content ps-container ps-theme-light ps-active-y" data-ps-id="6e46558a-e67b-6cfc-c980-f1968435a6d0">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="nav-item  {{ Request::segment(2) == 'dashboard' ? 'open' :''}}"><a href="{{ route('admin.dashboard') }}" ><i class="la la-home"></i><span class="menu-title">Dashboard</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'landlords' ? 'open' :''}}"><a href="{{route('admin.settings.landlords')}}" ><i class="la la-institution"></i><span class="menu-title">Landlords</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'tenants' ? 'open' :''}}"><a href="{{route('admin.settings.tenants')}}" ><i class="la la-user"></i><span class="menu-title">Tenants</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'contractors' ? 'open' :''}}"><a href="{{route('admin.settings.contractors')}}" ><i class="la la-user-secret"></i><span class="menu-title">Contractors</span></a>
        <li class="nav-item  {{ Request::segment(2) == 'properties' ? 'open' :''}}"><a href="{{route('admin.properties')}}" ><i class="la la-building"></i><span class="menu-title">Properties</span></a>
        </li>
        <li class="nav-item {{ Request::segment(2) == 'accounts' ? 'open' : '' }}">
          <a href="{{route('admin.invoices')}}">
              <i class="la la-calculator"></i>
              <span class="menu-title">Accounts</span>
          </a>
      </li>
      
      <li class="nav-item {{ Request::segment(2) == 'jobs' ? 'open' : '' }}">
        <a href="{{route('admin.jobs')}}">
            <i class="la la-briefcase"></i>
            <span class="menu-title">Jobs</span>
        </a>
      </li>  
      <li class="nav-item">
        <a class="{{ Request::segment(2) == 'tasks' ? 'active' :''}}" href="{{ route('admin.tasks') }}" >
          <i class="la la-tasks"></i>
          <span class="menu-title">Tasks</span>
          @php 
            use Carbon\Carbon;
            $countDueTodayOrOlderTasks = DB::table('tasks')
            ->where('status', 'not_completed')
            ->where('priority', 1)
            ->where('due_date', '<=', Carbon::today()->toDateString())
            ->count();
          @endphp
          @if ($countDueTodayOrOlderTasks > 0)
            <span class="task-count">{{ $countDueTodayOrOlderTasks }}</span>
          @endif
        </a>
      </li>  
      <li class="nav-item"><a class="{{ Request::segment(2) == 'diary' ? 'active' :''}}" href="{{ route('admin.diary') }}" ><i class="la la-calendar"></i><span class="menu-title">Diary</span></a>
      </li>  
      <li class="nav-item has-sub {{ Request::segment(2) == 'settings' ? 'open' :''}}"><a href="javascript:void(0)"><i class="la la-cog"></i><span class="menu-title">Settings</span>
        <ul class="menu-content">
          <li class=""><a class="menu-item {{ Request::segment(3) == 'general' ? 'active' :''}}" href="{{ route('admin.settings.general.create') }}">General Settings</a></li>
          <li class=""><a class="menu-item {{ Request::segment(3) == 'admins' ? 'active' :''}}" href="{{ route('admin.settings.admins') }}">Admins</a></li>
          <li class=""><a class="menu-item {{ Request::segment(3) == 'emailTemplate' ? 'active' :''}}" href="{{ route('admin.emailTemplate.index') }}">Email Templates</a></li>
          <li class=""><a class="menu-item {{ Request::segment(3) == 'themeOptions' ? 'active' :''}}" href="{{ route('admin.settings.themeOptions') }}">Theme Options</a></li>
          <li class=""><a class="menu-item {{ Request::segment(3) == 'propertyType' ? 'active' :''}}" href="{{ route('admin.settings.propertyType') }}">Property Types</a></li>
          <li class=""><a class="menu-item {{ Request::segment(3) == 'contractorType' ? 'active' :''}}" href="{{ route('admin.settings.contractorType') }}">Contractor Types</a></li>
          <li class="">
            <a class="menu-item {{ (Request::segment(3) == 'task-tray') ? 'active' :''}}" href="{{ route('admin.settings.taskTray') }}">Task Tray</a>
          </li>
          <li class=""><a class="menu-item {{ Request::segment(3) == 'event' ? 'active' :''}}" href="{{ route('admin.settings.event-type') }}">Event Type</a>
          </li>
        </ul>
        </a>
      </li>
    </ul>
    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 326px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 24px;"></div></div></div>
  </div>
