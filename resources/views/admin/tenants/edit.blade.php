@extends('admin.partials.main')

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
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='managetenant' action="{{ route('admin.settings.tenants.update', $tenant->id) }}">
                                    @csrf
                                    @method('PUT')
                                    @php $name = explode(" ", $tenant->name); @endphp
                                    <div class="form-body">
                                        <div class="tenant-container">
                                            <div class="row cloneable position-relative">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="name"><span style="color: red;">*</span>Name</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="name_0" class="form-control" placeholder="First name" name="name[0]" value="{{ old('name.0', $tenant->name ?? '') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-user"></i>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('name'))
                                                            <p class="text-danger">{{ $errors->first('name') }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group form-check">
                                                        <input class="form-check-input primary-user-checkbox" type="checkbox" name="primary_user[0]" id="primary_0" checked>
                                                        <label for="primary_user">Primary User</label>
                                                        <div class="primary_user_error error"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="phone_number">Phone Number</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="phone_number_0" class="form-control" placeholder="Phone number" name="phone_number[0]" value="{{ old('phone_number.0', $tenant->profile->phone_number ?? '') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-phone"></i>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('phone_number'))
                                                            <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="work_phone">Work Phone</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="work_phone_0" class="form-control" placeholder="Work phone" name="work_phone[0]" value="{{ old('work_phone', $tenant->work_phone ?? '') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-phone"></i>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('work_phone'))
                                                            <p class="text-danger">{{ $errors->first('work_phone') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="home_phone">Home Phone</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="home_phone_0" class="form-control" placeholder="Home phone" name="home_phone[0]" value="{{ old('home_phone.0', $tenant->home_phone ?? '') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-phone"></i>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('home_phone'))
                                                            <p class="text-danger">{{ $errors->first('home_phone') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="email"><span style="color: red;">*</span>Email</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="email" id="email_0" class="form-control" placeholder="Email" name="email[0]" value="{{ old('email.0', $tenant->email ?? '') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-envelope"></i>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('email'))
                                                            <p class="text-danger">{{ $errors->first('email') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>  
                                            @php $i =1; @endphp
                                            @foreach ($tenantDetails as $tenant_detail)
                                                <div class="row cloneable position-relative">
                                                    <div class="col-md-12"><button type="button" class="btn btn-danger remove-row" style="margin-bottom:0px; float: right;">X</button></div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="name"><span style="color: red;">*</span>Name</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="name_{{ $i }}" class="form-control" placeholder="First name" name="name[{{ $i }}]" value="{{ old('name.' . $i, $tenant_detail->name ?? '') }}">
                                                                <div class="form-control-position">
                                                                    <i class="la la-user"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('name'))
                                                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="form-group form-check">
                                                            <input class="form-check-input primary-user-checkbox" type="checkbox" name="primary_user[{{ $i }}]" id="primary_{{ $i }}">
                                                            <label for="primary_user">Primary User</label>
                                                            <div class="primary_user_error error"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="phone_number">Phone Number</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="phone_number_{{ $i }}" class="form-control" placeholder="Phone number" name="phone_number[{{ $i }}]" value="{{ old('phone_number.' . $i, $tenant_detail->phone_number ?? '') }}">
                                                                <div class="form-control-position">
                                                                    <i class="la la-phone"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('phone_number'))
                                                                <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="work_phone">Work Phone</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="work_phone_{{ $i }}" class="form-control" placeholder="Work phone" name="work_phone[{{ $i }}]" value="{{ old('work_phone.' . $i, $tenant_detail->work_phone ?? '') }}">
                                                                <div class="form-control-position">
                                                                    <i class="la la-phone"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('work_phone'))
                                                                <p class="text-danger">{{ $errors->first('work_phone') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="home_phone">Home Phone</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="home_phone_{{ $i }}" class="form-control" placeholder="Home phone" name="home_phone[{{ $i }}]" value="{{ old('home_phone.' . $i, $tenant_detail->home_phone ?? '') }}">
                                                                <div class="form-control-position">
                                                                    <i class="la la-phone"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('home_phone'))
                                                                <p class="text-danger">{{ $errors->first('home_phone') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="email"><span style="color: red;">*</span>Email</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="email" id="email_{{ $i }}" class="form-control" placeholder="Email" name="email[{{ $i }}]" value="{{ old('email.' . $i, $tenant_detail->email ?? '') }}">
                                                                <div class="form-control-position">
                                                                    <i class="la la-envelope"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('email'))
                                                                <p class="text-danger">{{ $errors->first('email') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $i++; @endphp
                                            @endforeach
                                        </div>
                                        <div class="form-group">
                                        <button class="theme-btn btn btn-primary add_tenant">
                                            <i class="la la-plus"></i> Add Tenant
                                        </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="password" id="password" class="form-control"
                                                            placeholder="Enter new password (leave blank to keep current)" name="password" />
                                                        <div class="form-control-position">
                                                            <i class="la la-lock"></i>
                                                        </div>
                                                    </div>
                                                    @error('password') <p class="text-danger">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password_confirmation">Confirm Password</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="password" id="password_confirmation" class="form-control"
                                                            placeholder="Confirm new password" name="password_confirmation">
                                                        <div class="form-control-position">
                                                            <i class="la la-lock"></i>
                                                        </div>
                                                    </div>
                                                    @error('password_confirmation') <p class="text-danger">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Status">
                                                            <option value="Active" {{ old('status', $tenant->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ old('status', $tenant->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-toggle-on"></i>
                                                        </div>
                                                    </div>
                                                    @error('status') <p class="text-danger">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>                                        

                                        <div class="form-group">
                                            <label for="profile_image">Profile Image</label>
                                            <br />
                                            <input type="file" name="profile_image" id="profile_image" />
                                            <div class="relative mt-2">
                                                <img src="{{isset($tenant->profile->profile_image) ? asset('uploads/tenant-'.$tenant->id.'/'.$tenant->profile->profile_image) :  asset('/dashboard/images/avatar.png') }}" alt="{{ $tenant->name }}" class="img-fluid" width="50" height="50" />
                                            </div>

                                            @if ($errors->has('profile_image'))
                                               <p class="text-danger">{{ $errors->first('profile_image') }}</p>
                                            @endif
                                        </div>


                                        <h3 class="mt-3 mb-2"><strong>Property</strong></h3>
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
                                                        <input type="text" id="deposit" class="form-control" placeholder="Enter deposit amount" name="deposit"
                                                            value="{{ old('deposit', $tenant->deposit) }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-money"></i>
                                                        </div>
                                                    </div>
                                                    @error('deposit') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="adjust">Adjust</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="adjust" class="form-control" placeholder="Enter adjustment amount" name="adjust"
                                                            value="{{ old('adjust', $tenant->adjust) }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-balance-scale"></i>
                                                        </div>
                                                    </div>
                                                    @error('adjust') <span class="text-danger">{{ $message }}</span> @enderror
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

                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <textarea id="note" class="form-control" name="note" rows="4" placeholder="Enter your note here...">{{ old('note', $tenant->note ?? '') }}</textarea>
                                            @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>                                        

                                        <div class="form-actions right">
                                            <a href="{{ route('admin.settings.tenants') }}" class="theme-btn btn btn-primary">
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
                    }
                }
            });
        });
    });
</script>

<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
<script>
    $(function() {
        $('.select2').select2();
    })
</script>

<script>
    $(document).ready(function () {
         // Apply jQuery validation
        $("#managetenant").validate({
            rules: {
                "deposit": { 
                    number: true 
                },
                "property": { required: true },
                "contract_start_display": { required: true, date: true },
                "contract_end_display": { required: true, date: true }
            },
            messages: {
                "deposit": { 
                    number: "Please enter a valid number" 
                },
                "property": { required: "Property is required" },
                "contract_start_display": { required: "Contract start date is required", date: "Please enter a valid date" },
                "contract_end_display": { required: "Contract end date is required", date: "Please enter a valid date" }
            },
            submitHandler: function (form) {
                console.log("here");
                
                var primaryUserCheckbox = $(".primary-user-checkbox:checked").length;
                console.log(primaryUserCheckbox);
                if(primaryUserCheckbox == 0){
                    $("#primary_0").last().closest('.form-check').append("<p class='text-danger primary_user_error'>At least one user should be selected as the primary user</p>");
                    return false;
                }
                form.submit();
            }
        });
    let rowIndex = 1; // Start index for rows

    // Function to update the indexes
    updateIndexes();
    function updateIndexes() {
        $(".row.cloneable").each(function (index) {
            var primaryUser = $(this).find("input[type='checkbox']").prop("checked");
            $(this).find("input").each(function () {
                let fieldName = $(this).attr("name").split("[")[0]; // Extract field name
                let fieldId = $(this).attr("id").split("_")[0]; // Extract field ID

                let newName = fieldName + "[" + index + "]";
                let newId = fieldId + "_" + index;

                $(this).attr("name", newName);
                $(this).attr("id", newId);

                if (fieldName == "name") {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "This field is required",
                        }
                    });
                } else if (fieldName == "email") {
                    $(this).rules("add", {
                        required: true,
                        email: true,
                        messages: {
                            required: "This field is required",
                            email: "Please enter a valid email address",
                        }
                    });
                } else if (fieldName == "phone_number") {
                    $(this).rules("add", {
                        number: true,
                        messages: {
                            number: "Please enter a valid phone number",
                        }
                    });
                }
            });
        });
    }

    $(document).on("change", "input[name^='primary_user']", function() {
        $("input[name^='primary_user']").not(this).prop("checked", false);
    });


    // Add new tenant row
    $(".add_tenant").click(function (e) {
        e.preventDefault();

        let newRow = $(".row.cloneable").first().clone(); // Clone first row

        newRow.find("input").val(""); // Clear values
        newRow.find(".remove-row").remove(); // Remove existing delete button if any
        newRow.prepend('<div class="col-md-12"><button type="button" class="btn btn-danger remove-row" style="margin-bottom:0px; float: right;">X</button></div>');

        newRow.appendTo(".tenant-container"); // Append the new row
        updateIndexes(); // Update indexes
    });

    // Remove tenant row
    $(document).on("click", ".remove-row", function () {
        $(this).closest(".row").remove();
        updateIndexes(); // Reset indexes

        // Reapply validation
        $("form").validate().destroy();
        $("form").validate();
    });
});

</script>
@endsection
