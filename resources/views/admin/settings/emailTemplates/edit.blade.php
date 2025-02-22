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
                        <li class="breadcrumb-item active">Edit Page
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
                                <form method="post" enctype="multipart/form-data" id='manageCMSContent'
                                    action="{{ route('admin.emailTemplate.update',['id' => $data['id']]) }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="tite">Email Type</label>
                                            <div class="position-relative">
                                                <input type="text" id="type" class="form-control {{ $errors->has('type') ? 'error' : '' }}"
                                                    placeholder="Email Type" name="type" value="{{ old('type') ? old('type') : $data['type'] }}" />
                                            </div>
                                            @if ($errors->has('type'))
                                            <label id="type-error" class="error"
                                            for="type">{{ $errors->first('type') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="tite">Subject</label>
                                            <div class="position-relative">
                                                <input type="text" id="subject" class="form-control {{ $errors->has('subject') ? 'error' : '' }}"
                                                    placeholder="Subject" name="subject" value="{{ old('subject') ? old('subject') : $data['subject'] }}" />
                                            </div>
                                            @if ($errors->has('subject'))
                                            <label id="subject-error" class="error"
                                            for="subject">{{ $errors->first('subject') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="tite">Status</label>
                                            <div class="position-relative">
                                                <select id="issueinput6" name="status" class="form-control {{ $errors->has('status') ? 'error' : '' }}"
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Status" data-original-title="" title="">
                                                    <option value="1" {{ ($data['deleted_at'] == NULL) ? 'selected' : '' }} >Active</option>
                                                    <option value="0" {{ ($data['deleted_at'] != NULL) ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            @if ($errors->has('status'))
                                            <label id="status-error" class="error"
                                            for="status">{{ $errors->first('status') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="is_html">Html</label>
                                            <div class="position-relative">
                                                <select id="issueinput6" name="is_html" class="form-control {{ $errors->has('is_html') ? 'error' : '' }}"
                                                    data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                    data-title="Status" data-original-title="" title="">
                                                    <option value="yes" {{ ($data['is_html'] == 'yes') ? 'selected' : '' }} >Yes</option>
                                                    <option value="no" {{ ($data['is_html'] == 'no') ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            @if ($errors->has('status'))
                                            <label id="status-error" class="error"
                                            for="status">{{ $errors->first('status') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <div class="position-relative">
                                                <textarea name="description" id="ckeditor" cols="30" rows="15" class="ckeditor">
                                                    {{ old('content') ? old('content') : base64_decode($data['content']) }}
                                                </textarea>
                                            </div>
                                            @if ($errors->has('description'))
                                            <label id="description-error" class="error"
                                            for="description">{{ $errors->first('description') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-actions right">
                                            <a href="{{ route('admin.emailTemplate.index') }}" class="theme-btn btn btn-primary">
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
<script src="{{ asset('/dashboard/vendors/js/editors/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
<script src="{{ asset('/dashboard/js/scripts/editors/editor-ckeditor.js') }}" type="text/javascript"></script>
<script src="{{ asset('/dashboard/vendors/js/editors/ckfinder/ckfinder.js') }}" type="text/javascript"></script>
<script src="{{ asset('/dashboard/js/custom.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            CKFinder.setupCKEditor( CKEDITOR );
            var validate = $('#manageEmailTemplate').validate({
                rules: {
                    type: {
                        required: true,
                    },
                    subject: {
                        required: true,
                        minlength:6
                    },
                    status: {
                        required: true,
                    },
                    is_html:"required"
                },
                messages: {
                   type:{
                       required: 'The Email Type field is required.',
                   },
                   subject:{
                       required: 'The Subject field is required.',
                       minlength:'The min length for Subject field should be at least 6'
                   },
                   is_html:'The Html field is required',
                   status: 'The Status field is required',
                }

            });

            $('input, textarea').on('focusout keyup', function() {
                $(this).valid();
            });
        });
    </script>
@endsection
