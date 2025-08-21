@extends('admin.partials.main')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ $page['page_parent_link'] }}" class="theme-color">
                            {{ $page['page_parent'] }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $page['page_current'] }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <section id="logsTable">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Model</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = ($logs->currentPage() - 1) * $logs->perPage() + 1 @endphp
                            @forelse($logs as $log)
                            @if(
                            Str::contains(strtolower(class_basename($log->model_type)), 'profile')
                            && Str::contains(strtolower($log->action), 'created')
                            )
                            @continue
                            @endif

                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                                <td>
                                    @switch($log->action)
                                    @case('created')
                                    <span class="badge badge-success">{{ ucfirst($log->action) }}</span>
                                    @break
                                    @case('updated')
                                    <span class="badge badge-info">{{ ucfirst($log->action) }}</span>
                                    @break
                                    @case('deleted')
                                    <span class="badge badge-danger">{{ ucfirst($log->action) }}</span>
                                    @break
                                    @case('restored')
                                    <span class="badge badge-warning">{{ ucfirst($log->action) }}</span>
                                    @break
                                    @default
                                    <span class="badge badge-secondary">{{ ucfirst($log->action) }}</span>
                                    @endswitch
                                </td>
                                <td style="font-size: 0.9rem;">
                                    {{ $log->description }}
                                </td>
                                <td>{{ $log->user_name ?? 'System' }}</td>
                                <td>{{ $log->performed_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if(!in_array($log->action, ['deleted', 'restored']) && $log->url)
                                    <a href="{{ $log->url }}" target="_blank">View</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <p class="text-center" style="font-size:1.2rem">No Logs Found</p>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-center pagination-wrapper mt-2">
                    {!! $logs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection