@extends('admin.partials.main')

@section('css')
    <style>
        .borderless td {
            border: 0 !important;
        }

        .icons-cust {
            margin-right: 0.5em;
        }

        .dropdown-menu {
            width: 100%;
        }

    </style>
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-12 mb-2 breadcrumb-new">
            <h3 class="mb-0 d-inline-block">{{ $page['page_title'] }} | {{ $page['tenant_name'] }}</h3>
        </div>
    </div>
    <div class="content-body">
        <section id="search-matters">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.tenants.edit', $tenant->id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.tenants.edit.property', $tenant->id)}}">Current Property</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.tenants.jobs', $tenant->id)}}">Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="correspondence" data-toggle="tab"
                                            aria-controls="correspondence" href="#correspondence" aria-expanded="true">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.properties.past.tenancy', $tenant->id) }}">Past Tenancy</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.tenants.calendar', $tenant->id) }}">Diary</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body" style="padding-top:0">
                                <div class="clearfix"></div>
                                <form id="searchForm" action="" method="get">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="d-flex  align-items-start justify-content-between">
                                                    <div class="form-right d-flex" style="width: 100%">
                                                        <div class="col-12 col-md-12">
                                                            <div class="form-group">
                                                                <label for="filterType">Filter By Type:</label>
                                                                <select name="filterType" id="filterType" class="form-control" onchange="applyFilter()">
                                                                    <option value="">All</option>
                                                                    <option {{ $filterType == 'folder' ? 'selected' : '' }} value="folder">Folders</option>
                                                                    <option {{ $filterType == 'file' ? 'selected' : '' }} value="file">Files</option>
                                                                    <option {{ $filterType == 'file' ? 'call' : '' }} value="call">Calls/Meetings</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="btn-group">
                                                                    <button type="submit" class="btn btn-primary basic-btn mr-1"
                                                                        ><i class="la la-search"></i>
                                                                        Search</button>
                                                                    <a href="{{ route('admin.tenants.correspondence', $tenant->id) }}" class="btn btn-primary basic-btn"
                                                                        ><i class="la la-refresh"></i>
                                                                        Reset</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
    @include('admin.partials.flashes')
    <section id="manageTransactionsSection">
        <div class="card">
            <form id="delSubmit" action="{{ route('admin.tenants.correspondence.delete', ['id' => $tenant->id]) }}"
                method="post">
                @csrf
                <div class="card-body" style="padding:0">
                    <div class="options pt-2 pb-2 pl-1 pr-1" style="display: flex;  justify-content: space-between;">
                        <div class="1iconsContainer d-flex align-items-center">
                            <div class="" style="color:#fff">
                                <a data-target="#newComment" data-toggle="modal"
                                    data-action="{{ route('admin.tenants.correspondence.ajax-add-comment', ['id' => $tenant->id]) }}"
                                    class="theme-btn btn btn-secondary" style="cursor:pointer; display: flex; align-items: center;" role="button" id="">
                                    <i style="padding-right: 0.2em;" class="la la-plus"></i>
                                    New Comment
                                    <i style="padding-left: 0.2em;" class="la la-comment" ></i>
                                </a>
                            </div>
                            <div class="ml-1" style="color:#fff">
                                <a data-toggle="modal" data-target="#feeEntryModal"
                                id="openNewCallModal"
                                    class="theme-btn btn btn-secondary" style="cursor:pointer; display: flex; align-items: center;" role="button" id="">
                                    <i style="padding-right: 0.2em;" class="la la-plus"></i>
                                    New Call
                                    <i style="padding-left: 0.2em;" class="la la-phone"></i>
                                </a>
                            </div>
                            <div class="ml-1" style="color:#fff">
                                <a data-toggle="modal" data-target="#meetingModal"
                                id="openMeetingModal"
                                    class="theme-btn btn btn-secondary" style="cursor:pointer; display: flex; align-items: center;" role="button" id="">
                                    <i style="padding-right: 0.2em;" class="la la-plus"></i>
                                    New Meeting
                                    <i style="padding-left: 0.2em;" class="la la-users"></i>
                                </a>
                            </div>
                            <div class="ml-1" style="color:#fff">
                                <a href="{{route('admin.suppliers.correspondence.task', ['id' => $tenant->id])}}"
                                    class="theme-btn btn btn-secondary" 
                                    style="cursor:pointer; display: flex; align-items: center;" 
                                    role="button">
                                    <i style="padding-right: 0.2em;" class="la la-plus"></i>
                                    New Task
                                    <i style="padding-left: 0.2em;" class="la la-tasks"></i>
                                </a>
                            </div>                            
                        </div>
                        <div class="2iconsContainer d-flex" style="align-items: center">

                        </div>
                    </div>
                    <div class="options pb-2 pl-1 pr-1" style="display: flex;">
                        <div data-target="#newFolderModal" data-toggle="modal" style="cursor: pointer"
                            class="new_folder d-flex align-items-center icons-cust mr-1">
                            <i class="la la-plus-circle"></i>
                            <span>Folder</span>
                        </div>
                        <div data-target="" class="icons-cust mr-1" style="cursor: pointer">
                            <?php if (Request::segment(5)) {
                                $parent_id = Request::segment(5);
                            } else {
                                $parent_id = 0;
                            } ?>
                            <a href="{{ route('admin.tenants.correspondence.uploadFilesForm', ['id' => $tenant->id, 'parent_id' => $parent_id]) }}"
                                class="d-flex align-items-center upload_file"
                                style="color: #6B6F82; text-decoration: none;">
                                <i class="la la-upload"></i>
                                <span>Upload Files</span>
                            </a>
                        </div>
                        <div id="moveFileButton" style="cursor: pointer; display: none;" data-target="#moveFile"
                            data-toggle="modal">
                            <div class="move_files d-flex align-items-center icons-cust mr-1">
                                <i class="la la-arrows-alt"></i>
                                <span>Move</span>
                            </div>
                        </div>
                        <div id="editComment" style="curser: pointer; display: none;">
                            <a style="text-decoration: none; color: #6B6F82;" href="#" id="submitEditFunc"
                                class="d-flex align-items-center icons-cust">
                                <i class="la la-edit"></i>
                                <span>Edit</span>
                            </a>
                        </div>
                        <div id="del" data-target="#moveFile" style="cursor: pointer; display: none;" data-togal="modal">
                            <a style="text-decoration: none; color: #6B6F82;" href="#" id="submitDelFunc"
                                class="d-flex align-items-center">
                                <i class="la la-trash"></i>
                                <span>Delete</span>
                            </a>
                        </div>
                        {{-- <div id="copyToFiles" class="pl-1 mr-1" style="cursor: pointer; display: none;" data-target="#copyToFilesModal"
                            data-toggle="modal">
                            <div class="move_files d-flex align-items-center icons-cust">
                                <i class="la la-edit"></i>
                                <span>Copy To File Vault</span>
                            </div>
                        </div> --}}
                        {{-- <div id="copyToCross" class="pl-1 mr-1" style="cursor: pointer; display: none;" data-target="#copyToCrossModal"
                            data-toggle="modal">
                            <div class="move_cross d-flex align-items-center icons-cust">
                                <i class="la la-edit"></i>
                                <span>Copy To Correspondence</span>
                            </div>
                        </div> --}}
                        <div id="editDescription" class="ml-1 mr-1" style="cursor: pointer; display: none;" data-target="#editDescriptionFilesModal"
                            data-toggle="modal">
                            <div class="move_files d-flex align-items-center icons-cust">
                                <i class="la la-arrows-alt"></i>
                                <span>Add/Edit Description</span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 custom-table" id="manageTransactions">
                            <thead>
                                <tr>
                                    <th style="width: 5%"></th>
                                    <th style="width: 6%;">Type</th>
                                    <th>Created</th>
                                    <th>Description</th>
                                    <th>Item</th>
                                </tr>
                            </thead>
                            <tbody class="borderless">
                                @foreach ($all as $d)

                                    @if(isset($d->is_task) && $d->is_task == "yes")
                                        @if ($d->is_task == "yes")
                                        @php
                                            $taskDetail = \DB::table('tasks')->where('id',$d->task_id)->first();
                                        @endphp
                                        <tr>
                                            <td><input class="check" value="{{ $d->id . '+' . 'task' }}"
                                                    style="cursor: pointer;" type="checkbox" name="check[]"></td>
                                            <td style="display: flex; height: 50px"><i style="margin-top: 0.2em;" class="la la-tasks"></i></td>
                                            <td style="color: black; width: 10%">
                                                {{ isset($d['created_at']) ? date('d/m/Y H:i ', strtotime($d['created_at'])) : null }}
                                            </td>
                                            <td style="width: 40%;">
                                                <span class="meeting_description" 
                                                      data-text="{{ $taskDetail->description ?? '' }}" 
                                                      data-date="{{ date('d/m/Y', strtotime($d->date)) }}" 
                                                      data-time="{{ $d->time }}" 
                                                      data-time-to="{{ $d->time_to }}" 
                                                      style="cursor: pointer">
                                                    {{ strlen($taskDetail->description) > 150 ? substr($taskDetail->description, 0, 150).'...' : $taskDetail->description }}
                                                </span>
                                            </td>
                                            <td style="wdith: 50%;">Task
                                            </td>
                                        </tr>
                                        @endif
                                    @elseif(isset($d->name))
                                        <tr>
                                            <td><input class="check" value="{{ $d->id . '+' . 'folder' }}"
                                                    style="cursor: pointer;" type="checkbox" name="check[]"></td>
                                            <td><i style="color: #fdb900;" class="la la-folder"></i></td>
                                            <td style="color: black; width: 10%">
                                                {{ isset($d->created_at) ? date('d/m/Y H:i', strtotime($d->created_at)) : null }}
                                            </td>
                                            <td style="width: 40%;"><a style="color: black; text-decoration: none;"
                                                href="{{ route('admin.tenants.correspondence.child', ['id' => $tenant->id, 'parent_id' => $d['id']]) }}">{{ $d['name'] }}</a></td>
                                            <td style="wdith: 50%;">
                                                Folder
                                            </td>
                                        </tr>
                                    @elseif(isset($d->is_call))
                                        @if ($d->is_call == 'yes')
                                            @php $admin = \DB::table('admins')->where('id', $d->admin_id)->first(); @endphp
                                            <tr>
                                                <td><input class="check" value="{{ $d->id . '+' . 'call' }}"
                                                        style="cursor: pointer;" type="checkbox" name="check[]"></td>
                                                <td style="display: flex; height: 50px"><i style="margin-top: 0.2em;" class="la la-phone"></i>@if($d->call_type == 'outgoing') <i style="transform: rotate(45deg); margin-bottom: 1.7em;" class="la la-arrow-up"></i> @else <i style="transform: rotate(45deg); margin-bottom: 1.7em;" class="la la-arrow-down"></i> @endif</td>
                                                <td style="color: black; width: 10%">
                                                    {{ isset($d['created_at']) ? date('d/m/Y H:i ', strtotime($d['created_at'])) : null }}
                                                </td>
                                                <td style="width: 40%">{{ $d->description }}</td>
                                                <td style="wdith: 50%;"><span> Call </span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td><input class="check" value="{{ $d->id . '+' . 'meeting' }}"
                                                        style="cursor: pointer;" type="checkbox" name="check[]"></td>
                                                <td style="display: flex; height: 50px"><i style="margin-top: 0.2em;" class="la la-users"></i></td>
                                                <td style="color: black; width: 10%">
                                                    {{ isset($d['created_at']) ? date('d/m/Y H:i ', strtotime($d['created_at'])) : null }}
                                                </td>
                                                <td style="width: 40%;"><span class="meeting_description" data-text="{{ $d->description }}" data-date="{{ date('d/m/Y', strtotime($d->date)) }}" data-time="{{ $d->time }}" data-time-to="{{ $d->time_to }}" style="cursor: pointer" > {{ strlen($d->description) > 150 ? substr($d->description, 0, 150).'...' : $d->description }} </span></td>
                                                <td style="wdith: 50%;">Meeting
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                    <tr>
                                         @if(!$d->is_text)
                                            @php 
                                                $extension = pathinfo($d->original_name, PATHINFO_EXTENSION);
                                                if ($extension === 'eml') {
                                                    $fileLogo = '<i class="la la-envelope-o"></i>';
                                                } else {
                                                    $fileLogo = '<i class="la la-file-o"></i>';
                                                }
                                            @endphp
                                            <td><input value="{{ $d->id . '+' . 'file' . '+' . $d->file_name }}{{ !empty($d->file_description) ? "+".$d->file_description : '' }}"
                                                    class="check" style="cursor: pointer;" type="checkbox"
                                                    name="check[]"></td>
                                            <td>{!! $fileLogo !!}</td>   
                                            <td style="color: black; width: 10%">
                                                {{ isset($d->created_at) ? date('d/m/Y H:i', strtotime($d->created_at)) : null }}
                                            </td>
                                            <td style="width: 40%;">{{ $d->file_description }}
                                                
                                            <td style="color: black; width: 50%;"><a href="{{ asset($d->original_link) }}" download="{{ $d->file_name }}">{{ $d->file_name }}</a></td>   
                                        @else
                                            <td><input value="{{ $d->id . '+' . 'text' . '+' . $d->text . '+' . $d->copy_to_correspondence }}"
                                                class="check" style="cursor: pointer;" type="checkbox"
                                                name="check[]"></td>   
                                            <td>
                                                <i class="la la-comment" ></i>
                                            </td>
                                            <td style="color: black; width: 10%">
                                                {{ isset($d->created_at) ? date('d/m/Y H:i', strtotime($d->created_at)) : null }}
                                            </td>
                                            <td style="width: 40%;"><span>{{ $d->text }}</span></td>
                                            <td style="wdith: 50%;">Comment
                                            </td>
                                        @endif          
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center pagination-wrapper mt-2">
                    {!! $all->links('pagination::bootstrap-4') !!}
                </div>
            </form>
            @if (Request::segment(5))
                <div class="card-footer">
                    <div class="form-actions left">
                        <a href="<?php if ($parent->parent_id != 0) {
                            echo route('admin.tenants.correspondence.child', ['id' => $tenant->id, 'parent_id' => $parent->parent_id]);
                        } else {
                            echo route('admin.tenants.correspondence', ['id' => $tenant->id]);
                        } ?>" class="theme-btn btn btn-primary">
                            <i class="la la-arrow-left" style="vertical-align: bottom;"></i> Go Back
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="form-actions right" style="padding: 3em 2em 3em 0em; text-align: right;">
                <a href="{{ route('admin.settings.tenants') }}" class="theme-btn btn btn-primary">
                    <i class="la la-times"></i> Cancel
                </a>
            </div>   
        </div>    ~
    </section>

    {{-- Folder modal --}}
    <div class="modal" id="newFolderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="<?php if (Request::segment(5)) {
                    echo route('admin.tenants.correspondence.newFolder', ['id' => $tenant->id, 'parent_id' => Request::segment(5)]);
                } else {
                    echo route('admin.tenants.correspondence.newFolder', ['id' => $tenant->id, 'parent_id' => 0]);
                } ?>" id="newFolder" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">New Folder</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="new_folder">Enter Folder Name</label>
                            <input type="text" name="new_folder" id="new_folder" class="form-control"
                                placeholder="Folder Name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">New Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="post" onsubmit="save_comment(event)" class="saveCommentForm"  id="commentForm">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn theme-btn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!--Edit Description Modal -->
    <div class="modal" id="editDescriptionFilesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('admin.tenants.correspondence.add-edit-description', ['id' => $tenant->id]) }}" class="editDescriptionForm" id="editDescriptionForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add/Edit Description</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                            <input type="hidden" name="descriptionData" value="" id="descriptionData">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn theme-btn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Edit Modal -->
    <div class="modal" id="editCommentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="<?php echo route('admin.tenants.correspondence.ajax-edit-comment', $tenant->id) ?>" class="editCommentForm" id="editCommentForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" name="editCommentInput" id="editCommentInput" rows="3" required></textarea>
                            <input type="hidden" id="commentData" name="commentData" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn theme-btn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete" class="modal modal-danger fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form name="deleteForm" action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to delete this Folder/File, All data inside will be deleted.
                    </div>
                    <div class="modal-footer">
                        <input style="background-color: #A6A6A6; color:white;" class="btn btn-outline pull-left"
                            type="button" value="Cancel" data-dismiss="modal">
                        <input id="submitDel" type="button" style="background-color: #FF1616; color:white;"
                            class="btn btn-outline" value="Confirm">
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- move file modal --}}
    <div class="modal" id="moveFile" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="<?php if (Request::segment(5)) {
                    echo route('admin.tenants.correspondence.moveFile', ['id' => $tenant->id, 'parent_id' => Request::segment(5)]);
                } else {
                    echo route('admin.tenants.correspondence.moveFile', ['id' => $tenant->id, 'parent_id' => 0]);
                } ?>" id="move_file" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Move File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="move_link">Enter the link where you want to move the file</label>
                            <input type="text" name="move_link" id="move_link" class="form-control"
                                placeholder="Absolute Path from first folder to destination folder">
                            <input type="hidden" id="fileName" name="fileName" value="">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="copyToFilesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.tenants.correspondence.fileVault', $tenant->id) }}" id="copy_to_files" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Copy To File Vault</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure? You want to copy this file to file vault.
                        <input type="hidden" name="copyFileNames" id="copyFileNames">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="modal" id="copyToCrossModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('tenant.corres.add', ['id' => $tenant->id])}}" id="copy_to_cross" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Copy To Correspondence</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="ids" type="hidden" name="ids">
                        Are you sure? You want to copy this Correspondence.
                        <input type="hidden" name="copyFileNames" id="copyFileNames">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!---- New Fee Modal--->
    <div class="modal" id="feeEntryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.tenants.correspondence.newCall', ['id' => $tenant->id, 'parent_id' => $parent_id]) }}" id="feeTypeForm"
                    method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">New Call</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Call Type</label>
                                    <select name="call_type" class="form-control"> 
                                        <option value="incoming">Incoming</option>
                                        <option value="outgoing">Outgoing</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control fee_description" rows="7" id="description" name="description"></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group position-relative">
                                    <label for="date">Date</label>
                                    <input type="text" class="form-control pickadate picker__input" name="date"
                                        placeholder="Date" />
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="meetingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.tenants.correspondence.newMeeting', ['id' => $tenant->id, 'parent_id' => $parent_id]) }}" id="meetingForm"
                    method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">New Meeting</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group position-relative">
                                    <label for="date">Date</label>
                                    <input type="text" class="form-control pickadate picker__input" name="meeting_date"
                                        placeholder="Date" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group position-relative">
                                    <label for="time">Time From</label>
                                    <input type="text" class="form-control pickadtime picker__time" name="meeting_time"
                                        placeholder="Time" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group position-relative">
                                    <label for="time">Time To</label>
                                    <input type="text" class="form-control pickadtime picker__time" name="meeting_time_to"
                                        placeholder="Time" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tenant</label>
                            <select name="tenant" class="form-control" id="tenant">
                                <option value="">Choose Tenant</option>
                                @foreach ($tenants as $item)
                                    <option {{ $tenant->id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="meeting_notes">Notes</label>
                            <textarea class="form-control meeting_notes" rows="7" id="meeting_notes" name="meeting_notes"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveFeeEntry" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal" id="viewMeetingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="" id="meetingForm"
                    method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">View Meeting Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group position-relative">
                                    <label for="date">Date</label>
                                    <input type="text" class="form-control pickadate picker__input meeting_date_view" disabled name="meeting_date"
                                        placeholder="Date" />
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group position-relative">
                                    <label for="time">Time From</label>
                                    <input type="text" class="form-control pickadtime picker__time meeting_time_view" disabled name="meeting_time"
                                        placeholder="Time" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group position-relative">
                                    <label for="time">Time To</label>
                                    <input type="text" class="form-control pickadtime picker__time meeting_time_view_to" name="meeting_time_view_to"
                                        placeholder="Time" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="meeting_notes">Notes</label>
                            <textarea class="form-control meeting_notes_view" disabled rows="7" id="meeting_notes" name="meeting_notes"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        {{-- <button type="submit" id="saveFeeEntry" class="btn btn-primary">Save changes</button> --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.date.js') }}" type="text/javascript"></script>
<script src="{{ asset('/dashboard/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('/dashboard/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script>

        var validator = $("#newFolder").validate({
            rules: {
                new_folder: 'required',
            },
            messages: {
                new_folder: "The Folder Name field is required",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        location.reload();
                        // $("#newFolderModal").modal('hide');
                        // $("#manageTransactions").replaceWith(response);
                    },
                    error: function(data) {
                        if (data.responseJSON.type == 1) {
                            var error = '<div class="alert alert-danger"><p>' + data.responseJSON
                                .message + '</p></div>';
                            $('#newFolderModal .modal-body').prepend(error);
                        } else if (data.responseJSON.errors) {
                            var errors = $.parseJSON(data.responseText).errors;
                            if (errors.new_folder) {
                                validator.showErrors({
                                    new_folder: errors.new_folder[0],
                                });
                            }
                        }
                    }
                })
            }
        });

        $(".new_folder").click(function() {
            //removing the error shown
            $('#newFolderModal').find('.alert').remove();

            //reseting the form
            $('#newFolder').trigger('reset');

            //remove the validator error
            $("#new_folder").removeClass("error");
            validator.resetForm();
        })

        var validator = $(".saveCommentForm").validate({
            rules: {
                comment: 'required',
            },
            messages: {
                comment: "The Comment field is required",
            },
            submitHandler: function(form) {
                let action = document.querySelector('[data-target="#newComment"]').getAttribute('data-action');
                $.ajax({
                    type: 'POST',
                    url: action,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}",
                    },
                    success: function(response) {
                        location.reload();
                    }, error: function(response) {
                        alert('Something went wrong Please try again later.')
                    }
                })
            }
        });

        // save_comment = (event) => {
        //     event.preventDefault();
        //     let action = document.querySelector('[data-target="#newComment"]').getAttribute('data-action');
        //     $.ajax({
        //         type: 'POST',
        //         url: action,
        //         data: $('#commentForm').serialize(),
        //         headers: {
        //              'X-CSRF-TOKEN': "{{csrf_token()}}",
        //         },
        //         success: function(response) {
        //             location.reload();
        //         }, error: function(response) {
        //             alert('Something went wrong Please try again later.')
        //         }
        //     })
        // }

        $(document).on('click', '.custom-pag .pagination a', (e) => {
            e.preventDefault();
            let url = $(e.target).attr('href');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    $('#manageSettings').replaceWith(response);
                },
                error: function(data) {
                    alert('Something went wrong Please try again  later.');
                }
            })
        })
        
        $("#copyToCross").click(function (e) { 
            e.preventDefault();
            
            var checkedValues = [];

            $('input[name="check[]"]:checked').each(function() {
                checkedValues.push($(this).val()); 
            });

            var resultString = checkedValues.join('-'); 

            $("#ids").val(resultString); 
        });



        $(document).on('change', '.check', function() {
            var boxes = $('input[name="check[]"]:checked');
            var checkCommentVal = "";
            var foundFolder = 0;
            let foundFile = 0;
            let foundComment = 0;
            let foundCall = 0;
            let foundFiles = 0;
            // var showComment = 0;
            if (boxes.length > 0) {
                boxes.each(function(index, element) {
                    checkCommentVal = $(this).val();
                    var checkCommentArray = checkCommentVal.split("+");
                    if(checkCommentArray[1] == 'file') {
                        foundFile = 1;
                        foundFiles++;
                    } else if(checkCommentArray[1] == 'text') {
                        foundComment = 1;
                    } else if(checkCommentArray[1] == 'folder') {
                        foundFolder = 1;
                    } else if(checkCommentArray[1] == 'call') {
                        foundCall = 1;
                    }
                }); 
                $("#del").show();
                $("#copyToCross").show();

                if(foundFile == 1 && foundCall == 0 && foundComment == 0 && foundFolder == 0) {
                    $("#moveFileButton").show();
                    $("#copyToFiles").show();
                    if(foundFiles == 1) {
                        $("#editDescription").show();
                    } else {
                        $("#editDescription").hide();
                    }
                } else {
                    $("#copyToFiles").hide();
                    $("#editDescription").hide();
                }
                if(foundComment == 1 && foundFile == 0 && foundCall == 0 && foundFolder == 0) {
                    $("#editComment").show();
                } else {
                    $("#editComment").hide();
                }
            } else {
                $("#del").hide();
                $("#copyToCross").hide();
                $("#moveFileButton").hide();
                $("#editComment").hide();
                $("#copyToFiles").hide();
                $("#editDescription").hide();
            }

            window.checkedBoxes = [];
            boxes.each(function(index, element) {
                window.checkedBoxes.push($(this).val());
            });

            // if(boxes.length > 1 || boxes.length == 0) {
            //     $("#moveFileButton").hide();
            // }

            // if(boxes.length == 1) {
            //     $("#moveFileButton").show();
            // }
        });

        $("#submitDel").click(function() {
            $("#delSubmit").submit();
        });

        $("#submitDelFunc").on('click', function() {
            $("#delete").modal('show');
        });

        $("#submitEditFunc").on('click', function() {
            $("#commentData").val(window.checkedBoxes);

            //removing the error shown
            $('#editCommentModal').find('.alert').remove();

            //reseting the form
            $('#editCommentForm').trigger('reset');

            //remove the validator error
            $("#editCommentInput").removeClass("error");
            validation.resetForm();

            if(window.checkedBoxes.length == 1) {
                var checkedBoxesVal = window.checkedBoxes[0].split("+");
                $("#editCommentInput").val(checkedBoxesVal[2]);
                // Assuming checkedBoxesVal[3] contains "yes" or "no"
                if (checkedBoxesVal[3] === "yes") {
                    // Check the "yes" radio button
                    $('input[id="edit_comment_copy_to_correspondence_yes"][value="yes"]').prop('checked', true);
                } else if (checkedBoxesVal[3] === "no") {
                    // Check the "no" radio button
                    $('input[id="edit_comment_copy_to_correspondence_no"][value="no"]').prop('checked', true);
                }

            }

            $("#editCommentModal").modal('show');
        })

        $("#editDescription").on('click', function() {
            $("#descriptionData").val(window.checkedBoxes);

            if(window.checkedBoxes.length == 1) {
                let checkedBoxesVal = window.checkedBoxes[0].split("+");
                if(checkedBoxesVal.length === 4) {
                    $("#description").val(checkedBoxesVal[3]);
                }
            }
        });



        var validation = $("#editCommentForm").validate({
            rules: {
                editCommentInput: 'required',
            },
            messages: {
                editCommentInput: "This field is required",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#editCommentModal").modal('hide');
                        location.reload();
                    },
                    error: function(data) {
                        if (data.responseJSON.errors) {
                            var errors = $.parseJSON(data.responseText).errors;
                            if (errors.editCommentInput) {
                                validator.showErrors({
                                    editCommentInput: errors.editCommentInput[0],
                                });
                            }
                        }
                    }
                })
            }
        });

        var validation = $("#move_file").validate({
            rules: {
                move_link: 'required',
            },
            messages: {
                move_link: "This field is required",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#moveFile").modal('hide');
                        location.reload();
                    },
                    error: function(data) {
                        if (data.responseJSON.type == 1) {
                            var error = '<div class="alert alert-danger"><p>' + data.responseJSON
                                .message + '</p></div>';
                            $('#moveFile .modal-body').prepend(error);

                            if (data.responseJSON.fileNameError == 1) {
                                setInterval(function() {
                                    location.reload();
                                }, 3000);
                            }
                        } else if (data.responseJSON.errors) {
                            var errors = $.parseJSON(data.responseText).errors;
                            if (errors.move_link) {
                                validator.showErrors({
                                    move_link: errors.move_link[0],
                                });
                            }
                        }
                    }
                })
            }
        });

        $("#copy_to_files").submit(function(e) {
            e.preventDefault();

            let form = $("#copy_to_files");
            let action = $(form).attr('action');
            $.ajax({
                url: action,
                type: "POST",
                data: $(form).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    var success = '<div class="alert alert-success"><p>File Copied Successfully</p></div>';
                    $('#copyToFilesModal .modal-body').prepend(success);
                    setInterval(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(data) {
                    var error = '<div class="alert alert-danger"><p>' + data.responseJSON
                        .message + '</p></div>';
                    $('#copyToFilesModal .modal-body').prepend(error);
                    // setInterval(function() {
                    //     location.reload();
                    // }, 2000);
                }
            })
        })

        $("#moveFileButton").click(function() {
            $("#fileName").val(window.checkedBoxes);

            //removing the error shown
            $('#moveFile').find('.alert').remove();

            //reseting the form
            $('#move_file').trigger('reset');

            //remove the validator error
            $("#move_link").removeClass("error");
            validation.resetForm();
        });

        $("#copyToFiles").click(function() {
            $("#copyFileNames").val(window.checkedBoxes);
        });

        $('[data-target="new-document"]').on('click', (e) => {
            e.preventDefault();
            let action = $('#addDocument').data('action')
            $.ajax({
                type: "GET",
                url: action,
                success: function(response) {
                    $('#manageTransactionsSection').replaceWith(response);
                    // $(".contact_filter").show();
                },
                error: function(data) {
                    alert('Something went wrong Please try again  later.');
                }

            })
        })

                /*--- Load Email Templates ---*/
        // $('[data-target="new-email-template"]').on('click', (e) => {
        //     e.preventDefault();
        //     let action = $('#addEmailTemplate').data('action')
        //     $.ajax({
        //         type: "GET",
        //         url: action,
        //         success: function(response) {
        //             $('#manageTransactionsSection').replaceWith(response);
        //         },
        //         error: function(data) {
        //             alert('Something went wrong Please try again  later.');
        //         }

        //     })
        // })


        $('.pickadate').pickadate({
            min: true,
            format: 'dd/mm/yyyy',
            formatSubmit: 'dd/mm/yyyy',
        });

        $('.pickadtime').pickatime({
            format: 'HH:i',
        });

        $("#openFeeEntryModal").on('click', function() {
            $('#feeTypeForm').find('.alert').remove();

            $(".fee_description").val(" ");

            $("option:selected", "#feeType").prop("selected", false);
        });

        var validator = $('#feeTypeForm').validate({
            ignore: "",
            rules: {
                date: {
                    required: true
                },
            },
            messages: {
                date: 'Please select date',
            },
            submitHandler: function(form) {
                $('#feeTypeForm .modal-body').find('.alert').remove();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function(data) {
                        console.log(data);
                        if (data.responseJSON.errors) {
                            var errors = $.parseJSON(data.responseText).errors;
                            if (errors.date) {
                                validator.showErrors({
                                    date: errors.date[0]
                                });
                            }
                        } else if (data.responseJSON.success == false) {
                            var error = '<div class="alert alert-danger"><p>' + data.responseJSON
                                .message + '</p></div>';
                            $('#feeTypeForm .modal-body').prepend(error);
                        }
                    },
                    success: function(data) {
                        console.log(data);
                        var success = '<div class="alert alert-success"><p>' + data.message +
                            '</p></div>';
                        $('#feeTypeForm .modal-body').prepend(success);
                        $('#feeTypeForm').trigger('reset');
                        setTimeout(function() {
                              location.reload();
                            $('#feeEntryModal').modal('hide');
                        }, 1000)

                    }
                });
            }

        });

        $("#openMeetingModal").on('click', function() {
            // $('#feeTypeForm').find('.alert').remove();

            $(".meeting_notes").val(" ");
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
            },
            submitHandler: function(form) {
                $('#meetingForm .modal-body').find('.alert').remove();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function(data) {
                        if (data.responseJSON.errors) {
                            var errors = $.parseJSON(data.responseText).errors;
                            if (errors.meeting_date) {
                                validator.showErrors({
                                    meeting_date: errors.meeting_date[0]
                                });
                            }
                        } else if (data.responseJSON.success == false) {
                            var error = '<div class="alert alert-danger"><p>' + data.responseJSON
                                .message + '</p></div>';
                            $('#meetingForm .modal-body').prepend(error);
                        }
                    },
                    success: function(data) {
                        console.log(data);
                        var success = '<div class="alert alert-success"><p>' + data.message +
                            '</p></div>';
                        $('#meetingForm .modal-body').prepend(success);
                        $('#meetingForm').trigger('reset');
                        setTimeout(function() {
                              location.reload();
                            $('#meetingModal').modal('hide');
                        }, 1000)

                    }
                });
            }

        });

        $(document).on('click', '.card-body .meeting_description', function() {

            let meeting_date = $(this).attr('data-date');
            let meeting_description = $(this).attr('data-text');
            let meeting_time = $(this).attr('data-time');
            let meeting_time_to = $(this).attr('data-time-to');

            $(".meeting_date_view").val(meeting_date);
            $(".meeting_notes_view").val(meeting_description);
            $(".meeting_time_view").val(meeting_time);
            $('.meeting_time_view_to').val(meeting_time_to);

            $("#viewMeetingModal").modal('show');



        });
    </script>
@endsection
