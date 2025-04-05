@extends('admin.partials.main')

@section('css')
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

        /* html body a {
            color: initial !important;
        } */

        #calendar a.fc-event {
            color: initial !important;
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
            background-color: #ffb400 !important;
            border-color: #ffb400 !important; 
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
        <div class="content-header-right col-md-6 col-12">
            <div class="dropdown float-md-right">
                <a href="{{ route('admin.diary.event.create') }}" class="btn btn-primary basic-btn btn-min-width mr-1 mb-1"
                    type="button">
                    <i class="la la-plus"></i>
                    New Event
                </a>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="settings-form">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-content collapse show">
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
    </div>
@endsection

{{-- {{dd(json_encode($data))}} --}}

@section('js')
    <script src="{{ asset('assets/plugins/fullcalendar/dist/index.global.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var data = {!! json_encode($data) !!};
            data.forEach(function(event) {
                console.log(event.title);
                event.title = event.title.replace(/\\r\\n/g, '\r\n').replace(/\\t/g, '\t');
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
                    },
                    start: element.start,
                    end: element.end,
                    url: element.url,
                };

                objArray.push(obj);

                i++;
            })

            const date = new Date();

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
                initialDate: currentDate,
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,timeGridDay'
                },
                initialView: "timeGridDay",
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true, // allow "more" link when too many events
                slotLabelInterval: "00:30",
                eventColor: '#1d1e53',
                eventBorderColor: '#FFFFFF',
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: 'short',
                    hour12: false,
                },
                events: objArray,
                eventClick: function(event) {
                    event.jsEvent.preventDefault();
                    if (event.event.url) {
                        window.open(event.event.url, "_blank");
                        return false;
                    }
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

                        return {
                          html: '<div class="fc-daygrid-event-dot"></div> <div class="fc-event-time">' + eventTime + '</div>' +
                            '<div class="fc-event-title" title="'+arg.event.title+'">' + arg.event.title + '</div>'
                        };
                      },
                      dayHeaderFormat: {
                            weekday: 'long'
                        },
                    },
                    dayGridWeek: {
                        eventTimeFormat: {
                            hour: 'numeric',
                            minute: '2-digit',
                            omitZeroMinute: false,
                            meridiem: 'short',
                            hour12: false,
                        },
                        dayHeaderFormat: { 
                            weekday: 'long', 
                            day: 'numeric',
                            month: 'numeric', 
                            omitCommas: true, 
                            delimiter: "/" 
                        }
                    },
                    timeGridDay: {
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

                            return {
                            html: '<div class="fc-event-main-frame"><div class="fc-event-time">' + eventTime + '</div>' +
                                '<div class="fc-event-title-container"><div class="fc-event-title fc-sticky">' + arg.event.title + '</div></div></div>'
                            };
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

            calendar.scrollToTime( "07:00" );
        });
    </script>
@endsection