<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
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
}
