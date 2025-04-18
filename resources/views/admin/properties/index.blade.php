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
                <a href="{{route('admin.properties.create')}}"
                    class="btn btn-primary basic-btn btn-min-width mr-1 mb-1" type="button">
                    <i class="la la-plus"></i>
                    Add Property
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        @include('admin.partials.flashes')
            <section id="search-types">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="searchForm" action="{{route('admin.properties.search')}}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input value="{{ $keywords }}" type="text" class="form-control" name="keywords"
                                                        placeholder="Address line 1, City, County, Postal Code">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="searchLandlord" id="searchLandlord" style="width: 100%" class="form-control select2">
                                                            <option value="">Select landlord</option>
                                                            @foreach ($landlords as $item)
                                                                <option {{ $item->id == $searchLandlord ? 'selected' : '' }} value="{{ $item->id }}">
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="searchTenant" id="searchTenant" style="width: 100%" class="form-control select2">
                                                            <option value="">Select tenant</option>
                                                            @foreach ($tenants as $item)
                                                                <option {{ $item->id == $searchTenant ? 'selected' : '' }} value="{{ $item->id }}">
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="row"> 
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                            <select id="searchType" name="searchType" class="form-control">
                                                                <option value="">Select Property Type</option>
                                                                @foreach($type as $type)
                                                                    <option value="{{ $type->name }}" {{ old('searchType', $searchType ?? '') == $type->name ? 'selected' : '' }}>
                                                                        {{ $type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @error('searchType') 
                                                            <span class="text-danger">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="searchBedrooms" id="searchBedrooms" style="width: 100%" class="form-control select2">
                                                            <option value="">Select bedrooms</option>
                                                            @for ($i = 1; $i <= 10; $i++)
                                                                <option value="{{ $i }}" {{ isset($searchBedrooms) && $searchBedrooms == $i ? 'selected' : '' }}>
                                                                    {{ $i }} {{ $i == 1 ? 'Bedroom' : 'Bedrooms' }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="status" id="status" style="width: 100%" class="form-control">
                                                            <option value="">Select status</option>
                                                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>                                              
                                            </div>
                                            <div class="row">
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
                                        <tr>
                                            <th>#</th>
                                            <th>Property Id</th>
                                            <th>Landlord</th>
                                            <th>Tenant</th>
                                            <th>Property Type</th>
                                            <th>Address</th>
                                            <th>Created At</th>
                                            <th>Modified At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $j=1 @endphp
                                    <?php
                                        if($properties->currentPage() !== 1){
                                            $j =  10 * ( $properties->currentPage() - 1) + 1;
                                        }
                                    ?>
                                    @forelse ($properties as $data)
                                        <tr>
                                            <td><strong>{{ $j }}</strong></td>
                                            <td>{{$data->id}}</td>
                                            <td>
                                                <a href="{{ isset($data->landlord) ? route('admin.settings.landlords.show', $data->landlord->id) : '#' }}">
                                                    {{ $data->landlord->name ?? "" }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ isset($data->tenant) ? route('admin.settings.tenants.show', $data->tenant->id) : '#' }}">
                                                    {{ $data->tenant->name ?? "" }}
                                                </a>
                                            </td>                                            
                                            <td>{{$data->type}}</td>
                                            <td>{{ $data->line1 . ', ' . $data->county . ', ' . $data->city . ', ' . $data->postcode }}</td>
                                            <td>{{ $data->created_at->format('d/m/Y, h:i') }}</td>
                                             <td>{{ $data->updated_at->format('d/m/Y, h:i') }}</td>
                                            <td>
                                                {{-- <a href="{{ route('admin.properties.show', $data->id) }}" 
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="More Details">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-primary text-white">
                                                         <i class="la la-eye"></i>
                                                     </span>
                                                 </a>     --}}
                                                 {{-- <a href="{{route('admin.properties.diary', $data->id)}}" 
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="Diary">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-success text-white">
                                                         <i class="la la-book"></i>
                                                     </span>
                                                 </a> --}}
                                                 {{-- <a href="{{route('admin.properties.viewjobs', $data->id)}}" 
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="View Jobs">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-secondary text-white">
                                                         <i class="la la-wrench"></i>
                                                     </span>
                                                 </a>                                              --}}
                                                <a href="{{route('admin.properties.edit', $data->id)}}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Edit Property"><span
                                                        style="padding:0.5rem 0.75rem" data-row-id=""
                                                        class="d-inline-block rounded bg-warning bg text-white"><i
                                                            class="la la-edit"></i></span></a>
                                                <a href="#" class="clickDeleteFunction" 
                                                data-modal="forceDelete"
                                                data-action="{{ route('admin.properties.destroy', $data->id) }}"
                                                data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="Delete Property">
                                                    <span style="padding:0.5rem 0.75rem" 
                                                        data-row-id="" 
                                                        class="d-inline-block rounded bg-danger text-white">
                                                        <i class="la la-trash" aria-hidden="true"></i>
                                                    </span>
                                                </a>
                                                    
                                            </td>
                                        </tr>
                                    @php $j++ @endphp
                                    @empty
                                        <tr>
                                            <td colspan="15">
                                                <p class="text-center" style="font-size:1.5rem">No Data Available</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-center pagination-wrapper mt-2">
                            {!! $properties->appends(request()->query())->links('pagination::bootstrap-4') !!}
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
                            <h4 class="modal-title">Active/Inactive Property</h4>
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
                <form name="deleteForm" action="" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete Property</h4>
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
        
    </div>

@endsection

@section('js')
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
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

<script>
    $(function() {
        $('.select2').select2();
    })
</script>
@endsection

