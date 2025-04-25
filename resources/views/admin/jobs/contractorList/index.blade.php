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
                        <li class="breadcrumb-item">
                            <a href="{{ $page['page_parent_link'] }}" class="theme-color">{{ $page['page_parent'] }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="search-contractors">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="POST" enctype="multipart/form-data" id="manageJobs" action="{{ route('admin.jobs.update.contractor_list', $job->id) }}">
                                    @csrf
                                    <div class="card-header">
                                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('admin.jobs.edit', $job->id) }}">Overview</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link active disabled" id="contractorList" data-toggle="tab"
                                                    aria-controls="contractorList" href="#contractorList" aria-expanded="true">Contractor list</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="form-body">
                                        {{-- Contractor rows loop --}}
                                        <div id="contractor-rows">
                                            @foreach($contractorDetails as $i => $detail)
                                                <div class="contractor-row d-flex mb-2">
                                                    <div class="col-md-6">
                                                        <select name="contractors[{{ $i }}][contractor_id]" class="form-control contractor-select">
                                                            <option value="">Select Contractor</option>
                                                            @foreach($contractors as $contractor)
                                                                <option value="{{ $contractor->id }}" {{ $detail['contractor_id'] == $contractor->id ? 'selected' : '' }}>
                                                                    {{ $contractor->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 d-flex align-items-end">
                                                        <label class="mb-0 mr-2">
                                                            <span class="text-danger" style="font-size: 1.2rem;">🏅</span> Won contract?
                                                        </label>
                                                        <input type="radio" name="won_contract_global" value="{{ $i }}" class="won-contract-radio" {{ $detail['won_contract'] == 'yes' ? 'checked' : '' }}>
                                                    </div>
                                                    @if($i > 0)
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Add Button --}}
                                        <div class="mb-3 col">
                                            <button type="button" class="btn btn-sm btn-primary" id="add-contractor">+ Add Contractor</button>
                                        </div>

                                
                                        <div class="form-actions right">
                                            <a href="{{ route('admin.jobs') }}" class="theme-btn btn btn-primary">
                                                <i class="la la-times"></i> Cancel
                                            </a>
                                            <button type="submit" class="theme-btn btn btn-primary">
                                                <i class="la la-check-square-o"></i> Update
                                            </button>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
<script>
    // Initial contractor row count (based on existing loaded data)
    let contractorIndex = {{ isset($contractorDetails) ? count($contractorDetails) : 1 }};

    document.getElementById('add-contractor').addEventListener('click', function () {
        const container = document.getElementById('contractor-rows');

        const newRow = document.createElement('div');
        newRow.classList.add('contractor-row', 'd-flex', 'mb-2');

        newRow.innerHTML = `
            <div class="col-md-6">
                <select name="contractors[${contractorIndex}][contractor_id]" class="form-control contractor-select">
                    <option value="">Select Contractor</option>
                    @foreach($contractors as $contractor)
                        <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <label class="mb-0 mr-2">
                    <span class="text-danger" style="font-size: 1.2rem;">🏅</span> Won contract?
                </label>
                <input type="radio" name="won_contract_global" value="${contractorIndex}" class="won-contract-radio">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </div>
        `;

        container.appendChild(newRow);

        // Re-initialize Select2 if you're using it
        if ($.fn.select2) {
            $(newRow).find('select').select2();
        }

        contractorIndex++;
    });

    // Delegate removal of dynamically added rows
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            const row = e.target.closest('.contractor-row');
            const radio = row.querySelector('.won-contract-radio');
            if (radio && radio.checked) {
                // Clear the selection if this row had the selected radio
                radio.checked = false;
            }
            row.remove();
        }
    });
</script>

@endsection

