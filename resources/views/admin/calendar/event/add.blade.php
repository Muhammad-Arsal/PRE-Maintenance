@extends('admin.partials.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/new-multi-select/filter_multi_select.css') }}" />
    <style>
        @keyframes check {
            0% {
                height: 0;
                width: 0;
            }

            25% {
                height: 0;
                width: 10px;
            }

            50% {
                height: 20px;
                width: 10px;
            }
        }

        .checkbox {
            background-color: #fff;
            display: inline-block;
            height: 28px;
            margin: 0 .25em;
            width: 28px;
            border-radius: 4px;
            border: 1px solid #ccc;
            float: right
        }

        .checkbox span {
            display: block;
            height: 28px;
            position: relative;
            width: 28px;
            padding: 0
        }

        .checkbox span:after {
            -moz-transform: scaleX(-1) rotate(135deg);
            -ms-transform: scaleX(-1) rotate(135deg);
            -webkit-transform: scaleX(-1) rotate(135deg);
            transform: scaleX(-1) rotate(135deg);
            -moz-transform-origin: left top;
            -ms-transform-origin: left top;
            -webkit-transform-origin: left top;
            transform-origin: left top;
            border-right: 4px solid #fff;
            border-top: 4px solid #fff;
            content: '';
            display: block;
            height: 20px;
            left: 3px;
            position: absolute;
            top: 15px;
            width: 10px
        }

        .checkbox span:hover:after {
            border-color: #999
        }

        .checkbox input {
            display: none
        }

        .checkbox input:checked+span:after {
            -webkit-animation: check .8s;
            -moz-animation: check .8s;
            -o-animation: check .8s;
            animation: check .8s;
            border-color: #555
        }

        .checkbox input:checked+.default:after {
            border-color: #444
        }

        .checkbox input:checked+.primary:after {
            border-color: #2196F3
        }

        .checkbox input:checked+.success:after {
            border-color: #8bc34a
        }

        .checkbox input:checked+.info:after {
            border-color: #3de0f5
        }

        .checkbox input:checked+.warning:after {
            border-color: #FFC107
        }

        .checkbox input:checked+.danger:after {
            border-color: #f44336
        }
    </style>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 breadcrumb-new mb-2">
            <h3 class="content-header-title d-inline-block mb-0">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ $page['page_parent_link'] }}" class="theme-color">{{ $page['page_parent'] }}</a>
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
                                <form method="post" id='manageType' enctype="multipart/form-data"
                                    action="{{ route('admin.diary.event.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="event_type">Event Type</label>
                                            <div class="position-relative has-icon-left">
                                                <select name="event_type" id="event_type" class="form-control {{ $errors->has('event_type') ? 'error' : '' }}">
                                                    <option value="">Event Type</option>
                                                    @foreach ($event_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->event_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="la la-calendar-check-o"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('event_type'))
                                                <label id="event_type-error" class="error"
                                                    for="event_type">{{ $errors->first('event_type') }}</label>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="platform_users">Platform Users</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="platform_user[]" multiple id="platform_user" class="form-control {{ $errors->has('platform_users') ? 'error' : '' }}">
                                                            @foreach ($platform_users as $platform_user)
                                                                <option value="{{ $platform_user->id }}">{{ $platform_user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-users"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('platform_users'))
                                                        <label id="platform_users-error" class="error"
                                                            for="platform_users">{{ $errors->first('platform_users') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="platform_users">External User Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="external_user_name" class="form-control {{ $errors->has('external_user_name') ? 'error' : '' }}" placeholder="External User Name" name="external_user_name" value="" aria-invalid="true">
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('external_user_name'))
                                                        <label id="external_user_name-error" class="error"
                                                            for="external_user_name">{{ $errors->first('external_user_name') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="platform_users">External User Email</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="email" id="external_user" class="form-control {{ $errors->has('external_user') ? 'error' : '' }}" placeholder="External User Email" name="external_user" value="" aria-invalid="true">
                                                        <div class="form-control-position">
                                                            <i class="la la-envelope"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('external_user'))
                                                        <label id="external_user-error" class="error"
                                                            for="external_user">{{ $errors->first('external_user') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="cc">CC</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="email" id="cc" class="form-control {{ $errors->has('cc') ? 'error' : '' }}" placeholder="CC" name="cc" value="" aria-invalid="true">
                                                        <div class="form-control-position">
                                                            <i class="la la-envelope"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('cc'))
                                                        <label id="cc-error" class="error"
                                                            for="cc">{{ $errors->first('cc') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="address_main_contact">Address Main Contact</label>
                                                    <div class="position-relative has-icon-left">
                                                        <textarea id="address_main_contact" name="address_main_contact" class="form-control {{ $errors->has('address_main_contact') ? 'error' : '' }}" placeholder="Address Main Contact">{{ old('address_main_contact') ? old('address_main_contact') : '' }}</textarea>
                                                        <div class="form-control-position">
                                                            <i class="la la-globe"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('address_main_contact'))
                                                        <label id="address_main_contact-error" class="error"
                                                            for="address_main_contact">{{ $errors->first('address_main_contact') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contacts">Add Contacts</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="contacts[]" multiple id="contacts" class="form-control">
                                                            @foreach ($contacts as $contact)
                                                                <option value="{{ $contact->id }}">{{ $contact->name . ' ' . (!empty($contact->contactType) ? '(' . $contact->contactType->name . ')' : '') }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="providers">Add Providers</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="providers[]" multiple id="providers" class="form-control">
                                                            @foreach ($providers as $provider)
                                                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="date">Date From</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="date"
                                                            class="form-control pickadate {{ $errors->has('date') ? 'error' : '' }}"
                                                            placeholder="Date From" name="date"
                                                            value="{{ old('date') ? old('date') : '' }}" />
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('date'))
                                                        <label id="date-error" class="error"
                                                            for="date">{{ $errors->first('date') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="date">Date To</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="date_to"
                                                            class="form-control pickadate {{ $errors->has('date_to') ? 'error' : '' }}"
                                                            placeholder="Date To" name="date_to"
                                                            value="{{ old('date_to') ? old('date_to') : '' }}" />
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('date_to'))
                                                        <label id="date_to-error" class="error"
                                                            for="date_to">{{ $errors->first('date_to') }}</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="time_from">Time From</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" autocomplete="off"
                                                            class="form-control pickatime picker__input mandatory"
                                                            id="time_from" name="time_from" placeholder="Time From">
                                                        <div class="form-control-position">
                                                            <i class="la la-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="time_to">Time To</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" autocomplete="off"
                                                            class="form-control pickatime picker__input mandatory"
                                                            id="time_to" name="time_to" placeholder="Time To">
                                                        <div class="form-control-position">
                                                            <i class="la la-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Docs</label>
                                                    <input multiple type="file" name="docs[]" id="docs" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Add Description</label>
                                            <div class="position-relative has-icon-left">
                                                <textarea class="form-control {{ $errors->has('description') ? 'error' : '' }}" rows="6" placeholder="Add Description" name="description" id="description">{{ old('description') ? old('description') : '' }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-align-justify"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('description'))
                                                <label id="description-error" class="error"
                                                    for="description">{{ $errors->first('description') }}</label>
                                            @endif
                                        </div>

                                        <div class="form-group" style="border: 1px solid #cacfe7;">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="reminder" value="1" />
                                                        <span class="primary"></span>
                                                    </label>
                                                    Add Reminder
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="form-group {{ $errors->has('recurrence') ? 'has-error' : '' }}">
                                            <label>Recurrence</label>
                                            @foreach (App\Models\Events::RECURRENCE_RADIO as $key => $label)
                                                <div>
                                                    <input id="recurrence_{{ $key }}" name="recurrence" type="radio" value="{{ $key }}" {{ old('recurrence', 'none') === (string) $key ? 'checked' : '' }} required>
                                                    <label for="recurrence_{{ $key }}">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                            {{-- @if ($errors->has('recurrence'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('recurrence') }}
                                                </em>
                                            @endif --}}
                                        </div>

                                        <div class="form-group repeated_for" style="display:none">
                                            <label>Repeated For? (How many days/weeks/months)</label>
                                            <input type="text" name="repeated_for" class="form-control" id="repeated_for" placeholder="Repeated For">
                                        </div>

                                        <div class="form-actions right">
                                            <a href="{{ route('admin.diary', ['savedState' => 'true']) }}" class="theme-btn btn btn-primary">
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
    <script src="{{ asset('/dashboard/js/custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/plugins/new-multi-select/filter-multi-select-bundle.js ') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/dateTime/inputmask.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            var validate = $('#manageType').validate({
                rules: {
                    date: 'required',
                    date_to: 'required',
                    time_from: 'required',
                    time_to: 'required',
                    event_type: 'required',
                    repeated_for: {
                        digits: true,
                    },
                },
                messages: {
                    repeated_for: {
                        digits: 'This field must be in numbers',
                    },
                    date: 'The Date From field is required',
                    date_to: 'The Date To field is required',
                    time_from: 'The Time From field is required',
                    time_to: 'The Time To field is required',
                    event_type: 'The Event Type field is required',
                },
                submitHandler: function(form) {
                    const selectedItemsPlatformUsers = $('#platform_user .selected-items .item');


                    if (selectedItemsPlatformUsers.length === 0) {
                        $('#platform_user-error').remove();
                        $('<label id="platform_user-error" class="error">Please select at least one platform user.</label>')
                            .insertAfter('#platform_user');

                        return false;
                    }

                    form.submit();
                }
            });

            var date = "";

            $('.pickadate').pickadate({
                min: true,
                format: 'dd/mm/yyyy',
                formatSubmit: 'dd/mm/yyyy',
                onSet: function() {
                    date = this.get('select', 'dd/mm/yyyy');
                }
            });

            $('.pickatime').pickatime({
                format: 'HH:i',
                interval: 30,
                editable: true
            });

            Inputmask("99:99", {
                placeholder: "HH:MM",
                insertMode: false,
                showMaskOnHover: false
            }).mask("#time_from");

            Inputmask("99:99", {
                placeholder: "HH:MM",
                insertMode: false,
                showMaskOnHover: false
            }).mask("#time_to");


            const platform_user = $("#platform_user").filterMultiSelect({
                placeholderText: "Choose Platform Users"
            });

            const contacts = $("#contacts").filterMultiSelect({
                placeholderText: "Add Contacts"
            });

            $("input[name='recurrence']").change(function() {
                if ($(this).val() != 'none') {
                    $(".repeated_for").show();
                } else {
                    $(".repeated_for").hide().find('input').val("");
                }
            });

            // var validate = $('#manageType').validate({
            //     rules: {
            //         event_name: {
            //             required: true,
            //             minlength: 3,
            //         },
            //     },
            //     messages: {
            //         event_name:{
            //            required: 'The Event Name field is required.',
            //            minlength:'The min length for event name field should be at least 3'
            //        },
            //     }

            // });

            // var $input = $("#time_from").pickatime({
            //         min: true,
            //         onSet: function() {
            //             if(date != '') {
            //                 var todayDate = new Date();
            //                 var givenDate = new Date(date);
            //                 time = this.get('select', 'h:i A'); 
            //                 if (givenDate.getTime() !== todayDate.getTime()) {
            //                     $("#time_to").replaceWith('<input type="text" class="form-control pickatime1 picker__input mandatory" id="time_to" name="time_to" placeholder="Time To">');
            //                     $("#time_to").pickatime({
            //                         onOpen: "Close",
            //                     });
            //                 } else {
            //                     $("#time_to").replaceWith('<input type="text" class="form-control pickatime1 picker__input mandatory" id="time_to" name="time_to" placeholder="Time To">');
            //                     $("#time_to").pickatime({
            //                         onOpen: "Close",
            //                         min: time,
            //                     });
            //                 }
            //             } else {
            //                 time = this.get('select', 'h:i A'); 
            //                 $("#time_to").replaceWith('<input type="text" class="form-control pickatime1 picker__input mandatory" id="time_to" name="time_to" placeholder="Time To">');
            //                 $("#time_to").pickatime({
            //                     onOpen: "Close",
            //                     min: time,
            //                 });
            //             }
            //         }
            //     });

            $('input, textarea').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
