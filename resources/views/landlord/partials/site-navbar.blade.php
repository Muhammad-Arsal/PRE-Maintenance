<div class="main-menu menu-fixed menu-custom menu-dark menu-accordion menu-shadow expanded" data-scroll-to-active="true">
    <div class="main-menu-content ps-container ps-theme-light ps-active-y" data-ps-id="6e46558a-e67b-6cfc-c980-f1968435a6d0">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="nav-item  {{ Request::segment(2) == 'dashboard' ? 'open' :''}}"><a href="{{ route('landlord.dashboard') }}" ><i class="la la-home"></i><span class="menu-title">Dashboard</span></a>
        </li>
        <li class="nav-item  {{ Request::segment(2) == 'profile' ? 'open' :''}}"><a href="{{route('landlord.settings.landlords.edit', auth('landlord')->id())}}" ><i class="la la-institution"></i><span class="menu-title">Profile</span></a>
        </li>
    </ul>
    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 326px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 24px;"></div></div></div>
  </div>
