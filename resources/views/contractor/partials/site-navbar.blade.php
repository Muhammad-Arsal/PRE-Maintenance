<div class="main-menu menu-fixed menu-custom menu-dark menu-accordion menu-shadow expanded" data-scroll-to-active="true">
    <div class="main-menu-content ps-container ps-theme-light ps-active-y" data-ps-id="6e46558a-e67b-6cfc-c980-f1968435a6d0">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="nav-item  {{ Request::segment(2) == 'overview' ? 'open' :''}}"><a href="{{ route('contractor.settings.contractors.edit', auth('contractor')->user()->id) }}" ><i class="la la-home"></i><span class="menu-title">Overview</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'account' ? 'open' :''}}"><a href="{{ route('contractor.invoices') }}" ><i class="la la-file"></i><span class="menu-title">Account</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'address' ? 'open' :''}}"><a href="{{ route('contractor.settings.contractors.edit.address', auth('contractor')->user()->id) }}" ><i class="la la-map-marker"></i><span class="menu-title">Address</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'jobs' ? 'open' :''}}"><a href="{{ route('contractor.contractors.viewjobs', auth('contractor')->user()->id) }}" ><i class="la la-suitcase"></i><span class="menu-title">Jobs</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'correspondence' ? 'open' :''}}"><a href="{{ route('contractor.contractors.correspondence', auth('contractor')->user()->id) }}" ><i class="la la-envelope"></i><span class="menu-title">Correspondence</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'diary' ? 'open' :''}}"><a href="{{ route('contractor.calendar', auth('contractor')->user()->id) }}" ><i class="la la-book"></i><span class="menu-title">Diary</span></a>
        </li>
    </ul>
    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 326px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 24px;"></div></div></div>
  </div>
