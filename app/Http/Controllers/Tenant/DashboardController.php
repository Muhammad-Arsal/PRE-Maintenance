<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request){

        $page['page_title'] = 'Dashboard';

        return  view('tenant.index', compact('page'));
    }
}

