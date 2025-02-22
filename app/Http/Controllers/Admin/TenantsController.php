<?php

namespace App\Http\Controllers\Admin;

use App\Events\TenantAdded;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TenantsController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Tenants';
    
        $tenants = Tenant::orderBy('name', 'asc')->with('profile')->paginate(10);
    
        $searchTenants = Tenant::orderBy('name', 'asc')->get();
    
        $keywords = "";
        $searchTenant = "";
    
        return view('admin.tenants.index', compact('page', 'tenants', 'searchTenants', 'searchTenant', 'keywords'));
    }
    
    public function create() 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Tenant';
    
        return view('admin.tenants.create', compact('page'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'email' => 'required|email|unique:tenants',
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048', // Optional, check for image
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
    
        $tenant = Tenant::create([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'deleted_at' => $request->status == 'Active' ? null : now(),
            'status' =>  $request->status,
        ]);
    
        if (!$tenant) {
            return redirect()->back()
                ->withFlashMessage('Failed to create tenant.')
                ->withFlashType('errors');
        }
    
        // Create tenant profile
        $tenant_profile = TenantProfile::create([
            'tenant_id' => $tenant->id,
            'phone_number' => $request->phone_number,
        ]);
    
        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');
    
            // Create directory if it doesn't exist
            $directory = public_path('uploads/tenant-' . $tenant->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
    
            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();
    
            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);
    
            // Update tenant profile with image path
            $tenant_profile->update([
                'profile_image' => $profile_image_name,
            ]);
        }
    
        if($tenant->deleted_at == null) {
            event(new TenantAdded($tenant));
        }
    
        return redirect()
            ->route('admin.settings.tenants')
            ->withFlashMessage('Tenant created successfully! User will receive an email for verification')
            ->withFlashType('success');
    }
    
    public function edit($id) 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Tenant';
    
        $tenant = Tenant::where('id', $id)->first();
    
        return view('admin.tenants.edit', compact('page', 'tenant'));
    }
    
    public function update(Request $request, $id)
    {
        $tenant = Tenant::where('id', $id)->first();
    
        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $tenant->update([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'deleted_at' => $request->status == 'Active' ? null : now(),
            'status' =>  $request->status,
        ]);
    
        if ($request->filled('password')) {
            $tenant->update([
                'password' => Hash::make($request->password),
            ]);
        }
    
        $tenant->profile()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            ['phone_number' => $request->phone_number]
        );
    
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');
    
            // Create directory if it doesn't exist
            $directory = public_path('uploads/tenant-' . $tenant->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
    
            if (isset($tenant->profile) && !empty($tenant->profile->profile_image)) {
                $existingProfileImage = $directory . $tenant->profile->profile_image;
                if (file_exists($existingProfileImage)) unlink($existingProfileImage);
            }
    
            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();
    
            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);
    
            // Update profile image in tenant profile
            $tenant->profile()->update([
                'profile_image' => $profile_image_name,
            ]);
        }
    
        return redirect()
            ->route('admin.settings.tenants')
            ->withFlashMessage('Tenant updated successfully!')
            ->withFlashType('success');
    }    


    // public function destroy($id)
    // {
    //     // Find the tenant including trashed ones
    //     $tenant = Tenant::find($id);

    //     if (!$tenant) {
    //         return redirect()
    //             ->route('admin.settings.tenants')
    //             ->withFlashMessage('Tenant not found!')
    //             ->withFlashType('errors');
    //     }

    //     // Check if the tenant is already trashed
    //     if ($tenant->trashed()) {
    //         $tenant->restore();
    //     } else {
    //         $tenant->delete();
    //     }
    //     return redirect()
    //         ->route('admin.settings.tenants')
    //         ->withFlashMessage('Tenant status changed successfully!')
    //         ->withFlashType('success');
    // }

    public function delete($id)
    {
        $tenant = Tenant::where('id', $id)->first();

        if (!$tenant) {
            return redirect()
                ->route('admin.settings.tenants')
                ->withFlashMessage('Tenant not found!')
                ->withFlashType('errors');
        }

        // Permanently delete the tenant
        $deleted = $tenant->forceDelete();

        if ($deleted) {
            return redirect()
                ->route('admin.settings.tenants')
                ->withFlashMessage('Tenant deleted successfully!')
                ->withFlashType('success');
        } else {
            return redirect()
                ->route('admin.settings.tenants')
                ->withFlashMessage('Oops! Something went wrong')
                ->withFlashType('errors');
        }
    }

    public function searchData(Request $request)
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Tenants';

        $searchTenant = $request['tenant'];
        $keywords = $request['keywords'];

        $query = Tenant::with('profile')->orderBy('name', 'asc');

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%')
                ->orWhere('email', 'like', '%' . $keywords . '%')
                ->orWhereHas('profile', function ($q) use ($keywords) {
                    $q->where('phone_number', 'like', '%' . $keywords . '%');
                });
            });
        }

        $tenants = $query->orderBy('name', 'asc')->paginate(10);

        $searchTenants = Tenant::orderBy('name', 'asc')->get();

        return view('admin.tenants.index', compact('page', 'searchTenants', 'searchTenant', 'tenants', 'keywords'));
    }
}