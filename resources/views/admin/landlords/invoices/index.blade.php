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
                                        <a class="nav-link"
                                            href="{{route('admin.settings.landlord.bank', $landlord->id)}}">Bank Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active disabled" id="invoices" data-toggle="tab"
                                            aria-controls="invoices" href="#invoices" aria-expanded="true">Invoices</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.settings.landlords.jobs', $landlord->id) }}">Quotes</a>
                                    </li>
                                     <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin.settings.landlord.properties', $landlord->id) }}">Properties</a>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="text-white" style="background-color:  #041E41;">
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice ID</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Property</th>
                                                <th>Date</th>
                                                <th>Created</th>
                                                <th>Modified</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($invoices as $invoice)
                                            
                                                <tr>            
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a href="{{ route('admin.invoices.show', $invoice->id) }}">{{ $invoice->id }}</a></td>
                                                    <td>{{ $invoice->status }}</td>
                                                    <td>{{ $invoice->total }}</td>
                                                    <td>
                                                        @if(isset($invoice->property) && $invoice->property->id)
                                                            <a href="{{ route('admin.properties.edit', $invoice->property->id) }}">
                                                                {{ $invoice->property->line1 . ', ' . $invoice->property->city . ', ' . $invoice->property->county . ', ' . $invoice->property->postcode }}
                                                            </a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ date("d/m/Y", strtotime($invoice->date)) }}</td>
                                                    <td>{{date("d/m/Y", strtotime($invoice->created_at))}}</td>
                                                    <td>{{date("d/m/Y", strtotime($invoice->updated_at))}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No properties found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row justify-content-center pagination-wrapper mt-2">
                                    {!! $invoices->appends(request()->query())->links('pagination::bootstrap-4') !!}
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
