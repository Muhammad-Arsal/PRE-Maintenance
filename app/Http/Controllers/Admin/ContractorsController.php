<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jobs;
use App\Models\Invoices;
use App\Models\Contractor;
use Illuminate\Http\Request;
use App\Models\ContractorType;
use App\Events\ContractorAdded;
use App\Models\ContractorProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ContractorsController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Contractors';

        $contractors = Contractor::orderBy('name', 'asc')->with('profile', 'contractorType')->paginate(10);

        $contractorTypes = ContractorType::all();

        $keywords = "";
        $status = "";
        $contractorType = "";

        return view('admin.contractors.index', compact('page', 'contractors', 'status', 'keywords', 'contractorTypes', 'contractorType'));
    }

    public function create() 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Contractor';

        $contractorTypes = ContractorType::all();

        return view('admin.contractors.create', compact('page', 'contractorTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' =>  'required',
            'email' => 'required|email|unique:contractors',
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'title' => 'required',
            'contact_type' => 'required',
            'contractorTypes' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $contractor = Contractor::create([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'deleted_at' => $request->status == 'Active' ? null : now(),
            'country' => $request->country,
            'line1' => $request->address_line_1,
            'line2' => $request->address_line_2,
            'line3' => $request->address_line_3,
            'city' => $request->city,
            'county' => $request->county,
            'postcode' => $request->postal_code, 
            'status' => $request->status,
            'note' => $request->note, 
            'company_name' => $request->company_name,
            'work_phone' => $request->work_phone,
            'fax' => $request->fax,
            'contact_type' => $request->contact_type,
            'title' => $request->title,
            'contractor_type_id' => $request->contractorTypes,
        ]);

        if (!$contractor) {
            return redirect()->back()
                ->withFlashMessage('Failed to create contractor.')
                ->withFlashType('errors');
        }

        $contractor_profile = ContractorProfile::create([
            'contractor_id' => $contractor->id,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');

            $directory = public_path('uploads/contractor-' . $contractor->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();
            $profile_image->move($directory, $profile_image_name);

            $contractor_profile->update([
                'profile_image' => $profile_image_name,
            ]);
        }

        if($contractor->deleted_at == null) {
            event(new ContractorAdded($contractor));
        }

        return redirect()
            ->route('admin.settings.contractors')
            ->withFlashMessage('Contractor created successfully! User will receive an email for verification')
            ->withFlashType('success');
    }

    public function edit($id) 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Contractor';

        $contractor = Contractor::where('id', $id)->first();

        $contractorTypes = ContractorType::all();

        return view('admin.contractors.edit', compact('page', 'contractor', 'contractorTypes'));
    }

    public function editAddress($id) 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Contractor';

        $contractor = Contractor::where('id', $id)->first();

        return view('admin.contractors.address.index', compact('page', 'contractor'));
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
            'contractorTypes' => 'required',
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
            'contractor_type_id' => $request->contractorTypes,
            'note' => $request->note,
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
            ->route('admin.settings.contractors')
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
        ]);
        return redirect()
            ->route('admin.settings.contractors')
            ->withFlashMessage('Contractor updated successfully!')
            ->withFlashType('success');
    }

    public function delete($id)
    {
        $contractor = Contractor::where('id', $id)->first();

        if (!$contractor) {
            return redirect()
                ->route('admin.settings.contractors')
                ->withFlashMessage('Contractor not found!')
                ->withFlashType('errors');
        }

        // Permanently delete the contractor
        $deleted = $contractor->forceDelete();

        if ($deleted) {
            return redirect()
                ->route('admin.settings.contractors')
                ->withFlashMessage('Contractor deleted successfully!')
                ->withFlashType('success');
        } else {
            return redirect()
                ->route('admin.settings.contractors')
                ->withFlashMessage('Oops! Something went wrong')
                ->withFlashType('errors');
        }
    }

    public function searchData(Request $request)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Contractors';

        $status = $request['status'];
        $keywords = $request['keywords'];
        $contractorType = $request['contractorType'];

        $query = Contractor::with('profile')->orderBy('name', 'asc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($contractorType) {
            $query->where('contractor_type_id', $contractorType);
        }

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%')
                ->orWhere('email', 'like', '%' . $keywords . '%')
                ->orWhere('id', intval($keywords)) 
                ->orWhereHas('profile', function ($q) use ($keywords) {
                    $q->where('phone_number', 'like', '%' . $keywords . '%');
                });
            });
        }

        $contractors = $query->orderBy('name', 'asc')->paginate(10);

        $contractorTypes = ContractorType::all();

        return view('admin.contractors.index', compact('page', 'status', 'contractors', 'keywords', 'contractorType', 'contractorTypes'));
    }

    public function jobs($id)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'View Contractor Jobs';

        $jobs = Jobs::whereHas('jobDetail', function($query) use ($id) {
            $query->where('contractor_id', $id);
        })->with('property', 'contractor', 'jobDetail')->paginate(10); 
        $contractor_id = $id;

        return view('admin.contractors.jobs.index', compact('page', 'jobs', 'contractor_id'));
    }

    public function invoices($id)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'View Contractor Invoices';

        $invoices = Invoices::where('contractor_id', $id)->with('property', 'job')->paginate(10);
        $contractor_id = $id;

        return view('admin.contractors.invoices.index', compact('page', 'invoices', 'contractor_id'));
    }
}