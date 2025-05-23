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
                                <form method="POST" enctype="multipart/form-data" id="manageJobs" action="{{ route('admin.jobs.update', $job->id) }}">
                                    @csrf
                                    <div class="card-header">
                                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                                            <li class="nav-item">
                                                <a class="nav-link active disabled" id="overview" data-toggle="tab"
                                                    aria-controls="overview" href="#overview" aria-expanded="true">Overview</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('admin.jobs.edit.contractorList', $job->id) }}">Contractor List</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Job Details</strong></h3>
                                
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status"><span style="color: red;">*</span>Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="">Select Status</option>
                                                            <option value="Quote required" {{ old('status', $job->status ?? '') == 'Quote required' ? 'selected' : '' }}>Quote required</option>
                                                            <option value="1 quote received" {{ old('status', $job->status ?? '') == '1 quote received' ? 'selected' : '' }}>1 quote received</option>
                                                            <option value="2 quotes received" {{ old('status', $job->status ?? '') == '2 quotes received' ? 'selected' : '' }}>2 quotes received</option>
                                                            <option value="3 quotes received" {{ old('status', $job->status ?? '') == '3 quotes received' ? 'selected' : '' }}>3 quotes received</option>
                                                            <option value="Additional quote requested" {{ old('status', $job->status ?? '') == 'Additional quote requested' ? 'selected' : '' }}>Additional quote requested</option>
                                                            <option value="Quotes sent to landlord" {{ old('status', $job->status ?? '') == 'Quotes sent to landlord' ? 'selected' : '' }}>Quotes sent to landlord</option>
                                                            <option value="Contractor completed" {{ old('status', $job->status ?? '') == 'Contractor completed' ? 'selected' : '' }}>Contractor completed</option>
                                                            <option value="Contractor Invoiced" {{ old('status', $job->status ?? '') == 'Contractor Invoiced' ? 'selected' : '' }}>Contractor Invoiced</option>
                                                            <option value="Closed - completed & paid" {{ old('status', $job->status ?? '') == 'Closed - completed & paid' ? 'selected' : '' }}>Closed - completed & paid</option>
                                                            <option value="Closed - no response from tenant" {{ old('status', $job->status ?? '') == 'Closed - no response from tenant' ? 'selected' : '' }}>Closed - no response from tenant</option>
                                                            <option value="Closed - other" {{ old('status', $job->status ?? '') == 'Closed - other' ? 'selected' : '' }}>Closed - other</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-list"></i>
                                                        </div>
                                                    </div>
                                                    @error('status') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>                                               
                                            </div>
                                
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="property"><span style="color: red;">*</span>Property</label>
                                                    <select id="property" name="property_id" class="form-control select2">
                                                        <option value="">Select Property</option>
                                                        @foreach($properties as $item)
                                                            <option value="{{ $item->id }}" {{ old('property_id', $job->property_id) == $item->id ? 'selected' : '' }}>
                                                                {{ $item->line1 . ', ' . $item->city . ', ' . $item->county . ', ' . $item->postcode }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('property_id') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>                                              
                                            </div>  
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="priority"><span style="color: red;">*</span>Priority</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="priority" name="priority" class="form-control">
                                                            <option value="">Select Priority</option>
                                            
                                                            <option value="Risk to Life/ Property - 4 hours"
                                                                {{ old('priority', $job->priority ?? '') == 'Risk to Life/ Property - 4 hours' ? 'selected' : '' }}>
                                                                Risk to Life/ Property – 4 hours
                                                            </option>
                                            
                                                            <option value="Emergency - 24 Hours"
                                                                {{ old('priority', $job->priority ?? '') == 'Emergency - 24 Hours' ? 'selected' : '' }}>
                                                                Emergency – 24 Hours
                                                            </option>
                                            
                                                            <option value="Urgent - 5 days"
                                                                {{ old('priority', $job->priority ?? '') == 'Urgent - 5 days' ? 'selected' : '' }}>
                                                                Urgent – 5 days
                                                            </option>
                                            
                                                            <option value="Routine - 28 days"
                                                                {{ old('priority', $job->priority ?? '') == 'Routine - 28 days' ? 'selected' : '' }}>
                                                                Routine – 28 days
                                                            </option>
                                                        </select>
                                            
                                                        <div class="form-control-position">
                                                            <i class="la la-exclamation-triangle"></i>
                                                        </div>
                                                    </div>
                                            
                                                    @error('priority')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div> 
                                        </div>
                                                                        
                                        <div class="form-group">
                                            <label for="other_information">Other Information</label>
                                            <textarea id="other_information" name="other_information" class="form-control" rows="4">{{ old('other_information', $job->other_information) }}</textarea>
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
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script>
    $(function() {
        $('.select2').select2();
    })
</script>

<script>
    $(document).ready(function () {
        $("#manageJobs").validate({
            rules: {
                status: {
                    required: true
                },
                priority: {
                    required: true
                },
                property_id: {
                    required: true
                },
                description: {
                    required: true,
                }
            },
            messages: {
                status: {
                    required: "Please select a status."
                },
                priority: {
                    required: "Please select a priority."
                }, 
                property_id: {
                    required: "Please select a property."
                },
                description: {
                    required: "Please enter a description.",
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger"); // Add Bootstrap error styling
                if (element.parent(".position-relative").length) {
                    error.insertAfter(element.parent()); // For icon inputs
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>

@endsection

