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
                        <li class="breadcrumb-item active">View Landlord</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid p-0 m-0">        
            <div class="card">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Details</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold ">Name:</th>
                            <td class="">{{ $landlord->title . ' ' . $landlord->name }}</td>
                            <th class="font-weight-bold ">Mobile phone:</th>
                            <td class="">{{ $landlord->profile->phone_number ?? 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Work phone:</th>
                            <td class="">{{ $landlord->work_phone ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">Home phone:</th>
                            <td class="">{{ $landlord->home_phone ?? 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Company name:</th>
                            <td class="">{{ $landlord->company_name ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">Commission rate:</th>
                            <td class="">{{ $landlord->commission_rate ? $landlord->commission_rate . '%' : 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">E-mail:</th>
                            <td class="">{{ $landlord->email ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">Is active:</th>
                            <td class="font-weight-bold ">{{ $landlord->status ? 'Yes' : 'No' }}</td>
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
                            <td class="">{{ $landlord->line1 ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">Line 2:</th>
                            <td class="">{{ $landlord->line2 ?? 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Line 3:</th>
                            <td class="">{{ $landlord->line3 ?? 'Not set' }}</td>
                            <th class="font-weight-bold ">City:</th>
                            <td class="">{{ $landlord->city }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">County:</th>
                            <td class="">{{ $landlord->county }}</td>
                            <th class="font-weight-bold ">Post code:</th>
                            <td class="">{{ $landlord->postcode }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Country:</th>
                            <td class="" colspan="3">{{ $landlord->country ?? 'Not set' }}</td>
                        </tr>
                    </tbody>
                </table>
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
            <div class="form-group mb-3">
                <label for="notes"><strong>Notes:</strong></label>
                <textarea id="notes" class="form-control" rows="4" readonly>{{ $landlord->note }}</textarea>
            </div> 

            <div class="form-actions right">
                <a href="{{route('admin.settings.landlords')}}" class="theme-btn btn btn-primary">
                    <i class="la la-times"></i> Back
                </a>
            </div>
                                   
        </div>        
    </div>
@endsection
