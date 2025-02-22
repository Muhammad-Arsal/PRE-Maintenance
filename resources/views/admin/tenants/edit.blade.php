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
                                        <div class="form-group">
                                            <label for="fname">First Name</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="fname" class="form-control" placeholder="First name" name="fname" value="{{ old('fname', $name[0]) }}">
                                                <div class="form-control-position">
                                                    <i class="la la-user"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('fname'))
                                               <p class="text-danger">{{ $errors->first('fname') }}</p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="lname">Last Name</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="lname" class="form-control" placeholder="Last name" name="lname" value="{{ old('lname', array_key_exists('1', $name) ? $name[1] : '') }}">
                                                <div class="form-control-position">
                                                    <i class="la la-user"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('lname'))
                                               <p class="text-danger">{{ $errors->first('lname') }}</p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="phone_number">Phone Number</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="phone_number" class="form-control" placeholder="Phone number" name="phone_number" value="{{ old('phone_number', $tenant->profile->phone_number) }}">
                                                <div class="form-control-position">
                                                    <i class="la la-phone"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('phone_number'))
                                               <p class="text-danger">{{ $errors->first('phone_number') }}</p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="{{ old('email', $tenant->email) }}">
                                                <div class="form-control-position">
                                                    <i class="la la-envelope"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('email'))
                                               <p class="text-danger">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password (leave blank to keep current password)</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="password" id="password" class="form-control" placeholder="Password" name="password">
                                                <div class="form-control-position">
                                                    <i class="la la-lock"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('password'))
                                               <p class="text-danger">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="confirmPassword">Confirm Password</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                                                <div class="form-control-position">
                                                    <i class="la la-lock"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('password_confirmation'))
                                               <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                            @endif
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

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <div class="position-relative has-icon-left">
                                                <select id="status" name="status" class="form-control">
                                                    <option value="Active" {{ old('status', $tenant->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ old('status', $tenant->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="la la-lock"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('status'))
                                               <p class="text-danger">{{ $errors->first('status') }}</p>
                                            @endif
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
    <script type="text/javascript">
        $(function() {
            var validate = $('#managetenant').validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone_number: {
                        required: true,
                    },
                    password: {
                        minlength: 6,
                    },
                    password_confirmation: {
                        minlength: 6,
                        equalTo: '#password',
                    },
                },
                messages: {
                    fname: 'The first name field is required',
                    email: 'The email field is required',
                    phone_number: 'The phone number field is required',
                    password: {
                        minlength: "Your password must be at least 6 characters long",
                    },
                    password_confirmation: {
                        minlength: "The confirm password must be at least 6 characters long",
                        equalTo: 'Confirm Password should be equal to Password'
                    },
                }
            });

            $('input').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
