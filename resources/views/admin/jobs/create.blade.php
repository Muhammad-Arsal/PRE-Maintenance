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
        <section id="search-contractors">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="POST" enctype="multipart/form-data" id='manageJobs'  action="{{route('admin.jobs.store')}}">
                                    @csrf
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Job Details</strong></h3>
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status"><span style="color: red;">*</span>Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="">Select Status</option>
                                                            <option value="Quote required" {{ old('status', $job->status ?? '') == 'Quote required' ? 'selected' : '' }}>Quote required</option>
                                                            <option value="1 quote received" {{ old('status', $job->status ?? '') == '1 quote received' ? 'selected' : '' }}>1 quote received</option>
                                                            <option value="2 quotes received" {{ old('status', $job->status ?? '') == '2 quotes received' ? 'selected' : '' }}>2 quotes received</option>
                                                            <option value="3 quotes received" {{ old('status', $job->status ?? '') == '3 quotes received' ? 'selected' : '' }}>3 quotes received</option>
                                                            <option value="Additional quote requested" {{ old('status', $job->status ?? '') == 'Additional quote requested' ? 'selected' : '' }}>Additional quote requested</option>
                                                            <option value="Quotes sent to landlord" {{ old('status', $job->status ?? '') == 'Quotes sent to landlord' ? 'selected' : '' }}>Quotes sent to landlord</option>
                                                            <option value="Contractor completed" {{ old('status', $job->status ?? '') == 'Contractor completed' ? 'selected' : '' }}>Contractor completed</option>
                                                            <option value="Contractor Invoiced" {{ old('status', $job->status ?? '') == 'Contractor Invoiced' ? 'selected' : '' }}>Contractor Invoiced</option>
                                                            <option value="Closed - completed & paid" {{ old('status', $job->status ?? '') == 'Closed - completed & paid' ? 'selected' : '' }}>Closed - completed & paid</option>
                                                            <option value="Closed - no response from tenant" {{ old('status', $job->status ?? '') == 'Closed - no response from tenant' ? 'selected' : '' }}>Closed - no response from tenant</option>
                                                            <option value="Closed - other" {{ old('status', $job->status ?? '') == 'Closed - other' ? 'selected' : '' }}>Closed - other</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-list"></i>
                                                        </div>
                                                    </div>
                                                    @error('status') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>                                               
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="property"><span style="color: red;">*</span>Property</label>
                                                    <select id="property" name="property_id" class="form-control select2">
                                                        <option value="">Select Property</option>
                                                        @foreach($properties as $item)
                                                            <option value="{{ $item->id }}" {{ old('property_id', $property_id) == $item->id ? 'selected' : '' }}>
                                                                {{ $item->line1 . ', ' . $item->city . ', ' . $item->county . ', ' . $item->postcode }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('property_id') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>                                              
                                            </div> 
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="priority"><span style="color: red;">*</span>Priority</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="priority" name="priority" class="form-control">
                                                            <option value="">Select Priority</option>
                                            
                                                            <option value="Risk to Life/ Property - 4 hours"
                                                                {{ old('priority', $job->priority ?? '') == 'Risk to Life/ Property - 4 hours' ? 'selected' : '' }}>
                                                                Risk to Life/ Property – 4 hours
                                                            </option>
                                            
                                                            <option value="Emergency - 24 Hours"
                                                                {{ old('priority', $job->priority ?? '') == 'Emergency - 24 Hours' ? 'selected' : '' }}>
                                                                Emergency – 24 Hours
                                                            </option>
                                            
                                                            <option value="Urgent - 5 days"
                                                                {{ old('priority', $job->priority ?? '') == 'Urgent - 5 days' ? 'selected' : '' }}>
                                                                Urgent – 5 days
                                                            </option>
                                            
                                                            <option value="Routine - 28 days"
                                                                {{ old('priority', $job->priority ?? '') == 'Routine - 28 days' ? 'selected' : '' }}>
                                                                Routine – 28 days
                                                            </option>
                                                        </select>
                                            
                                                        <div class="form-control-position">
                                                            <i class="la la-exclamation-triangle"></i>
                                                        </div>
                                                    </div>
                                            
                                                    @error('priority')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>                                            
                                        </div>

                                        <div id="contractor-rows">
                                            {{-- First empty row --}}
                                            <div class="contractor-row d-flex mb-2">
                                                <div class="col-md-6 p-0">
                                                    <label for="contractor_0">Contractor:</label>
                                                    <select name="contractors[0][contractor_id]" class="form-control contractor-select select2">
                                                        <option value="">Select Contractor</option>
                                                        @foreach($contractors as $contractor)
                                                            <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>                                             
                                            </div>
                                        </div>
                                        
                                        {{-- Add Button --}}
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-sm btn-primary" id="add-contractor">+ Add Contractor</button>
                                        </div>    

                                        <div id="task-container">
                                            <div class="task-row row mb-2">
                                                <!-- Description -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description <span class="text-danger">*</span></label>
                                                        <textarea name="description[0]" class="form-control description" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                        
                                                <!-- Contractor Comment -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contractor Comment</label>
                                                        <textarea name="contractor_comment[0]" class="form-control contractor_comment" rows="4"></textarea>
                                                    </div>
                                                </div>
                                        
                                                <!-- Admin Upload -->
                                                <div class="col-md-6">
                                                    <label>Admin Upload</label>
                                                    <input type="file" name="admin_upload[0]" class="form-control-file admin_upload">
                                                </div>
                                        
                                                <!-- Contractor Upload -->
                                                <div class="col-md-6">
                                                    <label>Contractor Upload</label>
                                                    <input type="file" name="contractor_upload[0]" class="form-control-file contractor_upload">
                                                </div>
                                        
                                                <!-- Date -->
                                                <div class="col-md-6 mt-2">
                                                    <div class="form-group">
                                                        <label>Date <span class="text-danger">*</span></label>
                                                        <input type="text" name="date[0]" class="form-control flatpickr date" placeholder="DD/MM/YYYY" required>
                                                    </div>
                                                </div>
                                        
                                                <!-- Price -->
                                                <div class="col-md-6 mt-2">
                                                    <div class="form-group">
                                                        <label>Price</label>
                                                        <input type="text" name="price[0]" class="form-control price" placeholder="Enter price">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Add Button -->
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-task">+ Add</button>
                                                                                                                      
                                        
                                        
                                        
                                        <div class="form-group mt-2">
                                            <label for="other_information">Other Information</label>
                                            <textarea id="other_information" name="other_information" class="form-control" rows="4"></textarea>
                                        </div>
                                        

                                        <div class="form-actions right">
                                            <a href="" class="theme-btn btn btn-primary">
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
<script src="{{ asset('/dashboard/vendors/js/forms/select/select2.js') }}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(function() {
        $('.select2').select2();
    })
