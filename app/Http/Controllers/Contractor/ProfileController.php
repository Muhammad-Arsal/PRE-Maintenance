<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Models\Contractor;
use App\Models\Jobs;
use App\Models\ContractorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class ProfileController extends Controller
{
    public function __construct()
    {
        $currentRouteId = (int)Route::current()->id;
        $authTenantId = Auth::guard('contractor')->id();

        if ($authTenantId !== $currentRouteId) {
            redirect()->route(Route::currentRouteName(), ['id' => $authTenantId])->send();
        }
    }
    public function showProfileForm() {
        $page['page_title'] = 'Manage Contractor Profile';

        $contractor = Auth::guard('contractor')->user();

        return view('contractor.profile', compact('page', 'contractor'));
    }

    public function updateProfile(Request $request){
        if($request->has('contractor_id')){
            $id =  base64_decode($request->get('contractor_id'));

            $validator = Validator::make($request->all(),[
                'fname' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email|unique:contractors,id,',$id,
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


        if($request->has('contractor_id')){

            $id =  base64_decode($request->get('contractor_id'));

            $contractor  =  Contractor::where('id', $id)->first();


            if(!empty($request->get('password')))   $contractor->pass = Hash::make($data['password']);

            Contractor::where('id', $id)->update([
                'name' => $data['fname'].' '.$data['lname'],
                'email' => $data['email'],
                // 'status' => $data['status'],
            ]);


            if(!empty($request->get('password'))) Contractor::where('id', $id)->update(['password' => Hash::make($data['password'])]);

            if(isset($contractor->profile)){
                $contractor_profile = ContractorProfile::where('contractor_id', $id)->update([
                    'phone_number' => $data['phone_number'],
                ]);
            }else{
                $contractor_profile = ContractorProfile::create([
                    'contractor_id' => $id,
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
                    $directory = public_path('uploads/contractor-'.$id.'/');
                    if(!$directory) mkdir($directory,0777);

                        if(isset($contractor->profile) && !empty($contractor->profile->profile_image)){
                        $existingProfileImage = $directory.$contractor->profile->profile_image;
                        if(file_exists($existingProfileImage)) unlink($existingProfileImage);
                    }
                    $profile =  base64_encode('profile_image-'.time()).'.'.$ext;
                    $profile_image->move($directory, $profile);

                        $contractor_profile = ContractorProfile::where('contractor_id', $id)->update([
                            'profile_image' => $profile,
                        ]);
                }

            }
            return redirect()
            ->route( 'contractor.profile' )
            ->withFlashMessage( 'profile updated successfully!' )
            ->withFlashType( 'success' );
        }
    }


    public function edit($id) 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'Edit Contractor';

        $contractor = Contractor::where('id', $id)->first();

        return view('contractor.profile.edit', compact('page', 'contractor'));
    }

    public function editAddress($id) 
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'Edit Contractor';

        $contractor = Contractor::where('id', $id)->first();

        return view('contractor.profile.address.index', compact('page', 'contractor'));
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
            ->route('contractor.settings.contractors.edit', auth('contractor')->user()->id)
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
            ->route('contractor.settings.contractors.edit.address', auth('contractor')->user()->id)
            ->withFlashMessage('Contractor updated successfully!')
            ->withFlashType('success');
    }

    public function jobs($id)
    {
        $page['page_title'] = 'Manage Contractors';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('contractor.dashboard');
        $page['page_current'] = 'View Contractor Jobs';

        $jobs = Jobs::whereJsonContains('contractor_details', [
            'contractor_id' => (string) $id
        ])->with('property', 'contractor')->paginate(10);     
        $contractor_id = $id;

        return view('contractor.profile.jobs.index', compact('page', 'jobs', 'contractor_id'));
    }
}
