@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/forms/selects/select2.css') }}" />
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Property Types</h3>
        </div>
        <div class="content-header-right col-md-6 col-12">
            <div class="dropdown float-md-right">
                <a href="{{route('admin.settings.propertyType.create')}}" class="btn btn-primary basic-btn btn-min-width mr-1 mb-1">
                    <i class="la la-plus"></i> Add Property Type
                </a>
            </div>
        </div>
    </div>

    <div class="content-body">
        @include('admin.partials.flashes')

        <section id="manageTypes">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($propertyTypes as $index => $type)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $type->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $type->updated_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.settings.propertyType.edit', $type->id) }}" 
                                                data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                data-title="Edit Property Type">
                                                 <span style="padding:0.5rem 0.75rem" class="d-inline-block rounded bg-warning text-white">
                                                     <i class="la la-edit"></i>
                                                 </span>
                                             </a>
                                             
                                             <a href="#" class="clickDeleteFunction" 
                                                data-action="{{ route('admin.settings.propertyType.destroy', $type->id) }}" 
                                                data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                data-title="Delete Property Type">
                                                 <span style="padding:0.5rem 0.75rem" class="d-inline-block rounded bg-danger text-white">
                                                     <i class="la la-trash"></i>
                                                 </span>
                                             </a>
                                             
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrapper mt-2">
                        {{-- {{ $propertyTypes->links() }} --}}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="deleteModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Property Type</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this property type?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $(".clickDeleteFunction").on("click", function(e) {
        e.preventDefault();
        let actionUrl = $(this).data("action");
        $("form[name='deleteForm']").attr("action", actionUrl);
        $("#deleteModal").modal("show");
    });
});
</script>
@endsection
