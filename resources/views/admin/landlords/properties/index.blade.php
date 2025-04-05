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
                                        <a class="nav-link active disabled" id="bankDetails" data-toggle="tab"
                                            aria-controls="bankDetails" href="#bankDetails" aria-expanded="true">Properties</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{route('admin.landlords.correspondence', $landlord->id)}}">Correspondence</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="text-white" style="background-color:  #041E41;">
                                            <tr>
                                                <th>Property ID</th>
                                                <th>Address</th>
                                                <th>Type</th>
                                                <th>Tenant</th>
                                                <th>Created</th>
                                                <th>Modified</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($properties as $property)
                                            <tr>
                                                <td>{{$property->id}}</td>
                                                <td><a href="{{route('admin.properties.edit',$property->id)}}">{{$property->line1 . ', ' . $property->city . ', ' . $property->county . ', ' . $property->postcode}}</a></td>
                                                <td>{{$property->type}}</td>
                                                <td>
                                                    @if(isset($property->tenant) && $property->tenant->id)
                                                        <a href="{{ route('admin.settings.tenants.edit', $property->tenant->id) }}">
                                                            {{ $property->tenant->name }}
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>                        
                                                <td>{{date("d/m/Y", strtotime($property->created_at))}}</td>
                                                <td>{{date("d/m/Y", strtotime($property->updated_at))}}</td>
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
                                    {!! $properties->appends(request()->query())->links('pagination::bootstrap-4') !!}
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
<script type="text/javascript">
    $(function() {
        var validate = $('#managelandlord').validate({
            rules: {
                address_line_1: {
                    required: true,
                },
                city: {
                    required: true,
                },
                county: {   required: true  },
                postal_code: {  required: true  },
                country: {
                    required: true,
                },
            },
            messages: {
                address_line_1: 'The Address Line 1 field is required',
                city: 'The City field is required',
                county: 'The County field is required',
                postal_code: 'The Postal Code field is required',
                country: 'The Country field is required',
            }
        });

        $('input').on('focusout keyup', function() {
            $(this).valid();
        });
    });
</script>
@endsection
