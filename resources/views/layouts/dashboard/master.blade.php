<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($page) && isset($page['page_title'])) <title>{{ $page['page_title'] }} | PRE Maintenance</title>
    @else  @yield('title') @endif

    @yield('pages-meta')

    <link rel="apple-touch-icon" href="{{ \App\Helper\Helpers::get_logo_url('favicon') }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Helper\Helpers::get_logo_url('favicon') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
    rel="stylesheet">
    {{-- <link href="{{ asset('/dashboard/fonts/line-awesome/css/line-awesome.min.css')}}" type="text/css" rel="stylesheet"/> --}}
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css"
    rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/vendors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/forms/icheck/icheck.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/forms/icheck/custom.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/app.css') }}">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/core/menu/menu-types/vertical-menu-modern.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/plugins/pickers/daterange/daterange.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/pages/login-register.css') }}">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/custom.css') }}" />

    @yield('css')
</head>
    @if(Request::path() === 'admin' || Request::path() == 'landlord' || Request::path() == 'contractor' || Request::path() == 'tenant' )
        @yield('content')
    @else
        @yield('wrapper')
    @endif

    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('/dashboard/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('/assets/plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('/dashboard/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/forms/validation/jqBootstrapValidation.js') }}"
    type="text/javascript"></script>
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
        </script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN MODERN JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('/dashboard/js/core/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/js/core/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/js/custom.js') }}" type="text/javascript"></script>
    <!-- END MODERN JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!-- END PAGE LEVEL JS-->

    @yield('js')
</body>
</html>
