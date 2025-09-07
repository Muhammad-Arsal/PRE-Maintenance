@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/forms/selects/select2.css') }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                                @include('admin.partials.flashes')
                                <form method="POST" enctype="multipart/form-data" id="manageJobs" action="{{ route('admin.jobs.update.contractor_list', $job->id) }}">
                                    @csrf
                                    <div class="card-header">
                                        <ul class="nav nav-tabs nav-underline no-hover-bg">
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('admin.jobs.edit', $job->id) }}">Overview</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link active disabled" id="contractorList" data-toggle="tab"
                                                    aria-controls="contractorList" href="#contractorList" aria-expanded="true">Contractor list</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="form-body">
                                        <div id="contractor-rows">
                                            {{-- Existing contractor blocks --}}
                                            @foreach($job->jobDetail->groupBy('contractor_id') as $i => $taskGroup)
                                                @php
                                                    $contractor = $contractors->firstWhere('id', $i);
                                                    $wonContract = $taskGroup->first()->won_contract === 'yes';
                                                @endphp
                                        
                                                <div class="contractor-block mb-4" data-index="{{ $loop->index }}">
                                                    <!-- Contractor Info -->
                                                    <div class="row contractor-row mb-2">
                                                        <div class="col-md-6">
                                                            <label>Contractor</label>
                                                            <select name="contractors[{{ $loop->index }}][contractor_id]" class="form-control select2">
                                                                <option value="">Select Contractor</option>
                                                                @foreach($contractors as $c)
                                                                    <option value="{{ $c->id }}" {{ $c->id == $i ? 'selected' : '' }}>{{ $c->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 d-flex align-items-end" style="top: -10px;">
                                                            <label class="mb-0 mr-2"><span class="text-danger">🏅</span> Won contract?</label>
                                                            <input type="radio" name="won_contract_global" value="{{ $loop->index }}" {{ $wonContract ? 'checked' : '' }}>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-contractor">X</button>
                                                        </div>
                                                    </div>
                                        
                                                    <!-- Task Rows -->
                                                    <div class="task-rows" data-contractor-index="{{ $loop->index }}">
                                                        @foreach($taskGroup as $j => $task)
                                                            <div class="task-row row mb-2">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Description <span class="text-danger">*</span></label>
                                                                        <textarea name="contractors[{{ $loop->parent->index }}][tasks][{{ $j }}][description]" class="form-control description" rows="4" required>{{ $task->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Contractor Comment</label>
                                                                        <textarea name="contractors[{{ $loop->parent->index }}][tasks][{{ $j }}][contractor_comment]" class="form-control" rows="4">{{ $task->contractor_comment }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Admin Upload</label>
                                                                    <input type="file" name="contractors[{{ $loop->parent->index }}][tasks][{{ $j }}][admin_upload]" class="form-control-file">
                                                                
                                                                    @if (!empty($task->admin_upload))
                                                                        <div class="mt-1">
                                                                            <a href="{{ asset('storage/' . $task->admin_upload) }}" download>
                                                                                <img src="{{ asset('storage/' . $task->admin_upload) }}" alt="Admin Upload" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;">
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                
                                                                <div class="col-md-6">
                                                                    <label>Contractor Upload</label>
                                                                    <input type="file" name="contractors[{{ $loop->parent->index }}][tasks][{{ $j }}][contractor_upload]" class="form-control-file">
                                                                
                                                                    @if (!empty($task->contractor_upload))
                                                                        <div class="mt-1">
                                                                            <a href="{{ asset('storage/' . $task->contractor_upload) }}" download>
                                                                                <img src="{{ asset('storage/' . $task->contractor_upload) }}" alt="Contractor Upload" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;">
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>                                                                                                                              
                                                                <div class="col-md-6 mt-2">
                                                                    <div class="form-group">
                                                                        <label>Date <span class="text-danger">*</span></label>
                                                                        <input type="text" name="contractors[{{ $loop->parent->index }}][tasks][{{ $j }}][date]" class="form-control flatpickr" value="{{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }}" placeholder="DD/MM/YYYY" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-2">
                                                                    <div class="form-group">
                                                                        <label>Price</label>
                                                                        <input type="text" name="contractors[{{ $loop->parent->index }}][tasks][{{ $j }}][price]" class="form-control price" value="{{ $task->price }}" placeholder="Enter price">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @php
                                                            $totalPrice = $taskGroup->sum(function($t) {
                                                                return floatval($t->price);
                                                            });
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-md-12 offset-md-12">
                                                                <div class="form-group">
                                                                    <label><strong>Total Price</strong></label>
                                                                    <input type="text" class="form-control" value="{{ number_format($totalPrice, 2) }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        
                                                    <!-- Add Task Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary add-task" data-contractor-index="{{ $loop->index }}">+ Add Task</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Add New Contractor -->
                                        <button type="button" class="btn btn-sm btn-primary" id="add-contractor">+ Add Contractor</button>
                                        
                                        <!-- Task Template -->
                                        <script type="text/template" id="task-template">
                                            <div class="task-row row mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description <span class="text-danger">*</span></label>
                                                        <textarea name="__TASK__[description]" class="form-control description" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contractor Comment</label>
                                                        <textarea name="__TASK__[contractor_comment]" class="form-control" rows="4"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Admin Upload</label>
                                                    <input type="file" name="__TASK__[admin_upload]" class="form-control-file">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Contractor Upload</label>
                                                    <input type="file" name="__TASK__[contractor_upload]" class="form-control-file">
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <div class="form-group">
                                                        <label>Date <span class="text-danger">*</span></label>
                                                        <input type="text" name="__TASK__[date]" class="form-control flatpickr" placeholder="DD/MM/YYYY" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <div class="form-group">
                                                        <label>Price</label>
                                                        <input type="text" name="__TASK__[price]" class="form-control price" placeholder="Enter price">
                                                    </div>
                                                </div>
                                            </div>
                                        </script>                                                                              

                                
                                        <div class="form-actions right">
                                            <a href="{{ route('admin.jobs') }}" class="theme-btn btn btn-primary">
                                                <i class="la la-times"></i> Cancel
                                            </a>
                                            <button type="submit" class="theme-btn btn btn-primary">
                                                <i class="la la-check-square-o"></i> Update
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
<script>
    $(function() {
        $('.select2').select2();
    })
</script>

<script>
     document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".flatpickr", {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    });

</script>
<script>
  $(function() {
    $('.select2').select2();

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
    let contractorIndex = {{ $job->jobDetail->groupBy('contractor_id')->count() }};
    
    document.getElementById('add-contractor').addEventListener('click', function () {
        const container = document.getElementById('contractor-rows');
        const block = document.createElement('div');
        block.classList.add('contractor-block', 'mb-4');
        block.dataset.index = contractorIndex;
    
        // Contractor Header
        const contractorRow = `
            <div class="row contractor-row mb-2">
                <div class="col-md-6">
                    <select name="contractors[${contractorIndex}][contractor_id]" class="form-control contractor-select">
                        <option value="">Select Contractor</option>
                        @foreach($contractors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <label class="mb-0 mr-2"><span class="text-danger">🏅</span> Won contract?</label>
                    <input type="radio" name="won_contract_global" value="${contractorIndex}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-contractor">X</button>
                </div>
            </div>
        `;
        block.innerHTML = contractorRow;
    
        // Tasks Wrapper
        const taskWrapper = document.createElement('div');
        taskWrapper.classList.add('task-rows');
        taskWrapper.dataset.contractorIndex = contractorIndex;
    
        const firstTaskHtml = document.getElementById('task-template').innerHTML
            .replace(/__TASK__/g, `contractors[${contractorIndex}][tasks][0]`);
    
        taskWrapper.insertAdjacentHTML('beforeend', firstTaskHtml);
        block.appendChild(taskWrapper);
    
        // Add Task button
        const addTaskBtn = document.createElement('button');
        addTaskBtn.type = 'button';
        addTaskBtn.className = 'btn btn-sm btn-outline-primary add-task';
        addTaskBtn.textContent = '+ Add Task';
        addTaskBtn.dataset.contractorIndex = contractorIndex;
        block.appendChild(addTaskBtn);
    
        container.appendChild(block);
    
        flatpickr(block.querySelector('.flatpickr'), {
            dateFormat: "d/m/Y",
            allowInput: true,
            defaultDate: new Date()
        });
    
        if ($.fn.select2) {
            $(block).find('select').select2();
        }
    
        contractorIndex++;
    });
    
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-contractor')) {
            const block = e.target.closest('.contractor-block');
            const radio = block.querySelector('input[type=radio]');
            if (radio && radio.checked) radio.checked = false;
            block.remove();
        }
    
        if (e.target.classList.contains('add-task')) {
            const contractorIndex = e.target.dataset.contractorIndex;
            const taskContainer = document.querySelector(`.task-rows[data-contractor-index="${contractorIndex}"]`);
            const taskCount = taskContainer.querySelectorAll('.task-row').length;
            const template = document.getElementById('task-template').innerHTML;
            const taskHtml = template.replace(/__TASK__/g, `contractors[${contractorIndex}][tasks][${taskCount}]`);
            taskContainer.insertAdjacentHTML('beforeend', taskHtml);
    
            flatpickr(taskContainer.querySelectorAll('.flatpickr')[taskCount], {
                dateFormat: "d/m/Y",
                allowInput: true,
                defaultDate: new Date()
            });
        }
    });
    </script>    

@endsection

