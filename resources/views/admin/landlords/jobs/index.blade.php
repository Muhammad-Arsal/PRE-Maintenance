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
                                            href="{{route('admin.settings.landlords.edit', $landlord->id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.address', $landlord->id)}}">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.bank', $landlord->id)}}">Bank Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.settings.landlords.invoices', $landlord->id) }}">Invoices</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link active disabled" id="quotes" data-toggle="tab"
                                            aria-controls="quotes" href="#quotes" aria-expanded="true">Quotes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.properties', $landlord->id)}}">Properties</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.landlords.correspondence', $landlord->id)}}">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="">Remittances</a>
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
                                                            <th>Job ID</th>
                                                            <th>Property</th>
                                                            <th>Status</th>
                                                            <th>Priority</th>
                                                            <th>Winning Contractor</th>
                                                            <th>Created At</th>
                                                            <th>Modified At</th>
                                                            <th>Job Quotes</th>
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
                                                                <td><a href="{{ route('admin.jobs.edit', $item->id) }}">{{ $item->id }}</a></td>
                                                                <td><a href="{{route('admin.properties.edit', $item->property->id)}}">{{$item->property->line1 . ', ' . $item->property->city . ', '. $item->property->county . ', ' . $item->property->postcode }}</a></td>
                                                                <td>{{$item->status}}</td>
                                                                <td>{{$item->priority}}</td>
                                                                <td>
                                                                    @if ($item->winningContractor)
                                                                        <a href="{{ route('admin.settings.contractors.edit', $item->winningContractor->id) }}">
                                                                            {{ $item->winningContractor->name ?? 'Not Set' }}
                                                                        </a>
                                                                    @else
                                                                        Not Set
                                                                    @endif
                                                                </td>
                                                                <td>{{$item->created_at->format('d/m/Y, h:i') }}</td>
                                                                <td>{{$item->updated_at->format('d/m/Y, h:i') }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.settings.landlords.jobs.quotes', [$item->id, $landlord->id]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                                    data-title="View Job Quotes"><span
                                                                    style="padding:0.5rem 0.75rem" data-row-id=""
                                                                    class="d-inline-block rounded bg-warning bg text-white"><i
                                                                    class="la la-eye"></i></span>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @php $j++ @endphp
                                                        @empty
                                                            <tr>
                                                                <td colspan="7">
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
                                    <a href="{{route('admin.settings.landlords.edit', $landlord->id)}}" class="theme-btn btn btn-primary">
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
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        $(".clickDeleteFunction").on("click", function(e) {
            e.preventDefault(); // Prevent default link behavior
            let actionUrl = $(this).data("action");
            $("form[name='deleteForm']").attr("action", actionUrl);
            $("#forceDelete").modal("show");
        });
    });
    </script>
@endsection
