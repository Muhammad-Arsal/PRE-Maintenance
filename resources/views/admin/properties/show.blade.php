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
            <div class="card mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Details</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold w-25">Landlord:</th>
                            <td class="w-25">
                                @if(isset($property->landlord) && isset($property->landlord->id))
                                    <a href="{{ route('admin.settings.landlords.show', $property->landlord->id) }}">{{ $property->landlord->name ?? 'Not Set' }}</a>
                                @else
                                    Not Set
                                @endif
                            </td>
                            <th class="font-weight-bold w-25">Tenant:</th>
                            <td class="w-25">
                                @if(isset($property->tenant) && isset($property->tenant->id))
                                    <a href="{{ route('admin.settings.tenants.show', $property->tenant->id) }}">{{ $property->tenant->name ?? 'Not Set' }}</a>
                                @else
                                    Not Set
                                @endif
                            </td>
                        </tr>                        
                        <tr>
                            <th class="font-weight-bold w-25">Weekly rent:</th>
                            <td class="w-25">£ {{ isset($property->monthly_rent) ? number_format($property->monthly_rent / 4, 2) : 'Not Set' }}</td>
                            <th class="font-weight-bold w-25">Monthly rent:</th>
                            <td class="w-25">£ {{ isset($property->monthly_rent) ? number_format($property->monthly_rent, 2) : 'Not Set' }}</td>
                        </tr>                        
                        <tr>
                            <th class="font-weight-bold w-25">Type:</th>
                            <td class="w-25">{{$property->type}}</td>
                            <th class="font-weight-bold w-25">No. bedrooms:</th>
                            <td class="w-25">{{$property->bedrooms}}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold w-25">Management Charge For Maintenance:</th>
                            <td class="w-25">{{$property->management_charge == 1 ? "Yes" : "No"}}</td>
                            <th class="font-weight-bold w-25">Is active:</th>
                            <td class="w-25">{{$property->status == "Active" ? "Yes" : "No"}}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold w-25">Gas certificate Due:</th>
                            <td class="w-25">{{ $property->gas_certificate_due ? \Carbon\Carbon::parse($property->gas_certificate_due)->format('d/m/Y') : 'Not Set' }}</td>
                            <th class="font-weight-bold w-25">Is furnished:</th>
                            <td class="w-25">{{$property->is_furnished == 1 ? "Yes" : "No"}}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold w-25">No. floors:</th>
                            <td class="w-25">{{$property->number_of_floors ?? "Not Set"}}</td>
                            <th class="font-weight-bold w-25">Has garage:</th>
                            <td class="w-25">{{$property->has_garage == 1 ? "Yes" : "No"}}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold w-25">Has parking:</th>
                            <td class="w-25">{{$property->has_parking == 1 ? "Yes" : "No"}}</td>
                            <th class="font-weight-bold w-25">Rent safe month:</th>
                            <td class="w-25">{{$property->rent_safe_month ?? "Not Set"}}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold w-25">Has garden:</th>
                            <td class="w-25">{{$property->has_garden == 1 ? "Yes" : "No"}}</td>
                        </tr>
                    </tbody>
                </table>
                                
            </div>  

            <div class="card mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Address</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold ">Line 1:</th>
                            <td class="">{{ $property->line1 ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">Line 2:</th>
                            <td class="">{{ $property->line2 ?? 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Line 3:</th>
                            <td class="">{{ $property->line3 ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">City:</th>
                            <td class="">{{ $property->city }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">County:</th>
                            <td class="">{{ $property->county }}</td>
                            <th class="font-weight-bold ">Post code:</th>
                            <td class="">{{ $property->postcode }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Country:</th>
                            <td class="" colspan="3">{{ $property->country ?? 'Not set' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>            
            
            <div class="form-group mb-3">
                <label for="notes"><strong>Notes:</strong></label>
                <textarea id="notes" class="form-control" rows="4" readonly>{{ $property->note }}</textarea>
            </div>

            <div class="form-actions right">
                <a href="{{route('admin.properties')}}" class="theme-btn btn btn-primary">
                    <i class="la la-times"></i> Back
                </a>
            </div>
                                   
        </div>        
    </div>
@endsection
