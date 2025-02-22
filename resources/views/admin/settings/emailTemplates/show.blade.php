@extends('admin.partials.main')

@section('css')

@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a>
                        </li>
                        <li class="breadcrumb-item active">View Email Template
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="search-admins">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @foreach ($template->toArray() as $key => $value)
                                        <div
                                            class="position-relative form-group d-flex justify-content-between  border-bottom p-1">
                                            <label for="name"
                                                class="         ">
                                                {!! ucwords(str_replace('_', ' ', $key)) !!}
                                            </label>
                                            <div class="sm:mt-0 sm:col-span-2">
                                                @if (in_array($key, ['created_at', 'updated_at']))
                                                    {!! !is_null($value) ? date('d/m/Y', strtotime($value)) : '' !!}
                                                @elseif($key == 'content')     
                                                   {{ base64_decode($value) }}  
                                                @else
                                                    {!! $value !!}
                                                @endif
                                            </div>
                                        </div>
                                @endforeach
                                <div class="form-actions right">
                                    <a href="{{ route('admin.emailTemplate.index') }}" class="theme-btn btn btn-primary">
                                        <i class="la la-times"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
