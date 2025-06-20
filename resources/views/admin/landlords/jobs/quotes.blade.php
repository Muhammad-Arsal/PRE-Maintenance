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
        <section id="search-landlords">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                               <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlords.edit', $landlord_id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.address', $landlord_id)}}">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.bank', $landlord_id)}}">Bank Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.settings.landlords.invoices', $landlord_id) }}">Invoices</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link active disabled" id="quotes" data-toggle="tab"
                                            aria-controls="quotes" href="#quotes" aria-expanded="true">Quotes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.properties', $landlord_id)}}">Properties</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.landlords.correspondence', $landlord_id)}}">Correspondence</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="">Remittances</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.landlords.calendar', $landlord->id) }}">Diary</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                @foreach ($quotes as $contractorId => $tasks)
                                    <div class="contractor-group mb-4">
                                        <!-- Contractor Name Input -->
                                        <div class="form-group mb-3">
                                            <label>Contractor Name</label>
                                            <input type="text" class="form-control" value="{{ $tasks->first()->contractor->name }}" readonly>
                                        </div>

                                        @foreach ($tasks as $item)
                                            <div class="task-row row mb-2">
                                                <!-- Description -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <textarea class="form-control description" rows="4" readonly>{{ $item->description }}</textarea>
                                                    </div>
                                                </div>

                                                <!-- Contractor Comment -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contractor Comment</label>
                                                        <textarea class="form-control contractor_comment" rows="4" readonly>{{ $item->contractor_comment }}</textarea>
                                                    </div>
                                                </div>

                                                <!-- Admin Upload -->
                                                <div class="col-md-6">
                                                    <label>Admin Upload</label>
                                                    @if (!empty($item->admin_upload))
                                                        <div class="mt-2">
                                                            <a href="{{ asset('storage/' . $item->admin_upload) }}" download>
                                                                <img src="{{ asset('storage/' . $item->admin_upload) }}" alt="Admin Upload" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;">
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div style="margin-top: 8px;">No image uploaded</div>
                                                    @endif
                                                </div>

                                                <!-- Contractor Upload -->
                                                <div class="col-md-6">
                                                    <label>Contractor Upload</label>
                                                    @if (!empty($item->contractor_upload))
                                                        <div class="mt-2">
                                                            <a href="{{ asset('storage/' . $item->contractor_upload) }}" download>
                                                                <img src="{{ asset('storage/' . $item->contractor_upload) }}" alt="Contractor Upload" style="max-height: 80px; border: 1px solid #ddd; padding: 2px;">
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div style="margin-top: 8px;">No image uploaded</div>
                                                    @endif
                                                </div>

                                                <!-- Date -->
                                                <div class="col-md-6 mt-2">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="text" class="form-control date" value="{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}" readonly>
                                                    </div>
                                                </div>

                                                <!-- Price -->
                                                <div class="col-md-6 mt-2">
                                                    <div class="form-group">
                                                        <label>Price</label>
                                                        <input type="text" class="form-control price" value="{{ $item->price }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- hr after each task -->
                                            <hr>
                                        @endforeach
                                    </div>
                                @endforeach


                    
                                <div class="form-actions right">
                                    <a href="{{route('admin.settings.landlords.jobs', $landlord_id)}}" class="theme-btn btn btn-primary">
                                        <i class="la la-times"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
@endsection
