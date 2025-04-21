@extends('contractor.partials.main')

@section('css')

@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ $page['page_parent_link'] }}" class="theme-color">{{ $page['page_parent'] }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="search-contractors">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('contractor.settings.contractors.edit', $contractor_id) }}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('contractor.settings.contractors.edit.address', $contractor_id) }}">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="jobs" data-toggle="tab"
                                            aria-controls="jobs" href="#jobs" aria-expanded="true">Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('contractor.contractors.correspondence', $contractor_id) }}">Correspondence</a>
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
                                                            <th>ID</th>
                                                            <th>Property</th>
                                                            <th>Description</th>
                                                            <th>Status</th>
                                                            <th>Won Contract?</th>
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
                                                                <td>{{$j}}</td>
                                                                <td>{{$item->property->line1 . ', ' . $item->property->city . ', '. $item->property->county . ', ' . $item->property->postcode }}</td>
                                                                <td>{{$item->description}}</td>
                                                                <td>{{$item->status}}</td>
                                                                <td>{{$item->won_contract == "yes" ? "Yes" : "no"}}</td>
                                                                <td>{{$item->created_at->format('d/m/Y, H:i') }}</td>
                                                                <td>{{$item->updated_at->format('d/m/Y, H:i') }}</td>                                            
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
                                    <a href="{{route('contractor.dashboard')}}" class="theme-btn btn btn-primary">
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
