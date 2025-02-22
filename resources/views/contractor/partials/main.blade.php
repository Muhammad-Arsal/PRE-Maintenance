@extends('layouts.dashboard.master')
  @section('wrapper')
  <body class="vertical-layout vertical-menu-modern 2-columns   menu-expanded fixed-navbar"
  data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
            @include('contractor.partials.loader')
            @include('contractor.partials.header')
            @include('contractor.partials.site-navbar')
            <div class="app-content content">
              <div class="content-wrapper">
                  @yield('content')   
              </div>
            </div>       
 @endsection