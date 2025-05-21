<?php

namespace App\Http\Controllers\Contractor;

use Carbon\Carbon;
use App\Models\Jobs;
use App\Models\Admin;
use App\Models\JobDetail;
use App\Models\Contractor;
use Illuminate\Http\Request;
use App\Models\ContractorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContractorSubmittedJobNotification;

class ProfileController extends Controller
{
    public function __construct()
    {
        $currentRouteId = (int)Route::current()->id;
        $authTenantId = Auth::guard('contractor')->id();

        if ($authTenantId !== $currentRouteId) {
            redirect()->route(Route::currentRouteName(), ['id' => $authTenantId])->send();
        }
    }
    public function showProfileForm() {
        $page['page_title'] = 'Manage Contractor Profile';

        $contractor = Auth::guard('contractor')->user();

        return view('contractor.profile', compact('page', 'contractor'));
    }

    public function updateProfile(Request $request){
        if($request->has('contractor_id')){
            $id =  base64_decode($request->get('contractor_id'));

            $validator = Validator::make($request->all(),[
                'fname' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email|unique:contractors,id,',$id,
                // 'status' => 'required',
            ]);

            $validator->sometimes( 'profile_image', 'required|image|mimes:jpg,png,jpeg,gif,svg',function($request){
                return  isset($request->profile_image);
            });


            $validator->sometimes( 'password', 'min:6|required_with:confirmPassword|same:confirmPassword',function($request){
                    return !empty($request->get('password'));
            });

            $validator->sometimes( 'confirmPassword', 'min:6',function($request){
                return !empty($request->get('confirmPassword'));
            });


        }

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $data = $request->all();


        if($request->has('contractor_id')){

            $id =  base64_decode($request->get('contractor_id'));

            $contractor  =  Contractor::where('id', $id)->first();


            if(!empty($request->get('password')))   $contractor->pass = Hash::make($data['password']);

            Contractor::where('id', $id)->update([
                'name' => $data['fname'].' '.$data['lname'],
                'email' => $data['email'],
                // 'status' => $data['status'],
            ]);


            if(!empty($request->get('password'))) Contractor::where('id', $id)->update(['password' => Hash::make($data['password'])]);

            if(isset($contractor->profile)){
                $contractor_profile = ContractorProfile::where('contractor_id', $id)->update([
                    'phone_number' => $data['phone_number'],
                ]);
            }else{
                $contractor_profile = ContractorProfile::create([
                    'contractor_id' => $id,
                    'phone_number' => $data['phone_number'],
                ]);
            }



            if($request->hasFile('profile_image')){
                $profile_image = $request->file('profile_image');
                if(!empty($profile_image)){
                    $ext =  strtolower($profile_image->getClientOriginalExtension());
                    if(!($ext == 'jpg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif')){
                        return redirect()->back()
                                ->withFlashMessage('Please Select Valod Logo Extension')
                                ->withFlashType('errors');
                    }
                    $directory = public_path('uploads/contractor-'.$id.'/');
                    if(!$directory) mkdir($directory,0777);

                        if(isset($contractor->profile) && !empty($contractor->profile->profile_image)){
                        $existingProfileImage = $directory.$contractor->profile->profile_image;
                        if(file_exists($existingProfileImage)) unlink($existingProfileImage);
                    }
                    $profile =  base64_encode('profile_image-'.time()).'.'.$ext;
                    $profile_image->move($directory, $profile);

                        $contractor_profile = ContractorProfile::where('contractor_id', $id)->update([
                            'profile_image' => $profile,
                        ]);
                }

            }
            return redirect()
            ->route( 'contractor.profile' )
            ->withFlashMessage( 'profile updated successfully!' )
            ->withFlashType( 'success' );
        }
    }


    public function edit($id) 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'Edit Contractor';

        $contractor = Contractor::where('id', $id)->first();

