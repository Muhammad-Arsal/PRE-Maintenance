@extends('landlord.partials.main')

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
        <section id="search-landlords">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='managelandlord' action="{{ route('landlord.settings.landlords.update', auth('landlord')->id()) }}">
                                    @csrf
                                    @method('PUT')
                                    @php $name = explode(" ", $landlord->name); @endphp
                                    <div class="form-body">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <!-- Company Name -->
                                                <div class="form-group">
                                                    <label for="company_name">Company Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="company_name" class="form-control" name="company_name"
                                                            value="{{ old('company_name', $landlord->company_name ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                 <!-- Work Phone -->
                                                <div class="form-group">
                                                    <label for="work_phone">Work Phone</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="work_phone" class="form-control" name="work_phone"
                                                            value="{{ old('work_phone', $landlord->work_phone ?? '') }}" >
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                    @error('work_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- Commission Rate -->
                                                <div class="form-group">
                                                    <label for="commission_rate">Repairs Rate (%)</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="number" step="0.01" id="commission_rate" class="form-control" name="commission_rate"
                                                            value="{{ old('commission_rate', $landlord->commission_rate ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-money"></i>
                                                        </div>
                                                    </div>
                                                    @error('commission_rate') <span class="text-danger">{{ $message }}</span> @enderror
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
                                                            <option value="Mr" {{ old('title', $landlord->title ?? '') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                                            <option value="Mrs" {{ old('title', $landlord->title ?? '') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                                            <option value="Miss" {{ old('title', $landlord->title ?? '') == 'Miss' ? 'selected' : '' }}>Miss</option>
                                                            <option value="Ms" {{ old('title', $landlord->title ?? '') == 'Ms' ? 'selected' : '' }}>Ms</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fname">First Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="fname" class="form-control"  name="fname" value="{{ old('fname', $name[0]) }}">
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
                                                        <input type="text" id="lname" class="form-control" name="lname" value="{{ old('lname', array_key_exists('1', $name) ? $name[1] : '') }}">
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
                                                        <input type="text" id="phone_number" class="form-control"  name="phone_number" value="{{ old('phone_number', $landlord->profile->phone_number) }}">
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
                                                        <input type="email" id="email" class="form-control"  name="email" value="{{ old('email', $landlord->email) }}">
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
                                                    <label for="status">Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="Active" {{ old('status', $landlord->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ old('status', $landlord->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                                                        <input type="text" id="home_phone" class="form-control" name="home_phone"
                                                            value="{{ old('home_phone', $landlord->home_phone ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                    @error('home_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password">Password (leave blank to keep current password)</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="password" id="password" class="form-control" name="password">
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
                                                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation">
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
                                            <label for="profile_image">Profile Image</label>
                                            <br />
                                            <input type="file" name="profile_image" id="profile_image" />
                                            <div class="relative mt-2">
                                                <img src="{{isset($landlord->profile->profile_image) ? asset('uploads/landlord-'.$landlord->id.'/'.$landlord->profile->profile_image) :  asset('/dashboard/images/avatar.png') }}" alt="{{ $landlord->name }}" class="img-fluid" width="50" height="50" />
                                            </div>

                                            @if ($errors->has('profile_image'))
                                               <p class="text-danger">{{ $errors->first('profile_image') }}</p>
                                            @endif
                                        </div>

                                        <div class="form-actions right">
                                            <a href="{{ route('landlord.settings.landlords.edit', auth('landlord')->id()) }}" class="theme-btn btn btn-primary">
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
@endsection
