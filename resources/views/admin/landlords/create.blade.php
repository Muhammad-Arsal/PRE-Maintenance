@extends('admin.partials.main')

@section('css')
<style>
    label {
        font-weight: bold;
    }
</style>
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
                                <form method="post" enctype="multipart/form-data" id='managelandlord'  action="{{ route('admin.settings.landlords.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="company_name">Company Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="company_name" class="form-control"
                                                             name="company_name" value="{{ old('company_name') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('company_name'))
                                                        <p class="text-danger">{{ $errors->first('company_name') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="work_phone">Work Phone</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="work_phone" class="form-control"
                                                             name="work_phone" value="{{ old('work_phone') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('work_phone'))
                                                        <p class="text-danger">{{ $errors->first('work_phone') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="commission_rate">Repairs Rate (%)</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="number" step="0.01" id="commission_rate" class="form-control"
                                                            name="commission_rate" value="{{ old('commission_rate') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-money"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('commission_rate'))
                                                        <p class="text-danger">{{ $errors->first('commission_rate') }}</p>
                                                    @endif
                                                </div>  
                                            </div>                                                                                                           
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="title" name="title" class="form-control">
                                                            <option value="">Select Title</option>
                                                            <option value="Mr" {{ old('title', $user->title ?? '') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                                            <option value="Mrs" {{ old('title', $user->title ?? '') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                                            <option value="Miss" {{ old('title', $user->title ?? '') == 'Miss' ? 'selected' : '' }}>Miss</option>
                                                            <option value="Ms" {{ old('title', $user->title ?? '') == 'Ms' ? 'selected' : '' }}>Ms</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @error('title') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fname">First Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="fname" class="form-control"
                                                            name="fname" value="{{ old('fname') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('fname'))
                                                       <p class="text-danger">{{ $errors->first('fname') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="lname">Last Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="lname" class="form-control" 
                                                            name="lname" value="{{ old('lname') }}" />
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('lname'))
                                                        <p class="text-danger">{{ $errors->first('lname') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phone_number">Phone Number</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="phone_number" class="form-control"
                                                            name="phone_number" value="{{ old('phone_number') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('phone_number'))
                                                    <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="email" id="email" class="form-control" 
                                                            name="email" value="{{ old('email') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-envelope"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('email'))
                                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="confirmPassword">Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Status" data-original-title="" title="">
                                                            <option value="Active">Active</option>
                                                            <option value="Inactive">Inactive</option>
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
                                        
                                        
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="home_phone">Home Phone</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="home_phone" class="form-control"
                                                             name="home_phone" value="{{ old('home_phone') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('home_phone'))
                                                        <p class="text-danger">{{ $errors->first('home_phone') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="password" id="password" class="form-control"
                                                             name="password"  />
                                                        <div class="form-control-position">
                                                            <i class="la la-lock"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('password'))
                                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm Password</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="password" id="password_confirmation" class="form-control"
                                                             name="password_confirmation">
                                                        <div class="form-control-position">
                                                            <i class="la la-lock"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('password_confirmation'))
                                                    <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="confirmPassword">Profile image</label>
                                            <br />
                                            <input type="file" name="profile_image" id="profile_image"  />
                                            @if ($errors->has('profile_image'))
                                                <p class="text-danger">{{ $errors->first('profile_image') }}</p>
                                            @endif
                                        </div>


                                        <h3 class="mb-2"><strong>Address</strong></h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_1">Address Line 1</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_1" class="form-control" name="address_line_1" value="{{ old('address_line_1', $property->address_line_1 ?? '') }}">
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
                                                        <input type="text" id="address_line_2" class="form-control"  name="address_line_2" value="{{ old('address_line_2', $property->address_line_2 ?? '') }}">
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
                                                        <input type="text" id="address_line_3" class="form-control" name="address_line_3" value="{{ old('address_line_3', $property->address_line_3 ?? '') }}">
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
                                                    <label for="city">City</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="city" class="form-control"  name="city" value="{{ old('city', $property->city ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="county">County</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="county" class="form-control"  name="county" value="{{ old('county', $property->county ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map"></i>
                                                        </div>
                                                    </div>
                                                    @error('county') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="postal_code">Postal Code</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="postal_code" class="form-control"  name="postal_code" value="{{ old('postal_code', $property->postal_code ?? '') }}">
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
                                                    <label for="country">Country</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="country" class="form-control" 
                                                            name="country" value="{{ old('country', $property->country ?? 'United Kingdom') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-globe"></i>
                                                        </div>
                                                    </div>
                                                    @error('country') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>  
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="overseas_landlord">Overseas Landlord</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="overseas_landlord" name="overseas_landlord" class="form-control"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Is the landlord overseas?" title="">
                                                            <option value="">Select Option</option>
                                                            <option value="Yes" >Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-globe"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('overseas_landlord'))
                                                        <p class="text-danger">{{ $errors->first('overseas_landlord') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4" id="tax_exemption_code_wrapper" style="display: none;">
                                                <div class="form-group">
                                                    <label for="tax_exemption_code">Tax at Source Exemption Code</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="tax_exemption_code" class="form-control"
                                                            name="tax_exemption_code" value="{{ old('tax_exemption_code') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-file-code-o"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('tax_exemption_code'))
                                                        <p class="text-danger">{{ $errors->first('tax_exemption_code') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <textarea id="note" class="form-control" name="note" rows="4" >{{ old('note', $property->note ?? '') }}</textarea>
                                            @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-actions right">
                                            <a href="{{ route('admin.settings.landlords') }}" class="theme-btn btn btn-primary">
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
    <script type="text/javascript">
        $(function() {
            var validate = $('#managelandlord').validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    lname: { 
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    commission_rate: {
                        required: true,
                    },
                    status: 'required',
                    password: {
                        minlength: 6,
                    },
                    password_confirmation: {
                        minlength: 6,
                        equalTo: '#password',
                    },
                    address_line_1: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    county: {   required: true  },
                    postal_code: {  required: true  },
                    country: {
                        required: true,
                    },
                    title: { required:true },
                },
                messages: {
                    fname: 'The first name field is required',
                    lname: 'The last name field is required',
                    email: 'The email field is required',
                    'status': 'The Status field is required',
                    commission_rate: 'The Commission Rate field is required',
                    address_line_1: 'The Address Line 1 field is required',
                    city: 'The City field is required',
                    county: 'The County field is required',
                    postal_code: 'The Postal Code field is required',
                    country: 'The Country field is required',
                    title: 'The Title field is required',
                    password: {
                        minlength: "Your password must be at least 6 characters long",
                    },
                    password_confirmation: {
                        minlength: "The confirm password must be at least 6 characters long",
                        equalTo:'Confirm Password should be equal to Password'
                    },
                }

            });

            $('input').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>



<script>
    $(document).ready(function () {
        function toggleTaxField() {
            const selected = $('#overseas_landlord').val();
            if (selected === 'Yes') {
                $('#tax_exemption_code_wrapper').show();
            } else {
                $('#tax_exemption_code_wrapper').hide();
                $('#tax_exemption_code').val(''); // Clear the field
            }
        }

        // Initial load
        toggleTaxField();

        // On change
        $('#overseas_landlord').on('change', function () {
            toggleTaxField();
        });
    });
</script>


@endsection
