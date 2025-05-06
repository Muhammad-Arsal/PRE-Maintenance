@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='manageInvoices'  action="{{route('admin.invoices.store')}}">
                                    @csrf
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Details</strong></h3>
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="property"><span style="color: red;">*</span>Property</label>
                                                    <select id="property" name="property" class="form-control select2">
                                                        <option value="">Select Property</option>
                                                        @foreach($properties as $item)
                                                            <option value="{{$item->id }}">{{ $item->line1 . ', ' . $item->city . ', ' . $item->county . ', ' . $item->postcode }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('property') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="contractor"><span style="color: red;">*</span>Contractor</label>
                                                    <select id="contractor" name="contractor" class="form-control select2">
                                                        <option value="">Select Contractor</option>
                                                        @foreach($contractors as $item)
                                                            <option value="{{$item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('contractor') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="job"><span style="color: red;">*</span>Job</label>
                                                    <select id="job" name="job" class="form-control select2">
                                                        <option value="">Select Job</option>
                                                        @foreach($jobs as $job)
                                                            <option value="{{ $job->id }}">
                                                                {{ $job->description . "  -  " . $job->property->line1 . ', ' . $job->property->city . ', ' . $job->property->county . ', ' . $job->property->postcode ?? 'Job #' . $job->id }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('job') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="reference">Reference</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="reference" class="form-control" placeholder="Reference" name="reference" value="{{ old('reference', $property->reference ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-tag"></i> 
                                                        </div>
                                                    </div>
                                                    @error('reference') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="date"><span style="color: red;">*</span>Date</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="date" class="form-control flatpickr" placeholder="DD/MM/YYYY" name="date" value="">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>


                                        </div>
                                        <h3 class="mb-2"><strong>Amount</strong></h3>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="subtotal">Subtotal</label>
                                                    <div class="position-relative has-icon-left">
                                                        <div class="form-control-position" style="top: -2px;">£</div> <!-- Pound Symbol -->
                                                        <input type="text" id="subtotal" class="form-control pl-4 auto-format" placeholder="Subtotal" name="subtotal" value="0.00">

                                                    </div>
                                                    @error('subtotal') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="vat_rate">VAT Rate (%)</label>
                                                    <div class="position-relative has-icon-left">
                                                        <div class="form-control-position" style="top: -2px;">%</div> <!-- Percentage Symbol -->
                                                        <input type="text" id="vat_rate" class="form-control pl-4 auto-format" placeholder="VAT Rate" name="vat_rate" 
                                                            value="{{ number_format(\DB::table('general_settings')->value('vat_rate'), 2) }}">

                                                    </div>
                                                    @error('vat_rate') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="vat">VAT</label>
                                                    <div class="position-relative has-icon-left">
                                                        <div class="form-control-position" style="top: -2px;">£</div> <!-- Pound Symbol -->
                                                        <input type="text" id="vat" class="form-control pl-4" placeholder="VAT" name="vat" 
                                                            value="0.00">
                                                    </div>
                                                    @error('vat') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="total">Total</label>
                                                    <div class="position-relative has-icon-left">
                                                        <div class="form-control-position" style="top: -2px;">£</div> <!-- Pound Symbol -->
                                                        <input type="text" id="total" class="form-control pl-4" placeholder="Total" name="total" 
                                                            value="0.00">
                                                    </div>
                                                    @error('total') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="vat_applicable">VAT Applicable</label>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="vat_applicable" class="custom-control-input" name="vat_applicable" 
                                                            value="yes" checked>
                                                        <label class="custom-control-label" for="vat_applicable">Is VAT applicable?</label>
                                                    </div>
                                                    @error('vat_applicable') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>                                            
                                            
                                        </div>
                                        <h3 class="mb-2"><strong>Address</strong></h3>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="entered_address" name="address_option" class="custom-control-input" 
                                                                value="entered" checked>
                                                            <label class="custom-control-label" for="entered_address">Use entered address?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="property_address" name="address_option" class="custom-control-input" 
                                                                value="property">
                                                            <label class="custom-control-label" for="property_address">Use property's address?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="landlord_address" name="address_option" class="custom-control-input" 
                                                                value="landlord">
                                                            <label class="custom-control-label" for="landlord_address">Use landlord's address?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_1"><span style="color: red;">*</span>Address Line 1</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_1" class="form-control" placeholder="Address Line 1" name="address_line_1" value="{{ old('address_line_1', $property->address_line_1 ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map-marker"></i>
                                                        </div>
                                                    </div>
                                                    @error('address_line_1') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_2">Address Line 2</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_2" class="form-control" placeholder="Address Line 2" name="address_line_2" value="{{ old('address_line_2', $property->address_line_2 ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map-marker"></i>
                                                        </div>
                                                    </div>
                                                    @error('address_line_2') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_3">Address Line 3</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_3" class="form-control" placeholder="Address Line 3" name="address_line_3" value="{{ old('address_line_3', $property->address_line_3 ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map-marker"></i>
                                                        </div>
                                                    </div>
                                                    @error('address_line_3') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>                                        
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="city"><span style="color: red;">*</span>City</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="city" class="form-control" placeholder="City" name="city" value="{{ old('city', $property->city ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="county"><span style="color: red;">*</span>County</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="county" class="form-control" placeholder="County" name="county" value="{{ old('county', $property->county ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map"></i>
                                                        </div>
                                                    </div>
                                                    @error('county') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="postal_code"><span style="color: red;">*</span>Postal Code</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="postal_code" class="form-control" placeholder="Postal Code" name="postal_code" value="{{ old('postal_code', $property->postal_code ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-envelope"></i>
                                                        </div>
                                                    </div>
                                                    @error('postal_code') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="country"><span style="color: red;">*</span>Country</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="country" class="form-control" placeholder="Country" 
                                                            name="country" value="{{ old('country', $property->country ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-globe"></i>
                                                        </div>
                                                    </div>
                                                    @error('country') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>                                            
                                        </div>

                                        <div class="form-group">
                                            <label for="description"><span style="color: red;">*</span>Description</label>
                                            <textarea id="description" class="form-control" name="description" rows="4" placeholder="Enter your description here...">{{ old('description', $property->description ?? '') }}</textarea>
                                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-actions right">
                                            <a href="{{route('admin.invoices')}}" class="theme-btn btn btn-primary">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    function formatToFixed(input) {
        let value = parseFloat(input.value);
        if (!isNaN(value)) {
            input.value = value.toFixed(2);
        } else {
            input.value = "0.00";
        }
    }

    document.getElementById('subtotal').addEventListener('change', function() {
        formatToFixed(this);
    });

    document.getElementById('vat_rate').addEventListener('change', function() {
        formatToFixed(this);
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(document).ready(function() {
        function validateNumberInput(selector) {
            $(selector).on("input", function() {
                let value = $(this).val();
                let validValue = value.match(/^\d*\.?\d{0,2}$/); // Allow numbers with up to 2 decimal places

                if (!validValue) {
                    $(this).val(value.slice(0, -1)); // Remove the last invalid character
                }
            });
        }

        // Apply validation to subtotal, vat_rate, vat, total
        validateNumberInput("#subtotal");
        validateNumberInput("#vat_rate");
        validateNumberInput("#vat");
        validateNumberInput("#total");
    });

    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date", {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    });

    $(document).ready(function () {
    // Initialize jQuery Validation
    $("#manageInvoices").validate({
        rules: {
            description: {
                required: true
            },
            job:{
                required: true
            },
            property: {
                required: true
            },
            contractor: {
                required: true
            },
            date: {
                required: true,
                date: true
            },
            subtotal: {
                required: true,
                number: true
            },
            vat_rate: {
                required: true,
                number: true,
                min: 0
            },
            vat: {
                required: true,
                number: true
            },
            total: {
                required: true,
                number: true
            },
            address_line_1: {
                required: true,
            },
            county: {
                required: true,
            },
            city: {
                required: true,
            },
            postal_code: {
                required: true,
            },
            country: {
                required: true,
            }
        },
        messages: {
            description: "Please enter a description",
            property: "Please select a property",
            contractor: "Please select a contractor",
            date: "Please enter a valid date",
            subtotal: "Please enter a valid subtotal",
            vat_rate: "Please enter a valid VAT rate",
            vat: "Please enter a valid VAT amount",
            total: "Please enter a valid total",
            address_line_1: "Address Line 1 is required",
            county: "County is required",
            city: "City is required",
            postal_code: "Postal Code is required",
            country: "Country is required",
            job: {
                required: "Please select a job."
            }
        },
    });

    // Revalidate form when address option changes
    $("input[name='address_option']").change(function () {
        $("#manageInvoices").valid();
    });
});

</script>


<script>
    $(document).ready(function() {
        function formatNumber(num) {
            return parseFloat(num || 0).toFixed(2);
        }

        function calculateVAT() {
            let subtotal = parseFloat($('#subtotal').val()) || 0;
            let vatRate = parseFloat($('#vat_rate').val()) || 0;
            let isVatApplicable = $('#vat_applicable').is(':checked');

            if (isVatApplicable) {
                let vat = (subtotal * vatRate) / 100;
                let total = subtotal + vat;

                $('#vat').val(formatNumber(vat)); 
                $('#total').val(formatNumber(total)); 
            } else {
                $('#vat').val(formatNumber(0));
                $('#total').val(formatNumber(subtotal));
            }
        }

        function enforceTwoDecimalPlaces() {
            $('.auto-format').each(function() {
                $(this).val(formatNumber($(this).val()));
            });
        }

        $('#subtotal, #vat_rate, #vat_applicable').on('input change', function() {
            calculateVAT();
        });

        $('.auto-format').on('blur', function() {
            $(this).val(formatNumber($(this).val()));
        });

        calculateVAT();
        enforceTwoDecimalPlaces();
    });
</script>

<script>
            $(document).ready(function () {
            $('input[name="address_option"]').change(function () {
                let selectedOption = $(this).val(); 
                let propertyId = $('#property').val(); 
                
                $('#address_line_1, #address_line_2, #address_line_3, #city, #county, #postal_code, #country').val('');

                if (selectedOption === 'entered') {
                    return;
                }

                if ((selectedOption === 'property' || selectedOption === 'landlord') && propertyId) {
                    $.ajax({
                        url: "{{ route('admin.get.address.details') }}",
                        type: "GET",
                        data: { 
                            property_id: propertyId, 
                            address_type: selectedOption 
                        },
                        success: function (response) {
                            if (response.success) {
                                let address = response.data;

                                $('#address_line_1').val(address.address_line_1);
                                $('#address_line_2').val(address.address_line_2);
                                $('#address_line_3').val(address.address_line_3);
                                $('#city').val(address.city);
                                $('#county').val(address.county);
                                $('#postal_code').val(address.postal_code);
                                $('#country').val(address.country);
                            }
                        },
                        error: function () {
                            alert('Failed to fetch address details.');
                        }
                    });
                } else {
                    alert("Please select a property first.");
                }
            });
            });
</script>

@endsection
