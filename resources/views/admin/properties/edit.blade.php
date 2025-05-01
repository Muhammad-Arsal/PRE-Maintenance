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
        <section id="search-landlords">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="overview" data-toggle="tab"
                                            aria-controls="overview" href="#overview" aria-expanded="true">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.edit.address', $property->id) }}">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.diary', $property->id) }}">Diary</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.viewjobs', $property->id) }}">Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.invoices.index', $property->id) }}">Invoices</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.propertys.correspondence', $property->id) }}">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.current.tenant', $property->id) }}">Current Tenant</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.past.tenant', $property->id) }}">Past Tenants</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="POST" enctype="multipart/form-data" id='manageProperties'  action="{{route('admin.properties.update',  $property->id)}}">
                                    @csrf
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Detail</strong></h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="landlord">Landlord</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="landlord" class="form-control" name="landlord">
                                                            <option value="">Select Landlord</option>
                                                            @foreach($landlords as $landlord)
                                                                <option value="{{ $landlord->id }}" {{ old('landlord', $property->landlord_id ?? '') == $landlord->id ? 'selected' : '' }}>
                                                                    {{ $landlord->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @error('landlord') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="property_type">Type</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="property_type" name="property_type" class="form-control">
                                                            <option value="">Select Property Type</option>
                                                            @foreach($property_types as $type)
                                                                <option value="{{ $type->name }}" {{ old('property_type', $property->type ?? '') == $type->name ? 'selected' : '' }}>
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @error('property_type') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>                                            

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="bedrooms">No. of Bedrooms</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="number" id="bedrooms" class="form-control" placeholder="Number of Bedrooms" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-bed"></i>
                                                        </div>
                                                    </div>
                                                    @error('bedrooms') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="monthly_rent">Monthly Rent</label>
                                                    <div class="position-relative has-icon-left">
                                                        <div class="form-control-position" style="top: -2px;">£</div> <!-- Pound Symbol -->
                                                        <input type="text" id="monthly_rent" class="form-control" placeholder="Monthly Rent" name="monthly_rent" 
                                                            value="{{ old('monthly_rent', $property->monthly_rent ?? '') }}">
                                                    </div>
                                                    @error('monthly_rent') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="number_of_floors">Number of Floors</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="number_of_floors" class="form-control" placeholder="Number of Floors" name="number_of_floors" value="{{ old('number_of_floors', $property->number_of_floors ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @error('number_of_floors') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="gas_certificate_due">Gas Certificate Due</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="gas_certificate_due" class="form-control datepicker" name="gas_certificate_due_display"
                                                            value="{{ old('gas_certificate_due', $property->gas_certificate_due ? \Carbon\Carbon::parse($property->gas_certificate_due)->format('d/m/Y') : '') }}"
                                                            placeholder="DD/MM/YYYY">
                                                        <input type="hidden" id="gas_certificate_due_hidden" name="gas_certificate_due"
                                                            value="{{ old('gas_certificate_due', $property->gas_certificate_due ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('gas_certificate_due') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="eicr_due">EICR Due</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="eicr_due" class="form-control datepicker" name="eicr_due_display"
                                                            value="{{ old('eicr_due', $property->eicr_due ? \Carbon\Carbon::parse($property->eicr_due)->format('d/m/Y') : '') }}"
                                                            placeholder="DD/MM/YYYY">
                                                        <input type="hidden" id="eicr_due_hidden" name="eicr_due"
                                                            value="{{ old('eicr_due', $property->eicr_due ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('eicr_due') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="epc_due">EPC Due</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="epc_due" class="form-control datepicker" name="epc_due_display"
                                                            value="{{ old('epc_due', $property->epc_due ? \Carbon\Carbon::parse($property->epc_due)->format('d/m/Y') : '') }}"
                                                            placeholder="DD/MM/YYYY">
                                                        <input type="hidden" id="epc_due_hidden" name="epc_due"
                                                            value="{{ old('epc_due', $property->epc_due ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('epc_due') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="epc_rate">EPC Rate</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="epc_rate" name="epc_rate" class="form-control">
                                                            <option value="">Select EPC Rate</option>
                                                            @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $rate)
                                                                <option value="{{ $rate }}" {{ old('epc_rate', $property->epc_rate ?? '') == $rate ? 'selected' : '' }}>
                                                                    {{ $rate }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-bar-chart"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('epc_rate'))
                                                        <p class="text-danger">{{ $errors->first('epc_rate') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="Active" {{ old('status', $property->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ old('status', $property->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-lock"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('status'))
                                                       <p class="text-danger">{{ $errors->first('status') }}</p>
                                                    @endif
                                                </div>
                                            </div> 
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" id="has_garden" class="form-check-input" name="has_garden" value="1" {{ old('has_garden', $property->has_garden ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="has_garden">Has Garden</label>
                                                    @error('has_garden') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" id="management_charge" class="form-check-input" name="management_charge" value="1" {{ old('management_charge', $property->management_charge ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="management_charge">Management Charge for Maintenance</label>
                                                    @error('management_charge') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" id="has_garage" class="form-check-input" name="has_garage" value="1" {{ old('has_garage', $property->has_garage ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="has_garage">Has Garage</label>
                                                    @error('has_garage') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" id="has_parking" class="form-check-input" name="has_parking" value="1" {{ old('has_parking', $property->has_parking ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="has_parking">Has Parking</label>
                                                    @error('has_parking') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" id="is_furnished" class="form-check-input" name="is_furnished" value="1" {{ old('is_furnished', $property->is_furnished ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_furnished">Is Furnished</label>
                                                    @error('is_furnished') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="form-actions right">
                                            <a href="{{route('admin.properties')}}" class="theme-btn btn btn-primary">
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
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function () {
        $('#monthly_rent').on('input', function () {
            $(this).val($(this).val().replace(/[^0-9.]|(\..*)\./g, '$1'));
        });
        $('#number_of_floors').on('input', function () {
            $(this).val($(this).val().replace(/[^0-9.]|(\..*)\./g, '$1'));
        });
        $('#bedrooms').on('input', function () {
            $(this).val($(this).val().replace(/[^0-9.]|(\..*)\./g, '$1'));
        });
    });
    $(document).ready(function () {
        $("#manageProperties").validate({
            rules: {
                landlord: {
                    required: true
                },
                property_type: {
                    required: true
                },
                bedrooms: {
                    required: true,
                    number: true,
                    min: 1
                },
            },
            messages: {
                landlord: "Please select a landlord.",
                property_type: "Please select a property type.",
                bedrooms: {
                    required: "Number of bedrooms is required.",
                    number: "Please enter a valid number.",
                    min: "Number of bedrooms must be at least 1."
                },
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger");
                if (element.parent(".position-relative").length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".datepicker").forEach(function (element) {
            flatpickr(element, {
                dateFormat: "d/m/Y", // Display format
                altInput: true,
                altFormat: "d/m/Y",
                allowInput: true,
                onChange: function (selectedDates, dateStr, instance) {
                    let hiddenInput = document.getElementById(element.name.replace("_display", "_hidden"));
                    if (hiddenInput) {
                        hiddenInput.value = selectedDates.length ? instance.formatDate(selectedDates[0], "Y-m-d") : "";
                    }
                }
            });
        });
    });
</script>



@endsection
