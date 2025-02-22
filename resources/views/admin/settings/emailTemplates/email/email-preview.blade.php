@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="{{ asset('dashboard/plugins/filter-multi-select-main/filter_multi_select.css') }}" />
    <style>
        .variables p {
            margin-right: 0.8em;
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a>
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
                                <form method="post" enctype="multipart/form-data" id='manageEmailTemplate'
                                    action="{{ route("admin.ajax-send-email", ['matter' => $matter->id , 'parent_id' => $parent_id, 'email' => $email->id]) }}">
                                    @csrf
                                    <div class="form-body">
                                        {{-- <div class="form-group">
                                            <label> Variables </label>
                                            <div style="color: black; display: flex;" class="variables">
                                                <p>Matter_No</p>
                                                <p>Matter_Type</p>
                                            </div>
                                        </div> --}}
                                        <div class="form-group" style="width: 50%">
                                            <label>Attachement</label>
                                            <input type="file" name="attachement" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Subject</label>
                                            <div class="position-relative">
                                                <textarea name="subject" id="ckeditor" cols="30" rows="15" class="ckeditor {{ $errors->has('subject') ? 'error' : '' }}">
                                                    {{ old('subject') ? old('subject') : $email->subject }}
                                                </textarea>
                                            </div>
                                            <div class="errorCustom">

                                            </div>
                                            @if ($errors->has('subject'))
                                            <label id="subject-error" class="error"
                                            for="subject">{{ $errors->first('subject') }}</label>
                                            @endif
                                        </div>
                                        <div class="form-actions right">
                                         <a href="{{ route('admin.suppliers.correspondence', ['id' => $supplier->id]) }}" class="theme-btn btn btn-primary">
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
<script src="{{ asset('/dashboard/js/scripts/editors/editor-ckeditor.js') }}" type="text/javascript" ></script>
<script src="{{ asset('dashboard/plugins/filter-multi-select-main/filter-multi-select-bundle.min.js ') }}"></script>
<script src="{{ asset('/dashboard/js/custom.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            var validate = $('#manageEmailTemplate').validate({
                ignore: "",
                rules: {
                    subject: 'required',
                },         
                messages: {
                  'subject' : 'Please enter subject',
                },
                errorPlacement: function(error, element) 
                {
                    if (element.attr("name") == "subject") 
                   {
                        error.appendTo(".errorCustom");
                    } else {
                        error.insertAfter(element);
                    }
                }

            });

            $('input').on('focusout keyup', function() {
                $(this).valid();
            });

            jQuery.validator.addMethod("ckeditor", function (value, element) {  
                var idname = $(element).attr('id');  
                var editor = CKEDITOR.instances[idname];  
                var ckValue = GetTextFromHtml(editor.getData()).replace(/<[^>]*>/gi, '').trim();  
                if (ckValue.length === 0) {  
                    //if empty or trimmed value then remove extra spacing to current control  
                    $(element).val(ckValue);  
                } else {  
                    //If not empty then leave the value as it is  
                    $(element).val(editor.getData());  
                }  
                return $(element).val().length > 0;  
            }, "The Subject field is required");  
  
        function GetTextFromHtml(html) {  
            var dv = document.createElement("DIV");  
            dv.innerHTML = html;  
            return dv.textContent || dv.innerText || "";  
        }
        });
    </script>
@endsection
