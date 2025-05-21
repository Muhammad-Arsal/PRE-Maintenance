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
                <a href="{{route('admin.invoices.create')}}"
                    class="btn btn-primary basic-btn btn-min-width mr-1 mb-1" type="button">
                    <i class="la la-plus"></i>
                    Add Invoices
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
                                    <form id="searchForm" action="{{route('admin.invoices.search')}}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <input value="{{ $keywords }}" type="text" class="form-control" name="keywords"
                                                        placeholder="Address">
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
                                        <th>Invoice Id</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Description</th>
                                        <th>Property</th>
                                        <th>Contractor</th>
                                        <th>Date</th>
                                        <th>Created At</th>
                                        <th>Modified At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $j=1 @endphp
                                    <?php
                                        if($invoices->currentPage() !== 1){
                                            $j =  10 * ( $invoices->currentPage() - 1) + 1;
                                        }
                                    ?>
                                    @forelse($invoices as $data)
                                        <tr>
                                            <td>{{$j}}</td>
                                            <td>{{$data->id}}</td>
                                            <td>{{$data->status}}</td>
                                            <td>{{$data->total}}</td>
                                            <td>
                                                @if($data->job)
                                                    {{ $data->job->jobDetail->pluck('description')->join(', ') }}
                                                @else
                                                    Not Set
                                                @endif
                                            </td>
                                            <td><a href="{{route('admin.properties.edit',$data->property->id)}}">{{$data->property->line1 . ', ' . $data->property->city . ', ' . $data->property->county . ', ' . $data->property->postcode}}</a></td>
                                            <td><a href="{{route('admin.settings.contractors.edit',$data->contractor->id)}}">{{$data->contractor->name}}</a></td>
                                            <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                                            <td>{{ $data->created_at->format('d/m/Y,  h:i') }}</td>
                                            <td>{{ $data->updated_at->format('d/m/Y,  h:i') }}</td>
                                            <td>
                                                <a href="{{route('admin.invoices.show', $data->id)}}" 
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="More Details">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-primary text-white">
                                                         <i class="la la-eye"></i>
                                                     </span>
                                                 </a> 
                                                <a href="{{route('admin.invoices.edit.status', $data->id)}}" 
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top" 
                                                    data-title="Update Status">
                                                     <span style="padding:0.5rem 0.75rem" data-row-id="" 
                                                           class="d-inline-block rounded bg-warning text-white">
                                                         <i class="la la-pencil"></i>
                                                     </span>
                                                 </a> 
                                                {{-- <a href="{{route('admin.invoices.edit', $data->id)}}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Edit invoices"><span
                                                        style="padding:0.5rem 0.75rem" data-row-id=""
                                                        class="d-inline-block rounded bg-warning bg text-white"><i
                                                            class="la la-edit"></i></span></a>
                                                
                                                <a href="#" style="word-wrap: nowrap; margin-right: 0.35rem;" 
                                                    class="clickDeleteFunction" 
                                                    data-modal="forceDelete"
                                                    data-action="{{ route('admin.invoices.destroy', $data->id) }}" 
                                                    data-row-id="{{ $data->id }}">
                                                    <span style="padding:0.5rem 0.75rem" 
                                                            class="d-inline-block rounded bg-danger text-white">
                                                            <i class="la la-trash" aria-hidden="true"></i>
                                                    </span>
                                                </a> --}}

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
                            {!! $invoices->appends(request()->query())->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </section>

            <div id="forceDelete" class="modal modal-danger fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <form name="deleteForm" action="" method="post">
                        @csrf
                        @method('DELETE') <!-- Laravel DELETE method -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete Invoice</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                Are you sure? You want to delete this invoice.
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline pull-left" data-dismiss="modal" style="background-color: #A6A6A6; color:white;">Cancel</button>
                                <button type="submit" class="btn btn-outline" style="background-color: #FF1616; color:white;">Confirm</button>
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
    $(".clickDeleteFunction").on("click", function () {
        let deleteUrl = $(this).data("action"); // Get the delete URL
        let rowId = $(this).data("row-id"); // Get the row ID (if needed)

        // Set the form action dynamically
        $("form[name='deleteForm']").attr("action", deleteUrl);

        // Show the modal
        $("#forceDelete").modal("show");
    });
});

</script>
@endsection

