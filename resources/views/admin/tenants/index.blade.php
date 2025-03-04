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
                <a href="{{ route('admin.settings.tenants.create') }}"
                    class="btn btn-primary basic-btn btn-min-width mr-1 mb-1" type="button">
                    <i class="la la-plus"></i>
                    Add tenant
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        @include('tenant.partials.flashes')
            <section id="search-types">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="searchForm" action="{{ route('admin.settings.tenants.search') }}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <input value="{{ $keywords }}" type="text" class="form-control" name="keywords"
                                                        placeholder="Name, Phone Number, Email">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <select name="status" id="status" style="width: 100%" class="form-control">
                                                            <option value="">Select status</option>
                                                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div> 
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="btn-group">
                                                            <button type="submit" class="btn btn-primary basic-btn mr-1"><i
                                                                    class="la la-search"></i> Search</button>
                                                            <a href="{{ route('admin.settings.tenants') }}" class="btn btn-primary basic-btn"
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
                                        <th>Name</th>
                                        <th>Property</th>
                                        <th>Contract Start</th>
                                        <th>Contract End</th>
                                        <th>Created At</th>
                                        <th>Modified At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $j=1 @endphp
                                    <?php
                                        if($tenants->currentPage() !== 1){
                                            $j =  $per_page * ( $tenants->currentPage() - 1) + 1;
                                        }
                                    ?>
                                    @forelse($tenants as $data)
                                        <tr>
                                            <td>{{ $j }}</td>
                                            <td>{{ $data['name'] }}</td>
                                            <td><a href="{{route('admin.properties.show', $data->property->id)}}">{{$data->property->line1 . ', ' . $data->property->city .', '. $data->property->county . ', '. $data->property->postcode}}</a></td>
                                            <td>{{ $data['contract_start'] ? date('d/m/Y', strtotime($data['contract_start'])) : '' }}</td>
                                            <td>{{ $data['contract_end'] ? date('d/m/Y', strtotime($data['contract_end'])) : '' }}</td>
                                            <td>{{ isset($data['created_at']) ? \Carbon\Carbon::parse($data['created_at'])->format('d/m/Y') : '' }}</td>
                                            <td>{{ isset($data['updated_at']) ? \Carbon\Carbon::parse($data['updated_at'])->format('d/m/Y') : '' }}</td>
                                            <td>
                                                <a href="{{route('admin.settings.tenants.show', $data->id)}}"
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="More Details">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-primary text-white">
                                                         <i class="la la-eye"></i>
                                                     </span>
                                                 </a>
                                                <a href="{{ route('admin.settings.tenants.edit', ['id' => $data['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Edit tenant"><span
                                                        style="padding:0.5rem 0.75rem" data-row-id="{{ $data['id'] }}"
                                                        class="d-inline-block rounded bg-warning bg text-white"><i
                                                            class="la la-edit"></i></span></a>
                                                {{-- <a href="#" style="word-wrap: nowrap" class="clickDeleteFunction" data-modal="deleteEventModal"
                                                    data-action="{{ route('admin.settings.tenants.destroy', ['id' => $data['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="{{ isset($data['deleted_at']) ? 'Inactive tenant' : 'Active tenant' }}"><span
                                                        style="padding:0.5rem 0.75rem" data-row-id="{{ $data['id'] }}"
                                                        class="d-inline-block rounded {{ isset($data['deleted_at']) ? 'bg-danger' : 'bg-success' }} bg text-white"><i class="la la-power-off" aria-hidden="true"></i></span></a> --}}
                                                <a href="#" style="word-wrap: nowrap; margin-right: 0.35rem;" class="clickDeleteFunction" data-modal="forceDelete"
                                                    data-action="{{ route('admin.settings.tenants.delete', ['id' => $data['id']]) }}"><span
                                                    style="padding:0.5rem 0.75rem" data-row-id="{{ $data['id'] }}"
                                                        class="d-inline-block rounded bg-danger bg text-white"><i class="la la-trash" aria-hidden="true"></i></span></a>
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
                            {!! $tenants->appends(request()->query())->links('pagination::bootstrap-4') !!}
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
                            <h4 class="modal-title">Active/Inactive tenant</h4>
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
                <form name="deleteForm" action="" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delete tenant</h4>
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
    $(function() {
        $('.select2').select2();
    })
</script>
@endsection

