@extends('contractor.partials.main')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/charts/morris.css') }}" />

@endsection

@section('content')
<div class="content-header row"></div>
<div class="content-body">
    <div class="card">
        <div class="card-body">
            <div class="row">
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
@if(Session::has('success'))
    toastr.options = {
        "closeButton": true,
        "progressBar": true
    };
    toastr.success("{{ session('success') }}");
@endif
</script>
@endsection
