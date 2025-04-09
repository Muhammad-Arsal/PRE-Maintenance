@extends('tenant.partials.main')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        <section id="search-tenants">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('tenant.settings.tenants.edit', $tenant->id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="property" data-toggle="tab"
                                            aria-controls="property" href="#property" aria-expanded="true">Property</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('tenant.tenants.correspondence', $tenant->id)}}">Correspondence</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                @include('tenant.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='managetenant' action="{{ route('tenant.settings.tenants.update.property', $tenant->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="property"><span style="color: red;">*</span>Property</label>
                                                    <select id="property" name="property" class="form-control">
                                                        <option value="">Select Property</option>
                                                        @foreach($properties as $item)
                                                            <option value="{{ $item->id }}" {{ $tenant->property_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->line1 . ', ' . $item->city . ', ' . $item->county . ', ' . $item->postcode }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('property') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="contract_start"><span style="color: red;">*</span>Contract Start</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="contract_start" class="form-control datepicker" name="contract_start_display"
                                                            value="{{ old('contract_start_display', \Carbon\Carbon::parse($tenant->contract_start)->format('d/m/Y')) }}" placeholder="DD/MM/YYYY">
                                                        <input type="hidden" id="contract_start_hidden" name="contract_start"
                                                            value="{{ old('contract_start', $tenant->contract_start) }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('contract_start') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="contract_end"><span style="color: red;">*</span>Contract End</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="contract_end" class="form-control datepicker" name="contract_end_display"
                                                            value="{{ old('contract_end_display', \Carbon\Carbon::parse($tenant->contract_end)->format('d/m/Y')) }}" placeholder="DD/MM/YYYY">
                                                        <input type="hidden" id="contract_end_hidden" name="contract_end"
                                                            value="{{ old('contract_end', $tenant->contract_end) }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('contract_end') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="deposit">Deposit</label>
                                                    <div class="position-relative has-icon-left">
                                                        <div class="form-control-position" style="top: -2px;">£</div> <!-- Pound Symbol -->
                                                        <input type="text" id="deposit" class="form-control" placeholder="Enter deposit amount" name="deposit"
                                                            value="{{ old('deposit', $tenant->deposit) }}">
                                                    </div>
                                                    @error('deposit') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="date_left_property">Date Left Property</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="date_left_property" class="form-control datepicker" placeholder="DD/MM/YYYY" name="date_left_property_display"
                                                            value="{{ old('date_left_property_display', $tenant->left_property ? \Carbon\Carbon::parse($tenant->left_property)->format('d/m/Y') : '') }}">
                                                        <input type="hidden" id="date_left_property_hidden" name="date_left_property"
                                                            value="{{ old('date_left_property', $tenant->left_property) }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('date_left_property') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>                                      

                                        <div class="form-actions right">
                                            <a href="{{ route('tenant.settings.tenants.edit', auth('tenant')->id()) }}" class="theme-btn btn btn-primary">
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".datepicker").forEach(function (element) {
                flatpickr(element, {
                    dateFormat: "d/m/Y", // Display format
                    altInput: true,
                    altFormat: "d/m/Y",
                    allowInput: true,
                    onChange: function (selectedDates, dateStr, instance) {
                        let hiddenInput = document.getElementById(element.id + "_hidden");
                        if (hiddenInput) {
                            hiddenInput.value = selectedDates.length ? instance.formatDate(selectedDates[0], "Y-m-d") : "";
                            console.log(hiddenInput.name, hiddenInput.value); // Debugging: Log hidden field value
                            $(hiddenInput).valid(); // Trigger validation
                        }
                    }
                });
            });
    });

    $(document).ready(function () {
        $("#manageTenant").validate({
            rules: {
                "deposit": { 
                    number: true
                },
                "property": { required: true },
                "contract_start_hidden": { required: true, date: true },
                "contract_end_hidden": { required: true, date: true }
            },
            messages: {
                "deposit": { 
                    number: "Please enter a valid number"
                },
                "property": { required: "Property is required" },
                "contract_start_hidden": { 
                    required: "Contract start date is required", 
                    date: "Please enter a valid date" 
                },
                "contract_end_hidden": { 
                    required: "Contract end date is required", 
                    date: "Please enter a valid date" 
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "contract_start_hidden" || element.attr("name") === "contract_end_hidden") {
                    // Place error message after the visible field's container
                    error.insertAfter(element.closest('.position-relative'));
                } else {
                    error.insertAfter(element);
                }
            },
        });
    });
</script>

<script>
    $(function() {
        $('.select2').select2();
    })
</script>
@endsection
