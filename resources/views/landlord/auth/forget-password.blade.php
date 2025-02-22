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
                                                style="max-width:60%">
                                        </div>
                                        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                            <span>We will send you a link to reset password.</span>
                                        </h6>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <form class="form-horizontal" id="forgetPasswordForm" method="post"
                                                action="{{ route('landlord.forgetSendEmail') }}">
                                                @csrf
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="email" name="email"
                                                        class="form-control theme-form-control form-control-lg input-lg mb-0" id="email"
                                                        placeholder="Your Email Address" style="margin-bottom:0 !important" required />
                                                    <div class="form-control-position">
                                                        <i class="ft-mail"></i>
                                                    </div>
                                                </fieldset>
                                                <button type="submit" class="theme-btn-outline btn btn-outline-info btn-lg btn-block"><i
                                                        class="ft-unlock"></i> Recover Password</button>

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
                                    <div class="card-footer border-0" style="padding-top: 0px !important;">
                                        <a href="{{ route('landlord.login') }}" class="card-link theme-color float-sm-left text-center btn btn-warning  btn-block btn-lg " style="color: white !important; "> Login</a>
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
        <script type="text/javascript">
            $(function() {
                $('#forgetPasswordForm').validate({
                    rules: {
                        email: {
                            required: true,
                            email: true,
                        },
                    },
                    messages: {
                        email: 'The email field is required',
                    }

                });
                $('input').on('focusout keyup', function() {
                    $(this).valid();
                });
            });
        </script>
    @endsection
