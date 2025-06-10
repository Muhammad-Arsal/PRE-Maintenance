@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/new-multi-select/filter_multi_select.css') }}" />
    <style>
        .fc-daygrid-dot-event {
            flex-wrap: wrap;
        }

        .custom-content {
            overflow: hidden;
        }

        .custom-content span:first-child {
            font-weight: bold;
        }

        /* Multi‐line (2‐line) clamp + ellipsis */
        .event-title {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;    /* show at most 2 lines */
        overflow: hidden;         /* hide everything past line 2 */
        max-width: 100%;
        line-height: 1.2em;       /* height of one text line */
        font-size: 0.85em;
        }

        /* html body a {
            color: initial !important;
        } */

        .fc-timegrid-slots tr {
            height: 3.5em;
        }

        .fc-event-main-frame .fc-event-title {
            margin-bottom: 2em;
        }

        #calendar a.fc-event {
            color: initial !important;
            padding-left:5px;
            padding-right:5px;
        }

        .custom-height-change {
            height: 60% !important;
        }

        /* .fc-timegrid-event-harness>.fc-timegrid-event {
            position:  static !important;
        } */

        .custom-users-span {
            color: #dcdcdc !important;
        }

        .custom-event-description-span {
            color: #dcdcdc !important;
        }

        .fc .fc-timegrid-slot {
            height: 2.5em !important;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: #d9d7d8 !important;
            /* border: 1px solid #1d1e53 !important; */
        }

        /* .--fc-today-bg-color {
            background-color: #d9d7d8 !important;
        } */

        .fc-theme-standard {
            --fc-today-bg-color: #d9d7d8;  /* Your desired color */
        }

        /* .fc-timegrid-slot-lane {
            background-color: #d9d7d8 !important;
            border: 1px solid #1d1e53 !important; 
        } */

        .mobile-event-change-flex {
            flex-wrap: wrap;
        }

        .bold-time {
            font-weight: bold;
        }

        .fc-theme-standard td, .fc-theme-standard th {
            border: 1px solid darkgray !important;
        }

        .fc-timegrid-event-harness .fc-event-title {
            text-wrap: nowrap !important;
        }

        /* for active button state */
        .fc-button.fc-button-active {
            background-color: #00BED6 !important;
            border-color: #00BED6 !important; 
        }

        .fc-h-event {
            padding:5px
        }

        .fc-daygrid-dot-event{
            display:flex;
            flex-direction: column;
            align-items: start !Important;

        }

        .fc-event .fc-event-time-frame {
            display: flex;
            align-items: center;
        }

        .fc-h-event .custom-content {
            color:#fff
        }

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

        #providers .dropdown-menu {
            width: 100%;
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
        <section id="settings-form">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-content collapse show">
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
                                    <div class="row">
                                        <div class="col-md-12">
                                                {{-- <div class="row form-group"> --}}
                                                    <div id="calendar"></div>
                                                {{-- </div> --}}
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="eventFormModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form name="eventForm" id="eventForm" action="" method="post" style="width: 100%;" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Create New Event</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="event_type">Event Type</label>
                                <select name="event_type" id="event_type" class="form-control">
                                    <option value="">Select Event Type</option>
                                    @foreach ($event_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->event_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="property">Property</label>
                                        <select name="property[]" class="form-control">
                                            @foreach ($properties as $property)
                                                <option value="{{ $property->id }}">{{ $property->line1 . ', ' . $property->city . ', ' . $property->county . ', ' . $property->country . ', ' . $property->postcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="platform_users">External User Name</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="text" id="external_user_name" class="form-control {{ $errors->has('external_user_name') ? 'error' : '' }}" placeholder="External User Name" name="external_user_name" value="" aria-invalid="true">
                                            <div class="form-control-position">
                                                <i class="la la-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="external_user">External User Email</label>
                                    <input type="email" id="external_user" class="form-control" placeholder="External User Email" name="external_user">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cc">CC</label>
                                        <div class="position-relative has-icon-left">
                                            <input type="email" id="cc" class="form-control {{ $errors->has('cc') ? 'error' : '' }}" placeholder="CC" name="cc" value="" aria-invalid="true">
                                            <div class="form-control-position">
                                                <i class="la la-envelope"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_main_contact">Address Main Contact</label>
                                        <div class="position-relative has-icon-left">
                                            <textarea id="address_main_contact" name="address_main_contact"  class="form-control {{ $errors->has('address_main_contact') ? 'error' : '' }} " placeholder="Address Main Contact">{{ old('address_main_contact') ? old('address_main_contact') : '' }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-globe"></i>
                                                </div>     
                                        </div>
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
                                                    <option value="{{ $contact->id }}">{{ $contact->name . ' ' . (!empty($contact->contactType) ? ('('.$contact->contactType->name.')') : '')  }}</option>
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
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="date">Date From</label>
                                        <input type="text" id="date" class="form-control startPickadate" placeholder="Date From" name="date" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_to">Date To</label>
                                        <input type="text" id="date_to" class="form-control endPickadate" placeholder="Date To" name="date_to" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="time_from">Time From</label>
                                        <input type="text" class="form-control startPickatime" id="time_from" name="time_from" placeholder="Time From">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="time_to">Time To</label>
                                        <input type="text" class="form-control endPickatime" id="time_to" name="time_to" placeholder="Time To">
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
                                <textarea class="form-control" rows="4" placeholder="Add Description" name="description"></textarea>
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

                            <div class="form-group">
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
@endsection

{{-- {{dd(json_encode($data))}} --}}

@section('js')
    <script src="{{ asset('assets/plugins/fullcalendar/dist/index.global.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/new-multi-select/filter-multi-select-bundle.js ') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var validate = $('#eventForm').validate({
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
                submitHandler: function (form) {
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


            var startPicker = $('.startPickadate').pickadate({
                format: 'dd/mm/yyyy',
                formatSubmit: 'dd/mm/yyyy',
                // onSet: function() {
                //     date = this.get('select', 'dd/mm/yyyy');
                // }
            });

            startPicker = startPicker.pickadate('picker')




            var endPicker = $('.endPickadate').pickadate({
                format: 'dd/mm/yyyy',
                formatSubmit: 'dd/mm/yyyy',
                // onSet: function() {
                //     date = this.get('select', 'dd/mm/yyyy');
                // }
            });

            endPicker = endPicker.pickadate('picker')

            var startTimePicker = $('.startPickatime').pickatime({
                format: 'HH:i',
                interval: 30,
                editable: true
            });
            var endTimePicker = $('.endPickatime').pickatime({
                format: 'HH:i',
                interval: 30,
                editable: true
            });

            startTimePicker = startTimePicker.pickatime('picker')
            endTimePicker = endTimePicker.pickatime('picker')

            const contacts = $("#contacts").filterMultiSelect({
                placeholderText: "Add Contacts"
            });
            // const providers = $("#providers").filterMultiSelect({
            //     placeholderText: "Add Providers"
            // });

            $("input[name='recurrence']").change(function() {
                if($(this).val() != 'none') {
                    $(".repeated_for").show();
                } else {
                    $(".repeated_for").hide().find('input').val("");
                }
            });


            var data = {!! json_encode($data) !!};
            data.forEach(function(event) {
                event.title = event.title.replace(/\\r\\n/g, '\r\n').replace(/\\t/g, '\t');
                event.toolTipTitle = event.toolTipTitle.replace(/\\r\\n/g, '\r\n').replace(/\\t/g, '\t');
                event.mobileTitle = event.mobileTitle.replace(/\\r\\n/g, '\r\n').replace(/\\t/g, '\t');
            });
            // console.log(data);
            // data = data.replace(/&quot;/ig,'"');
            // data = data.replace(/\r\n/g, '');
            // console.log(data);
            // data = $.parseJSON(data);

            // console.log(data);

            var objArray = [];

            var i = 1;
            $(data).each(function(name, element) {
                let obj = {
                    groupId: i,
                    title: element.title,
                    extendedProps: {
                        platform_users: element.description,
                        toolTipTitle: element.toolTipTitle,
                        mobileTitle: element.mobileTitle,
                        eventType: element.event_type,
                    },
                    start: element.start,
                    end: element.end,
                    url: element.url,
                    backgroundColor: element.color,
                    borderColor: element.color,
                };

                objArray.push(obj);

                i++;
            })

            const date = new Date();

            function formatDate(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = String(date.getFullYear());
                return `${day}/${month}/${year}`;
            }

            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();

            if(day < 10) {
                day = "0" + day;
            } 

            if(month < 10) {
                month = "0" + month;
            }
            let currentDate = year + "-" + month + "-" + day;

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                // timeZone: 'Europe/London',
                SlotMinTime: "08:00:00",
                initialDate: currentDate,
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,timeGridDay'
                },
                initialView: "{{ $calendarView }}",
                initialDate: "{{ $calendarDate }}",
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                selectable: true,
                stickyHeaderDates: true,
                selectMirror: true,
                dayMaxEvents: true, // allow "more" link when too many events
                slotLabelInterval: "00:30",
                eventColor: '#1d1e53',
                slotEventOverlap: false,
                eventBorderColor: '#FFFFFF',
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: 'short',
                    hour12: false,
                },
                events: objArray,
                datesSet: function (info) {
                    const currentDate = info.startStr;
                    const currentView = info.view.type;

                    saveDateAndView(currentDate, currentView);
                },
                eventClick: function(event) {
                    // event.jsEvent.preventDefault();
                    // if (event.event.url) {
                    //     window.open(event.event.url, "_blank");
                    //     return false;
                    // }
                },
                select: function(selectionInfo) {
                    let startSelectionTime = selectionInfo.start.toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                    let endSelectionTime = selectionInfo.end.toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });

                    let startSelectionDate = formatDate(selectionInfo.start);
                    let endSelectionDate = formatDate(selectionInfo.end);

                    startPicker.set('select', startSelectionDate);
                    endPicker.set('select', endSelectionDate);

                    startTimePicker.set('select', startSelectionTime);
                    endTimePicker.set('select', endSelectionTime);



                    // $('#eventFormModal').modal('show');

                },
                  views: {
                    month: {
                      // Set a custom event rendering function for the month view
                      eventContent: function(arg) {
                        var eventTime = '';
                        if (arg.event.start !== arg.event.end) {
                            var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                            var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                            if(arg.event.start != null && arg.event.end != null) {
                                var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                eventTime = startTime + ' - ' + endTime;
                            } else {
                                if(arg.event.start != null) {
                                    var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                    eventTime = startTime;
                                } else {
                                    var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                    eventTime = endTime;
                                }
                            }
                        }

                        let appendHtml = "";
                        if(arg.event.extendedProps.eventType) {
                            appendHtml = "<div class='event-title'>"+arg.event.extendedProps.eventType+"</div>";
                        }

                        return {
                          html: appendHtml+'<div class="fc-event-time-frame"><div class="fc-daygrid-event-dot"></div> <div class="fc-event-time">' + eventTime + '</div></div>' +
                            '<div class="fc-event-title" title="'+arg.event.extendedProps.toolTipTitle+'">' + arg.event.title + '</div>'
                        };
                      },
                      dayHeaderFormat: {
                            weekday: 'long'
                        },
                    },
                    dayGridWeek: {
                        eventContent: function(arg) {
                            var eventTime = '';
                            if (arg.event.start !== arg.event.end) {
                                var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                if(arg.event.start != null && arg.event.end != null) {
                                    var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                    var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                    eventTime = startTime + ' - ' + endTime;
                                } else {
                                    if(arg.event.start != null) {
                                        var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                        eventTime = startTime;
                                    } else {
                                        var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                        eventTime = endTime;
                                    }
                                }
                            }

                            let appendHtml = "";
                            if(arg.event.extendedProps.eventType) {
                                appendHtml = "<div class='event-title'>"+arg.event.extendedProps.eventType+"</div>";
                            }

                            return {
                            html: appendHtml+'<div class="fc-event-time-frame"><div class="fc-daygrid-event-dot"></div> <div class="fc-event-time">' + eventTime + '</div></div>' +
                                '<div class="fc-event-title" title="'+arg.event.extendedProps.toolTipTitle+'">' + arg.event.title + '</div>'
                            };
                        },
                        eventTimeFormat: {
                            hour: 'numeric',
                            minute: '2-digit',
                            omitZeroMinute: false,
                            meridiem: 'short',
                            hour12: false,
                        },
                        dayHeaderContent: (args) => {
                            const date = args.date;
                            const options = { weekday: 'long' };
                            const weekday = new Intl.DateTimeFormat('en-US', options).format(date);
                            return `${weekday} ${date.getDate()}/${date.getMonth() + 1}`;
                        }
                    },
                    timeGridDay: {
                        eventContent: function(arg) {
                            var timeDifference = '';

                            function formatTime(date) {
                                return new Date(date).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                            }

                            // Function to calculate time difference in minutes
                            function calculateTimeDifference(start, end) {
                                var startDate = new Date(start);
                                var endDate = new Date(end);
                                var differenceInMilliseconds = endDate - startDate;
                                var differenceInMinutes = differenceInMilliseconds / 60000;
                                return differenceInMinutes;
                            }

                            var eventTime = '';
                            if (arg.event.start !== arg.event.end) {
                                var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                if(arg.event.start != null && arg.event.end != null) {
                                    var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                    var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                    eventTime = startTime + ' - ' + endTime;
                                    timeDifference = calculateTimeDifference(arg.event.start, arg.event.end);
                                } else {
                                    if(arg.event.start != null) {
                                        var startTime = new Date(arg.event.start).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                        eventTime = startTime;
                                    } else {
                                        var endTime = new Date(arg.event.end).toLocaleString([], { hour: 'numeric', minute: '2-digit', hour12: false });
                                        eventTime = endTime;
                                    }
                                }
                            }

                            let appendHtml = "";
                            if(arg.event.extendedProps.eventType) {
                                appendHtml = "<div class='event-title'>"+arg.event.extendedProps.eventType+"</div>";
                            }


                            if(timeDifference == '30') {
                                return {
                                    html: appendHtml+'<div class="fc-event-main-frame mobile-event-change-flex"><div class="fc-event-time">' + eventTime + '</div>' +
                                        '<div class="fc-event-title-container"><div class="fc-event-title fc-sticky" title="'+arg.event.extendedProps.toolTipTitle+'">' +arg.event.extendedProps.mobileTitle+ '</div></div></div>'
                                };
                            } else {
                                return {
                                    html: appendHtml+'<div class="fc-event-main-frame"><div class="fc-event-time">' + eventTime + '</div>' +
                                        '<div class="fc-event-title-container"><div class="fc-event-title fc-sticky" title="'+arg.event.extendedProps.toolTipTitle+'">' +arg.event.extendedProps.mobileTitle+ '</div></div></div>'
                                };
                            }
                        }
                    }
                  },
                eventDidMount: function(info) {
                    var eventElement = info.el;
                    var eventDescription = info.event.extendedProps.platform_users;

                    // var getTime = $(info.el).find('.fc-event-time');

                    if(eventDescription) {
                        // Create a new element for custom content
                        var customContentElement = document.createElement('div');
                        customContentElement.classList.add('custom-content');

                        // Create a span element for "Users:"
                        var usersSpan = document.createElement('span');
                        if(info.view.type == 'timeGridDay') {
                            usersSpan.classList.add('custom-users-span');
                        }
                        usersSpan.textContent = 'Users: ';
                        customContentElement.appendChild(usersSpan);

                        // Create a span element for the event description
                        var eventDescriptionSpan = document.createElement('span');
                        if(info.view.type == 'timeGridDay') {
                            $(eventElement).find(".fc-event-time").addClass("bold-time");
                            var getFcEventMain = $(eventElement).find('.fc-event-main-frame');
                            eventDescriptionSpan.classList.add('custom-event-description-span');

                            eventDescriptionSpan.textContent = eventDescription;
                            customContentElement.appendChild(eventDescriptionSpan);

                            customContentElement.classList.add('custom-height-change');

                            $(getFcEventMain).append(customContentElement);
                        } else {
                            eventDescriptionSpan.textContent = eventDescription;
                            customContentElement.appendChild(eventDescriptionSpan);

                            eventElement.appendChild(customContentElement);
                        }

                        // Append the custom content to the event element
                        // if(info.view.type == 'timeGridDay') {
                        //     $(".fc-event-main-frame").css('flex-direction', 'column');
                        //     $(getTime).css('display', 'flex');
                        //     // $(getTime).css('margin-right', )
                        //     $(".custom-content").css('margin-left', '1em');
                        //     $(getTime).append(customContentElement);
                        // } else {
                        // }
                    }
                }
            });

// calendar.render();


            calendar.render();
            calendar.scrollToTime( "08:00" );

            function saveDateAndView(date, view) {
                $.ajax({
                    url: "{{ route('admin.tenants.saveCalendarState') }}", 
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', 
                        date: date,
                        view: view,
                    },
                    success: function (response) {
                        console.log('Calendar state saved:', response);
                    },
                    error: function (xhr) {
                        console.error('Failed to save calendar state:', xhr.responseText);
                    },
                });
            }

        });
    </script>
@endsection