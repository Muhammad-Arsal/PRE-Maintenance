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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Profile
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
                                <form method="post" enctype="multipart/form-data" id='manageAdmin'  action="{{ route('admin.profile.update') }}">
                                    @csrf
                                    <input type="hidden" name="admin_id" value="{{ base64_encode($admin->id) }}" />
                                    <div class="form-body">
                                        @php
                                          $name = explode(' ',$admin->name );
                                        @endphp
                                        <div class="form-group">
                                            <label for="fname">First Name</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="fname" class="form-control"
                                                    placeholder="First name" name="fname" value="{{ old('fname') ? old('fname') : $name[0] }}">
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
                                                <input type="text" id="lname" class="form-control" placeholder="Last name"
                                                    name="lname" value="{{ old('lname') ? old('lname') : (isset($name[1]) ? $name[1] : '') }}" />
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
                                                <input type="text" id="phone_number" class="form-control"
                                                    placeholder="Phone number" name="phone_number" value="{{ old('phone_number') ? old('phone_number') : ( isset($admin->profile->phone_number) ? $admin->profile->phone_number : '' ) }}">
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
                                                <input type="email" id="email" class="form-control" placeholder="Email"
                                                    name="email" value="{{ old('email') ? old('email') : $admin->email }}">
                                                <div class="form-control-position">
                                                    <i class="la la-envelope"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('email'))
                                            <p class="text-danger">{{ $errors->first('email') }}</p>
                                        @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="password" id="password" class="form-control"
                                                    placeholder="Password" name="password"  />
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
                                                <input type="password" id="confirmPassword" class="form-control"
                                                    placeholder="Confirm Password" name="confirmPassword">
                                                <div class="form-control-position">
                                                    <i class="la la-lock"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('confirmPassword'))
                                            <p class="text-danger">{{ $errors->first('confirmPassword') }}</p>
                                        @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Profile image</label>
                                            <br />
                                            <input type="file" name="profile_image" id="profile_image"  />
                                            @if ($errors->has('profile_image'))
                                            <p class="text-danger">{{ $errors->first('profile_image') }}</p>
                                            @endif
                                            <div class="relative mt-2">
                                               <img src="{{isset($admin->profile->profile_image) ? asset('uploads/admin-'.$admin->id.'/'.$admin->profile->profile_image) :  asset('/dashboard/images/avatar.png') }}" alt="{{ $admin->name }}" class="img-fluid" width="50" height="50" />
                                        </div>

                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="confirmPassword">Status</label>
                                            <div class="position-relative has-icon-left">
                                                <select id="issueinput6" name="status" class="form-control"
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Status" data-original-title="" title="">
                                                    <option value="">Select Status</option>
                                                    <option value="1" {{ $admin->status->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="2" {{ $admin->status->status == 2 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="la la-lock"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('status'))
                                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                            @endif
                                        </div> --}}

                                        <div class="form-actions right">
                                            {{-- <a href="{{ route('admin.manage.users') }}" class="theme-btn btn btn-primary">
                                                <i class="la la-times"></i> Cancel
                                         </a> --}}
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
            var validate = $('#manageAdmin').validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone_number: 'required',

                    status: 'required',
                },
                messages: {
                    fname: 'The first name field is required',
                    email: 'The email field is required',
                    phone_number: 'The phone number field is required',

                    'status': 'The Status field is required',
                }

            });

            $('input').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
