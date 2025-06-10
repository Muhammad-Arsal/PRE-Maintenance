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
                                <form action="{{ route('admin.diary.meeting.update', ['id' => $meeting->id, 'type' => 'contact']) }}" id="meetingForm"
                                    method="post">
                                    @csrf
                                    <div class="form-body">
            
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group position-relative">
                                                    <label for="date">Date</label>
                                                    <input type="text" class="form-control pickadate picker__input" value="{{ date('d/m/Y', strtotime($meeting->date)) }}" name="meeting_date"
                                                        placeholder="Date" />
                                                </div>
                                                @if ($errors->has('meeting_date'))
                                                    <label id="meeting_date-error" class="error"
                                                        for="meeting_date">{{ $errors->first('meeting_date') }}</label>
                                                @endif
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label for="time">Time From</label>
                                                    <input type="text" class="form-control pickatime picker__time" name="meeting_time" value="{{ $meeting->time }}"
                                                        placeholder="Time" />
                                                </div>

                                                @if ($errors->has('meeting_time'))
                                                    <label id="meeting_time-error" class="error"
                                                        for="meeting_time">{{ $errors->first('meeting_time') }}</label>
                                                @endif
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group position-relative">
                                                    <label for="time">Time To</label>
                                                    <input type="text" class="form-control pickatime picker__time" name="meeting_time_to" value="{{ $meeting->time_to }}"
                                                        placeholder="Time" />
                                                </div>
                                                @if ($errors->has('meeting_time_to'))
                                                    <label id="meeting_time_to-error" class="error"
                                                        for="meeting_time_to">{{ $errors->first('meeting_time_to') }}</label>
                                                @endif
                                            </div>
                                        </div>
                
                                        <div class="form-group">
                                            <label>Contacts</label>
                                            <select name="contacts" class="form-control" id="contacts">
                                                <option value="">Choose Contacts</option>
                                                @foreach ($contacts as $item)
                                                    <option {{ $meeting->landlord_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                
                                        <div class="form-group">
                                            <label for="meeting_notes">Notes</label>
                                            <textarea class="form-control meeting_notes" rows="7" id="meeting_notes" name="meeting_notes">{{ $meeting->description }}</textarea>
                                        </div>
                
                                    </div>
                                    <div class="form-actions left">
                                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Save</button>
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
    <script type="text/javascript">
        $(function() {

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
            });


            var validator = $('#meetingForm').validate({
                ignore: "",
                rules: {
                    meeting_date: {
                        required: true
                    },
                    meeting_time: {
                        required: true,
                    },
                    meeting_time_to: {
                        required: true,
                    }
                },
                messages: {
                    meeting_date: 'Please select date',
                    meeting_time: {
                        required: "Please select time from",
                    },
                    meeting_time_to: {
                        required: "Please select time to",
                    }
                }
            });

            $('input, textarea').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
