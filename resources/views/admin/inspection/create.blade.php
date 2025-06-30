@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ $page['page_parent_link'] }}"
                                class="theme-color">{{ $page['page_parent'] }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="search-admins">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='manageAdmin'  action="{{ route('admin.inspection.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            {{-- Report Type --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="report_type">Report Type</label>
                                                <div class="position-relative has-icon-left">
                                                    <select id="report_type" name="report_type" class="form-control">
                                                    <option value="">Select Report Type</option>
                                                    <option value="Inventory">Inventory</option>
                                                    <option value="Check In">Check In</option>
                                                    <option value="Check Out">Check Out</option>
                                                    <option value="Property">Property</option>
                                                    </select>
                                                    <div class="form-control-position">
                                                    <i class="la la-file-text"></i>
                                                    </div>
                                                </div>
                                                {{-- error message --}}
                                                <span class="text-danger">@error('report_type'){{ $message }}@enderror</span>
                                                </div>
                                            </div>

                                            {{-- Assign To --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="assign_to">Assign To</label>
                                                <div class="position-relative has-icon-left">
                                                    <select id="assign_to" name="assign_to" class="form-control">
                                                    <option value="">Select Assignee</option>
                                                    @foreach ($allAdmins as $admin)
                                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                    @endforeach
                                                    </select>
                                                    <div class="form-control-position">
                                                    <i class="la la-user-plus"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger">@error('assign_to'){{ $message }}@enderror</span>
                                                </div>
                                            </div>

                                            {{-- Property --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="property_id">Property</label>
                                                <div class="position-relative has-icon-left">
                                                    <select id="property_id" name="property_id" class="form-control">
                                                    <option value="">Select Property</option>
                                                    @foreach ($allProperties as $property)
                                                        <option value="{{ $property->id }}">{{ $property->line1 . ', ' . $property->city . ', ' . $property->county . ', ' . $property->postcode }}</option>
                                                    @endforeach
                                                    </select>
                                                    <div class="form-control-position">
                                                    <i class="la la-building"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger">@error('property_id'){{ $message }}@enderror</span>
                                                </div>
                                            </div>

                                            {{-- Template --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="template_id">Template</label>
                                                <div class="position-relative has-icon-left">
                                                    <select id="template_id" name="template_id" class="form-control">
                                                    <option value="">Select Template</option>
                                                    @foreach ($allTemplates as $template)
                                                        <option value="{{ $template->id }}">{{ $template->title }}</option> 
                                                    @endforeach
                                                    </select>
                                                    <div class="form-control-position">
                                                    <i class="la la-file-o"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger">@error('template_id'){{ $message }}@enderror</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="report_date">Report Date</label>
                                                <div class="position-relative has-icon-left">
                                                    <input
                                                    type="text"
                                                    id="report_date"
                                                    name="report_date"
                                                    class="form-control flatpickr-date"
                                                    placeholder="Select Date"
                                                    value="{{ old('report_date', $contractor->report_date ?? '') }}"
                                                    />
                                                    <div class="form-control-position">
                                                    <i class="la la-calendar"></i>
                                                    </div>
                                                </div>
                                                @error('report_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                </div>
                                            </div>

                                            {{-- Time Duration --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label for="time_duration">Time Duration</label>
                                                <div class="position-relative has-icon-left">
                                                    <input
                                                    type="text"
                                                    id="time_duration"
                                                    name="time_duration"
                                                    class="form-control"
                                                    placeholder="e.g. 2 hours, 30m"
                                                    value="{{ old('time_duration', $contractor->time_duration ?? '') }}"
                                                    />
                                                    <div class="form-control-position">
                                                    <i class="la la-clock-o"></i>
                                                    </div>
                                                </div>
                                                @error('time_duration')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-actions right">
                                            <a href="" class="theme-btn btn btn-primary">
                                                <i class="la la-times"></i> Cancel
                                            </a>
                                            <button type="submit" class="theme-btn btn btn-primary">
                                                <i class="la la-check-square-o"></i> Save
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Initialize Flatpickr
        flatpickr('.flatpickr-date', {
            dateFormat: 'd/m/Y',
            allowInput: true
        });

        // jQuery Validation for both forms
        $('#manageAdmin, #inspectionForm').validate({
            rules: {
                report_type: { required: true },
                assign_to:   { required: true },
                property_id: { required: true },
                template_id: { required: true },
                report_date: { required: true, date: true },
                time_duration: { required: true, maxlength: 50 },
            },
            messages: {
                report_type:    { required: 'Please select a report type.' },
                assign_to:      { required: 'Please select an assignee.' },
                property_id:    { required: 'Please select a property.' },
                template_id:    { required: 'Please select a template.' },
                report_date:    { required: 'Please pick a date.', date: 'Please enter a valid date.' },
                time_duration:  { required: 'Please enter a time duration.', maxlength: 'Maximum 50 characters allowed.' },
            },
            errorElement: 'span',
            errorClass: 'text-danger',
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            }
        });
    });
</script>
@endsection

