<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
      <div class="navbar-header expanded" style="background-color:#ffffff ">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs is-active" href="#"><i class="ft-menu font-large-1"></i></a></li>
          <li style="margin-left: 0.25em;" class="nav-item mr-auto">
            <a class="navbar-brand" href="{{ url('/')}}">
              <img class="" style="max-width:150px" alt="modern contractor logo" src="{{ !empty(\App\Helper\Helpers::get_logo_url('logo')) ? \App\Helper\Helpers::get_logo_url('logo') : asset('/dashboard/images/logo/logo.svg') }}">
            </a>
          </li>
          <li class="nav-item d-none d-md-block float-right"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3" data-ticon="ft-toggle-right"></i></a></li>
          <li class="nav-item d-md-none">
            <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
          </li>
        </ul>
      </div>
      <div class="navbar-container content">
        <div class="collapse navbar-collapse" id="navbar-mobile">
          <ul class="nav navbar-nav mr-auto float-left">
            <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>

          </ul>

          <ul class="nav navbar-nav float-right">
            <li class="dropdown dropdown-user nav-item">
                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                    <i class="fa fa-comment" style="color: #041e41; padding-top: 0.5em;"></i>
                      {{-- <span class="badge badge-pill" style="position: absolute; top: 10px; right: 5px; background-color: red; color: white;">0</span> --}}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" style="white-space: normal;">There are no new comments</a>
                </div>
            </li>
          </ul>

          <ul class="nav navbar-nav float-right">
            <li class="dropdown dropdown-user nav-item">
                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                    <i class="fa fa-bell" style="color: #041e41; padding-top: 0.5em;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="min-width: 15rem;">
                    <a class="dropdown-item" href="#" style="white-space: normal;">There are no new notifications</a>
                </div>
            </li>
          </ul>


          <ul class="nav navbar-nav float-right">
            <li class="dropdown dropdown-user nav-item">
              <a class="dropdown-toggle nav-link dropdown-user-link welcome_text_portal" href="#" data-toggle="dropdown">
                <span class="mr-1">Hello,
                  <span class="user-name text-bold-700">{{ Auth::guard('contractor')->user()->name }} </span>
                </span>
                <span class="avatar avatar-online">
                  <img src="{{ isset(Auth::guard('contractor')->user()->profile->profile_image) ? asset('uploads/contractor-' . Auth::guard('contractor')->user()->id . '/' . Auth::guard('contractor')->user()->profile->profile_image) : asset('/dashboard/images/avatar.png') }}" alt="avatar"><i></i></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{{ route('contractor.profile') }}"><i class="ft-user"></i> Edit Profile</a>

                <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ route('contractor.logout') }}"><i class="ft-power"></i> Logout</a>
              </div>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </nav>