        return view('contractor.profile.edit', compact('page', 'contractor'));
    }

    public function editAddress($id) 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'Edit Contractor';

        $contractor = Contractor::where('id', $id)->first();

        return view('contractor.profile.address.index', compact('page', 'contractor'));
    }

    public function update(Request $request, $id)
    {
        $contractor = Contractor::where('id', $id)->first();

        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:contractors,email,' . $contractor->id,
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'title' => 'required',
            'contact_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $contractor->update([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'deleted_at' => $request->status == 'Active' ? null : now(),
            'status' => $request->status,
            'company_name' => $request->company_name,
            'work_phone' => $request->work_phone,
            'fax' => $request->fax,
            'contact_type' => $request->contact_type,
            'title' => $request->title,
        ]);

        if ($request->filled('password')) {
            $contractor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $contractor->profile()->updateOrCreate(
            ['contractor_id' => $contractor->id],
            ['phone_number' => $request->phone_number]
        );

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');

            // Create directory if it doesn't exist
            $directory = public_path('uploads/contractor-' . $contractor->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if (isset($contractor->profile) && !empty($contractor->profile->profile_image)) {
                $existingProfileImage = $directory . $contractor->profile->profile_image;
                if (file_exists($existingProfileImage)) unlink($existingProfileImage);
            }

            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();

            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);

            // Update profile image in contractor profile
            $contractor->profile()->update([
                'profile_image' => $profile_image_name,
            ]);
        }

        return redirect()
            ->route('contractor.settings.contractors.edit', auth('contractor')->user()->id)
            ->withFlashMessage('Contractor updated successfully!')
            ->withFlashType('success');
    }
    public function updateAddress(Request $request, $id)
    {
        $contractor = Contractor::where('id', $id)->first();

        // Validation
        $validator = Validator::make($request->all(), [
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $contractor->update([
            'country' => $request->country,
            'line1' => $request->address_line_1,
            'line2' => $request->address_line_2,
            'line3' => $request->address_line_3,
            'city' => $request->city,
            'county' => $request->county,
            'postcode' => $request->postal_code, 
            'note' => $request->note, 
        ]);
        return redirect()
            ->route('contractor.settings.contractors.edit.address', auth('contractor')->user()->id)
            ->withFlashMessage('Contractor updated successfully!')
            ->withFlashType('success');
    }

    public function jobs($id)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'View Contractor Jobs';

        $jobs = Jobs::whereHas('jobDetail', function($query) use ($id) {
            $query->where('contractor_id', $id);
        })->with('property', 'contractor', 'jobDetail')->paginate(10); 
        $contractor_id = $id;

        return view('contractor.profile.jobs.index', compact('page', 'jobs', 'contractor_id'));
    }

    public function editJob(Request $request, $id,$jobId)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'Edit Contractor Job';

        $contractors = Contractor::where('status', 'Active')->get();
        $jobs = JobDetail::where('contractor_id', $id)->where('jobs_id', $jobId)->with('contractor')->get();
        $contractor_id = $id;

        $contractor = Auth::guard('contractor')->user();
        $allNotifications = $contractor->notifications()->where('data->notification_detail->type', 'job')->whereNull('read_at')->update(['read_at' => Carbon::now()]);
        return view('contractor.profile.jobs.edit', compact('page', 'jobs', 'contractor_id', 'contractors'));
    }
    private function handleFileUpload(Request $request, $path)
    {
        $file = data_get($request->allFiles(), $path);
        return $file ? $file->store('uploads/job_details', 'public') : null;
    }

    private function formatDate($date)
    {
        if (!$date) return null;

        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    public function updateContractorTasks(Request $request, $id)
    {
        $tasks = $request->input('tasks', []);
        $newTasks = $request->input('new_tasks', []);
        $jobId = null;

        foreach ($tasks as $taskId => $taskData) {
            $task = JobDetail::where('id', $taskId)
                ->where('contractor_id', $id)
                ->first();

            if (!$task) continue;

            $task->contractor_comment = $taskData['contractor_comment'] ?? null;
            $task->date = $this->formatDate($taskData['date'] ?? null);
            $task->price = $taskData['price'] ?? null;

            $newFile = $this->handleFileUpload($request, "tasks.{$taskId}.contractor_upload");
            if ($newFile) {
                $task->contractor_upload = $newFile;
            }

            if (!$jobId) {
                $jobId = $task->jobs_id;
            }

            $task->save();
        }

        foreach ($newTasks as $index => $task) {
            if (empty($task['description']) || empty($task['job_id']) || empty($task['contractor_id'])) {
                continue;
            }

            JobDetail::create([
                'jobs_id' => $task['job_id'],
                'contractor_id' => $task['contractor_id'],
                'description' => $task['description'],
                'contractor_comment' => $task['contractor_comment'] ?? null,
                'contractor_upload' => $this->handleFileUpload($request, "new_tasks.{$index}.contractor_upload"),
                'date' => $this->formatDate($task['date'] ?? null),
                'price' => $task['price'] ?? null,
                'won_contract' => 'no',
            ]);

            if (!$jobId) {
                $jobId = $task['job_id'];
            }
        }

        if ($jobId) {
            $admins = Admin::all();

            foreach ($admins as $admin) {
                $notificationDetails = array(
                    'type' => 'job',
                    'message' => 'Contractor has submitted job details.',
                    'route' => route('admin.jobs.edit.contractorList', $jobId),
                );
                Notification::send($admin, new ContractorSubmittedJobNotification($notificationDetails));
            }
        }

        return redirect()
            ->route('contractor.contractors.viewjobs', auth('contractor')->user()->id)
            ->withFlashMessage('Job details updated successfully!')
            ->withFlashType('success');
    }

    



}
