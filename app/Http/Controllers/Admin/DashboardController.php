<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Contractor;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\Admin;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request){

        $activeLandlords = Landlord::where('status', 'Active')->count();
        $totalLandlords = Landlord::count();

        $activeProperties = Property::where('status', 'Active')->count();
        $totalProperties = Property::count();

        $activeTenants = Tenant::where('status', 'Active')->count();
        $totalTenants = Tenant::count();

        $activeContractors = Contractor::where('status', 'Active')->count();
        $totalContractors = Contractor::count();

        $lastLoggedInAdmins = Admin::whereNotNull('last_logged_in')
                                ->orderBy('last_logged_in', 'desc')
                                ->take(5)
                                ->get();

        $page['page_title'] = 'Dashboard';

        return  view('admin.index', compact('lastLoggedInAdmins', 'page', 'activeTenants', 'totalTenants', 'activeLandlords', 'totalLandlords', 'activeContractors', 'totalContractors', 'activeProperties', 'totalProperties'));
    }
}

