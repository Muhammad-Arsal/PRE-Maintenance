<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\TenantProfile;
use App\Models\TenantDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class ProfileController extends Controller
{
    public function __construct(){
        if(Auth::guard('tenant')->id() != (int)Route::current()->id){
            redirect()->route(Route::current()->getName(), auth('tenant')->id())->send();
        }
    }
    public function showProfileForm() {
        $page['page_title'] = 'Manage Tenant Profile';

        $tenant = Auth::guard('tenant')->user();

        return view('tenant.profile', compact('page', 'tenant'));
    }

    public function updateProfile(Request $request){
        if($request->has('tenant_id')){
            $id =  base64_decode($request->get('tenant_id'));

            $validator = Validator::make($request->all(),[
                'fname' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email|unique:tenants,id,',$id,
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


        if($request->has('tenant_id')){

            $id =  base64_decode($request->get('tenant_id'));

            $tenant  =  Tenant::where('id', $id)->first();


            if(!empty($request->get('password')))   $tenant->pass = Hash::make($data['password']);

            Tenant::where('id', $id)->update([
                'name' => $data['fname'].' '.$data['lname'],
                'email' => $data['email'],
                // 'status' => $data['status'],
            ]);


            if(!empty($request->get('password'))) Tenant::where('id', $id)->update(['password' => Hash::make($data['password'])]);

            if(isset($tenant->profile)){
                $tenant_profile = TenantProfile::where('tenant_id', $id)->update([
                    'phone_number' => $data['phone_number'],
                ]);
            }else{
                $tenant_profile = TenantProfile::create([
                    'tenant_id' => $id,
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
                    $directory = public_path('uploads/tenant-'.$id.'/');
                    if(!$directory) mkdir($directory,0777);

                        if(isset($tenant->profile) && !empty($tenant->profile->profile_image)){
                        $existingProfileImage = $directory.$tenant->profile->profile_image;
                        if(file_exists($existingProfileImage)) unlink($existingProfileImage);
                    }
                    $profile =  base64_encode('profile_image-'.time()).'.'.$ext;
                    $profile_image->move($directory, $profile);

                        $tenant_profile = TenantProfile::where('tenant_id', $id)->update([
                            'profile_image' => $profile,
                        ]);
                }

            }
            return redirect()
            ->route( 'tenant.profile' )
            ->withFlashMessage( 'profile updated successfully!' )
            ->withFlashType( 'success' );
        }
    }

    public function edit($id) 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'Edit Tenant';
    
        $tenant = Tenant::where('id', $id)->first();

        $properties = Property::whereNull('tenant_id')->where('status', 'Active')->orWhere('tenant_id', $id)->get();

        $tenantDetails = TenantDetails::where('tenant_id', $id)->get();
    
        return view('tenant.profile.edit', compact('page', 'tenant', 'properties', 'tenantDetails'));
    }

    public function editProperty($id) 
    {
        $page['page_title'] = 'Manage Tenants';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('tenant.dashboard');
        $page['page_current'] = 'Edit Tenant';
    
        $tenant = Tenant::where('id', $id)->first();

        $properties = Property::whereNull('tenant_id')->where('status', 'Active')->orWhere('tenant_id', $id)->get();

        $tenantDetails = TenantDetails::where('tenant_id', $id)->get();
    
        return view('tenant.profile.property.index', compact('page', 'tenant', 'properties', 'tenantDetails'));
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
                ->route('tenant.settings.tenants.edit', auth('tenant')->id())
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
            Property::where('tenant_id', $tenant->id)->update([
                'tenant_id' => null
            ]);
        
            Property::where('id', $request->property)->update([
                'tenant_id' => $tenant->id
            ]);
        }        

        return redirect()
        ->route('tenant.settings.tenants.edit', auth('tenant')->id())
        ->withFlashMessage('Tenant updated successfully!')
        ->withFlashType('success');
    }
}
