@extends('layouts.master')
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
                <img src="{{ \App\Helper\Helpers::get_logo_url('logo') }}" style="max-width:60%" alt="branding logo" style="max-width:100%">
                
              </div>
            </section>
          </div>
        </div>
      </div>
@endsection
