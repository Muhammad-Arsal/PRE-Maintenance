@extends('admin.partials.main')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12">
            <h3 class="content-header-title">Edit Contractor Type</h3>
        </div>
    </div>

    <div class="content-body">
        @include('admin.partials.flashes')

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.settings.contractorType.update',$id->id)}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Contractor Type Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $id->name }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="la la-save"></i> Update</button>
                    <a href="{{ route('admin.settings.contractorType') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
