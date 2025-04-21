<?php

namespace App\Http\Controllers\Admin;

use App\Events\ContractorAdded;
use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\ContractorProfile;
use App\Models\Jobs;
use Illuminate\Http\Request;
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

        $contractors = Contractor::orderBy('name', 'asc')->with('profile')->paginate(10);

        $keywords = "";
        $status = "";

        return view('admin.contractors.index', compact('page', 'contractors', 'status', 'keywords'));
    }

    public function create() 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Contractor';

        return view('admin.contractors.create', compact('page'));
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

        return view('admin.contractors.edit', compact('page', 'contractor'));
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
            'note' => $request->note, 
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

        $query = Contractor::with('profile')->orderBy('name', 'asc');

        if ($status) {
            $query->where('status', $status);
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

        return view('admin.contractors.index', compact('page', 'status', 'contractors', 'keywords'));
    }

    public function jobs($id)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'View Contractor Jobs';

        $jobs = Jobs::where('contractor_id',$id)->with('property', 'contractor')->paginate(10);
        $contractor_id = $id;

        return view('admin.contractors.jobs.index', compact('page', 'jobs', 'contractor_id'));
    }
}