<?php

namespace App\Http\Controllers\Admin;

use App\Events\TenantAdded;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantProfile;
use App\Models\Property;
use App\Models\TenantDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TenantsController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Tenants';
    
        $tenants = Tenant::orderBy('name', 'asc')->with('profile','property')->paginate(10);
    
        $keywords = "";
        $status = "";
    
        return view('admin.tenants.index', compact('page', 'tenants', 'keywords', 'status'));
    }
    
    public function create() 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Tenant';

        $properties = Property::whereNull('tenant_id')->where('status', 'Active')->get();
    
        return view('admin.tenants.create', compact('page', 'properties'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048', // Optional, check for image
            'property' => 'required',
            'contract_start' =>'required',
            'contract_end' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $names = $request->name;
        $phone_numbers = $request->phone_number;
        $work_phones = $request->work_phone;
        $home_phones = $request->home_phone;
        $emails = $request->email;
        $primary_users = $request->primary_user;

        $length = count($names);

        try {
            DB::beginTransaction(); // Start the transaction
        
            // Validate that primary user email is unique before inserting
            if (!empty($request->primary_user)) {
                $primaryUserIndex = array_key_first($request->primary_user);
                $primaryEmail = $emails[$primaryUserIndex] ?? null;
        
                if ($primaryEmail) {
                    $existingTenant = Tenant::where('email', $primaryEmail)->first();
                    if ($existingTenant) {
                        throw ValidationException::withMessages(['email' => 'The primary user email must be unique.']);
                    }
                }
            }
        
            // Create the Tenant
            $tenant = Tenant::create([
                'password' => $request->password ? Hash::make($request->password) : null,
                'deleted_at' => $request->status == 'Active' ? null : now(),
                'status' =>  $request->status,
                'property_id' => $request->property,
                'contract_start' => $request->contract_start,
                'contract_end' => $request->contract_end,
                'deposit' => $request->deposit,
                'adjust' => $request->adjust,
                'left_property' => $request->date_left_property,
                'note' => $request->note,
            ]);

            if ($request->property) {
                Property::where('id', $request->property)->update([
                    'tenant_id' => $tenant->id
                ]);
            }

            // Iterate through the tenants
            for ($i = 0; $i < $length; $i++) {
                $name = $names[$i] ?? null;
                $phone_number = $phone_numbers[$i] ?? null;
                $work_phone = $work_phones[$i] ?? null;
                $home_phone = $home_phones[$i] ?? null;
                $email = $emails[$i] ?? null;
                $primary_user = $primary_users[$i] ?? null;
        
                if ($primary_user) {
                    // Update the primary tenant's information
                    $tenant->update([
                        'name' => $name,
                        'email' => $email,
                        'work_phone' => $work_phone,
                        'home_phone' => $home_phone,
                    ]);
        
                    if (!$tenant) {
                        throw new \Exception('Failed to update tenant.');
                    }
        
                    $tenant_profile = TenantProfile::create([
                        'tenant_id' => $tenant->id,
                        'phone_number' => $phone_number,
                    ]);
        
                    if ($request->hasFile('profile_image')) {
                        $profile_image = $request->file('profile_image');
        
                        $directory = public_path('uploads/tenant-' . $tenant->id . '/');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }
                        $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();
                        $profile_image->move($directory, $profile_image_name);
        
                        $tenant_profile->update([
                            'profile_image' => $profile_image_name,
                        ]);
                    }
        
                    if ($tenant->deleted_at == null) {
                        event(new TenantAdded($tenant));
                    }
                } else {
                    TenantDetails::create([
                        'tenant_id' => $tenant->id,
                        'name' => $name,
                        'email' => $email,
                        'phone_number' => $phone_number,
                        'work_phone' => $work_phone,
                        'home_phone' => $home_phone,
                    ]);
                }
            }
        
            DB::commit(); // Commit the transaction
        
            return redirect()
            ->route('admin.settings.tenants')
            ->withFlashMessage('Tenant created successfully! User will receive an email for verification')
            ->withFlashType('success');

        } catch (\Exception $e) {
            DB::rollback(); // Rollback if any error occurs
        
            return back()
            ->withFlashMessage('Error: ' . $e->getMessage())
            ->withFlashType('errors');        
        }
    }
    
    public function edit($id) 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Tenant';
    
        $tenant = Tenant::where('id', $id)->first();

        $properties = Property::whereNull('tenant_id')->where('status', 'Active')->orWhere('tenant_id', $id)->get();

        $tenantDetails = TenantDetails::where('tenant_id', $id)->get();
    
        return view('admin.tenants.edit', compact('page', 'tenant', 'properties', 'tenantDetails'));
    }

    public function editProperty($id) 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Tenant';
    
        $tenant = Tenant::where('id', $id)->first();

        $properties = Property::whereNull('tenant_id')->where('status', 'Active')->orWhere('tenant_id', $id)->get();

        $tenantDetails = TenantDetails::where('tenant_id', $id)->get();
    
        return view('admin.tenants.property.index', compact('page', 'tenant', 'properties', 'tenantDetails'));
    }
    
    public function update(Request $request, $id)
    {
        $tenant = Tenant::where('id', $id)->first();
    
        // Validation
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if (!empty($request->primary_user)) {
            $primaryUserIndex = array_key_first($request->primary_user);
            $primaryEmail = $request->email[$primaryUserIndex] ?? null;
            if ($primaryEmail) {
                $existingTenant = Tenant::where('email', $primaryEmail)->where("id", "!=", $tenant->id)->first();
                if ($existingTenant) {
                    return back()
                    ->withFlashMessage("The primary email address must be unique")
                    ->withFlashType('errors');  
                }
            }
        }
        try {
            DB::beginTransaction();

            $tenant->update([
                'deleted_at' => $request->status == 'Active' ? null : now(),
                'status' =>  $request->status,
            ]);

            if ($request->filled('password')) {
                $tenant->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        
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
    
            $names = $request->name;
            $phone_numbers = $request->phone_number;
            $work_phones = $request->work_phone;
            $home_phones = $request->home_phone;
            $emails = $request->email;
            $primary_users = $request->primary_user;
    
            TenantDetails::where('tenant_id', $tenant->id)->delete();

            $length = count($names);

            for ($i = 0; $i < $length; $i++) {
                $name = $names[$i] ?? null;
                $phone_number = $phone_numbers[$i] ?? null;
                $work_phone = $work_phones[$i] ?? null;
                $home_phone = $home_phones[$i] ?? null;
                $email = $emails[$i] ?? null;
                $primary_user = $primary_users[$i] ?? null;
        
                if ($primary_user) {
                    // Update the primary tenant's information
                    $tenant->update([
                        'name' => $name,
                        'email' => $email,
                        'work_phone' => $work_phone,
                        'home_phone' => $home_phone,
                    ]);

                    $tenant->profile()->updateOrCreate(
                        ['tenant_id' => $tenant->id],
                        ['phone_number' => $phone_number]
                    );
        
                    if (!$tenant) {
                        throw new \Exception('Failed to update tenant.');
                    }
                } else {
                    TenantDetails::create([
                        'tenant_id' => $tenant->id,
                        'name' => $name,
                        'email' => $email,
                        'phone_number' => $phone_number,
                        'work_phone' => $work_phone,
                        'home_phone' => $home_phone,
                    ]);
                }
            }
        
            DB::commit();
        
            return redirect()
                ->route('admin.settings.tenants')
                ->withFlashMessage('Tenant updated successfully!')
                ->withFlashType('success');    
        }catch (\Exception $e) {
            DB::rollback(); // Rollback if any error occurs
        
            return back()
            ->withFlashMessage('Error: ' . $e->getMessage())
            ->withFlashType('errors');        
        }
        
    }    

    public function storeProperty(Request $request, $id)
    {
        $tenant = Tenant::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            'property' => 'required',
            'contract_start' =>'required',
            'contract_end' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tenant->update([
            'property_id' => $request->property,
            'contract_start' => $request->contract_start,
            'contract_end' => $request->contract_end,
            'deposit' => $request->deposit,
            'left_property' => $request->date_left_property,
        ]);

        if ($request->property) {
            Property::where('id', $request->property)->update([
                'tenant_id' => $tenant->id
            ]);
        }

        return redirect()
        ->route('admin.settings.tenants')
        ->withFlashMessage('Tenant updated successfully!')
        ->withFlashType('success');
    }

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

        $status = $request->status;
        $keywords = $request['keywords'];

        $query = Tenant::with(['profile', 'property'])->orderBy('name', 'asc');

        if ($status) {
            $query->where('status', $status);
        }
        
        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('id', intval($keywords))
                  ->orWhereHas('property', function ($q) use ($keywords) {
                      $q->where('line1', 'like', '%' . $keywords . '%')
                        ->orWhere('city', 'like', '%' . $keywords . '%')
                        ->orWhere('county', 'like', '%' . $keywords . '%')
                        ->orWhere('postcode', 'like', '%' . $keywords . '%');
                  });
            });
        }           

        $tenants = $query->orderBy('name', 'asc')->paginate(10);

        return view('admin.tenants.index', compact('page', 'tenants', 'keywords', 'status'));
    }


    public function show($id){
        $page['page_title'] = 'View Tenants Details';

        $tenant = Tenant::where('id', $id)->with('profile','details', 'property')->first();
        
        return view('admin.tenants.show', compact('page', 'tenant'));
    }
}