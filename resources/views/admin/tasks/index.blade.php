@extends('admin.partials.main')
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
                <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary basic-btn btn-min-width mr-1 mb-1"
                    type="button">
                    <i class="la la-plus"></i>
                    New Task
                </a>
            </div>
            <div class="dropdown float-md-right">
                <a href="{{ route('admin.tasks.export') }}" class="btn btn-primary basic-btn btn-min-width mr-1 mb-1"
                    type="button">
                    <i class="la la-plus"></i>
                    Export CSV
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        @include('admin.partials.flashes')
        <section id="search-tasks">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <form id="searchForm" action="{{ route('admin.tasks') }}" method="get">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-xl-4" >
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Platform Users</label>
                                                            <select name="platform_users" id="platform_users" class="form-control">
                                                                <option value="all">All</option>
                                                                @foreach($users as $user)
                                                                    <option {{ $platform_user == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Task Status</label>
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="">Choose Task Status</option>
                                                                <option {{ $status == 'all' ? 'selected' : '' }} value="all">All</option>
                                                                <option {{ $status == 'not_completed' ? 'selected' : '' }} value="not_completed">Outstanding</option>
                                                                <option {{ $status == 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-2 d-flex align-items-end">
                                                <div class="form-group" style="width: 100%">
                                                    <label>Task Tray</label>
                                                    <select name="task_tray" class="form-control" id="task_tray">
                                                        <option value="">Task Tray</option>
                                                        @foreach ($taskTrays as $item)
                                                            <option {{ $task_tray == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 d-flex align-items-end">
                                                <div class="form-group" style="width: 100%">
                                                    <label>Priority</label>
                                                    <select name="priority" class="form-control" id="priority">
                                                        <option value="">Priority</option>
                                                        <option {{ $priority == '1' ? 'selected' : '' }} value="1">High</option>
                                                        <option {{ $priority == '2' ? 'selected' : '' }} value="2">Normal</option>
                                                        <option {{ $priority == '3' ? 'selected' : '' }} value="3">Low</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 d-flex align-items-end" >
                                                <div class="d-flex">
                                                    <div class="form-group mr-3">
                                                        <input type="text" value="{{ !empty($start_date) ? date('d/m/Y', strtotime($start_date)) : '' }}" class="form-control pickadate picker__input"
                                                            name="start_date" placeholder="Start Date">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" value="{{ !empty($end_date) ? date('d/m/Y', strtotime($end_date)) : '' }}" class="form-control pickadate picker__input"
                                                            placeholder="End Date" name="end_date" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                {{-- <div class="form-group" style="margin-bottom: 1em; margin-left: 1em;"> --}}
                                                    {{-- <form action="{{ route('admin.products.index') }}" method="get" id="filter_date"> --}}
                                                        <select name="sort_by_due_date" class="form-control" id="sort_by_due_date">
                                                            <option value="">Sort By Due Date</option>
                                                            <option {{ $sort_by_due_date == 'oldest_to_newest' ? 'selected' : '' }} value="oldest_to_newest">Oldest To Newest</option>
                                                            <option {{ $sort_by_due_date == 'newest_to_oldest' ? 'selected' : '' }} value="newest_to_oldest">Newest To Oldest</option>
                                                        </select>
                                                    {{-- </form> --}}
                                                {{-- </div> --}}
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" style="margin-bottom: 0">
                                                    <div class="btn-group">
                                                        <button class="btn btn-primary basic-btn mr-1"
                                                            onclick="searchForm(event)"><i class="la la-search"></i>
                                                            Search</button>
                                                        <a href="{{ route('admin.tasks') }}" class="btn btn-primary basic-btn"><i class="la la-refresh"></i>Reset</a>
                                                        {{-- <button type="button" class="btn btn-primary basic-btn"
                                                                onclick="resetForm()"><i class="la la-refresh"></i>
                                                                Reset</button> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-2">
                                                {{-- <label>Sort By Due Date</label>
                                                <select name="sortBy" id="sortBy" class="form-control">
                                                    <option>None</option>
                                                    <option>Asc</option>
                                                    <option>Desc</option>
                                                </select> --}}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row mt-1">
                                            <div class="col-12">
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
                                        </div> --}}
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <section id="manageTasksSection">
        <div class="card">
            <div class="card-body" id="manageTasks">
                <div class="table-responsive">
                    <table class="table mb-0 custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Platform User</th>
                                <th>Due Date</th>
                                <th>Priority</th>
                                <th>Task Status</th>
                                <th>Date Completed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $y=1 @endphp
                            <?php if($tasks->currentPage() !== 1){
                                $y =  config('cpr.pagination_limit') * ( $tasks->currentPage() - 1) + 1;
                            } ?>
                            @forelse($tasks as $data)
                                @php 
                                    $platform_user = \DB::table('admins')->where('id', $data['platform_user'])->first();
                                @endphp
                                <tr id="table-row-{{$data['id']}}">
                                    <td>{{ $y }}</td>
                                    <td>{{ $data['description'] }}</td>
                                    <td>{{ $platform_user ? $platform_user->name : '' }}</td>
                                    <td>
                                        @php
                                            $dueDateString = \Carbon\Carbon::today()->toDateString();
                                            $checkDueDates = \DB::table('tasks')->where('id', $data['id'])->where('status', 'not_completed')->where('due_date', '<=', $dueDateString)->first();
                                        @endphp
                                        @if (isset($data['due_date']))
                                            <span class="d-flex text {{ isset($checkDueDates) ? 'text-danger' : '' }} text-bold"> <i style="margin-right:4px"
                                                    class="la la-exclamation-circle"></i>
                                                {{ date('d/m/Y H:i', strtotime($data['due_date'])) }} </span>
                                        @else {{ null }}
                                        @endif
                                    </td>
                                    <td>
                                        @php 
                                            $buttonClass = '';
                                            $priorityLabel = '';
                                    
                                            if ($data['priority'] == 1) {
                                                $buttonClass = 'btn-danger';
                                                $priorityLabel = 'High';
                                            } elseif ($data['priority'] == 2) {
                                                $buttonClass = 'btn-warning'; 
                                                $priorityLabel = 'Normal';
                                            } elseif ($data['priority'] == 3) {
                                                $buttonClass = 'btn-success'; 
                                                $priorityLabel = 'Low';
                                            }
                                        @endphp
                                    
                                        <button class="btn {{ $buttonClass }} btn-sm">{{ $priorityLabel }}</button>
                                    </td>
                                    <td>{{ $data['status'] == 'completed' ? 'Completed' : 'Outstanding' }}</td>
                                    <td>{{ $data['status'] == 'completed' ? date('d/m/Y H:i', strtotime($data['completed_date'])) : '' }}</td>
                                    <td class="d-flex">
                                        <a href="{{ route('admin.tasks.edit', ['id' => $data['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                            data-title="Edit Task"
                                            style="margin-right:0.35rem"><span style="padding:0.5rem 0.75rem"
                                                data-row-id="{{ $data['id'] }}"
                                                class="d-inline-block  rounded  bg-warning bg text-white"><i
                                                    class="la la-edit"></i></span></a>
                                        <a href="#" class="clickDeleteFunction" data-modal="deleteEventModal" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                            data-title="Delete Task"
                                            data-action="{{ route('admin.tasks.delete', $data['id']) }}" style="margin-right:0.35rem"><span
                                                style="padding:0.5rem 0.75rem" data-row-id="{{ $data['id'] }}"
                                                class="d-inline-block rounded  bg-danger bg text-white"><i
                                                    class="la la-trash"></i></span></a>

                                        <a href="#" class="clickDeleteFunction" data-modal="changeStatusModal"
                                            data-action="{{ route('admin.tasks.changeStatus', $data['id']) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                            data-title="Change Status"><span
                                                style="padding:0.5rem 0.75rem" data-row-id="{{ $data['id'] }}"
                                                class="d-inline-block rounded  <?php if($data['status'] == 'completed') { echo 'bg-success'; } else { echo 'bg-primary'; } ?> bg text-white"><i
                                                    class="la la-power-off"></i></span></a>
                                    </td>
                                </tr>
                                @php $y++ @endphp
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <p class="text-center" style="font-size:1.5rem">No Data Available</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center pagination-wrapper mt-2">
                    {!! $tasks->links('pagination::bootstrap-4') !!}
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
                        <h4 class="modal-title">Delete Task</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to delete this Task.
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

    <div id="changeStatusModal" class="modal modal-danger fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Status</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to change the status of this Task.
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
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>

    <script>
        $(function() {
            $('.pickadate').pickadate({
                format: 'dd/mm/yyyy',
                onSet: function() {
                    date = this.get('select', 'dd/mm/yyyy');
                }
            });
        });
 
    </script>
@endsection
