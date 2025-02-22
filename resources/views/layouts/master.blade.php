<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PRE Maintenance</title>

    @yield('pages-meta')

    <link rel="apple-touch-icon" href="{{ \App\Helper\Helpers::get_logo_url('favicon') }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Helper\Helpers::get_logo_url('favicon') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
        rel="stylesheet">

    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">

    <!-- BEGIN VENDOR CSS-->

    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/vendors.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/app.css') }}">
    @yield('css')

</head>

@yield('content')
@yield('js')
</body>
</html>
