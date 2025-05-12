<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Jobs;
use App\Models\Property;
use App\Models\JobDetail;
use App\Models\Contractor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobAssignedNotification;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Jobs';

        $jobs = Jobs::orderBy('created_at', 'desc')->with('property', 'contractor', 'winningContractor')->paginate(10);

        $properties = Property::where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();

        $keywords = "";
        $selectedProperty = "";
        $selectedContractor = "";

        return view('admin.jobs.index', compact('page', 'jobs', 'keywords', 'selectedProperty', 'selectedContractor', 'properties', 'contractors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Jobs';

        $properties = Property::where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();
        $property_id = '';

        return view('admin.jobs.create', compact('page', 'properties', 'contractors', 'property_id'));
    }

    public function customCreate($property_id){
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Jobs';

        $properties = Property::where('id',$property_id)->where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();

        return view('admin.jobs.create', compact('page', 'properties', 'contractors','property_id'));
    }

    public function handleFileUpload(Request $request, $field, $index)
    {
        $fileArray = data_get($request->allFiles(), $field, []);

        if (is_array($fileArray) && isset($fileArray[$index])) {
            return $fileArray[$index]->store('uploads/job_details', 'public');
        }

        return null;
    }

    public function updateHandleFileUpload(Request $request, $fieldPath)
    {
        $file = data_get($request->allFiles(), $fieldPath);
        return $file ? $file->store('uploads/job_details', 'public') : null;
    }



    public function formatDate($date)
    {
        // Converts d/m/Y to Y-m-d for MySQL
        if (!$date) return null;

        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
            'property_id' => 'required',
            'other_information' => 'nullable|string',
            'contractors.*.contractor_id' => 'nullable|exists:contractors,id',
            'priority' => 'required',
        ]);

        $contractorsInput = $request->input('contractors', []);

        $job = Jobs::create([
            'status' => $request->status,
            'property_id' => $request->property_id,
            'other_information' => $request->other_information,
            'priority' => $request->priority,
        ]);

        
            foreach ($contractorsInput as $contractorIndex => $contractor) {
                if (empty($contractor['contractor_id'])) continue;

                $jobDetailCount = count($request->input('description', []));

                for ($i = 0; $i < $jobDetailCount; $i++) {
                    if (empty($request->description[$i])) continue;
                
                    JobDetail::create([
                        'jobs_id' => $job->id, // explicit foreign key
                        'contractor_id' => $contractor['contractor_id'],
                        'description' => $request->description[$i],
                        'contractor_comment' => $request->contractor_comment[$i] ?? null,
                        'admin_upload' => $this->handleFileUpload($request, 'admin_upload', $i),
                        'contractor_upload' => $this->handleFileUpload($request, 'contractor_upload', $i),
                        'date' => $this->formatDate($request->date[$i] ?? null),
                        'price' => $request->price[$i] ?? null,
                        'won_contract' => 'no',
                    ]);
            }
        }

        $contractorIds = JobDetail::where('jobs_id', $job->id)
            ->pluck('contractor_id')
            ->unique()
            ->filter()
            ->values();

        $contractors = Contractor::whereIn('id', $contractorIds)->get();

        foreach ($contractors as $contractor) {
            $notificationDetails = array(
                'type' => 'job',
                'message' => 'You have been assigned a new job.',
                'route' => route('contractor.contractors.editJob.details', [$contractor->id, $job->id]),
            );
            Notification::send($contractor, new JobAssignedNotification($notificationDetails));
        }


        return redirect()
            ->route('admin.jobs')
            ->withFlashMessage('Job and job details added successfully!')
            ->withFlashType('success');
    }

  

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Job Details';

        $job = Jobs::where('id', $id)->with('property','contractor')->first();

        return view('admin.jobs.show', compact('page', 'job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Job';

        $job = Jobs::where('id', $id)->with('jobDetail')->first();
        $properties = Property::where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();

        return view('admin.jobs.edit', compact('page', 'job', 'properties', 'contractors'));
    }
    public function editContractorList($id)
    {
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Job';

        $job = Jobs::where('id', $id)->with('jobDetail')->first();
        $contractors = Contractor::where('status', 'Active')->get();

        $admin = Auth::guard('admin')->user();
        $allNotifications = $admin->notifications()->where('data->notification_detail->type', 'job')->whereNull('read_at')->update(['read_at' => Carbon::now()]);

        return view('admin.jobs.contractorList.index', compact('page', 'job', 'contractors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'property_id' => 'required', 
            'other_information' => 'nullable|string',
            'priority' => 'required',
        ]);

        $job = Jobs::where('id', $id)->first();
        $job->status = $request->status;
        $job->property_id = $request->property_id;
        $job->other_information = $request->other_information;
        $job->priority = $request->priority;
        $job->save();

        return redirect()
        ->route('admin.jobs')
        ->withFlashMessage('Job Updated successfully!')
        ->withFlashType('success');
    }
    public function updateContractorList(Request $request, $id)
    {
        $request->validate([
            'contractors.*.contractor_id' => 'nullable|exists:contractors,id',
            'contractors.*.tasks.*.description' => 'required|string',
        ]);
    
        $job = Jobs::findOrFail($id);
    
        // Remove old job details
        $job->jobDetail()->delete();
    
        $contractorsInput = $request->input('contractors', []);
        $wonIndex = $request->input('won_contract_global');
        $winningContractorId = null;
    
        foreach ($contractorsInput as $contractorIndex => $contractor) {
            if (empty($contractor['contractor_id'])) continue;
    
            // Capture the winning contractor
            if ((string)$contractorIndex === (string)$wonIndex) {
                $winningContractorId = $contractor['contractor_id'];
            }
    
            $tasks = $contractor['tasks'] ?? [];
    
            foreach ($tasks as $taskIndex => $task) {
                if (empty($task['description'])) continue;
    
                JobDetail::create([
                    'jobs_id' => $job->id,
                    'contractor_id' => $contractor['contractor_id'],
                    'won_contract' => ((string)$contractorIndex === (string)$wonIndex) ? 'yes' : 'no',
                    'description' => $task['description'],
                    'contractor_comment' => $task['contractor_comment'] ?? null,
                    'admin_upload' => $this->updateHandleFileUpload($request, "contractors.{$contractorIndex}.tasks.{$taskIndex}.admin_upload"),
                    'contractor_upload' => $this->updateHandleFileUpload($request, "contractors.{$contractorIndex}.tasks.{$taskIndex}.contractor_upload"),
                    'date' => $this->formatDate($task['date'] ?? null),
                    'price' => $task['price'] ?? null,
                ]);
            }
        }
    
        // Save winning contractor ID to the job
        $job->winning_contractor_id = $winningContractorId;
        $job->save();
    
        return redirect()
            ->route('admin.jobs')
            ->withFlashMessage('Job updated successfully!')
            ->withFlashType('success');
    }
       

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Jobs::with('jobDetail')->findOrFail($id);

        // Delete all related job details first
        $job->jobDetail()->delete();

        // Then delete the job itself
        $job->delete();

        return redirect()
            ->route('admin.jobs')
            ->withFlashMessage('Job and its associated tasks deleted successfully!')
            ->withFlashType('success');
    }


    public function searchData(Request $request)
    {
        $page['page_title'] = 'Manage Properties';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Properties';

        $keywords = $request->keywords;
        $selectedProperty = $request->property_id;
        $selectedContractor = $request->contractor_id;

        $query = Jobs::query()->with('property', 'contractor');

        if (!empty($selectedProperty)) {
            $query->where('property_id', $selectedProperty);
        }
        if (!empty($selectedContractor)) {
            $query->where('contractor_id', $selectedContractor);
        }
        if (!empty($keywords)) {
            $query->where(function ($q) use ($keywords) {
                $q->where('description', 'LIKE', '%'.$keywords.'%')
                  ->orWhere('id', intval($keywords)); 
            });
        }        

        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);

        $properties = Property::where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();

        return view('admin.jobs.index', compact('page', 'jobs', 'keywords', 'selectedProperty', 'selectedContractor', 'properties', 'contractors'));
    }

}
