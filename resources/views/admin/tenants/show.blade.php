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
                        <li class="breadcrumb-item active">View Tenant</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">   
        <div class="container-fluid p-0 m-0">
            <table class="table table-bordered table-striped">
                <thead class="text-white" style="background-color:  #041E41;">
                    <tr>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Home Phone</th>
                        <th>Work Phone</th>
                        <th>Email Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $tenant->name ?? 'Not Set' }}</td>
                        <td>{{ $tenant->profile->phone_number ?? 'Not Set' }}</td>
                        <td>{{ $tenant->home_phone ?? 'Not Set' }}</td>
                        <td>{{ $tenant->work_phone ?? 'Not Set' }}</td>
                        <td>{{ $tenant->email ?? 'Not Set' }}</td>
                    </tr>  
                    @foreach ($tenant->details as $item)
                        <tr>
                            <td>{{ $item->name ?? 'Not Set' }}</td>
                            <td>{{ $item->phone_number ?? 'Not Set' }}</td>
                            <td>{{ $item->home_phone ?? 'Not Set' }}</td>
                            <td>{{ $item->work_phone ?? 'Not Set' }}</td>
                            <td>{{ $item->email ?? 'Not Set' }}</td>
                        </tr>    
                    @endforeach                  
                </tbody>
                </tbody>
            </table>

            <div class="card mt-3 mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Other Details</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold ">Active:</th>
                            <td class="">{{ $tenant->status == "Active" ? "Yes" : "No" }}</td>
                            <th></th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="card">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Details</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold">Property:</th>
                            <td>
                                <a href="{{ route('admin.properties.show', $tenant->property->id ?? '#') }}">
                                    {{ $tenant->property->line1 ?? 'Not Set' }},
                                    {{ $tenant->property->city ?? 'Not Set' }},
                                    {{ $tenant->property->county ?? 'Not Set' }},
                                    {{ $tenant->property->postcode ?? 'Not Set' }}
                                </a>
                            </td>
                            <th class="font-weight-bold">Landlord:</th>
                            <td>
                                <a href="{{route('admin.settings.landlords.show', $tenant->property->landlord->id)}}">
                                    {{ $tenant->property->landlord->name ?? 'Not Set' }}
                                </a>
                            </td>
                        </tr>                        
                        <tr>
                            <th class="font-weight-bold">Contract Start:</th>
                            <td>{{ $tenant->contract_start ? \Carbon\Carbon::parse($tenant->contract_start)->format('d M Y') : 'Not Set' }}</td>
                            <th class="font-weight-bold">Contract End:</th>
                            <td>{{ $tenant->contract_end ? \Carbon\Carbon::parse($tenant->contract_end)->format('d M Y') : 'Not Set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold">Monthly Rent:</th>
                            <td>{{ $tenant->property->monthly_rent ?? 'Not Set' }}</td>
                            <th class="font-weight-bold">Deposit:</th>
                            <td>{{ $tenant->deposit ?? 'Not Set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold">Adjust:</th>
                            <td>{{ $tenant->adjust ?? 'Not Set' }}</td>
                            <th class="font-weight-bold"></th>
                            <td class="font-weight-bold"></td>
                        </tr>
                    </tbody>                    
                </table>
            </div>

            <div class="form-group mt3-3">
                <label for="notes"><strong>Notes:</strong></label>
                <textarea id="notes" class="form-control" rows="4" readonly>{{ $tenant->note ?? '' }}</textarea>
            </div> 
        </div>   
    </div>
@endsection
