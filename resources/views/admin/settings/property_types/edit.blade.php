@extends('admin.partials.main')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12">
            <h3 class="content-header-title">Edit Property Type</h3>
        </div>
    </div>

    <div class="content-body">
        @include('admin.partials.flashes')

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.settings.propertyType.update',$id->id)}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Property Type Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $id->name }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="la la-save"></i> Update</button>
                    <a href="{{ route('admin.settings.propertyType') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
