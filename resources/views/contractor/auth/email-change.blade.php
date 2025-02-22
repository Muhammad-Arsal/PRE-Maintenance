@extends('layouts.master')

@section('content')
@include('frontend.partials.loader')
<div class="theme-layout">
	<div class="error-page">
		<div id="container-inside">
		  <div id="circle-small"></div>
		  <div id="circle-medium"></div>
		  <div id="circle-large"></div>
		  <div id="circle-xlarge"></div>
		  <div id="circle-xxlarge"></div>
	  	</div>
		<div class="thanks-purchase">
			<div class="logo mb-2"><a href="{{ url('/')}}"><img src="{{ \App\Helper\Helpers::get_logo_url('secondary_logo') }}" alt=""/></a></div>
			<span>{{ __('Email Addreess Changed') }}</span>
            <p>{{ __('Your Email was successfully changed') }}</p>
         
           <a class="button dark circle mt-2" href="{{route('contractor.dashboard')}}" title="">Go to Dashboard</a>
		</div>
	</div>	
</div>
@endsection
