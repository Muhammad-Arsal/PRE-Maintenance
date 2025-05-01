@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/forms/selects/select2.css') }}" />
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
        <div class="content-header-right col-md-6 col-12">
            <div class="dropdown float-md-right">
                <a href="{{route('admin.jobs.create')}}"
                    class="btn btn-primary basic-btn btn-min-width mr-1 mb-1" type="button">
                    <i class="la la-plus"></i>
                    Add Jobs
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        @include('contractor.partials.flashes')
            <section id="search-types">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="searchForm" action="{{route('admin.jobs.search')}}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <input value="{{ $keywords }}" type="text" class="form-control" name="keywords"
                                                        placeholder="Description">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <select name="property_id" id="property_id" style="width: 100%" class="form-control select2">
                                                            <option value="">Select Property</option>
                                                            @foreach($properties as $property)
                                                                <option value="{{ $property->id }}" {{ old('property_id', $selectedProperty ?? '') == $property->id ? 'selected' : '' }}>
                                                                    {{ $property->line1 }}, {{ $property->city }}, {{ $property->county }}, {{ $property->postcode }},
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <select name="contractor_id" id="contractor_id" style="width: 100%" class="form-control select2">
                                                            <option value="">Select Contractor</option>
                                                            @foreach($contractors as $contractor)
                                                                <option value="{{ $contractor->id }}" {{ old('contractor_id', $selectedContractor ?? '') == $contractor->id ? 'selected' : '' }}>
                                                                    {{ $contractor->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>                                                
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="btn-group">
                                                            <button type="submit" class="btn btn-primary basic-btn mr-1"><i
                                                                    class="la la-search"></i> Search</button>
                                                            <a href="" class="btn btn-primary basic-btn"
                                                                ><i class="la la-refresh"></i>
                                                                Reset</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="manageTypes">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 custom-table" id="manageTypes">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Job Id</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Description</th>
                                        <th>Property</th>
                                        <th>Wining Contractor</th>
                                        <th>Created At</th>
                                        <th>Modified At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $j=1 @endphp
                                    <?php
                                        if($jobs->currentPage() !== 1){
                                            $j =  10 * ( $tenants->currentPage() - 1) + 1;
                                        }
                                    ?>
                                    @forelse ($jobs as $item)
                                        <tr>
                                            <td>{{ $j }}</td>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->status ?? 'Not Set' }}</td>
                                            <td>{{ $item->priority ?? 'Not Set' }}</td>
                                            <td>{{ $item->description ?? 'Not Set' }}</td>
                                            <td>
                                                <a href="{{ route('admin.properties.show', $item->property_id) }}">
                                                    {{ $item->property->line1 . ', ' . $item->property->city . ', ' . $item->property->county . ', ' . $item->property->postcode }}
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $details = json_decode($item->contractor_details, true);
                                                    $winner = collect($details)->firstWhere('won_contract', 'yes');
                                                    $contractor = $winner ? \App\Models\Contractor::find($winner['contractor_id']) : null;
                                                @endphp
                                            
                                                @if($contractor)
                                                    <a href="{{ route('admin.settings.contractors.edit', $contractor->id) }}">
                                                        {{ $contractor->name }}
                                                    </a>
                                                @endif
                                            </td>                                            
                                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y, h:i') : 'N/A' }}</td>
                                            <td>{{ $item->updated_at ? $item->updated_at->format('d/m/Y, h:i') : 'N/A' }}</td>
                                            <td >
                                                {{-- <a href="{{route('admin.jobs.show', $item->id)}}"
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="More Details">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-primary text-white">
                                                         <i class="la la-eye"></i>
                                                     </span>
                                                 </a> --}}
                                                <a href="{{route('admin.jobs.edit', $item->id)}}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Edit Jobs"><span
                                                        style="padding:0.5rem 0.75rem" data-row-id=""
                                                        class="d-inline-block rounded bg-warning bg text-white"><i
                                                            class="la la-edit"></i></span></a>
                                                        <a href="#" style="word-wrap: nowrap; margin-right: 0.35rem;" 
                                                            class="clickDeleteFunction" 
                                                            data-modal="forceDelete"
                                                            data-action="{{ route('admin.jobs.destroy', $item->id) }}"
                                                            data-row-id="{{ $item->id }}">
                                                            <span style="padding:0.5rem 0.75rem" 
                                                                    class="d-inline-block rounded bg-danger text-white">
                                                                <i class="la la-trash" aria-hidden="true"></i>
                                                            </span>
                                                        </a>
                                                         
                                            </td>
                                        </tr>
                                        @php $j++ @endphp
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No data found</td>
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
            </section>

        <div id="deleteEventModal" class="modal modal-danger fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <form name="deleteForm" action="" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Active/Inactive contractor</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            Are you sure? You want to do this.
                        </div>
                        <div class="modal-footer">
                            <input style="background-color: #A6A6A6; color:white;" class="btn btn-outline pull-left"
                                type="button" value="Cancel" data-dismiss="modal">
                            <input style="background-color: #FF1616; color:white;" class="btn btn-outline" type="submit"
                                value="Confirm">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="forceDelete" class="modal modal-danger fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <form name="deleteForm" action="" method="POST" id="deleteForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Job</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            Are you sure? You want to delete this job.
                        </div>
                        <div class="modal-footer">
                            <input style="background-color: #A6A6A6; color:white;" class="btn btn-outline pull-left"
                                type="button" value="Cancel" data-dismiss="modal">
                            <input style="background-color: #FF1616; color:white;" class="btn btn-outline" type="submit"
                                value="Confirm">
                        </div>
                    </div>
                </form>
            </div>
        </div>        
    </div>

@endsection

@section('js')
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
<script>
    $(function() {
        $('.select2').select2();
    })

    $(document).ready(function () {
    $('.clickDeleteFunction').on('click', function () {
        let actionUrl = $(this).data('action'); // Get action URL
        $('#deleteForm').attr('action', actionUrl); // Set form action
        $('#forceDelete').modal('show'); // Show modal
    });
    });

</script>
@endsection

