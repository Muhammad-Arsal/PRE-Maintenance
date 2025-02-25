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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a></li>
                        <li class="breadcrumb-item active">View Property</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid p-0 m-0">        
            <!-- Details Section -->
            <div class="card mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Details</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $landlord->title . ' ' . $landlord->name }}</p>
                            <p><strong>Mobile phone:</strong> {{ $landlord->profile->phone_number ?? 'Not set' }}</p>
                            <p><strong>Work phone:</strong> {{ $landlord->work_phone ?? 'Not set' }}</p>
                            <p><strong>Home phone:</strong> {{ $landlord->home_phone ?? 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Company name:</strong> {{ $landlord->company_name ?? 'Not set' }}</p>
                            <p><strong>Commission rate:</strong> {{ $landlord->commission_rate ? $landlord->commission_rate . '%' : 'Not set' }}</p>
                            <p><strong>E-mail:</strong> 
                                @if($landlord->email)
                                    <a href="mailto:{{ $landlord->email }}">{{ $landlord->email }}</a>
                                @else
                                    Not set
                                @endif
                            </p>
                            <p><strong>Is active:</strong> 
                                <span class="{{ $landlord->status ? 'text-success font-weight-bold' : 'text-danger' }}">
                                    {{ $landlord->status ? '✔' : '✖' }}
                                </span>
                            </p>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Address</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Line 1:</strong> {{ $landlord->line1 ?? 'Not set' }}</p>
                            <p><strong>Line 2:</strong> {{ $landlord->line2 ?? 'Not set' }}</p>
                            <p><strong>Line 3:</strong> {{ $landlord->line3 ?? 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>City:</strong> {{ $landlord->city }}</p>
                            <p><strong>County:</strong> {{ $landlord->county }}</p>
                            <p><strong>Post code:</strong> {{ $landlord->postcode }}</p>
                            <p><strong>Country:</strong> {{ $landlord->country ?? 'Not set' }}</p>
                        </div>                        
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="text-white" style="background-color:  #041E41;">
                    <tr>
                        <th>ID</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Tenant</th>
                        <th>Created</th>
                        <th>Modified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($landlord->property as $property)
                    <tr>
                        <td><a href="{{route('admin.properties.show',$property->id)}}">{{$property->id}}</a></td>
                        <td>{{$property->line1 . ', ' . $property->city . ', ' . $property->county . ', ' . $property->postcode}}</td>
                        <td>{{$property->type}}</td>
                        <td>
                            @if(isset($property->tenant) && $property->tenant->id)
                                <a href="{{ route('admin.settings.tenants.edit', $property->tenant->id) }}">
                                    {{ $property->tenant->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>                        
                        <td>{{date("d/m/Y", strtotime($property->created_at))}}</td>
                        <td>{{date("d/m/Y", strtotime($property->updated_at))}}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No properties found.</td>
                        </tr>
                    @endforelse
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-center font-weight-bold">
                                {{ $landlord->property->count() }} properties found
                            </td>
                        </tr>
                    </tfoot>
                </tbody>
            </table>
            <div class="form-group">
                <label for="notes"><strong>Notes:</strong></label>
                <textarea id="notes" class="form-control" rows="4" readonly>{{ $landlord->note }}</textarea>
            </div> 
            <div class="card">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Remittance Report</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Month:</strong></label>
                            <select class="form-control">
                                @php
                                    $months = [
                                        'January', 'February', 'March', 'April', 'May', 'June',
                                        'July', 'August', 'September', 'October', 'November', 'December'
                                    ];
                                    $currentMonth = date('F');
                                @endphp
                                @foreach($months as $month)
                                    <option {{ $month == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Year:</strong></label>
                            <select class="form-control">
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                <option selected>{{ $currentYear }}</option>
                                <option>{{ $currentYear - 1 }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">Show VAT?</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn" style="background-color: rgb(0,190,214);">
                            <i class="la la-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Annual Statement</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label><strong>Year:</strong></label>
                            <select class="form-control">
                                <option selected>{{ $currentYear - 1 }} - {{ $currentYear }}</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">Separate VAT?</label>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">HMRC Statement?</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn" style="background-color: rgb(0,190,214);">
                            <i class="la la-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>                       
        </div>        
    </div>
@endsection
