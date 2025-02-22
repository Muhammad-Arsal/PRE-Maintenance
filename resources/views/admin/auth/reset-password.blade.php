@extends('layouts.dashboard.master')
@section('wrapper')
<body class="vertical-layout vertical-menu-modern 1-column   menu-expanded blank-page blank-page" data-open="click"
        data-menu="vertical-menu-modern" data-col="1-column">
        <div class="app-content content">
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <section class="flexbox-container">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="col-md-4 col-10 box-shadow-2 p-0">
                                <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                                    <div class="card-header border-0 pb-0">
                                        <div class="card-title text-center" style="background: #041e41 !important;">
                                            <img src="{{ !empty(\App\Helper\Helpers::get_logo_url('logo')) ? \App\Helper\Helpers::get_logo_url('logo') : asset('/dashboard/images/logo/logo.svg') }}" alt="branding logo"
                                                style="max-width:100%">
                                        </div>
                                    </div>
                                    <div class="card-content mt-2">
                                        <div class="card-body">
                                            <form class="form-horizontal" id="resetForm" method="post"
                                                action="{{ route('admin.password.update') }}">
                                                @csrf
                                                <input type="hidden" name="email" value={{ $email }}>
                                                <input type="hidden" name="token" value="{{ $token }}">
                                                <fieldset class="form-group position-relative">
                                                    <input type="password" name="password"
                                                        class="form-control form-control-lg input-lg mb-0" id="password"
                                                        placeholder="New password" style="margin-bottom:0 !important" required />

                                                        @error('password')
                                                           <p style="text-align: left" class="text-danger"> {{ $message }} </p>
                                                        @enderror

                                                </fieldset>
                                                <fieldset class="form-group position-relative">
                                                    <input type="password" name="password_confirmation"
                                                        class="form-control theme-form-control form-control-lg input-lg mb-0" id="password_confirmation"
                                                        placeholder="Confirm Password" style="margin-bottom:0 !important" name="password_confirmation" autocomplete="new-password" required />

                                                </fieldset>
                                                <button type="submit" class="theme-btn-outline btn btn-outline-info btn-lg btn-block"><i
                                                        class="ft-unlock"></i> Reset Password</button>

                                                @error('email')
                                                    <p style="text-align: left;" class="text-danger"> {{ $message }}
                                                    </p>
                                                @enderror

                                                @if (Session::has('status'))
                                                    <p style="text-align: left;" class="text-success mt-1">
                                                        {{ Session::get('status') }}</p>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-footer border-0">
                                        <p class="float-sm-left text-center btn btn-warning  btn-block btn-lg "><a href="{{ route('admin.login') }}" class="card-link theme-color" style="color: white !important; "> Login</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
{{--
    <div class="theme-layout gray-bg">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <div class="forget-password">
                        <figure class="logo"><a href="{{ url('/')}}"><img src="{{ \App\Helper\Helpers::get_logo_url('logo') }}" alt=""/></a></figure>
                        <div class="pass-form">
                            <h4>Request a Password Reset</h4>
                            <form id="resetForm" method="post" action="{{ route('admin.password.update') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="email" value={{ $email }}>
                                </div>
                                <div class="form-group">
                                    <input type="password" id="password" name="password" placeholder="New Password">
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    @error('password')
                                        <p style="text-align: left" class="text-danger"> {{ $message }} </p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password_confirmation" type="password" placeholder="Enter password" name="password_confirmation" autocomplete="new-password">
                                </div>

                                @error('email')
                                    <p style="text-align: left" class="text-danger"> {{ $message }} </p>
                                @enderror

                                @if (Session::has('status'))
                                    <p class="text-success">{{ Session::get('status') }}</p>
                                @endif
                                <button class="main-btn" type="submit">Reset Password</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <figure class="bottom-mockup"><img alt="" src="{{ asset('/dashboard/images/footer.png') }}"></figure>
        <div class="bottombar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <span class="">&copy; Copyright All rights reserved by Flex TV {{ date('Y')}}</span>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}
@endsection
@section('js')
<script type="text/javascript">
    $(function(){
         $('#resetForm').validate({
             rules:{
                 password:{
                     required:true,
                     minlength:6,
                 },
                 password_confirmation:{
                     required:true,
                     minlength:6,
                     equalTo:'#password'
                 }
             },
             messages:{
                 password:{
                    required: "The password field is required",
					minlength: "Your password must be at least 6 characters long"
                 },
                 password_confirmation: {
					required: "The Confirm password field is required",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Password do not match."
				},
             }

         });
         $('input').on('focusout keyup', function () {
            $(this).valid();
         });
    });
</script>
@endsection
