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

        <div class="content-header-right col-md-6 col-12">
            <div class="dropdown float-md-right">
                <a href="{{route('admin.jobs.custom.create',$property_id)}}"
                    class="btn btn-primary basic-btn btn-min-width mr-1 mb-1" type="button">
                    <i class="la la-plus"></i>
                    Add Job
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="search-landlords">
            <div class="row">
                <div class="col-12">

                    <div class="container-fluid p-0 m-0">
                        <div class="table-responsive">
                            <table id="diaryTable" class="table table-bordered">
                                <thead style="background-color: rgb(4,30,65); color: white;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Winning Contractor</th>
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
                                            <td>{{$item->status}}</td>
                                            <td>{{$item->description}}</td>
                                            <td><a href="{{route('admin.settings.contractors.edit', $item->contractor_id)}}">{{$item->contractor->name}}</a></td>
                                            <td>{{$item->created_at->format('d/m/Y') }}</td>
                                            <td>{{$item->updated_at->format('d/m/Y') }}</td>
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
