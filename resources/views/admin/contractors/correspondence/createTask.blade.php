@extends('admin.partials.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/filter-multi-select-main/filter_multi_select.css') }}" />
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
        <section id="search-admins">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data" id='addTaskForm'
                                    action="{{ route('admin.contractors.tasks.cross.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="name">Task Description</label>
                                            <div class="position-relative has-icon-left">
                                                <textarea name="description" id="description"
                                                    class="form-control {{ $errors->has('description') ? 'error' : '' }}">
                                                </textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-align-justify"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('description'))
                                                <label id="description-error" class="error"
                                                    for="description">{{ $errors->first('description') }}</label>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="platform_users">Assign to Platform Users</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="platform_users" name="platform_users"
                                                            class="form-control {{ $errors->has('platform_users') ? 'error' : '' }}"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Assign to Platform Users" data-original-title="Assign to Platform Users"
                                                            title="Assign to Platform Users">
                                                            <option value="">Platform Users</option>
                                                            @foreach ($platform_users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                                </option>
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

                                                <div class="form-group">
                                                    <label for="task_tray">Assign to Task Tray</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="task_tray" name="task_tray"
                                                            class="form-control {{ $errors->has('task_tray') ? 'error' : '' }}"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Assign to Task Tray" data-original-title="Assign to Task Tray"
                                                            title="Assign to Task Tray">
                                                            <option value="">Task Tray</option>
                                                            @foreach ($taskTray as $tray)
                                                                <option value="{{ $tray->id }}">{{ $tray->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-users"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('task_tray'))
                                                        <label id="task_tray-error" class="error"
                                                            for="task_tray">{{ $errors->first('task_tray') }}</label>
                                                    @endif
                                                </div>

                                                {{-- <div class="form-group">
                                                    <label for="provider">Providers</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="provider" name="provider"
                                                            class="form-control {{ $errors->has('provider') ? 'error' : '' }}">
                                                            <option value="">Choose Provider</option>
                                                            @foreach ($providers as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
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
                                                </div> --}}
                                                <div class="form-group">
                                                    <label for="contact">Contacts</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="contact" name="contact" class="form-control {{ $errors->has('contact') ? 'error' : '' }}">
                                                            <option value="{{$contractors->id}}">{{$contractors->name}}</option>    
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                                                               

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label for="priority">Priority</label>
                                                            <div class="position-relative has-icon-left">
                                                                <select id="priority" name="priority" class="form-control"
                                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                                    data-title="Priority" data-original-title="Priority"
                                                                    title="Priority">
                                                                    <option value="1">High</option>
                                                                    <option value="2">Normal</option>
                                                                    <option value="3">Low</option>
                                                                </select>
                                                            </div>
                                                            @if ($errors->has('priority'))
                                                                <label id="priority-error" class="error"
                                                                    for="priority">{{ $errors->first('priority') }}</label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <label for="due_date">Due Date</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" class="form-control pickadate picker__input"
                                                                    name="due_date">
                                                                <div class="form-control-position">
                                                                    <i class="la la-calendar"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('due_date'))
                                                                <label id="due_date-error" class="error"
                                                                    for="due_date">{{ $errors->first('due_date') }}</label>
                                                            @endif
                                                        </div>
                                                        <div class="col-12 col-md-6 mt-1 mt-md-0">
                                                            <div class="form-group">
                                                                <label>Critical Task</label>
                                                                <br />
                                                                <label class="switch">
                                                                    <input type="checkbox" name="is_critical">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group reminders-row">
                                                    <label for="reminders">Reminders</label>
                                                    <div id="reminders-val"></div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="calender-icon">
                                                            <img src="{{ asset('assets/images/calendar.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                        <div class="reminders">
                                                            <p class="mb-0">No reminders set for this task</p>
                                                            <a href="" data-toggle="modal" id="openReminderModal"><i
                                                                    class="la la-plus-circle font-weight-bold mr-0.5"></i>Add a
                                                                reminder</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="status" class="form-control" id="status">
                                                            <option selected value="not_completed">Not Completed</option>
                                                            <option value="completed">Completed</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-lock"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="notes">Add Notes</label>
                                                    <div class="position-relative has-icon-left">
                                                        <textarea name="notes" cols="6" rows="6" id="notes"
                                                            class="form-control">{{ old('notes') ? old('notes') : '' }}</textarea>
                                                        <div class="form-control-position">
                                                            <i class="la la-align-justify"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="file">Upload File</label>
                                                    <input type="file" name="file" class="form-control" id="file">
                                                </div>
                                            </div>

                                            
                                        </div>
                                        <div class="form-actions right">
                                            <a href="{{ route('admin.contractors.correspondence', $contractor) }}" class="theme-btn btn btn-primary">   
                                                <i class="la la-times"></i> Cancel
                                            </a>
                                            <button type="submit" class="theme-btn btn btn-primary">
                                                <i class="la la-check-square-o"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="remindersModal" tabindex="-1" role="dialog"
                                        aria-labelledby="remindersModal" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Set a Reminder</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row form-group">
                                                        <div class="col-md-6">
                                                            <label for="reminder_date">Date</label>
                                                            <div class="position-relative has-icon-left remidner--date">
                                                                <input type="text"
                                                                    class="form-control pickadate picker__input mandatory"
                                                                    id="reminder_date">
                                                                <div class="form-control-position">
                                                                    <i class="la la-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="reminder_time">Time</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text"
                                                                    class="form-control pickatime picker__input mandatory"
                                                                    id="reminder_time">
                                                                <div class="form-control-position">
                                                                    <i class="la la-time"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <label for="reminder_name">Name</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" class="form-control mandatory"
                                                                    id="reminder_name" />
                                                                <div class="form-control-position">
                                                                    <i class="la la-commenting"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label for="reminder_description">Description</label>
                                                            <div class="position-relative has-icon-left">
                                                                <textarea class="form-control mandatory"
                                                                    id="reminder_description"></textarea>
                                                                <div class="form-control-position">
                                                                    <i class="la la-commenting"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="saveReminder" class="btn btn-primary">Add
                                                        Reminder</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="viewRemiderModal" tabindex="-1" role="dialog"
                                        aria-labelledby="remindersModal" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit a Reminder</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row form-group">
                                                        <div class="col-md-6">
                                                            <label for="reminder_view_date">Date</label>
                                                            <div class="position-relative has-icon-left remidner--date">
                                                                <input type="text"
                                                                    class="form-control pickadate picker__input mandatory"
                                                                    id="reminder_view_date">
                                                                <div class="form-control-position">
                                                                    <i class="la la-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="reminder_view_time">Time</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text"
                                                                    class="form-control pickatime picker__input mandatory"
                                                                    id="reminder_view_time">
                                                                <div class="form-control-position">
                                                                    <i class="la la-time"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <label for="reminder_view_name">Name</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" class="form-control mandatory"
                                                                    id="reminder_view_name" />
                                                                <div class="form-control-position">
                                                                    <i class="la la-commenting"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label for="reminder_view_description">Description</label>
                                                            <div class="position-relative has-icon-left">
                                                                <textarea class="form-control mandatory"
                                                                    id="reminder_view_description"></textarea>
                                                                <div class="form-control-position">
                                                                    <i class="la la-commenting"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="saveViewReminder"
                                                        class="btn btn-primary">Update Reminder</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
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
<script src="{{ asset('dashboard/plugins/new-multi-select/filter-multi-select-bundle.js ') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('/dashboard/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        let reminder_id = '';

        function viewReminder(id) {
            $('#viewRemiderModal').modal('toggle');
            reminder_id = id;

            var reminder_values = $('.reminders').find(`[data-id='${id}']`).attr('data-values');
            var reminder_data = reminder_values.split('+');
            $('#viewRemiderModal').find('#reminder_view_date').val(reminder_data[0]);
            $('#viewRemiderModal').find('#reminder_view_time').val(reminder_data[1]);
            $('#viewRemiderModal').find('#reminder_view_name').val(reminder_data[2]);
            $('#viewRemiderModal').find('#reminder_view_description').val(reminder_data[3]);

            $('#viewRemiderModal').find('#saveViewReminder').attr('data-id', id);

        }
        $(function() { 


            $('.pickadate').pickadate({
                min: true,
                format: 'dd/mm/yyyy',
                formatSubmit: 'dd/mm/yyyy',
            });

            $('.pickatime').pickatime();

            $('#openReminderModal').on('click', () => {
                $('#remindersModal .mandatory').each(function(index) {
                    if ($(this).val() == '') {
                        flag = 1;
                    }
                })
                $('#remindersModal').modal('toggle');
            })


            $("#saveReminder").on('click', () => {
                let flag = 0;
                $('#remindersModal .mandatory').each(function(index) {
                    if ($(this).val() == '') {
                        flag = 1;
                    }
                })
                if (flag) alert('Please fill all the fields');
                else {
                    var date_val = $('#reminder_date').val();


                    var time_val = $('#reminder_time').val();
                    console.log(time_val);
                    var remidner_name = $('#reminder_name').val();
                    var remidnder_description = $('#reminder_description').val();

                    let reminder_field = date_val + '+' + time_val + '+' + remidner_name + '+' +
                        remidnder_description;

                    let seconds = Math.ceil(new Date().getTime() / 1000);
                    console.log(seconds);
                    let reminder_id = 'reminder_' + ((Math.floor(Math.random() * 10)) + seconds);

                    $('#reminders-val').append('<input type="hidden" id="' + reminder_id + '" value="' +
                        reminder_field + '" name="reminders[]">');
                    $('#remindersModal').modal('hide');
                    $('.mandatory').each(function(index) {
                        $(this).val('');
                    })
                    let rem_id = "'" + reminder_id + "'";
                    $('.reminders').prepend('<a href="#" onclick="viewReminder(' + rem_id + ')" data-id="' +
                        reminder_id + '"  data-values="' + reminder_field +
                        '" style="text-decoration:underline">Reminder set for ' + date_val + ' at ' +
                        time_val + '</a>');
                    $('.reminders p').remove();

                }
            });

            $(document).on('click', '#saveViewReminder', function() {
                let flag = 0;
                console.log(reminder_id);

                $('#viewRemiderModal .mandatory').each(function(index) {
                    if ($(this).val() == '') {
                        flag = 1;
                    }
                })
                if (flag) alert('Please fill all the fields');
                else {
                    var date_val = $('#reminder_view_date').val();
                    var time_val = $('#reminder_view_time').val();
                    console.log(time_val);
                    var remidner_name = $('#reminder_view_name').val();
                    var remidnder_description = $('#reminder_view_description').val();

                    let reminder_field = date_val + '+' + time_val + '+' + remidner_name + '+' +
                        remidnder_description;
                    console.log('input#' + reminder_id);
                    $('#reminders-val').find('input#' + reminder_id).replaceWith(
                        '<input type="hidden" id="' + reminder_id + '" value="' + reminder_field +
                        '" name="reminders[]">');
                    let rem_id = "'" + reminder_id + "'";
                    $('.reminders a[data-id="' + reminder_id + '"]').replaceWith(
                        '<a href="#" onclick="viewReminder(' + rem_id + ')" data-id="' + reminder_id +
                        '"  data-values="' + reminder_field +
                        '" style="text-decoration:underline">Reminder set for ' + date_val + ' at ' +
                        time_val + '</a>');
                    $('#viewRemiderModal').modal('hide');
                    $('.mandatory').each(function(index) {
                        $(this).val('');
                    })
                }
            });



            jQuery.validator.setDefaults({ ignore: '' });
            var validate = $('#addTaskForm').validate({
                rules: {
                    description: 'required',
                    // task_type: "required",
                    due_date: 'required',
                    platform_users: 'required',
                    status: "required",
                },
                messages: {
                    description: "The Task description field is required",
                    due_date: 'The Due Date field is required',
                    platform_users: "The Assign to Platform Users field is required",
                    status: "The Status field is required",
                }
            });

            $('input, select').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
