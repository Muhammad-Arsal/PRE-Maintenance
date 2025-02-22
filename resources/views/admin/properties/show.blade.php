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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a></li>
                        <li class="breadcrumb-item active">View Property</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="view-property">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @php
                                    $tenant = \DB::table('tenants')->where('id', $property->tenant_id)->value('name');
                                    $landlord = \DB::table('landlords')->where('id', $property->landlord_id)->value('name');
                                @endphp
                                
                                @foreach ($property->toArray() as $key => $value)
                                    <div class="position-relative form-group d-flex justify-content-between border-bottom p-1">
                                        <label for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                        <div class="sm:mt-0 sm:col-span-2">
                                            @if ($key == 'tenant_id')
                                                {{ $tenant ?? 'N/A' }}
                                            @elseif ($key == 'landlord_id')
                                                {{ $landlord ?? 'N/A' }}
                                            @elseif (in_array($key, ['created_at', 'updated_at']))
                                                {!! !is_null($value) ? date('d/m/Y', strtotime($value)) : '' !!}
                                            @else
                                                {!! $value !!}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="form-actions right">
                                    <a href="{{ route('admin.properties') }}" class="theme-btn btn btn-primary">
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
