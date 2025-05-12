@extends('tenant.partials.main')

@section('css')
@endsection

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}" class="theme-color">Home</a></li>
                        <li class="breadcrumb-item active">View tenant</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="container-fluid p-0 m-0">        
            <div class="card">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Details</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold ">Property:</th>
                            <td class="">{{$invoice->property->line1 . ', ' . $invoice->property->city . ', ' . $invoice->property->county . ', ' . $invoice->property->postcode}}</td>
                            <th class="font-weight-bold ">Date:</th>
                            <td class="">{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Reference:</th>
                            <td class="">{{$invoice->reference ?? ''}}</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
                       
            
            <div class="card mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Amount</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold ">Sub Total:</th>
                            <td class="">{{"£ " . $invoice->subtotal}}</td>
                            <th class="font-weight-bold ">VAT:</th>
                            <td class="">{{"£ " .$invoice->vat }}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">Total:</th>
                            <td class="">{{ "£ " . $invoice->total}}</td>
                            <th class="font-weight-bold ">VAT Applicable:</th>
                            <td class="">{{$invoice->vat_applicable == 'yes' ? 'Yes' : 'no'}}</td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold ">VAT Rate:</th>
                            <td class="">{{$invoice->vat_rate . "%"}}</td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            
            <div class="card mb-3">
                <div class="card-header text-white" style="background-color: #041E41;">
                    <strong>Address</strong>
                </div>
                <table class="table table-bordered text-muted mb-0">
                    <tbody>
                        <tr>
                            <th class="font-weight-bold">Line 1:</th>
                            <td class="">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->line1 ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->line1 ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->line1 ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                            <th class="font-weight-bold">Line 2:</th>
                            <td class="">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->line2 ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->line2 ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->line2 ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold">Line 3:</th>
                            <td class="">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->line3 ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->line3 ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->line3 ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                            <th class="font-weight-bold">City:</th>
                            <td class="">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->city ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->city ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->city ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold">County:</th>
                            <td class="">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->county ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->county ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->county ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                            <th class="font-weight-bold">Post code:</th>
                            <td class="">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->postcode ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->postcode ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->postcode ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="font-weight-bold">Country:</th>
                            <td class="" colspan="3">
                                @if ($invoice->address_option == "landlord")
                                    {{ $invoice->property->landlord->country ?? 'Not set' }}
                                @elseif ($invoice->address_option == "property")
                                    {{ $invoice->property->country ?? 'Not set' }}
                                @elseif ($invoice->address_option == "entered")
                                    {{ $invoice->country ?? 'Not set' }}
                                @else
                                    Not set
                                @endif
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div> 
            <div class="form-actions right">
                <a href="{{route('landlord.invoices')}}" class="theme-btn btn btn-primary">
                    <i class="la la-times"></i> Back
                </a>
            </div>
                                   
        </div>        
    </div>
@endsection
