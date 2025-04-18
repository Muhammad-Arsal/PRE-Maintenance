<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function showProfileForm() {
        $page['page_title'] = 'Manage Admin Profile';

        $admin = Auth::guard('admin')->user();

        return view('admin.profile', compact('page', 'admin'));
    }

    public function updateProfile(Request $request){
        if($request->has('admin_id')){
            $id =  base64_decode($request->get('admin_id'));

            $validator = Validator::make($request->all(),[
                'fname' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email|unique:admins,id,',$id,
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


        if($request->has('admin_id')){

            $id =  base64_decode($request->get('admin_id'));

            $admin  =  Admin::where('id', $id)->first();


            if(!empty($request->get('password')))   $admin->pass = Hash::make($data['password']);

            Admin::where('id', $id)->update([
                'name' => $data['fname'].' '.$data['lname'],
                'email' => $data['email'],
                // 'status' => $data['status'],
            ]);


            if(!empty($request->get('password'))) Admin::where('id', $id)->update(['password' => Hash::make($data['password'])]);

            if(isset($admin->profile)){
                $admin_profile = AdminProfile::where('admin_id', $id)->update([
                    'phone_number' => $data['phone_number'],
                ]);
            }else{
                $admin_profile = AdminProfile::create([
                    'admin_id' => $id,
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
                    $directory = public_path('uploads/admin-'.$id.'/');
                    if(!$directory) mkdir($directory,0777);

                        if(isset($admin->profile) && !empty($admin->profile->profile_image)){
                        $existingProfileImage = $directory.$admin->profile->profile_image;
                        if(file_exists($existingProfileImage)) unlink($existingProfileImage);
                    }
                    $profile =  base64_encode('profile_image-'.time()).'.'.$ext;
                    $profile_image->move($directory, $profile);

                        $admin_profile = AdminProfile::where('admin_id', $id)->update([
                            'profile_image' => $profile,
                        ]);
                }

            }
            return redirect()
            ->route( 'admin.profile' )
            ->withFlashMessage( 'profile updated successfully!' )
            ->withFlashType( 'success' );
        }
    }
}
