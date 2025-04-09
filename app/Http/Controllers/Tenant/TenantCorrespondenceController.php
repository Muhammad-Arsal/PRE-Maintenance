<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralCorrespondenceCall;
use App\Models\GeneralCorrespondenceFiles;
use App\Models\GeneralCorrespondence;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Admin;
use App\Models\Landlord;
use App\Models\TaskTray;
use App\Models\Tasks;
use App\Models\Tenant;
use App\Models\EmailTemplate;
use Validator;
use PDF;

class TenantCorrespondenceController extends Controller
{
    public function paginate($items, $base_url='', $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $lap =  new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        if($base_url) {
            $lap->setPath($base_url);
        }

        return $lap;
    }

    public function index(Request $request, Tenant $id)
    {
        $page['page_title'] = 'Manage Correspondence';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'Correspondence';
        $page['tenant_name'] = $id->name;

        $filterType = $request->input('filterType', '');

        $tenant = $id;
        $admin_id = auth()->guard('admin')->user()->id;

        if ($filterType === 'folder') { 
            $data = GeneralCorrespondence::where("parent_id", 0)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('name')->get();
            $files = collect();
            $call = collect();
        } elseif ($filterType === 'file') { 
            $data = collect();
            $files = GeneralCorrespondenceFiles::where("parent_id", 0)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('file_name')->get();
            $call = collect();
        } elseif ($filterType == 'call') {
            $data = collect();
            $files = collect();    
            $call = GeneralCorrespondenceCall::where('tenant_id', $tenant->id)->where('tenant_id', $tenant->id)->where('type','tenant')->get();
        } else {
            $data = GeneralCorrespondence::where("parent_id", 0)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('name')->get();
            $files = GeneralCorrespondenceFiles::where("parent_id", 0)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('file_name')->get();
            $call = GeneralCorrespondenceCall::where('parent_id', 0)->where('tenant_id', $tenant->id)->where('type','tenant')->get();
        }

        $parent = 0;
        $all = $data->concat($files)->concat($call);
        $all = $all->sortByDesc('created_at');
        $all = $this->paginate($all, route("tenant.tenants.correspondence", $tenant->id));

        $tenants = Tenant::orderBy('name', 'asc')->get();

        return view('tenant.profile.correspondence.index', compact('page', 'tenant', 'data', 'files', 'parent', 'all', 'tenants', 'filterType'));
    }

