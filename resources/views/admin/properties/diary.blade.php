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
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="POST" enctype="multipart/form-data" id='manageProperties'  action="{{route('admin.properties.diaryStore', $property_id)}}">
                                    @csrf
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Diary</strong></h3>
                                        <div class="form-group">
                                            <label for="new_entry"><span style="color: red;">*</span>New Entry</label>
                                            <textarea id="new_entry" class="form-control" name="new_entry" rows="4" placeholder="Enter your Entry here..."></textarea>
                                            @error('new_entry') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-actions right">
                                            <a href="{{route('admin.properties')}}" class="theme-btn btn btn-primary">
                                                <i class="la la-times"></i> Cancel
                                            </a>
                                            <button type="submit" class="theme-btn btn btn-primary">
                                                <i class="la la-check-square-o"></i> Save
                                            </button>
                                        </div>
                                    </div>                                   
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid p-0 m-0">
                        <div class="table-responsive">
                            <table id="diaryTable" class="table table-bordered">
                                <thead style="background-color: rgb(4,30,65); color: white;">
                                    <tr>
                                        <th>User</th>
                                        <th>Tenant</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $j=1 @endphp
                                    <?php
                                        if($diary->currentPage() !== 1){
                                            $j =  10 * ( $diary->currentPage() - 1) + 1;
                                        }
                                    ?>
                                    @forelse ($diary as $item)
                                        <tr>
                                            <td>{{$item->admin->name}}</td>
                                            <td>{{$item->property->tenant->name}}</td>
                                            <td>{{$item->entry}}</td>
                                            <td>{{ $item->created_at->format('d/m/Y, h:i') }}</td>
                                            <td>
                                                <a href="#" class="clickDeleteFunction" 
                                                data-modal="forceDelete"
                                                data-action="{{ route('admin.properties.diaryDelete', $item->id) }}"
                                                data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="Delete Entry">
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
                                            <td colspan="5">
                                                <p class="text-center" style="font-size:1.5rem">No Data Available</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-center pagination-wrapper mt-2">
                            {!! $diary->appends(request()->query())->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                                      
                </div>
            </div>
        </section>
    </div>

    <div id="forceDelete" class="modal modal-danger fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Diary Entry</h4>
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
