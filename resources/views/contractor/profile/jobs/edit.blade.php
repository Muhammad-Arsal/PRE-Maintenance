@extends('contractor.partials.main')

@section('css')
<!-- Flatpickr CSS -->
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ $page['page_parent_link'] }}" class="theme-color">{{ $page['page_parent'] }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $page['page_current'] }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="search-contractors">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            
                            <div class="card-body">
                                @include('contractor.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='manageJobs' action="{{ route('contractor.contractors.updateJob.details', $contractor_id) }}">
                                    @csrf
                                    <div class="form-body">
                                        @foreach($jobs as $i => $task)
                                            <div class="card">
                                                <h3 class="mb-2">Job task # {{ $i +1 }}</h3>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea class="form-control description" rows="3" readonly>{{ $task->description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Contractor Comment</label>
                                                            <textarea name="tasks[{{ $task->id }}][contractor_comment]" class="form-control" rows="3">{{ $task->contractor_comment }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        @if ($task->admin_upload)
                                                        <div class="form-group">
                                                            <label>Admin Upload</label><br>
                                                            <a href="{{ asset('storage/' . $task->admin_upload) }}" download>
                                                                <img src="{{ asset('storage/' . $task->admin_upload) }}" style="max-height: 80px;">
                                                            </a>
                                                        </div>
                                                    @endif
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Contractor Upload</label>
                                                            <input type="file" name="tasks[{{ $task->id }}][contractor_upload]" class="form-control-file">
                                                            @if ($task->contractor_upload)
                                                                <a href="{{ asset('storage/' . $task->contractor_upload) }}" download>
                                                                    <img src="{{ asset('storage/' . $task->contractor_upload) }}" style="max-height: 80px; margin-top: 5px;">
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Date</label>
                                                            <input id="date" type="text" name="tasks[{{ $task->id }}][date]" class="form-control flatpickr" value="{{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Price</label>
                                                            <input type="text" name="tasks[{{ $task->id }}][price]" class="form-control price" value="{{ $task->price }}">
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            <hr>
                                        @endforeach

                                        <script type="text/template" id="task-template">
                                            <div class="card mt-3 new-task-block">
                                                <h4 class="mb-2">New Task</h4>
                                                <div class="row">
                                                    <input type="hidden" name="new_tasks[__INDEX__][contractor_id]" value="{{ $contractor_id }}">
                                                    <input type="hidden" name="new_tasks[__INDEX__][job_id]" value="{{ $jobs->first()->jobs_id ?? '' }}">
                                        
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <textarea name="new_tasks[__INDEX__][description]" class="form-control description" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Contractor Comment</label>
                                                            <textarea name="new_tasks[__INDEX__][contractor_comment]" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Contractor Upload</label>
                                                            <input type="file" name="new_tasks[__INDEX__][contractor_upload]" class="form-control-file">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Date</label>
                                                            <input type="text" name="new_tasks[__INDEX__][date]" class="form-control flatpickr" placeholder="DD/MM/YYYY">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Price</label>
                                                            <input type="text" name="new_tasks[__INDEX__][price]" class="form-control price">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </script>
                                        <div id="dynamic-task-container"></div>

                                        <div class="form-group mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-task">+ Add Task</button>
                                        </div>
                                        
                                        <div class="form-actions right">
                                            <a href="{{route('contractor.settings.contractors.edit', auth('contractor')->user()->id)}}" class="theme-btn btn btn-primary">
                                                <i class="la la-times"></i> Back
                                            </a>
                                            <button type="submit" class="theme-btn btn btn-primary">
                                                <i class="la la-check-square-o"></i> Send To Office
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
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script>
  $(function() {

    $.validator.addClassRules('price', {
      number: true
    });
    $.validator.addClassRules('description', {
      required: true
    });

    $("#manageJobs").validate({
      errorPlacement: function(error, element) {
        error.addClass("text-danger");
        if (element.parent(".position-relative").length) {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      }
    });
  });
</script>
<script>
     document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date", {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    });
</script>
<script>
    let taskIndex = 0;

    document.getElementById('add-task').addEventListener('click', function () {
        const template = document.getElementById('task-template').innerHTML;
        const html = template.replace(/__INDEX__/g, taskIndex);

        const container = document.getElementById('dynamic-task-container');
        container.insertAdjacentHTML('beforeend', html);

        flatpickr(container.querySelectorAll('.flatpickr')[taskIndex], {
            dateFormat: "d/m/Y",
            allowInput: true,
            defaultDate: new Date()
        });

        taskIndex++;
    });

    document.addEventListener("DOMContentLoaded", function () {
        flatpickr('.flatpickr', {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    });
</script>

@endsection
