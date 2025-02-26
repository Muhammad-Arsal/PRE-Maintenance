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

        return view('admin.jobs.create', compact('page', 'properties', 'contractors'));
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
            'contractor_id' => 'nullable',
            'won_contract' => 'nullable|string', 
            'description' => 'required|string',
            'other_information' => 'nullable|string'
        ]);
    
        $job = Jobs::create([
            'status' => $request->status,
            'property_id' => $request->property_id,
            'contractor_id' => $request->contractor_id,
            'won_contract' => $request->has('won_contract') ? "yes" : "no",
            'description' => $request->description,
            'other_information' => $request->other_information,
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

        return view('admin.jobs.edit', compact('page', 'job', 'properties', 'contractors'));
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
            'contractor_id' => 'nullable',
            'won_contract' => 'nullable|string', 
            'description' => 'required|string',
            'other_information' => 'nullable|string'
        ]);

        $job = Jobs::where('id', $id)->first();
        $job->status = $request->status;
        $job->property_id = $request->property_id;
        $job->contractor_id = $request->contractor_id;
        $job->won_contract = $request->has('won_contract')? "yes" : "no";
        $job->description = $request->description;
        $job->other_information = $request->other_information;
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
            $query->where('description', 'LIKE', '%'.$keywords.'%');
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);

        $properties = Property::where('status', 'Active')->get();
        $contractors = Contractor::where('status', 'Active')->get();

        return view('admin.jobs.index', compact('page', 'jobs', 'keywords', 'selectedProperty', 'selectedContractor', 'properties', 'contractors'));
    }

}
