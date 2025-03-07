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
                        <li class="breadcrumb-item active">View Job</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">   
        <div class="container-fluid p-0 m-0">

            <div class="card mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Contractor Details</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold">Name:</th>
                            <td>{{ $job->contractor->name ?? 'Not Set' }}</td>
                            <th class="font-weight-bold">Job Sheet:</th>
                            <td>
                                <a href="#">View Job Sheet</a>
                            </td>                        
                        </tr>
                        <tr>
                            <th class="font-weight-bold">Contact Type:</th>
                            <td>{{$job->contractor->contact_type == "email" ? "Email" : "Fax"}}</td>
                            <th class="font-weight-bold">{{$job->contractor->contact_type == "email" ? "Email" : "Fax"}}</th>
                            <td>{{$job->contractor->contact_type == "email" ? $job->contractor->email : $job->contractor->fax}}</td>
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
                            <th class="font-weight-bold">Status:</th>
                            <td>{{ $job->status ?? 'Not Set' }}</td>
                            <th class="font-weight-bold">Property:</th>
                            <td>
                                <a href="{{ route('admin.properties.show', $job->property->id) }}">
                                    {{ $job->property->line1 . ', ' . $job->property->city . ', ' . $job->property->county . ', ' . $job->property->postcode }}
                                </a>
                            </td>                        </tr>
                    </tbody>                    
                </table>
            </div>

            <div class="form-group mt3-3">
                <label for="description"><strong>Description:</strong></label>
                <textarea id="description" class="form-control" rows="4" readonly>{{ $job->description ?? '' }}</textarea>
            </div> 
            <div class="form-group mt3-3">
                <label for="other_information"><strong>Other Information:</strong></label>
                <textarea id="other_information" class="form-control" rows="4" readonly>{{ $job->other_information ?? '' }}</textarea>
            </div> 

            <div class="form-actions right">
                <a href="{{route('admin.jobs')}}" class="theme-btn btn btn-primary">
                    <i class="la la-times"></i> Back
                </a>
            </div>
        </div>   
    </div>
@endsection
