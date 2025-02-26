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
                                    
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Job Details</strong></h3>
                                
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status"><span style="color: red;">*</span>Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="">Select Status</option>
                                                            <option value="Open" {{ old('status', $job->status) == 'Open' ? 'selected' : '' }}>Open</option>
                                                            <option value="Quote" {{ old('status', $job->status) == 'Quote' ? 'selected' : '' }}>Quote</option>
                                                            <option value="Partial" {{ old('status', $job->status) == 'Partial' ? 'selected' : '' }}>Partial</option>
                                                            <option value="Closed" {{ old('status', $job->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                                            <option value="Go Ahead" {{ old('status', $job->status) == 'Go Ahead' ? 'selected' : '' }}>Go Ahead</option>
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
                                
                                            <div class="col-md-6">
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
                                        </div>
                                
                                        <div class="row mb-2">
                                            <div class="col-md-6 d-flex flex-column">
                                                <label for="contractor">Contractor:</label>
                                                <select id="contractor" name="contractor_id" class="form-control select2">
                                                    <option value="">Select Contractor</option>
                                                    @foreach($contractors as $contractor)
                                                        <option value="{{ $contractor->id }}" {{ old('contractor_id', $job->contractor_id) == $contractor->id ? 'selected' : '' }}>
                                                            {{ $contractor->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                
                                            <div class="col-md-6 d-flex flex-column justify-content-end">
                                                <div class="d-flex align-items-center">
                                                    <label class="mb-0 mr-2">
                                                        <span class="text-danger" style="font-size: 1.2rem;">🏅</span> Won contract?
                                                    </label>
                                                    <input type="radio" name="won_contract" value="yes" {{ old('won_contract', $job->won_contract) == 'yes' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>                                        
                                                                        
                                        <div class="form-group">
                                            <label for="description"><span style="color: red;">*</span> Description</label>
                                            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $job->description) }}</textarea>
                                            @error('description') 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
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

