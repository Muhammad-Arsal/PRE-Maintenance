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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Email Templates
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12">
            <div class="dropdown float-md-right">
                <a href="{{ route('admin.emailTemplate.add') }}" class="btn btn-primary basic-btn btn-min-width mr-1 mb-1"
                    type="button">
                    <i class="la la-plus"></i>
                    New Template
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="search-types">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <form id="searchForm" action="{{ route('admin.emailTemplate.index') }}">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <input value="{{ $keywords }}" type="text" class="form-control" name="keywords"
                                                    placeholder="Type, Subject">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <select name="emailTemplate" id="emailTemplate" style="width: 100%" class="form-control select2">
                                                        <option value="">Select Email Template</option>
                                                        @foreach ($searchEmails as $item)
                                                            <option {{ $item->type == $searchEmail ? 'selected' : '' }} value="{{ $item->type }}">{{ $item->type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <div class="btn-group">
                                                        <button type="submit" class="btn btn-primary basic-btn mr-1"><i
                                                                class="la la-search"></i> Search</button>
                                                        <a href="{{ route('admin.emailTemplate.index') }}" class="btn btn-primary basic-btn"
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
        <section id="manageSettings">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 custom-table" id="manageEmailTemplates">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email Type</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>is Html</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $j=1 @endphp
                                @forelse($data as $row)
                                    <tr>
                                        <td>{{ $j }}</td>
                                        <td>{{ $row['type'] }}</td>
                                        <td>{{ $row['subject'] }}</td>
                                        <td>{{ isset($row['deleted_at']) ? 'Inactive' : 'Active' }}</td>
                                        <td>{{ $row['is_html'] == 'yes' ? 'Yes' : 'No ' }}</td>
                                        <td>{{ isset($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : null }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.emailTemplate.show', ['id' => $row['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="More Details"><span
                                                    style="padding:0.5rem 0.75rem" data-row-id="{{ $row['id'] }}"
                                                    class="d-inline-block rounded bg-primary bg text-white"><i
                                                        class="la la-eye"></i></span></a>
                                            <a href="{{ route('admin.emailTemplate.edit', ['id' => $row['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="Edit Template"><span
                                                    style="padding:0.5rem 0.75rem" data-row-id="{{ $row['id'] }}"
                                                    class="d-inline-block rounded bg-warning bg text-white"><i
                                                        class="la la-edit"></i></span></a>
                                            <a href="#" class="clickDeleteFunction" data-modal="deleteEventModal"
                                                data-action="{{ route('admin.emailTemplate.delete', ['id' => $row['id']]) }}" data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                data-title="{{ isset($row['deleted_at']) ? 'Inactive Template' : 'Active Template' }}"><span
                                                    style="padding:0.5rem 0.75rem; white-space: nowrap;" data-row-id="{{ $row['id'] }}"
                                                    class="d-inline-block rounded {{ isset($row['deleted_at']) ? 'bg-danger' : 'bg-success' }} bg text-white"><i class="la la-power-off" aria-hidden="true"></i></span></a>

                                        </td>
                                    </tr>
                                    @php $j++ @endphp
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
                        {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
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
                            <h4 class="modal-title">Active/Inactive Email Template</h4>
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
