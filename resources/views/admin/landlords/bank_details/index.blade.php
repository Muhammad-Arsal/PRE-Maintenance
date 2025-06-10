@extends('admin.partials.main')

@section('css')
<style>
    label {
        font-weight: bold;
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
                            <div class="card-header">
                                <ul class="nav nav-tabs nav-underline no-hover-bg">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlords.edit', $landlord->id)}}">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.address', $landlord->id)}}">Address</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="bankDetails" data-toggle="tab"
                                            aria-controls="bankDetails" href="#bankDetails" aria-expanded="true">Bank Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.settings.landlords.invoices', $landlord->id) }}">Invoices</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.settings.landlords.jobs', $landlord->id) }}">Quotes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.properties', $landlord->id)}}">Properties</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.landlords.correspondence', $landlord->id)}}">Correspondence</a>
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
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='managelandlord' action="{{ route('admin.settings.landlord.bank.store', $landlord->id) }}">
                                    @csrf
                                    @php $name = explode(" ", $landlord->name); @endphp
                                    <div class="form-body">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="account_number">Account Number</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="account_number" class="form-control" name="account_number"
                                                            value="{{ old('account_number', $landlord->account_number ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-credit-card"></i>
                                                        </div>
                                                    </div>
                                                    @error('account_number') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="sort_code">Sort Code</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="sort_code" class="form-control" name="sort_code"
                                                            value="{{ old('sort_code', $landlord->sort_code ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-exchange"></i>
                                                        </div>
                                                    </div>
                                                    @error('sort_code') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="account_name">Name of Account</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="account_name" class="form-control" name="account_name"
                                                            value="{{ old('account_name', $landlord->account_name ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-user"></i>
                                                        </div>
                                                    </div>
                                                    @error('account_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="bank">Bank</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="bank" class="form-control" name="bank"
                                                            value="{{ old('bank', $landlord->bank ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-bank"></i>
                                                        </div>
                                                    </div>
                                                    @error('bank') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>                                          
                                        </div>
                                        <div class="form-group">
                                            <label for="bank_address">Address</label>
                                            <textarea id="bank_address" class="form-control" name="bank_address" rows="3">{{ old('bank_address', $landlord->bank_address ?? '') }}</textarea>
                                            @error('bank_address') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>                                        

                                        <div class="form-actions right">
                                            <a href="{{ route('admin.settings.landlords') }}" class="theme-btn btn btn-primary">
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
@endsection
