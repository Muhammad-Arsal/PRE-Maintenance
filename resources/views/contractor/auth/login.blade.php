@extends('layouts.dashboard.master')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
  .admin_login_btn:hover {
    background: #007bff !important;
    color: white;
    border-color: #007bff !important;
  }
</style>
@endsection
@section('content')
<body class="vertical-layout vertical-menu-modern 1-column   menu-expanded blank-page blank-page"
data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <div class="app-content content">
        <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body">
            <section class="flexbox-container">
              <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="col-md-4 col-10 box-shadow-2 p-0">
                  <div class="card border-grey border-lighten-3 m-0">
                    <div class="card-header border-0">
                      <div class="card-title text-center" style="background: #041e41 !important;">
                        <div class="p-1">
                          <img src="{{ \App\Helper\Helpers::get_logo_url('logo') ? \App\Helper\Helpers::get_logo_url('logo') : asset('/dashboard/images/logo/logo.svg') }}" alt="branding logo" style="max-width:60%">
                        </div>
                      </div>
                      {{-- <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                        <span>Login with Modern</span>
                      </h6> --}}
                    </div>
                    <div class="card-content">
                      <div class="card-body">
                        @if(session()->has('error'))
                        <div class="alert alert-danger mt-3">
                             {{ session()->get('error')  }}
                        </div>
                   @endif
                   @if (Session::has('status'))
                     <p style="text-align: left;" class="text-success">{{ Session::get('status') }}</p>
                   @endif
                        <form class="form-horizontal form-simple" id="loginForm" method="post" action="{{ route('contractorLogin')}}" >
                             @csrf
                            <fieldset class="{{ ($errors->first('email')) ? 'form-error form-group position-relative has-icon-left mb-0' :  'form-group position-relative has-icon-left mb-0' }}">
                            <input type="text" class="{{ ($errors->first('email')) ? 'input-error theme-form-control form-control form-control-lg input-lg mb-0' : 'form-control theme-form-control form-control-lg input-lg mb-0' }}" name="email" id="user-name" placeholder="Your Email Address"
                            required />
                            <div class="form-control-position">
                              <i class="ft-user"></i>
                            </div>
                            @if ($errors->has('email'))
                               <p class="text-danger">{{ $errors->first('email') }}</p>
                            @endif
                          </fieldset>
                          <fieldset class="{{ ($errors->first('passwrod')) ? 'form-error form-group position-relative has-icon-left' : 'form-group position-relative has-icon-left mb-0' }}">
                            <input type="password" class="{{ ($errors->first('password')) ? 'input-error theme-form-control form-control form-control-lg input-lg mb-0' : 'form-control theme-form-control form-control-lg input-lg mb-0' }}" name="password" id="user-password"
                            placeholder="Enter Password" required>
                            <div class="form-control-position">
                              <i class="la la-key"></i>
                            </div>
                            @if ($errors->has('email'))
                             <p class="text-danger">{{ $errors->first('password') }}</p>
                            @endif
                          </fieldset>
                          <div class="form-group row mt-2">
                            <div class="col-md-6 col-12 text-center text-md-left">
                              <fieldset>
                                <input type="checkbox" name="remember" id="remember-me" class="chk-remember theme-color">
                                <label for="remember-me"> Remember Me</label>
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-12 text-center text-md-right"><a href="{{ route('contractor.showResetEmailForm') }}" class="card-link theme-color">Forgot Password?</a></div>
                          </div>
                          <button type="submit" class="theme-btn admin_login_btn btn btn-info btn-lg btn-block"><i class="ft-unlock"></i> Login</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
@endsection

@section('js')
<script src="{{ asset('/dashboard/js/scripts/forms/form-login-register.js') }}" type="text/javascript"></script>
<script>
  @if(Session::has('success'))
    toastr.options =
    {
      "closeButton" : true,
      "progressBar" : true
    }
    toastr.success("{{ session('success') }}");
  @endif
</script>
@endsection
