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
        <section id="search-contractors">
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
                                            href="{{ route('admin.settings.contractors.edit.address', $contractor->id) }}">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.contractors.viewjobs', $contractor->id) }}">Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.contractors.invoices.index', $contractor->id) }}">Invoices</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.contractors.correspondence', $contractor->id) }}">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.contractors.calendar', $contractor->id) }}">Diary</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='managecontractor' action="{{ route('admin.settings.contractors.update', $contractor->id) }}">
                                    @csrf
                                    @method('PUT')
                                    @php $name = explode(" ", $contractor->name); @endphp
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="title" name="title" class="form-control">
                                                            <option value="">Select Title</option>
                                                            <option value="Mr" {{ old('title', $contractor->title ?? '') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                                            <option value="Mrs" {{ old('title', $contractor->title ?? '') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                                            <option value="Miss" {{ old('title', $contractor->title ?? '') == 'Miss' ? 'selected' : '' }}>Miss</option>
                                                            <option value="Ms" {{ old('title', $contractor->title ?? '') == 'Ms' ? 'selected' : '' }}>Ms</option>
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
                                                        <input type="text" id="fname" class="form-control" placeholder="First name" name="fname" value="{{ old('fname', $name[0]) }}">
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
                                                        <input type="text" id="lname" class="form-control" placeholder="Last name" name="lname" value="{{ old('lname', array_key_exists('1', $name) ? $name[1] : '') }}">
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
                                                        <input type="text" id="phone_number" class="form-control" placeholder="Phone number" name="phone_number" value="{{ old('phone_number', $contractor->profile->phone_number) }}">
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
                                                        <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="{{ old('email', $contractor->email) }}">
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
                                                    <label for="fax">Fax</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="fax" class="form-control" placeholder="Fax Number" name="fax" 
                                                               value="{{ old('fax', $contractor->fax ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-fax"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('fax'))
                                                        <p class="text-danger">{{ $errors->first('fax') }}</p>
                                                    @endif
                                                </div>                                                
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="company_name">Company Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="company_name" class="form-control"
                                                            placeholder="Company Name" name="company_name" 
                                                            value="{{ old('company_name', $contractor->company_name ?? '') }}">
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
                                                            placeholder="Work Phone" name="work_phone" 
                                                            value="{{ old('work_phone', $contractor->work_phone ?? '') }}">
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
                                                    <label for="contact_type">Contact Type</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="contact_type" name="contact_type" class="form-control">
                                                            <option value="">Select Contact Type</option>
                                                            <option value="email" {{ old('contact_type', $contractor->contact_type ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                                                            <option value="fax" {{ old('contact_type', $contractor->contact_type ?? '') == 'fax' ? 'selected' : '' }}>Fax</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @error('contact_type') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>                                                
                                            </div>
                                        </div>                                        

                                        <div class="row">
                                            <div class="col-md-4">
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
                                            </div>
                                            <div class="col-md-4">
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
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="Active" {{ old('status', $contractor->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ old('status', $contractor->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
                                                    <label for="contractorTypes">Contractor Type</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="contractorTypes" name="contractorTypes" class="form-control"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Contractor Type" data-original-title="" title="">
                                                            <option value="">Select Contractor Type</option>
                                                            @foreach ($contractorTypes as $item)
                                                                <option value="{{ $item->id }}" 
                                                                    {{ old('contractorTypes', $contractor->contractor_type_id) == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('contractorTypes'))
                                                        <p class="text-danger">{{ $errors->first('contractorTypes') }}</p>
                                                    @endif
                                                </div>
                                            </div>                                            
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="profile_image">Profile Image</label>
                                            <br />
                                            <input type="file" name="profile_image" id="profile_image" />
                                            <div class="relative mt-2">
                                                <img src="{{isset($contractor->profile->profile_image) ? asset('uploads/contractor-'.$contractor->id.'/'.$contractor->profile->profile_image) :  asset('/dashboard/images/avatar.png') }}" alt="{{ $contractor->name }}" class="img-fluid" width="50" height="50" />
                                            </div>

                                            @if ($errors->has('profile_image'))
                                               <p class="text-danger">{{ $errors->first('profile_image') }}</p>
                                            @endif
                                        </div> 
                                        
                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <textarea id="note" class="form-control" name="note" rows="4" placeholder="Enter your note here...">{{ old('note', $contractor->note ?? '') }}</textarea>
                                            @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                    

                                        <div class="form-actions right">
                                            <a href="{{ route('admin.settings.contractors') }}" class="theme-btn btn btn-primary">
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
        var validate = $('#managecontractor').validate({
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
                contact_type: {
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
                title: { required:true },
                company_name: { required: true},
            },
            messages: {
                fname: 'The first name field is required',
                lname: 'The last name field is required',
                email: 'The email field is required',
                'status': 'The Status field is required',
                title: 'The Title field is required',
                contact_type: 'The Contact Type field is required',
                password: {
                    minlength: "Your password must be at least 6 characters long",
                },
                password_confirmation: {
                    minlength: "The confirm password must be at least 6 characters long",
                    equalTo:'Confirm Password should be equal to Password'
                },
                company_name: 'The Company Name field is required',
            }

        });

        $('input').on('focusout keyup', function() {
            $(this).valid();
        });
    });
</script>
@endsection