    public function saveComment(Tenant $id, Request $request)
    {
        try {
            $data = $request->except('_token');
            $admin_id = auth()->guard('admin')->user()->id;

            $documents = \DB::table('general_correspondence_files')->insert([
                'parent_id'  =>   0,
                'text' => $data['comment'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'is_text'      => true,
                'tenant_id' => $id->id,
                'type' => 'tenant',
                'tenant_id' => $id->id,
            ]);

            if ($documents) {
                return response()->json([
                    'success' => true,
                    'documents' => $documents
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function editComment(Tenant $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'editCommentInput' => 'required',
        ], [
            'editCommentInput.required' => "This field is required",
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.
        }
        $data = $request->except('_token');
        $newComment = $data['editCommentInput'];
        $commentData = $data['commentData'];

        $commentData = explode("+", $commentData);

        $comment = \DB::table('general_correspondence_files')->where('id', $commentData[0])->update([
            'text' => $newComment,
        ]);

        if ($comment) {
            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        }
    }

    public function createFolder(Tenant $id, $parent_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_folder' => 'required',
        ]);

        $tenant = $id;

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400); // 400 being the HTTP code for an invalid request.
        }

        $name = $request->new_folder;
        $name = str_replace(" ", "-", $name);

        $admin_id = auth()->guard('admin')->user()->id;
        $check = GeneralCorrespondence::where('parent_id', $parent_id)->where('name', $name)->where('tenant_id', $tenant->id)->where('type','tenant')->first();
        if (!empty($check)) {
            return response()->json(array(
                'success' => false,
                'type'    => 1,
                'message' => array('A Folder with this name already exists')

            ), 400); // 400 being the HTTP code for an invalid request.
        }

        $this->makeUserDirectory($tenant->id);

        $admin_id = auth()->guard('admin')->user()->id;

        if ($parent_id == 0) {
            $directory = public_path('generalFileManager/tenant-' . $tenant->id . '/' . $name . '/');
            if (!file_exists($directory)) mkdir($directory, 0777, true);
        } else {
            $parent = GeneralCorrespondence::find($parent_id);
            $directory = public_path($parent->link . '/' . $name . '/');
            if (!file_exists($directory)) mkdir($directory, 0777, true);
        }

        if ($parent_id == 0) {
            $link = "generalFileManager/tenant-" . $tenant->id . '/' . $name;
        } else {
            $parent = GeneralCorrespondence::find($parent_id);
            $link = $parent->link . '/' . $name;
        }

        

        $folder = GeneralCorrespondence::create([
            'parent_id' => $parent_id,
            'name'      => $name,
            'link'      => $link,
            'type' => 'tenant',
            'tenant_id' => $tenant->id,
        ]);

        if ($folder) {
            $data = GeneralCorrespondence::where('parent_id', $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('name')->get();
            $files = GeneralCorrespondenceFiles::where("parent_id", $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->get();
            $all = $data->merge($files);
            $all = $all->sortByDesc('created_at');

            return view('tenant.profile.correspondence.table', compact('data', 'files', 'tenant', 'all'));
        } else {
            return response()->json(array(
                'success' => false,
                'type'    => 1,
                'message' => 'Something went wrong. Please try again later.',

            ), 400); // 400 being the HTTP code for an invalid request.
        }
    }

    public function makeUserDirectory($tenant_id)
    {
        $admin_id = auth()->guard('admin')->user()->id;

        $directory = public_path('generalFileManager/tenant-' . $tenant_id);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    public function showUploadFileForm(Tenant $id, $parent_id, Request $request)
    {
        $page['page_title'] = 'Manage Correspondence';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'Upload Files';
        $page['tenant_name'] = $id->name;

        $tenant = $id;
        $parent_id = $parent_id;

        return view('tenant.profile.correspondence.uploadFiles', compact('page', 'tenant', 'parent_id'));
    }

    public function uploadFiles(Tenant $id, $parent_id, Request $request)
    {
        $tenant = $id;
        $admin_id = auth()->guard('admin')->user()->id;

        $parent = GeneralCorrespondence::find($parent_id);

        // $copyFiles = $request->copy_to_file_vault;
        // $copyToCorr = $request->copy_to_correspondence;

        if ($request->files) {
            foreach ($request->files as $key => $file) {

                $f = $file->getClientOriginalName();
                $filename = pathinfo($f, PATHINFO_FILENAME);

                if (!empty($file)) {
                    $ext =  strtolower($file->getClientOriginalExtension());

                    $this->makeUserDirectory($id->id);

                    if ($parent_id == 0) {
                        $directory = public_path('generalFileManager/tenant-' . $id->id . '/');
                    } else {
                        $parent = GeneralCorrespondence::find($parent_id);
                        $directory = public_path($parent->link . '/');
                    }

                    $fileSize = $request->file('file')->getSize();

                    $file_name =  base64_encode($key . '-file-' . time()) . '.' . $ext;
                    $file->move($directory, $file_name);

                    $sameName = \DB::table('general_correspondence_files')->where('file_name', $filename . '.' . $ext)->where('parent_id', $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->get();
                    $count = $sameName->count();

                    if ($count > 0) {
                        $filename = $filename . $count . '.' . $ext;
                    } else {
                        $filename = $filename . '.' . $ext;
                    }

                    if ($parent_id == 0) {
                        $link = "generalFileManager/tenant-" . $id->id . '/' . $filename;
                        $original_link = "generalFileManager/tenant-" . $id->id . '/' . $file_name;
                    } else {
                        $parent = GeneralCorrespondence::find($parent_id);
                        $link = $parent->link . '/' . $filename;
                        $original_link = $parent->link . '/' . $file_name;
                    }

                    $documentsId = \DB::table('general_correspondence_files')->insertGetId([
                        'parent_id'  => $parent_id,
                        'file_name'      => $filename,
                        'original_name' => $file_name,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                        'link'          => $link,
                        'file_description' => $request->description,
                        'original_link' => $original_link,
                        'tenant_id' => $id->id,
                        'type' => 'tenant',
                    ]);
                }
            }
        }
    }

    public function showTaskPage(Tenant $id, Request $request){
        $page['page_title'] = 'Manage Correspondence';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'New Task';
        $page['tenant_name'] = $id->name;

        $platform_users = Admin::orderBy('name', 'desc')->get();
        $taskTray = TaskTray::orderBy('name', 'asc')->get();
        $tenants = Tenant::where('id', $id->id)->first();

 
        $tenant = $id;
        return view('tenant.profile.correspondence.createTask', compact('page', 'tenant',  'platform_users', 'taskTray', 'tenants'));
    }

    public function storeTask(Tasks $task, Request $request)
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
        $task->contact_id = $data['contact'];
        if($data['status'] == 'completed') {
            $task->completed_date = Carbon::now();
        }

        if($request->is_critical) {
            $task->is_critical = "1";
        }

        if ($task->save()) {
            GeneralCorrespondenceCall::create([
                'tenant_id' => $data['contact'] ??  null,
                'type' => 'tenant',
                'task_id' => $task->id,
                'is_task' => 'yes',
            ]);
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
            
                        $file->move($directory, $fileName);
            
                        $filePaths[] = 'uploads/admin/tasks/' . $fileName;
                    }
                }
            
                if (!empty($filePaths)) {
                    $task->update([
                        'files' => json_encode($filePaths)
                    ]);
                }
            }
            
        }
        return redirect()->route('tenant.tenants.correspondence',auth('tenant')->id())
        ->withFlashMessage('Task added successfully!')
        ->withFlashType('success');          
    }

    public function showChild(Request $request, Tenant $id, $parent_id)
    {
        $page['page_title'] = 'Manage Correspondence';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'Correspondence';
        $page['tenant_name'] = $id->name;

        $tenant = $id;
        $admin_id = auth()->guard('admin')->user()->id;

        $filterType = $request->input('filterType', '');

        if ($filterType === 'folder') { 
            $data = GeneralCorrespondence::where("parent_id", $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('name')->get();
            $files = collect();
            $call = collect();
        } elseif ($filterType === 'file') { 
            $data = collect();
            $files = GeneralCorrespondenceFiles::where("parent_id", $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('file_name')->get();
            $call = collect();
        } elseif ($filterType == 'call') {
            $data = collect();
            $files = collect();    
            $call = GeneralCorrespondenceCall::where('tenant_id', $tenant->id)->where('type','tenant')->where('parent_id', $parent_id)->get();
        } else {
            $data = GeneralCorrespondence::where("parent_id", $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('name')->get();
            $files = GeneralCorrespondenceFiles::where("parent_id", $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->orderBy('file_name')->get();
            $call = GeneralCorrespondenceCall::where('tenant_id', $tenant->id)->where('type','tenant')->where('parent_id', $parent_id)->get();
        }
        $parent = GeneralCorrespondence::where('id', $parent_id)->where('tenant_id', $tenant->id)->where('type','tenant')->first();

        $all = $data->concat($files)->concat($call);
        $all = $all->sortByDesc('created_at');
        $all = $this->paginate($all, route("tenant.tenants.correspondence.child", ['id' => $tenant->id, 'parent_id' => $parent_id]));

        $tenants = Tenant::orderBy('name', 'asc')->get();

        return view('tenant.profile.correspondence.index', compact('page', 'tenant', 'data', 'files', 'parent', 'all', 'filterType', 'tenants'));
    }

    public function delete(Request $request)
    {
        $checks = $request->check;
        foreach ($checks as $c) {
            $val = explode("+", $c);
            if ($val[1] == 'folder') {
                $find = GeneralCorrespondence::find($val[0]);
                $directory = public_path($find->link);
                $response = File::deleteDirectory($directory);
                $find->delete();
            } else if ($val[1] == 'text') {
                \DB::table('general_correspondence_files')->where('id', $val[0])->delete();
            } else if($val[1] == 'call') { 
                GeneralCorrespondenceCall::where('id', $val[0])->delete();
            } else if($val[1] == 'meeting') {
                GeneralCorrespondenceCall::where('id', $val[0])->delete();
            } else if($val[1] == 'task') {
                GeneralCorrespondenceCall::where('id', $val[0])->delete();
            } else {
                $find = \DB::table('general_correspondence_files')->where('id', $val[0])->first();
                if($find->is_email != 'yes') {
                    $directory = public_path($find->original_link);
                    unlink($directory);
                }
                // \DB::table('general_correspondence_files_contacts')->where('correspondence_file_id', $val[0])->delete();
                \DB::table('general_correspondence_files')->where('id', $val[0])->delete();
            }
        }

        return back();
    }

    public function moveFile(Tenant $id, $parent_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'move_link' => 'required',
        ], [
            'move_link' => "This field is required",
        ]);

        $tenant = $id;


        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400);
        }

        $move_link = $request->move_link;
        $fileNames = explode(",", $request->fileName);

        foreach ($fileNames as $fileItem) {
            $fileName = explode("+", $fileItem);
            $fileType = $fileName[1];
            if ($fileType == 'folder') {
                return response()->json(array(
                    'success' => false,
                    'type'    => 1,
                    'fileNameError' => 0,
                    'message' => array('Please select only files'),

                ), 400);
            }
        }

        foreach ($fileNames as $ff) {
            $fileName = explode("+", $ff);
            $fileId = $fileName[0];
            $fileType = $fileName[1];
            $fileName = $fileName[2];

            $admin_id = auth()->guard('admin')->user()->id;
            if ($fileType == 'file') {
                $file = \DB::table('general_correspondence_files')->where('id', $fileId)->where('tenant_id', $tenant->id)->where('type','tenant')->first();

                if ($move_link == '/') {
                    $saveDir = "generalFileManager/tenant-" . $tenant->id . '/' . $file->file_name;
                    $originalDir = "generalFileManager/tenant-" . $tenant->id . '/' . $file->original_name;
                } else {
                    $saveDir = "generalFileManager/tenant-" . $tenant->id . "/" . $move_link . '/' . $file->file_name;
                    $originalDir = "generalFileManager/tenant-" . $tenant->id . "/" . $move_link . '/' . $file->original_name;
                }

                if ($move_link == '/') {
                    $directory = public_path("generalFileManager/tenant-" . $tenant->id . "/" . $file->original_name);
                } else {
                    $directory = public_path("generalFileManager/tenant-" . $tenant->id . "/" . $move_link . "/" . $file->original_name);
                }

                if ($parent_id == 0) {

                    if ($move_link != '/') {
                        $getParent = "generalFileManager/tenant-" . $tenant->id . '/' . $move_link;
                        $parent = GeneralCorrespondence::where('link', "generalFileManager/tenant-" . $tenant->id . '/' . $move_link)->where('tenant_id', $tenant->id)->where('type','tenant')->first();
                        if (empty($parent)) {
                            return response()->json(array(
                                'success' => false,
                                'type'    => 1,
                                'fileNameError' => 0,
                                'message' => array('No such directory exists'),

                            ), 400); // 400 being the HTTP code for an invalid request.
                        }
                    } else {
                        $getParent = "generalFileManager/tenant-" . $tenant->id;
                    }
                } else {

                    if ($move_link != '/') {

                        $parent = GeneralCorrespondence::where('link', "generalFileManager/tenant-" . $tenant->id . '/' . $move_link)->where('tenant_id', $tenant->id)->where('type','tenant')->first();
                        if (empty($parent)) {
                            return response()->json(array(
                                'success' => false,
                                'type'    => 1,
                                'fileNameError' => 0,
                                'message' => array('No such directory exists'),

                            ), 400); // 400 being the HTTP code for an invalid request.
                        }
                        $getParent = $parent->link;
                    } else {
                        $getParent = "generalFileManager/tenant-" . $tenant->id;
                    }
                }

                if ($move_link != '/') {
                    $explodeLink = explode('/', $move_link);
                    foreach ($explodeLink as $g) {
                        $parentFolderName = $g;
                    }
                }

                if ($move_link != '/') {

                    $parentIdToUpdate = GeneralCorrespondence::where("link", $getParent)->where('name', $parentFolderName)->where('tenant_id', $tenant->id)->where('type','tenant')->first();
                    $checkNames = \DB::table("general_correspondence_files")->where('parent_id', $parentIdToUpdate->id)->where('file_name', $fileName)->where('tenant_id', $tenant->id)->where('type','tenant')->get();
                    $count = $checkNames->count();

                    if ($count > 0) {
                        return response()->json(array(
                            'success' => false,
                            'type'    => 1,
                            'fileNameError' => 1,
                            'message' => array($fileName . ' already exists in this directory'),

                        ), 400); // 400 being the HTTP code for an invalid request.
                    } else {
                        if (rename(public_path($file->original_link), $directory)) {
                            \DB::table('general_correspondence_files')->where('id', $fileId)->where('tenant_id', $tenant->id)->where('type','tenant')->update([
                                'link'  => $saveDir,
                                'parent_id' => $parentIdToUpdate->id,
                                'original_link' => $originalDir,
                            ]);
                        }
                    }
                } else {

                    $parentIdToUpdate = 0;
                    $checkNames = \DB::table("general_correspondence_files")->where('parent_id', $parentIdToUpdate)->where('file_name', $fileName)->get();
                    $count = $checkNames->count();

                    if ($count > 0) {
                        return response()->json(array(
                            'success' => false,
                            'type'    => 1,
                            'fileNameError' => 1,
                            'message' => array($fileName . ' already exists in this directory'),

                        ), 400); // 400 being the HTTP code for an invalid request.
                    } else {
                        if (rename(public_path($file->original_link), $directory)) {
                            \DB::table('general_correspondence_files')->where('id', $fileId)->update([
                                'link'  => $saveDir,
                                'parent_id' => $parentIdToUpdate,
                                'original_link' => $originalDir,
                            ]);
                        }
                    }
                }
            }
        }
        return response()->json(array(
            'success' => true,
        ), 200); // 400 being the HTTP code for an invalid request.
    }

    public function newCall(Request $request, Tenant $id, $parent_id)
    {
        $tenant = $id;

        $validator = Validator::make($request->all(),[
            'date' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
        
            ), 400); // 400 being the HTTP code for an invalid request.
        }

        
        $data = $request->except('_token');
        if($data['date']) {
            $date = Carbon::createFromFormat("d/m/Y", $data['date'])
            ->format('Y-m-d H:i:s');
        }

        // $new_rate = 12 * $units;

        // $rate = $data['rate'] + $new_rate;
       

        $feeType = GeneralCorrespondenceCall::create([
            'parent_id' => $parent_id,
            'tenant_id' => $tenant->id,
            'description' => $data['description'],
            'date' => $date,
            'call_type'   => $request->call_type,
            'is_call' => 'yes',
            'type' => 'tenant',
        ]);

        

        if($feeType->id){
            return response()->json(array(
                'success' => true,
                'message' => 'Call Added Successfully',
                'data' => $feeType
            ), 200);
        }else{
            return response()->json(array(
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'data' => $feeType
            ), 400);
        }
        
    }

