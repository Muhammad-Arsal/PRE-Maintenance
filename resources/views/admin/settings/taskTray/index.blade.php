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
                <a href="{{ route('admin.settings.taskTray.create') }}"
                   class="btn btn-primary basic-btn btn-min-width mr-1 mb-1" type="button">
                    <i class="la la-plus"></i>
                    New Task Tray
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
    @include('admin.partials.flashes')
    <!-- <section id="search-types">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                   
                                    <form id="searchForm">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control" name="keywords"
                                                        placeholder="Keyword">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="btn-group">
                                                            <button type="submit" class="btn btn-primary basic-btn mr-1"><i
                                                                    class="la la-search"></i> Search</button>
                                                            <button type="button" class="btn btn-primary basic-btn"
                                                                onclick="resetForm()"><i class="la la-refresh"></i>
                                                                Reset</button>
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
            </section> -->
        <section id="manageTypes">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 custom-table" id="manageBankTypes">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php $j=1 @endphp
                                <?php if ($taskTray->currentPage() !== 1) {
                                    $j = config('cpr.pagination_limit') * ($taskTray->currentPage() - 1) + 1;
                                } ?>
                                @forelse($taskTray as $data)
                                    <tr>
                                        <td>{{ $j }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ isset($data['created_at']) ? date('d/m/Y H:i', strtotime($data['created_at'])) : null }}</td>
                                        <td class="d-flex">
                                            <a href="{{ route('admin.settings.taskTray.edit', ['id' => $data['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="Edit Task Tray"><span
                                                    style="padding:0.5rem 0.75rem; margin-right: 1em;" data-row-id="{{ $data['id'] }}"
                                                    class="d-inline-block rounded bg-warning bg text-white"><i
                                                        class="la la-edit"></i></span></a>
                                            <a href="#" class="clickDeleteFunction" data-modal="deleteEventModal"
                                                data-action="{{ route('admin.settings.taskTray.delete', ['id' => $data['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="{{ isset($data['deleted_at']) ? 'Inactive Task Tray' : 'Active Task Tray' }}"><span
                                                    style="padding:0.5rem 0.75rem" data-row-id="{{ $data['id'] }}"
                                                    class="d-inline-block rounded {{ isset($data['deleted_at']) ? 'bg-danger' : 'bg-success' }} bg text-white"><i class="la la-power-off" aria-hidden="true"></i></span></a>
                                        </td>
                                    </tr>
                                    @php $j++; @endphp
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
                        {!! $taskTray->links('pagination::bootstrap-4') !!}
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
                            <h4 class="modal-title">Delete Task Tray</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            Are you sure? You want to delete this Task Tray.
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
@endsection
