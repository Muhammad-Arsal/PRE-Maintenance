@extends('admin.partials.main')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/charts/morris.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .card {
        border-radius: 15px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        height: 100%;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .icon-container {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
    }
    .row > div {
        display: flex;
    }
</style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3 d-flex">
                <div class="card text-center p-3 shadow-sm w-100">
                    <div class="icon-container bg-primary text-white">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Landlords</h5>
                        <p class="card-text">Active: <strong>{{$activeLandlords}}</strong></p>
                        <p class="card-text">Total: <strong>{{$totalLandlords}}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="card text-center p-3 shadow-sm w-100">
                    <div class="icon-container bg-success text-white">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Properties</h5>
                        <p class="card-text">Active: <strong>{{$activeProperties}}</strong></p>
                        <p class="card-text">Total: <strong>{{$totalProperties}}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="card text-center p-3 shadow-sm w-100">
                    <div class="icon-container bg-danger text-white">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Tenants</h5>
                        <p class="card-text">Active: <strong>{{$activeTenants}}</strong></p>
                        <p class="card-text">Total: <strong>{{$totalTenants}}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="card text-center p-3 shadow-sm w-100">
                    <div class="icon-container bg-warning text-white">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Contractors</h5>
                        <p class="card-text">Active: <strong>{{$activeContractors}}</strong></p>
                        <p class="card-text">Total: <strong>{{$totalContractors}}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white " style="background-color: #041E41;">
                <h5 class="mb-0 font-weight-bold" style="color: white; font-size: 20px;">Last Logged-In Admins</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Last Logged In</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lastLoggedInAdmins as $index => $admin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $admin->name }}</strong></td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->last_logged_in ? \Carbon\Carbon::parse($admin->last_logged_in)->format('d M Y, h:i A') : 'Never' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent logins</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
    
    
@endsection

@section('js')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
