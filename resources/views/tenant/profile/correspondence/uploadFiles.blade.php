@extends('tenant.partials.main')

@section('css')
<link href="{{ asset('/assets/css/dropzone.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }} | {{ $page['tenant_name'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}" class="theme-color">Home</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('tenant.tenants.correspondence', $tenant->id) }}" class="theme-color">
                          Correspondence
                        </a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="search-tenants">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('tenant.settings.tenants.edit', $tenant->id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('tenant.settings.tenants.edit.property', $tenant->id)}}">Property</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="correspondence" data-toggle="tab"
                                            aria-controls="correspondence" href="#correspondence" aria-expanded="true">Correspondence</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div id="alertContainer" class="alert alert-success d-none" role="alert">
                                    File uploaded successfully!
                                </div>
                                <form method="post" enctype="multipart/form-data" id='uploadFiles' action="">
                                    @csrf
                                    <div class="form-group">
                                        <div class="dropzone" id="dropzone">
                                            {{-- <input type="file" name="files" id="files"> --}}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea rows="5" cols="5" id="description" name="description" class="form-control" placeholder="description"></textarea>
                                    </div>

                                    <div class="form-actions left">
                                        <a href="<?php if($parent_id != 0) { echo route('tenant.tenants.correspondence.child', ['id' => $tenant->id, 'parent_id' => $parent_id]); } else { echo route('tenant.tenants.correspondence', ['id' => $tenant->id]); } ?>"
                                            class="theme-btn btn btn-primary">
                                            <i class="la la-arrow-left" style="vertical-align: bottom;"></i> Go Back
                                        </a>
                                        <button type="button" class="theme-btn btn btn-primary submitBtn">
                                            <i class="la la-check-square-o"></i> Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- <div class="modal fade" id="copyToVaultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Copy To File Vault</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Do you want to copy these files to file vault?
            </div>
            <div class="modal-footer">
                <button type="button" id="cancelCopyFiles" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" id="copyFiles" class="btn btn-primary">Yes</button>
            </div>
            </div>
        </div>
    </div> --}}
@endsection

@php 
    if($parent_id != 0) {
        $endRoute = route('tenant.tenants.correspondence.child', ['id' => $tenant->id, 'parent_id' => $parent_id]);
    } else {
        $endRoute = route('tenant.tenants.correspondence', ['id' => $tenant->id]);
    }
@endphp

@section('js')
<script src="{{ asset('/assets/js/dropzone.min.js') }}" type="text/javascript"></script>
<script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone('.dropzone', { 
            autoProcessQueue: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            maxFilesize: 200,
            timeout: 600000,
            maxFiles: 1,
            url: "{{ route('tenant.tenants.correspondence.uploadFiles', ['id' => $tenant->id, 'parent_id' => Request::segment(5)]) }}",
            parallelUploads: 1, // Number of files process at a time (default 2)

        });

        myDropzone.on('sending', function(file, xhr, formData) {
            formData.append('description', $("#description").val());
            formData.append('copy_to_file_vault', $("input[name='copy_to_file_vault']:checked").val());
            formData.append('copy_to_correspondence', $("input[name='copy_to_correspondence']:checked").val());
        });

         // Handle file upload success
        myDropzone.on('success', function(file, response) {
            $("#alertContainer").removeClass('d-none');

            window.location.href = "{{ $endRoute }}";
            setTimeout(() => {
                window.location.href = "{{ $endRoute }}";
            }, 3000);
        });

        // Handle file upload error
        myDropzone.on('error', function(file, errorMessage) {
            alert("An error occurred while uploading the file. Please try again."); // Show error alert
        });

        $(".submitBtn").click(function(e) {
            e.preventDefault();
            
            if (myDropzone.getQueuedFiles().length > 0) {
                myDropzone.processQueue();
            } else {
                alert("Please add files before submitting.");
            }
        });
</script>
@endsection
