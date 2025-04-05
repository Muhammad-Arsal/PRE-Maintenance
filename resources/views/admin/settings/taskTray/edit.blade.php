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
                                    action="{{ route('admin.settings.taskTray.update', $taskTray->id) }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <div class="position-relative">
                                                <input type="text" id="name" class="form-control {{ $errors->has('name') ? 'error' : '' }}"
                                                    placeholder="Name" name="name" value="{{ $taskTray->name }}" />
                                            </div>
                                            @if ($errors->has('name'))
                                            <label id="name-error" class="error"
                                            for="name">{{ $errors->first('name') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-actions right">
                                         <a href="{{ route('admin.settings.taskTray') }}" class="theme-btn btn btn-primary">
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
                    name: {
                        required: true,
                    },
                },
                messages: {
                   name:{
                       required: 'The Name field is required.',
                   },
                }

            });

            $('input').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