</script>

<script>
    $(document).ready(function () {
        $("#manageJobs").validate({
            rules: {
                status: {
                    required: true
                },
                priority: {
                    required: true
                },
                property_id: {
                    required: true
                },
                description: {
                    required: true
                }
            },
            messages: {
                status: {
                    required: "Please select a status."
                },
                priority: {
                    required: "Please select a priority."
                },
                property_id: {
                    required: "Please select a property."
                },
                description: {
                    required: "Please enter a description."
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger"); // Add Bootstrap error styling
                if (element.parent(".position-relative").length) {
                    error.insertAfter(element.parent()); // For icon inputs
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>



<script>
    let contractorIndex = 1;

    document.getElementById('add-contractor').addEventListener('click', function () {
        const container = document.getElementById('contractor-rows');

        const newRow = document.createElement('div');
        newRow.classList.add('contractor-row', 'd-flex', 'mb-2');

        newRow.innerHTML = `
            <div class="col-md-6 p-0">
                <select name="contractors[${contractorIndex}][contractor_id]" class="form-control contractor-select select2">
                    <option value="">Select Contractor</option>
                    @foreach($contractors as $contractor)
                        <option value="{{ $contractor->id }}">{{ $contractor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end" style= "top:-7px">
                <button  type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </div>
        `;

        container.appendChild(newRow);

        if ($.fn.select2) {
            $(newRow).find('select').select2();
        }

        contractorIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            const row = e.target.closest('.contractor-row');
            const radio = row.querySelector('.won-contract-radio');
            if (radio && radio.checked) {
                radio.checked = false;
            }
            row.remove();
        }
    });

    
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date", {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initFlatpickr();
    
        // jQuery form validation init (assuming form exists)
        $('form').validate();
    
        $('#add-task').on('click', function () {
            const $original = $('.task-row:first');
            const $clone = $original.clone();
    
            // Clear values
            $clone.find('input, textarea').each(function () {
                if (this.type === 'file') {
                    $(this).val('');
                } else {
                    $(this).val('');
                }
            });
    
            // Add remove button if not already present
            if ($clone.find('.remove-btn').length === 0) {
                $clone.prepend(`
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-btn" onclick="removeRow(this)">✕</button>
                    </div>
                `);
            }
    
            $('#task-container').append($clone);
            updateRowIndexes();
    
            // Apply flatpickr to new date input and auto-fill current date
            $clone.find('.flatpickr').each(function () {
                flatpickr(this, {
                    dateFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: new Date() // auto-fill current date
                });
            });
    
            $('form').validate();
        });
    });
    
    // Flatpickr init for initial page load
    function initFlatpickr() {
        document.querySelectorAll(".flatpickr").forEach(function (el) {
            flatpickr(el, {
                dateFormat: "d/m/Y",
                allowInput: true,
                defaultDate: new Date() // today's date
            });
        });
    }
    
    // Remove row and reindex
    function removeRow(btn) {
        $(btn).closest('.task-row').remove();
        updateRowIndexes();
    }
    
    // Update names to match array indexes
    function updateRowIndexes() {
        $('#task-container .task-row').each(function (index) {
            $(this).find('input, textarea').each(function () {
                const base = $(this).attr('name')?.replace(/\[\d+\]/, '');
                if (base) {
                    $(this).attr('name', `${base}[${index}]`);
                }
            });
        });
    }
    </script>
    
    
@endsection
