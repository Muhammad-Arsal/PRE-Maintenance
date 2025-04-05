<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Consumer;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Supplier;
use App\Models\Tasks;
use App\Models\Landlord;
use App\Models\TaskTray;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Models\GeneralCorrespondenceCall;

class TasksController extends Controller
{
    protected $limit;

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $page['page_title'] = 'Manage Tasks';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Manage Tasks';
        

        $start_date = $request->input('start_date', null);
        $end_date = $request->input('end_date', null);
        $platform_user = $request->input('platform_users', '');
        $status = $request->input('status', '');
        $task_tray = $request->input('task_tray', '');
        $priority = $request->input('priority', '');
        $sort_by_due_date = $request->input('sort_by_due_date', 'newest_to_oldest');

        $users = Admin::orderBy('name')->get();
        $taskTrays = TaskTray::orderBy('name', 'asc')->get();
        // $tasks = Tasks::orderBy('due_date', 'desc')->where('status', 'not_completed')->paginate($this->limit);

        $query = Tasks::query();

        if($start_date != null || $end_date != null) {
            $start_date = $start_date != null ? Carbon::createFromFormat("d/m/Y", $start_date)->format('Y-m-d H:i:s') : null;
            $end_date = $end_date != null ? Carbon::createFromFormat("d/m/Y", $end_date)->format('Y-m-d H:i:s') : null;

            $query->whereBetween('due_date', [$start_date, $end_date]);
        }

        if($status) {
            if($status != 'all') {
                $query->where('status', $status);
            }
        } else {
            $query->where('status', 'not_completed');
        }

        if($priority) {
            $query->where('priority', $priority);
        }

        if($platform_user && $platform_user != 'all') {
            $query->where('platform_user', $platform_user);
        }

        if ($task_tray) {
            $query->join('task_tray', 'tasks.task_tray_id', '=', 'task_tray.id')
                ->where('task_tray.id', $task_tray) // Ensure we're comparing with the given task_tray ID
                ->select('tasks.*')
                ->orderBy('task_tray.name', 'asc');
        }

        if($sort_by_due_date == 'newest_to_oldest') {
            $query->orderBy('due_date', 'desc');
        } else {
            $query->orderBy('due_date', 'asc');
        }

        $tasks = $query->select('tasks.*')->paginate(10);

