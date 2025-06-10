@extends('admin.partials.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/new-multi-select/filter_multi_select.css') }}" />
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
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.tenants.edit', $tenant_id)}}">Current Property</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.tenants.edit.property', $tenant_id)}}">Current Property</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.tenants.jobs', $tenant_id)}}">Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.tenants.correspondence', $tenant_id)}}">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.past.tenancy', $tenant_id) }}">Past Tenancy</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="diary" data-toggle="tab"
                                            aria-controls="diary" href="#diary" aria-expanded="true">Diary</a>
                                    </li>
                                </ul>
                            </div>  
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" id='manageType'
                                    action="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="event_type">Event Type</label>
                                            <div class="position-relative has-icon-left">
                                                <select name="event_type" id="event_type" class="form-control {{ $errors->has('event_type') ? 'error' : '' }}" readonly>
                                                    @foreach ($event_types as $item)
                                                        @if ($event->event_type == $item->id)
                                                            <option selected value="{{ $item->id }}">{{ $item->event_name }}</option>
                                                        @endif
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
                                                <label for="property">Properties</label>
                                                <div class="position-relative has-icon-left">
                                                    <select name="property[]" id="property" readonly
                                                    class="form-control {{ $errors->has('property') ? 'error' : '' }}">
                                                    @foreach ($properties as $property)
                                                        @if (collect($event_property)->pluck('platform_user_id')->contains($property->id))
                                                            <option selected value="{{ $property->id }}">
                                                                {{ $property->line1 . ', ' . $property->city . ', ' . $property->county . ', ' . $property->country . ', ' . $property->postcode }}
                                                            </option>
                                                        @endif
                                                    @endforeach

                                                    </select>
                                                    <div class="form-control-position">
                                                    <i class="la la-building"></i>
                                                    </div>
                                                </div>
                                                @if ($errors->has('property'))
                                                    <label id="property-error" class="error" for="property">
                                                    {{ $errors->first('property') }}
                                                    </label>
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="platform_users">External User Name</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input readonly type="text" id="external_user_name" class="form-control {{ $errors->has('external_user_name') ? 'error' : '' }}" placeholder="External User Name" name="external_user_name" value="{{ old('external_user_name', $event->external_user_name) }}" aria-invalid="true">
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
                                                        <input readonly type="email" id="external_user" disabled class="form-control {{ $errors->has('external_user') ? 'error' : '' }}" placeholder="External User Email" name="external_user" value="{{ $event->external_user }}" aria-invalid="true">
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
                                                        <input readonly type="email" id="cc" class="form-control {{ $errors->has('cc') ? 'error' : '' }}" placeholder="CC" name="cc" value="{{ old('cc', $event->cc) }}" aria-invalid="true">
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
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="address_main_contact">Address Main Contact</label>
                                                    <div class="position-relative has-icon-left">
                                                        <textarea readonly id="address_main_contact" name="address_main_contact" class="form-control {{ $errors->has('address_main_contact') ? 'error' : '' }}" placeholder="Address Main Contact">{{ old('address_main_contact', $event->address_main_contact) }}</textarea>
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
                                                    <label for="contacts">Add Contractors</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="contacts[]" multiple id="contacts" class="form-control">
                                                            @foreach ($contacts as $contact)
                                                                <option
                                                                    @php foreach ($event_contacts as $event_contact) {
                                                                    if($event_contact->contact_id == $contact->id) {
                                                                        echo 'selected';
                                                                    }
                                                                } @endphp
                                                                    value="{{ $contact->id }}">{{ $contact->name . ' ' . (!empty($contact->contactType) ? '(' . $contact->contactType->name . ')' : '') }}</option>
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
                                                                <option 
                                                                @php foreach ($event_providers as $event_provider) {
                                                                    if($event_provider->provider_id == $provider->id) {
                                                                        echo 'selected';
                                                                    }
                                                                } @endphp
                                                                value="{{ $provider->id }}">{{ $provider->name }}</option>
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
                                                        <input readonly type="text" id="date"
                                                            class="form-control pickadate {{ $errors->has('date') ? 'error' : '' }}"
                                                            placeholder="Date From" name="date"
                                                            value="@php if($event->date_from) {echo date('d/m/Y', strtotime($event->date_from));} else if($event->date_to) {echo date('d/m/Y', strtotime($event->date_to));} @endphp" />
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
                                                        <input readonly type="text" id="date_to"
                                                            class="form-control pickadate {{ $errors->has('date_to') ? 'error' : '' }}"
                                                            placeholder="Date To" name="date_to"
                                                            value="@php if($event->date_to) {echo date('d/m/Y', strtotime($event->date_to));} @endphp" />
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
                                                        <input readonly type="text"
                                                            class="form-control pickatime picker__input mandatory"
                                                            id="time_from" value="{{ $event->time_from }}" name="time_from" placeholder="Time From">
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
                                                        <input readonly type="text"
                                                            class="form-control pickatime picker__input mandatory"
                                                            id="time_to" value="{{ $event->time_to ? date('H:i', strtotime($event->time_to)) : '' }}" name="time_to" placeholder="Time To">
                                                        <div class="form-control-position">
                                                            <i class="la la-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Add Description</label>
                                            <div class="position-relative has-icon-left">
                                                <textarea readonly class="form-control {{ $errors->has('description') ? 'error' : '' }}" rows="3" placeholder="Add Description" name="description" id="description">{{ old('description') ? old('description') : $event->description }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-align-justify"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('description'))
                                                <label id="description-error" class="error"
                                                    for="description">{{ $errors->first('description') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="comment">Add Comment</label>
                                            <div class="position-relative has-icon-left">
                                                <textarea readonly class="form-control" rows="3" placeholder="Add Comment" name="comment" id="comment">{{ old('comment') ? old('comment') : $event->comment }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-align-justify"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="custom-table mb-0 table" id="manageProducts">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File Name</th>
                                                <th>Uploaded At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $j=1 @endphp
                                            @forelse($event->docs as $row)
                                                <tr>
                                                    <td>{{ $j }}</td>
                                                    <td width="20%">{{ $row->original_name }}</td>
                                                    <td width="20%">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                                    <td style="padding:1.9rem 2rem !important; vertical-align: middle;">
                                                        <div class="d-flex">
                                                            <a href="{{ asset('events/event-' . $row->event_id . '/' . $row->file_name) }}"
                                                                style="margin-right:0.35rem"
                                                                data-toggle="tooltip"
                                                                data-trigger="hover"
                                                                data-placement="top"
                                                                data-title="View File"
                                                                data-original-title="View File">
                                                                <span style="padding:0.5rem 0.75rem"
                                                                    data-row-id="{{ $row['id'] }}"
                                                                    class="d-inline-block bg-primary bg rounded text-white">
                                                                    <i class="la la-eye"></i>
                                                                </span>
                                                            </a>
                                                        </div>

                                                    </td>
                                                </tr>
                                                @php $j++ @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="13">
                                                        <p class="text-center" style="font-size:1.5rem">No Data Available</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="updateRecurrenceModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apply Changes to Recurring Events</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Do you want to apply these changes to all future recurring events?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="applyToFutureNo" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="applyToFutureYes" data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteEventModal" class="modal modal-danger fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Event</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to delete this event
                    </div>
                    <div class="modal-footer">
                        <input style="background-color: #A6A6A6; color:white;" class="btn btn-outline pull-left"
                            type="button" value="Cancel" data-dismiss="modal">
                        <input style="background-color: #FF1616; color:white;" class="btn btn-outline" type="submit"
                            value="Confirm">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteEventRecurringModal" class="modal modal-danger fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete All Recurrences</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to deleted all recurrences of this event
                    </div>
                    <div class="modal-footer">
                        <input style="background-color: #A6A6A6; color:white;" class="btn btn-outline pull-left"
                            type="button" value="Cancel" data-dismiss="modal">
                        <input style="background-color: #FF1616; color:white;" class="btn btn-outline" type="submit"
                            value="Confirm">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="perDeleteEventModal" class="modal modal-danger fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete File</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to do this.
                    </div>
                    <div class="modal-footer">
                        <input style="background-color: #A6A6A6; color:white;" class="btn btn-outline pull-left"
                            type="button" value="Cancel" data-dismiss="modal">
                        <input style="background-color: #FF1616; color:white;" class="btn btn-outline" type="submit"
                            value="Confirm">
                    </div>
                </div>
            </form>
        </div>
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


            const hiddenInput = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'apply_to_future')
                .val(0);
            $('#manageType').append(hiddenInput);

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

                    if ("{{ $event->recurrence }}" !== "none") {
                        $('#updateRecurrenceModal').modal('show');

                        $('#applyToFutureYes').on('click', function() {
                            hiddenInput.val(1);
                            $('#manageType').off('submit');
                            form.submit();
                        });

                        $('#applyToFutureNo').on('click', function() {
                            hiddenInput.val(0);
                            $('#manageType').off('submit');
                            form.submit();
                        });

                        return false;
                    }

                    const recurrence_value = $('input[name="recurrence"]:checked').val();

                    if (recurrence_value) {
                        hiddenInput.val(1);
                    }


                    form.submit();
                }
            });

            var date = "";

            // $('.pickadate').pickadate({
            //     min: true,
            //     format: 'dd/mm/yyyy',
            //     formatSubmit: 'dd/mm/yyyy',
            //     onSet: function() {
            //         date = this.get('select', 'dd/mm/yyyy');
            //     }
            // });

            // $('.pickatime').pickatime({
            //     format: 'HH:i',
            //     formatSubmit: 'HH:i',
            //     interval: 30,
            //     editable: true
            // });

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
