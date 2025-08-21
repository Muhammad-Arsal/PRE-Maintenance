<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Logs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Logs';

        $logs = ActivityLog::orderBy('performed_at', 'desc')->paginate(10);

        return view('admin.logs.index', compact('page', 'logs'));
    }
}
