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
                                <form method="post" id='manageType'
                                    action="{{ route('admin.settings.event-type.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="event_name">Event Name</label>
                                            <div class="position-relative">
                                                <input type="text" id="event_name" class="form-control {{ $errors->has('event_name') ? 'error' : '' }}"
                                                    placeholder="Event Name" name="event_name" value="{{ old('event_name') ? old('event_name') : '' }}" />
                                            </div>
                                            @if ($errors->has('event_name'))
                                                <label id="event_name-error" class="error"
                                                for="event_name">{{ $errors->first('event_name') }}</label>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="email_template">Link Email Template</label>
                                            <div class="position-relative">
                                                <select class="form-control {{ $errors->has('email_template') ? 'error' : '' }}" name="email_template" id="email_template">
                                                    <option value="">Choose Email Template</option>
                                                    @foreach ($emailTemplates as $item)
                                                        <option value="{{ $item->id }}">{{ $item->type }}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                            @if ($errors->has('email_template'))
                                                <label id="email_template-error" class="error"
                                                for="email_template">{{ $errors->first('email_template') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-actions right">
                                         <a href="{{ route('admin.settings.event-type') }}" class="theme-btn btn btn-primary">
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
<script src="{{ asset('/dashboard/js/custom.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            var validate = $('#manageType').validate({
                rules: {
                    event_name: {
                        required: true,
                        minlength: 3,
                    },
                    email_template: 'required'
                },
                messages: {
                    event_name:{
                       required: 'The Event Name field is required.',
                       minlength:'The min length for event name field should be at least 3'
                   },
                   email_template: "The Email Template field is required",
                }

            });

            $('input, textarea').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
