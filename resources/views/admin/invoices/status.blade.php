@extends('admin.partials.main')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
        <section id="search-landlords">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='manageInvoices'  action="{{route('admin.invoices.update.status', $invoice->id)}}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="status"><span style="color: red;">*</span>Status</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="status" class="form-control" name="status">
                                                            <option value="">Select Status</option>
                                                            <option value="paid" {{ old('status', $invoice->status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                            <option value="unpaid" {{ old('status', $invoice->status ?? '') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                                            <option value="overdue" {{ old('status', $invoice->status ?? '') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                                            <option value="cancel" {{ old('status', $invoice->status ?? '') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-check-circle"></i>
                                                        </div>
                                                    </div>
                                                    @error('status') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>                                                                                        
                                        </div>
                                        <div class="form-actions right">
                                            <a href="{{route('admin.invoices')}}" class="theme-btn btn btn-primary">
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
