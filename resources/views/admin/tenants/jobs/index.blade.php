@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ $page['page_parent_link'] }}"
                                class="theme-color">{{ $page['page_parent'] }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="search-landlords">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.tenants.edit', $tenant_id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.tenants.edit.property', $tenant_id)}}">Current Property</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="job" data-toggle="tab"
                                            aria-controls="job" href="#job" aria-expanded="true">Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.tenants.correspondence', $tenant_id)}}">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="">Past Tenancy</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="container-fluid p-0 m-0">
                                            <div class="table-responsive">
                                                <table id="diaryTable" class="table table-bordered">
                                                    <thead style="background-color: rgb(4,30,65); color: white;">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Job ID</th>
                                                            <th>Status</th>
                                                            <th>Priority</th>
                                                            <th>Created At</th>
                                                            <th>Modified At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $j=1 @endphp
                                                        <?php
                                                            if($jobs->currentPage() !== 1){
                                                                $j =  10 * ( $jobs->currentPage() - 1) + 1;
                                                            }
                                                        ?>
                                                        @forelse ($jobs as $item)
                                                        <tr>
                                                            <td>{{ $j }}</td>
                                                            <td><a href="{{ route('admin.jobs.edit', $item->id) }}">{{ $item->id }}</a></td>
                                                            <td>{{ $item->status }}</td>
                                                            <td>{{ $item->priority }}</td>
                                                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y, h:i') : 'Not set' }}</td>
                                                            <td>{{ $item->updated_at ? $item->updated_at->format('d/m/Y, h:i') : 'Not set' }}</td>
                                                        </tr>
                                                        
                                                            @php $j++ @endphp
                                                        @empty
                                                            <tr>
                                                                <td colspan="6">
                                                                    <p class="text-center" style="font-size:1.5rem">No Data Available</p>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row justify-content-center pagination-wrapper mt-2">
                                                {!! $jobs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                            </div>
                                        </div>
                                                          
                                    </div>
                                </div>
                    
                                <div class="form-actions right">
                                    <a href="{{route('admin.settings.tenants')}}" class="theme-btn btn btn-primary">
                                        <i class="la la-times"></i> Back
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

@section('js')
@endsection