        return view('admin.tasks.index', compact('users', 'page', 'tasks', 'taskTrays', 'start_date', 'end_date', 'platform_user', 'status', 'task_tray', 'priority', 'sort_by_due_date'));

    }

    public function create()
    {

        $page['page_title'] = 'Manage Tasks';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Task';

        $platform_users = Admin::orderBy('name', 'desc')->get();
        $taskTray = TaskTray::orderBy('name', 'asc')->get();
        $landlords = Landlord::orderBy('name', 'asc')->get();

        return view('admin.tasks.create', compact('page', 'platform_users', 'taskTray', 'landlords'));
    }

    public function store(Tasks $task, Request $request)
    {

        $request->validate([
            'description' => 'required',
            // 'task_type' => 'required',
            // 'users' => 'required',
            'due_date' => 'required',
            'platform_users' => 'required',
            'status' => "required",
        ]);
        $data = $request->except('_token');

        if($data['due_date']) {
            $due_date = Carbon::createFromFormat("d/m/Y", $data['due_date'])
            ->format('Y-m-d H:i:s');
        }

        $task->description = $data['description'];
        $task->due_date = $due_date;
        $task->priority = $data['priority'];
        $task->platform_user = $data['platform_users'];
        $task->admin_id = auth()->guard('admin')->user()->id;
        $task->status = $data['status'];
        $task->notes = $data['notes'];
        $task->task_tray_id = $data['task_tray'];
        // $task->provider_id = $data['provider'];
        $task->contact_id = $data['contact'];
        if($data['status'] == 'completed') {
            $task->completed_date = Carbon::now();
        }

        if($request->is_critical) {
            $task->is_critical = "1";
        }

        if ($task->save()) {

            // if($request->is_critical) {
            //     $matters = array_filter($data['matters']);
            //     \DB::table('calendar_task_events')->insert([
            //         'matter_id' => $matters['0'],
            //         // 'task_id' => $data['task'],
            //         'matter_task_id' => $task->id,
            //         'due_date' => $due_date,
            //         'created_at' => Carbon::now(),
            //         'updated_at' => Carbon::now(),
            //     ]);
            // }

            //if reminders it not empty save the reminders
            if ($request->has('reminders') && count($data['reminders']) > 0) {
                $reminderData = [];


                foreach ($data['reminders'] as $reminder) {

                    list($date, $time, $name, $description) = explode('+', $reminder);

                    $date = Carbon::createFromFormat("d/m/Y", $date)
                    ->format('Y-m-d H:i:s');

                    $reminderData['task_id'] = $task->id;
                    $reminderData['name'] = $name;
                    $reminderData['description'] = $description;
                    $reminderData['date'] = Carbon::parse($date)->toDateString();
                    $reminderData['time'] = $time;
                    $reminderData['created_at'] = Carbon::now();
                    $reminderData['updated_at'] = Carbon::now();
                    $task->reminders()->create($reminderData);

                    $platform_user = Admin::find($data['platform_users']);
                    if($platform_user) {
                        if($platform_user->email) {
                            $template =  EmailTemplate::where('status','1')->where('type','Task Reminder')->first();
                            $data = array(
                                'user_name' => $platform_user->name,
                                'name' => $name,
                                'date' => $reminderData['date'] . ' ' . $time,
                                'description' => $description,
                                'template' => $template->content,
                            );

                            \Mail::to($platform_user->email)->send(new \App\Mail\TaskReminderMail($data));


                        }
                    }
                }
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePaths = [];
            
                if (!empty($file)) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $directory = public_path('uploads/admin/tasks/');
        
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }
        
                    $fileName = base64_encode('file-' . time() . rand()) . '.' . $ext;
                    $originalName = $file->getClientOriginalName();
                    $file->move($directory, $fileName);
        
                    $filePaths[] = [
                        'original_name' => $originalName, 
                        'path' => 'uploads/admin/tasks/' . $fileName,
                        'uploaded_at' => now()->format('Y-m-d H:i:s'),
                    ];
                }
            
                if (!empty($filePaths)) {
                    // Retrieve existing files and decode them into an array
                    $existingFiles = json_decode($task->files, true) ?? [];
            
                    // Merge new files with existing files
                    $updatedFiles = array_merge($existingFiles, $filePaths);

                    // Update the task with the new file list
                    $task->update([
                        'file' => json_encode($updatedFiles)
                    ]);
                }
            }            
        }

        return redirect()
            ->route('admin.tasks')
            ->withFlashMessage('Task added successfully!')
            ->withFlashType('success');
    }

    public function edit(Request $request, $id)
    {
        $task = Tasks::where('id', $id)->with('reminders')->first();

        $page['page_title'] = 'Manage Tasks';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Task | ' . $task->name;

        $platform_users = Admin::orderBy('name', 'desc')->get();
        $taskTray = TaskTray::orderBy('name', 'asc')->get();
        $landlords = Landlord::orderBy('name', 'asc')->get();

        return view('admin.tasks.edit', compact('page', 'task', 'platform_users', 'taskTray','landlords'));
    }

    public function update(Tasks $id, Request $request)
    {
        //  / dd($request->all());
        $task = $id;
        $request->validate([
            'description' => 'required',
            'due_date' => 'required',
            'platform_users' => 'required',
            'status' => "required",
        ]);
        $data = $request->except('_token');


        if($data['due_date']) {
            $due_date = Carbon::createFromFormat("d/m/Y", $data['due_date'])
            ->format('Y-m-d H:i:s');
        }

        $task->description = $data['description'];
        $task->due_date = $due_date;
        $task->priority = $data['priority'];
        $task->admin_id = auth()->guard('admin')->user()->id;
        $task->platform_user = $data['platform_users'];
        $task->status = $data['status'];
        $task->task_tray_id = $data['task_tray'];
        $task->notes = $data['notes'];
        $task->contact_id = $data['contact'];
        if($data['status'] == 'completed') {
            $task->completed_date = Carbon::now();
        }

        if($request->is_critical) {
            $task->is_critical = "1";
        } else {
            $task->is_critical = "0";
        }

        if ($task->save()) {

            // \DB::table('calendar_task_events')->where('matter_task_id', $task->id)->delete();
            // if($request->is_critical) {
            //     $matters = array_filter($data['matters']);
            //     \DB::table('calendar_task_events')->insert([
            //         'matter_id' => $matters['0'],
            //         // 'task_id' => $data['task'],
            //         'matter_task_id' => $task->id,
            //         'due_date' => $due_date,
            //         'created_at' => Carbon::now(),
            //         'updated_at' => Carbon::now(),
            //     ]);
            // }

            //if reminders it not empty save the reminders
            if ($request->has('reminders') && count($data['reminders']) > 0) {
                $reminderData = [];

                

                $task->reminders()->delete();


                foreach ($data['reminders'] as $reminder) {
                    list($date, $time, $name, $description, $checkOldReminder) = explode('+', $reminder);

                    $formats = [
                        'd/m/Y',
                        'Y-m-d',
                        // Add more formats if needed
                    ];
                    
                    foreach ($formats as $format) {
                        if (Carbon::hasFormat($date, $format)) {
                            $date = Carbon::createFromFormat($format, $date)->format('Y-m-d H:i:s');
                            break;
                        }
                    }

                    $reminderData['task_id'] = $task->id;
                    $reminderData['name'] = $name;
                    $reminderData['description'] = $description;
                    $reminderData['date'] = Carbon::parse($date)->toDateString();
                    $reminderData['time'] = $time;
                    $reminderData['created_at'] = Carbon::now();
                    $reminderData['updated_at'] = Carbon::now();
                    $task->reminders()->create($reminderData);

                    if($checkOldReminder != 'old_reminder') {
                        $platform_user = Admin::find($data['platform_users']);
                        if($platform_user) {
                            if($platform_user->email) {
                                $template =  EmailTemplate::where('status','1')->where('type','Task Reminder')->first();
                                $data = array(
                                    'user_name' => $platform_user->name,
                                    'name' => $name,
                                    'date' => $reminderData['date'] . ' ' . $time,
                                    'description' => $description,
                                    'template' => $template->content,
                                );

                                \Mail::to($platform_user->email)->send(new \App\Mail\TaskReminderMail($data));


                            }
                        }
                    }
                }
            }


            if ($request->hasFile('file')) {
                $task = Tasks::find($id->id);
            
                if ($task) {
                    $existingFiles = json_decode($task->file, true) ?? [];
            
                    $files = $request->file('file');
                    $filePaths = [];
            
                    foreach ($files as $file) {
                        if (!empty($file)) {
                            $ext = strtolower($file->getClientOriginalExtension());
                            $directory = public_path('uploads/admin/tasks/');
                            if (!file_exists($directory)) {
                                mkdir($directory, 0777, true);
                            }
            
                            $fileName = base64_encode('file-' . time() . rand()) . '.' . $ext;
                            $originalName = $file->getClientOriginalName();
                            $file->move($directory, $fileName);
            
                            $filePaths[] = [
                                'original_name' => $originalName, 
                                'path' => 'uploads/admin/tasks/' . $fileName,
                                'uploaded_at' => now()->format('Y-m-d H:i:s'),
                            ];
                        }
                    }
            
                    if (!empty($filePaths)) {
                        $updatedFiles = array_merge($existingFiles, $filePaths);
            
                        $task->update([
                            'file' => json_encode($updatedFiles)
                        ]);
                    }
                }
            }            
        }
        return redirect()
            ->route('admin.tasks')
            ->withFlashMessage('Task updated successfully!')
            ->withFlashType('success');


    }

    public function destroy(Tasks $id)
    {
        $task = $id;

        // Check if there are any attached files
        if (!empty($task->file)) {
            $files = json_decode($task->file, true) ?? [];

            foreach ($files as $file) {
                $filePath = public_path('uploads/admin/tasks/' . $file['path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        $task->reminders()->delete();
        
        GeneralCorrespondenceCall::where('task_id', $task->id)->delete();
        $task->delete();

        return back()
            ->withFlashMessage('Task deleted successfully!')
            ->withFlashType('success');
    }

    public function exportCSV() {
        $file_name = 'tasks'.date('Y_m_d_H_i_s').'.csv'; 
        $records =  Tasks::get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(        
            'Task',
            'Platform User',
            'Due Date',
            'Task Status',
            'Date Completed',
            'Notes',
        );

        $callback = function() use($records, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($records as $r) {
                $row['Task']  = $r->description;
                $platform_user = \DB::table('admins')->where('id', $r->platform_user)->first();
                $row['Platform User'] = $platform_user->name;
                $row['Due Date'] = date('d/m/Y H:i', strtotime($r->due_date));
                $row['Task Status'] = $r->status == 'completed' ? 'Completed' : 'Not Completed';
                $row['Date Completed']  = isset($r->completed_date) ? date('d/m/Y H:i', strtotime($r->completed_date)) : '--';
                $row['Notes'] = $r->notes;

                fputcsv($file, array($row['Task'], $row['Platform User'], $row['Due Date'], $row['Task Status'], $row['Date Completed'], $row['Notes']));
            }

            fclose($file);
        };


        return response()->stream($callback, 200, $headers);
    }

    // public function searchTasks(Request $request)
    // {
    //     $data = $request->all();
    //     $platform_users = $request->platform_users;
    //     $status = $request->status;
    //     $priority = $request->priority;


    //     // dd($status);
    //     // $user_filter = $request->user_filter;

    //     $start_date = $end_date = null;

    //     if ($request->start_date !== null) $start_date = Carbon::parse($request->start_date)->toDateTimeString();
    //     if ($request->end_date !== null) $end_date = Carbon::parse($request->end_date)->toDateTimeString();

    //     // if($user_filter != 'all') {
    //     //     $tasksArray = \DB::table('task_users')->where('user_id', $user_filter)->pluck('task_id');
    //     //     $tasksArray = $tasksArray->toArray();

    //     //     $query = Tasks::whereIn('id', $tasksArray)->orderBy('id', 'desc');
    //     // } else {
    //         $query = Tasks::orderBy('id', 'desc');
    //     // }

    //     if($status != 'all') {
    //         $query->where('status', $status);
    //     }

    //     if(!empty($priority)) {
    //         $query->where('priority', $priority);
    //     }

    //     if($platform_users != 'all') {
    //         $query->where('platform_user', $platform_users);
    //     }

    //     if ($start_date !== null || $end_date !== null) {
    //         $query->whereBetween('due_date', [$start_date, $end_date]);
    //     }
        

    //     $tasks = $query->paginate(10);

    //     return view('admin.ajax.tasksSearchData', compact('tasks'));

    //     //   else if($filter === '2'){
    //     //     $matters =  Matter::
    //     //     where('status', $status)
    //     //    ->whereNotNull('closing_date')
    //     //    ->where(function($query) use ($keywords){
    //     //       $query->where('referral', 'LIKE','%'.$keywords.'%')
    //     //          ->orWhere('fee', 'LIKE','%'.$keywords.'%')
    //     //          ->orWhere('client_ref_no', 'LIKE','%'.$keywords.'%');
    //     //     })->orderBy('id','desc')->get();
    //     //   }
    //     //   else if($filter === '0'){
    //     //     $matters =  Matter::
    //     //     where('status', $status)
    //     //    ->whereNotNull('pending_date')
    //     //    ->where(function($query) use ($keywords){
    //     //       $query->where('referral', 'LIKE','%'.$keywords.'%')
    //     //          ->orWhere('fee', 'LIKE','%'.$keywords.'%')
    //     //          ->orWhere('client_ref_no', 'LIKE','%'.$keywords.'%');
    //     //     })->orderBy('id','desc')->get();
    //     //   }
    //     //   else{
    //     //     $matters =  Matter::
    //     //     where('status', $status)
    //     //    ->where(function($query) use ($keywords){
    //     //       $query->where('referral', 'LIKE','%'.$keywords.'%')
    //     //          ->orWhere('fee', 'LIKE','%'.$keywords.'%')
    //     //          ->orWhere('client_ref_no', 'LIKE','%'.$keywords.'%');
    //     //     })->orderBy('id','desc')->get();
    //     //   }


    //     return view('admin.ajax.mattersSearchData', compact('matters'));
    // }

    public function changeStatus(Tasks $id) {
        if($id->status == 'not_completed') {
            $id->status = 'completed';
            $id->completed_date = Carbon::now();
            $id->save();
        } else {
            $id->status = 'not_completed';
            $id->save();
        }

        return back()
        ->withFlashMessage('Task status changed successfully!')
        ->withFlashType('success');
    }

    public function deleteFile(Request $request, $taskId)
    {
        $task = Tasks::find($taskId);

        if (!$task || !$task->file) {
            return back()->withFlashMessage('File not found!')->withFlashType('error');
        }

        $files = json_decode($task->file, true) ?? [];
        $fileToDelete = $request->file_path;

        $fileKey = array_search($fileToDelete, array_column($files, 'path'));

        if ($fileKey === false) {
            return back()->withFlashMessage('File not found in the record!')->withFlashType('error');
        }

        $filePath = public_path($fileToDelete);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove file entry from array
        unset($files[$fileKey]);
        $files = array_values($files); // Re-index array

        // Update the task's file list
        $task->update(['file' => !empty($files) ? json_encode($files) : null]);


        return back()->withFlashMessage('File deleted successfully!')->withFlashType('success');
    }

}
