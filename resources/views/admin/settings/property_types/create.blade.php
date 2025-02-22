@extends('admin.partials.main')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12">
            <h3 class="content-header-title">Add Property Type</h3>
        </div>
    </div>

    <div class="content-body">
        @include('admin.partials.flashes')

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.settings.propertyType.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Property Type Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary basic-btn btn-min-width"><i class="la la-save"></i> Save</button>
                    <a href="{{ route('admin.settings.propertyType') }}" class="btn btn-secondary btn-min-width">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
