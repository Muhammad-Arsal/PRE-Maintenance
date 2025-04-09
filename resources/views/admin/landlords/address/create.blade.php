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
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form method="post" enctype="multipart/form-data" id='managelandlord'  action="{{ route('admin.settings.landlords.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <h3 class="mb-2"><strong>Address</strong></h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_1">Address Line 1</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_1" class="form-control" name="address_line_1" value="{{ old('address_line_1', $property->address_line_1 ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map-marker"></i>
                                                        </div>
                                                    </div>
                                                    @error('address_line_1') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_2">Address Line 2</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_2" class="form-control"  name="address_line_2" value="{{ old('address_line_2', $property->address_line_2 ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map-marker"></i>
                                                        </div>
                                                    </div>
                                                    @error('address_line_2') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="address_line_3">Address Line 3</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="address_line_3" class="form-control" name="address_line_3" value="{{ old('address_line_3', $property->address_line_3 ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map-marker"></i>
                                                        </div>
                                                    </div>
                                                    @error('address_line_3') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>                                        
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="city" class="form-control"  name="city" value="{{ old('city', $property->city ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-building"></i>
                                                        </div>
                                                    </div>
                                                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="county">County</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="county" class="form-control"  name="county" value="{{ old('county', $property->county ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-map"></i>
                                                        </div>
                                                    </div>
                                                    @error('county') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="postal_code">Postal Code</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="postal_code" class="form-control"  name="postal_code" value="{{ old('postal_code', $property->postal_code ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-envelope"></i>
                                                        </div>
                                                    </div>
                                                    @error('postal_code') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="country">Country</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="country" class="form-control" 
                                                            name="country" value="{{ old('country', $property->country ?? '') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-globe"></i>
                                                        </div>
                                                    </div>
                                                    @error('country') 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>  
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="overseas_landlord">Overseas Landlord</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select id="overseas_landlord" name="overseas_landlord" class="form-control"
                                                            data-toggle="tooltip" data-trigger="hover" data-placement="top"
                                                            data-title="Is the landlord overseas?" title="">
                                                            <option value="">Select Option</option>
                                                            <option value="Yes" >Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-globe"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('overseas_landlord'))
                                                        <p class="text-danger">{{ $errors->first('overseas_landlord') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4" id="tax_exemption_code_wrapper" style="display: none;">
                                                <div class="form-group">
                                                    <label for="tax_exemption_code">Tax at Source Exemption Code</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="tax_exemption_code" class="form-control"
                                                            name="tax_exemption_code" value="{{ old('tax_exemption_code') }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-file-code-o"></i>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('tax_exemption_code'))
                                                        <p class="text-danger">{{ $errors->first('tax_exemption_code') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <textarea id="note" class="form-control" name="note" rows="4" >{{ old('note', $property->note ?? '') }}</textarea>
                                            @error('note') <span class="text-danger">{{ $message }}</span> @enderror
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



<script>
    $(document).ready(function () {
        function toggleTaxField() {
            const selected = $('#overseas_landlord').val();
            if (selected === 'Yes') {
                $('#tax_exemption_code_wrapper').show();
            } else {
                $('#tax_exemption_code_wrapper').hide();
                $('#tax_exemption_code').val('');
            }
        }

        // Initial load
        toggleTaxField();

        // On change
        $('#overseas_landlord').on('change', function () {
            toggleTaxField();
        });
    });
</script>


@endsection