    //saving meeting in the correspondence call table
    public function storeMeeting(Request $request, Tenant $id, $parent_id)
    {
        $tenant = $id;

        $validator = Validator::make($request->all(),[
            'meeting_date' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
        
            ), 400); // 400 being the HTTP code for an invalid request.
        }

        
        $data = $request->except('_token');
        if($data['meeting_date']) {
            $meeting_date = Carbon::createFromFormat("d/m/Y", $data['meeting_date'])
            ->format('Y-m-d H:i:s');
        }

        // $new_rate = 12 * $units;

        // $rate = $data['rate'] + $new_rate;
       

        $meeting = GeneralCorrespondenceCall::create([
            'parent_id' => $parent_id,
            'description' => $data['meeting_notes'],
            'time' => $data['meeting_time'],
            'time_to' => $data['meeting_time_to'],
            'date' => $meeting_date,
            'call_type'   => null,
            'is_call' => 'no',
            'type' => 'tenant',
            'tenant_id' => $tenant->id,
        ]);

        
        

        if($meeting->id){
            return response()->json(array(
                'success' => true,
                'message' => 'New Meeting Added Successfully',
                'data' => $meeting
            ), 200);
        }else{
            return response()->json(array(
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'data' => $meeting
            ), 400);
        }
        
    }


    public function add_edit_description(Request $request, $id) {
        $data = $request->except('_token');
        $description = $data['description'];
        $descriptionData = $data['descriptionData'];

        $descriptionData = explode("+", $descriptionData);

        $file = \DB::table('general_correspondence_files')->where('id', $descriptionData[0])->update([
            'file_description' => $description,
        ]);

        if ($file) {
            return redirect()->route('tenant.tenants.correspondence', $id)->withFlashMessage('File Description Edited Successfully')
            ->withFlashType('success');
        } else {
            return redirect()->route('tenant.tenants.correspondence', $id)->withFlashMessage('Oops! Something went wrong')
            ->withFlashType('errors');
        }
    }
}