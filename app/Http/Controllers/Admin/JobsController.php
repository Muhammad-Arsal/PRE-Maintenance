<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\Property;
use App\Models\Contractor;

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

        $jobs = Jobs::orderBy('created_at', 'desc')->with('property', 'contractor')->paginate(10);

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
            'description' => 'required|string',
            'other_information' => 'nullable|string',
            'contractors.*.contractor_id' => 'nullable|exists:contractors,id',
        ]);
    
        // Prepare contractor details JSON
        $contractorsInput = $request->input('contractors', []);
        $wonIndex = $request->input('won_contract_global');
    
        $contractorDetails = [];
    
        foreach ($contractorsInput as $index => $contractor) {
            if (empty($contractor['contractor_id'])) {
                continue; // Skip empty rows
            }
    
            $contractorDetails[] = [
                'contractor_id' => $contractor['contractor_id'],
                'won_contract' => ((string) $wonIndex === (string) $index) ? 'yes' : 'no',
            ];
        }
    
        $job = Jobs::create([
            'status' => $request->status,
            'property_id' => $request->property_id,
            'description' => $request->description,
            'other_information' => $request->other_information,
            'contractor_details' => json_encode($contractorDetails),
        ]);
    
        return redirect()
            ->route('admin.jobs')
            ->withFlashMessage('Job added successfully!')
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

        $job = Jobs::where('id', $id)->first();
        $properties = Property::where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();
        $contractorDetails = json_decode($job->contractor_details, true);

        return view('admin.jobs.edit', compact('page', 'job', 'properties', 'contractors', 'contractorDetails'));
    }
    public function editContractorList($id)
    {
        $page['page_title'] = 'Manage Jobs';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Job';

        $job = Jobs::where('id', $id)->first();
        $contractors = Contractor::where('status', 'Active')->get();
        $contractorDetails = json_decode($job->contractor_details, true);

        return view('admin.jobs.contractorList.index', compact('page', 'job', 'contractorDetails', 'contractors'));
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
            'description' => 'required|string',
            'other_information' => 'nullable|string'
        ]);

        $job = Jobs::where('id', $id)->first();
        $job->status = $request->status;
        $job->property_id = $request->property_id;
        $job->description = $request->description;
        $job->other_information = $request->other_information;
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
        ]);

        $job = Jobs::findOrFail($id);

        $contractorsInput = $request->input('contractors', []);
        $wonIndex = $request->input('won_contract_global');

        $contractorDetails = [];

        foreach ($contractorsInput as $index => $contractor) {
            if (empty($contractor['contractor_id'])) {
                continue;
            }

            $contractorDetails[] = [
                'contractor_id' => $contractor['contractor_id'],
                'won_contract' => ((string) $wonIndex === (string) $index) ? 'yes' : 'no',
            ];
        }

        $job->contractor_details = json_encode($contractorDetails);

        $job->save();

        return redirect()
        ->route('admin.jobs')
        ->withFlashMessage('Job Updated successfully!')
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
        $job = Jobs::findOrFail($id);
        $job->delete(); 

        return redirect()
        ->route('admin.jobs')
        ->withFlashMessage('Job Deleted successfully!')
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
