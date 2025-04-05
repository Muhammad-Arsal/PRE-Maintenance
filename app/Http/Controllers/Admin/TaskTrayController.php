<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskTray;
use Illuminate\Http\Request;

class TaskTrayController extends Controller
{
    public function index() {
        $page['page_title'] = 'Manage Task Tray';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Task Tray';

        $taskTray = TaskTray::withTrashed()->orderBy('id', 'desc')->paginate(10);

        return view('admin.settings.taskTray.index', compact('taskTray', 'page'));
    }

    public function create() {
        $page['page_title'] = 'Create Task Tray';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Create Task Tray';

        return view('admin.settings.taskTray.add', compact('page'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);

        $taskTray = TaskTray::create([
            'name' => $request->name
        ]);

        if($taskTray) {
            return redirect()
            ->route( 'admin.settings.taskTray' )
            ->withFlashMessage( 'Task Tray created successfully!' )
            ->withFlashType( 'success' );
        } else {
            return redirect()
            ->route( 'admin.settings.taskTray' )
            ->withFlashMessage( 'Oops! Something went wrong' )
            ->withFlashType( 'error' );
        }
    }

    public function edit($id) {
        $page['page_title'] = 'Edit Task Tray';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Task Tray';

        $taskTray = TaskTray::where('id', $id)->withTrashed()->first();

        return view('admin.settings.taskTray.edit', compact('page', 'taskTray'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
        ]);

        $taskTray = TaskTray::where('id', $id)->withTrashed()->update([
            'name' => $request->name
        ]);


        if($taskTray) {
            return redirect()
            ->route( 'admin.settings.taskTray' )
            ->withFlashMessage( 'Task Tray updated successfully!' )
            ->withFlashType( 'success' );
        } else {
            return redirect()
            ->route( 'admin.settings.taskTray' )
            ->withFlashMessage( 'Oops! Something went wrong' )
            ->withFlashType( 'error' );
        }
    }

    public function delete($id) {
        $task_tray = TaskTray::withTrashed()->find($id);
        if($task_tray->trashed()) {
            $task_tray->restore();
        } else {
            $task_tray->delete();
        }

        if($task_tray) {
            return redirect()
            ->route( 'admin.settings.taskTray' )
            ->withFlashMessage( 'Task Tray deleted successfully!' )
            ->withFlashType( 'success' );
        } else {
            return redirect()
            ->route( 'admin.settings.taskTray' )
            ->withFlashMessage( 'Oops! Something went wrong' )
            ->withFlashType( 'error' );
        }
    }
}