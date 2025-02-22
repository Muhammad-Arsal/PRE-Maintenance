<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminAdded;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    public function index(){
        $page['page_title'] = 'Manage Admins';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Admins';

        $admins = Admin::orderBy('name', 'asc')->with('profile')->paginate(10);

        $searchAdmins = Admin::orderBy('name', 'asc')->get();

        $keywords = "";
        $searchAdmin = "";

        return view('admin.settings.admins.index', compact('page','admins', 'searchAdmins', 'searchAdmin', 'keywords'));
    }

    public function create() {
        $page['page_title'] = 'Manage Admins';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Admins';

        return view('admin.settings.admins.create', compact('page'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'email' => 'required|email|unique:admins',
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048', // Optional, check for image
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $admin = Admin::create([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'deleted_at' => $request->status == 'Active' ? null : now(),
        ]);

        if (!$admin) {
            return redirect()->back()
                ->withFlashMessage('Failed to create admin.')
                ->withFlashType('errors');
        }

        // Create admin profile
        $admin_profile = AdminProfile::create([
            'admin_id' => $admin->id,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');

            // Create directory if it doesn't exist
            $directory = public_path('uploads/admin-' . $admin->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();

            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);

            // Update admin profile with image path
            $admin_profile->update([
                'profile_image' => $profile_image_name,
            ]);
        }

        if($admin->deleted_at == null) {
            event(new AdminAdded($admin));
        }

        return redirect()
            ->route('admin.settings.admins')
            ->withFlashMessage('Admin created successfully! User will recieve an email for verification')
            ->withFlashType('success');
    }

    public function edit($id) {
        $page['page_title'] = 'Manage Admins';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Admin';

        $admin = Admin::where('id', $id)->first();

        return view('admin.settings.admins.edit', compact('page', 'admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::where('id', $id)->first();

        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $admin->update([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'deleted_at' => $request->status == 'Active' ? null : now(),
        ]);

        if ($request->filled('password')) {
            $admin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $admin->profile()->updateOrCreate(
            ['admin_id' => $admin->id],
            ['phone_number' => $request->phone_number]
        );

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');

            // Create directory if it doesn't exist
            $directory = public_path('uploads/admin-' . $admin->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if(isset($admin->profile) && !empty($admin->profile->profile_image)){
                $existingProfileImage = $directory.$admin->profile->profile_image;
                if(file_exists($existingProfileImage)) unlink($existingProfileImage);
            }

            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();

            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);

            // Update profile image in admin profile
            $admin->profile()->update([
                'profile_image' => $profile_image_name,
            ]);
        }

        return redirect()
            ->route('admin.settings.admins')
            ->withFlashMessage('Admin updated successfully!')
            ->withFlashType('success');
    }

    public function destroy($id)
    {
        // Find the deal including trashed ones
        $admin = Admin::find($id);

        if($admin->id == Auth::guard('admin')->user()->id) {
            return redirect()
            ->route('admin.settings.admins')
            ->withFlashMessage('You cannot deactivate a logged in user')
            ->withFlashType('errors');
        }

        if (!$admin) {
            return redirect()
                ->route('admin.settings.admins')
                ->withFlashMessage('Admin not found!')
                ->withFlashType('errors');
        }

        // Check if the deal is already trashed
        if ($admin->trashed()) {
            $admin->restore();
        } else {
            $admin->delete();
        }

        return redirect()
            ->route('admin.settings.admins')
            ->withFlashMessage('Admin status changed successfully!')
            ->withFlashType('success');
    }

    public function delete($id)
    {
        $admin = Admin::where('id', $id)->first();

        if($admin->id == Auth::guard('admin')->user()->id) {
            return redirect()
            ->route('admin.settings.admins')
            ->withFlashMessage('You cannot delete a logged in user')
            ->withFlashType('errors');
        }

        if (!$admin) {
            return redirect()
                ->route('admin.settings.admins')
                ->withFlashMessage('Admin not found!')
                ->withFlashType('errors');
        }

        // Permanently delete the deal
        $deleted = $admin->forceDelete();

        if ($deleted) {
            return redirect()
                ->route('admin.settings.admins')
                ->withFlashMessage('Admin deleted successfully!')
                ->withFlashType('success');
        } else {
            return redirect()
                ->route('admin.settings.admins')
                ->withFlashMessage('Oops! Something went wrong')
                ->withFlashType('errors');
        }
    }

    public function searchData(Request $request){

        $page['page_title'] = 'Manage Admins';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Admins';

        $searchAdmin =  $request['admin'];
        $keywords = $request['keywords'];

        $query = Admin::with('profile')->orderBy('name', 'asc');

        if ($keywords) {
            $query->where(function($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%')
                  ->orWhere('email', 'like', '%' . $keywords . '%')
                  ->orWhereHas('profile', function ($q) use ($keywords) {
                      $q->where('phone_number', 'like', '%' . $keywords . '%');
                  });
            });
        }


        $admins = $query->orderBy('name', 'asc')->paginate('10');

        $searchAdmins = Admin::orderBy('name', 'asc')->get();

        return view('admin.settings.admins.index', compact('page', 'searchAdmins', 'searchAdmin', 'admins', 'keywords'));
    }
}
