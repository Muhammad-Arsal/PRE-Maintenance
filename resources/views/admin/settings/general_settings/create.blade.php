@extends('admin.partials.main')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">Admin - General Settings</h3>
        </div>
    </div>

    <div class="content-body">
        @include('admin.partials.flashes')

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.settings.general.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>VAT Rate (%)</label>
                        <input type="text" name="vat_rate" class="form-control" 
                               value="{{ old('vat_rate', $setting->vat_rate ?? '') }}" >
                    </div>                                       
                    
                    <button type="submit" class="btn btn-primary basic-btn btn-min-width"><i class="la la-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    $(document).ready(function () {
        $("#vatForm").validate({
            rules: {
                vat_rate: {
                    required: true,
                    number: true,
                }
            },
            messages: {
                vat_rate: {
                    required: "Please enter a VAT rate.",
                    number: "Only numeric values are allowed.",
                }
            },
            errorPlacement: function (error, element) {
                error.appendTo("#vat_error");
            }
        });
    });
    </script>
@endsection