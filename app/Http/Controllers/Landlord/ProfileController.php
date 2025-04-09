<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Landlord;
use App\Models\LandlordProfile;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;


class ProfileController extends Controller
{
    public function __construct(){
        if(Auth::guard('landlord')->id() != (int)Route::current()->id){
            redirect()->route(Route::current()->getName(), auth('landlord')->id())->send();
        }
    }
    public function showProfileForm() {
        $page['page_title'] = 'Manage Landlord Profile';

        $landlord = Auth::guard('landlord')->user();

        return view('landlord.profile', compact('page', 'landlord'));
    }

    public function updateProfile(Request $request){
        if($request->has('landlord_id')){
            $id =  base64_decode($request->get('landlord_id'));

            $validator = Validator::make($request->all(),[
                'fname' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email|unique:landlords,id,',$id,
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


        if($request->has('landlord_id')){

            $id =  base64_decode($request->get('landlord_id'));

            $landlord  =  Landlord::where('id', $id)->first();


            if(!empty($request->get('password')))   $landlord->pass = Hash::make($data['password']);

            Landlord::where('id', $id)->update([
                'name' => $data['fname'].' '.$data['lname'],
                'email' => $data['email'],
                // 'status' => $data['status'],
            ]);


            if(!empty($request->get('password'))) Landlord::where('id', $id)->update(['password' => Hash::make($data['password'])]);

            if(isset($landlord->profile)){
                $landlord_profile = LandlordProfile::where('landlord_id', $id)->update([
                    'phone_number' => $data['phone_number'],
                ]);
            }else{
                $landlord_profile = LandlordProfile::create([
                    'landlord_id' => $id,
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
                    $directory = public_path('uploads/landlord-'.$id.'/');
                    if(!$directory) mkdir($directory,0777);

                        if(isset($landlord->profile) && !empty($landlord->profile->profile_image)){
                        $existingProfileImage = $directory.$landlord->profile->profile_image;
                        if(file_exists($existingProfileImage)) unlink($existingProfileImage);
                    }
                    $profile =  base64_encode('profile_image-'.time()).'.'.$ext;
                    $profile_image->move($directory, $profile);

                        $landlord_profile = LandlordProfile::where('landlord_id', $id)->update([
                            'profile_image' => $profile,
                        ]);
                }

            }
            return redirect()
            ->route( 'landlord.profile' )
            ->withFlashMessage( 'profile updated successfully!' )
            ->withFlashType( 'success' );
        }
    }

    public function edit($id) 
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('landlord.dashboard');
        $page['page_current'] = 'Edit Landlord';

       
        $landlord = Landlord::where('id', $id)->first();

        return view('landlord.profile.edit', compact('page', 'landlord'));
    }

    public function address($id){
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('landlord.dashboard');
        $page['page_current'] = 'Edit Landlord';

        
        $landlord = Landlord::where('id', $id)->first();

        return view('landlord.profile.address.index', compact('page', 'landlord'));
    }

    public function bankDetails($id)
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('landlord.dashboard');
        $page['page_current'] = 'Edit Landlord';

       
        $landlord = Landlord::where('id', $id)->first();

        return view('landlord.profile.bank_details.index', compact('page', 'landlord'));
    }

    public function properties($id)
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('landlord.dashboard');
        $page['page_current'] = 'Edit Landlord';

        $landlord = Landlord::with('profile')->findOrFail($id);

        $properties = Property::where('landlord_id', $landlord->id)
                            ->with('tenant')
                            ->paginate(10);


        return view('landlord.profile.properties.index', compact('page', 'landlord', 'properties'));

    }

    public function update(Request $request, $id)
    {
        $landlord = Landlord::where('id', $id)->first();

        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:landlords,email,' . $landlord->id,
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'commission_rate' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $landlord->update([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'deleted_at' => $request->status == 'Active' ? null : now(),
            'title' => $request->title,
            'company_name' => $request->company_name,
            'work_phone' => $request->work_phone,
            'home_phone' => $request->home_phone,
            'commission_rate' => $request->commission_rate,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $landlord->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $landlord->profile()->updateOrCreate(
            ['landlord_id' => $landlord->id],
            ['phone_number' => $request->phone_number]
        );

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');

            // Create directory if it doesn't exist
            $directory = public_path('uploads/landlord-' . $landlord->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if (isset($landlord->profile) && !empty($landlord->profile->profile_image)) {
                $existingProfileImage = $directory . $landlord->profile->profile_image;
                if (file_exists($existingProfileImage)) unlink($existingProfileImage);
            }

            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();

            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);

            // Update profile image in landlord profile
            $landlord->profile()->update([
                'profile_image' => $profile_image_name,
            ]);
        }

        return redirect()
            ->route('landlord.settings.landlords.edit', auth('landlord')->id())
            ->withFlashMessage('Landlord updated successfully!')
            ->withFlashType('success');
    }

    public function updateAddress(Request $request, $id)
    {
        $landlord = Landlord::where('id', $id)->first();

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

        $landlord->update([
            'country' => $request->country,
            'line1' => $request->address_line_1,
            'line2' => $request->address_line_2,
            'line3' => $request->address_line_3,
            'city' => $request->city,
            'county' => $request->county,
            'postcode' => $request->postal_code, 
            'note' => $request->note, 
            'tax_exemption_code' => $request->tax_exemption_code,
            'overseas_landlord' => $request->overseas_landlord,
        ]);

        return redirect()
            ->route('landlord.settings.landlords.edit', auth('landlord')->id())
            ->withFlashMessage('Landlord updated successfully!')
            ->withFlashType('success');
    }

    public function updateBankDetails(Request $request, $id)
    {
        $landlord = Landlord::where('id', $id)->first();

        $landlord->update([
            'account_number' => $request->account_number,
            'sort_code' => $request->sort_code,
            'account_name' => $request->account_name,
            'bank' => $request->bank,
            'bank_address' => $request->bank_address,
        ]);

        return redirect()
            ->route('landlord.settings.landlords.edit', auth('landlord')->id())
            ->withFlashMessage('Landlord updated successfully!')
            ->withFlashType('success');
    }
}
