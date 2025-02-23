<?php

namespace App\Http\Controllers\Admin;

use App\Events\LandlordAdded;
use App\Http\Controllers\Controller;
use App\Models\Landlord;
use App\Models\LandlordProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LandlordsController extends Controller
{
    public function index()
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Landlords';

        $landlords = Landlord::orderBy('name', 'asc')->with('profile')->paginate(10);

        $keywords = "";
        $status = "";

        return view('admin.landlords.index', compact('page', 'landlords', 'keywords', 'status'));
    }

    public function create() 
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Add Landlord';

        return view('admin.landlords.create', compact('page'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:landlords',
            'status' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'commission_rate' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $landlord = Landlord::create([
            'name' => $request->fname . ' ' . $request->lname,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'deleted_at' => $request->status == 'Active' ? null : now(),
            'title' => $request->title,
            'company_name' => $request->company_name,
            'work_phone' => $request->work_phone,
            'home_phone' => $request->home_phone,
            'commission_rate' => $request->commission_rate,
            'country' => $request->country,
            'line1' => $request->address_line_1,
            'line2' => $request->address_line_2,
            'line3' => $request->address_line_3,
            'city' => $request->city,
            'county' => $request->county,
            'postcode' => $request->postal_code, 
            'status' => $request->status,
            'note' => $request->note, 
        ]);

        if (!$landlord) {
            return redirect()->back()
                ->withFlashMessage('Failed to create landlord.')
                ->withFlashType('errors');
        }

        // Create landlord profile
        $landlord_profile = LandlordProfile::create([
            'landlord_id' => $landlord->id,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->hasFile('profile_image')) {
            $profile_image = $request->file('profile_image');

            // Create directory if it doesn't exist
            $directory = public_path('uploads/landlord-' . $landlord->id . '/');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Generate a unique filename
            $profile_image_name = uniqid('profile_image_') . '.' . $profile_image->getClientOriginalExtension();

            // Move the image to the directory
            $profile_image->move($directory, $profile_image_name);

            // Update landlord profile with image path
            $landlord_profile->update([
                'profile_image' => $profile_image_name,
            ]);
        }

        if ($landlord->deleted_at == null) {
            event(new LandlordAdded($landlord));
        }

        return redirect()
            ->route('admin.settings.landlords')
            ->withFlashMessage('Landlord created successfully! User will receive an email for verification')
            ->withFlashType('success');
    }

    public function edit($id) 
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Edit Landlord';

        $landlord = Landlord::where('id', $id)->first();

        return view('admin.landlords.edit', compact('page', 'landlord'));
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
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
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
            'country' => $request->country,
            'line1' => $request->address_line_1,
            'line2' => $request->address_line_2,
            'line3' => $request->address_line_3,
            'city' => $request->city,
            'county' => $request->county,
            'postcode' => $request->postal_code, 
            'status' => $request->status,
            'note' => $request->note, 
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
            ->route('admin.settings.landlords')
            ->withFlashMessage('Landlord updated successfully!')
            ->withFlashType('success');
    }

    // public function destroy($id)
    // {
    //     // Find the landlord including trashed ones
    //     $landlord = Landlord::find($id);

    //     if (!$landlord) {
    //         return redirect()
    //             ->route('admin.settings.landlords')
    //             ->withFlashMessage('Landlord not found!')
    //             ->withFlashType('errors');
    //     }

    //     // Check if the landlord is already trashed
    //     if ($landlord->trashed()) {
    //         $landlord->restore();
    //     } else {
    //         $landlord->delete();
    //     }

    //     return redirect()
    //         ->route('admin.settings.landlords')
    //         ->withFlashMessage('Landlord status changed successfully!')
    //         ->withFlashType('success');
    // }

    public function delete($id)
    {
        $landlord = Landlord::where('id', $id)->first();

        if (!$landlord) {
            return redirect()
                ->route('admin.settings.landlords')
                ->withFlashMessage('Landlord not found!')
                ->withFlashType('errors');
        }

        // Permanently delete the landlord
        $deleted = $landlord->forceDelete();

        if ($deleted) {
            return redirect()
                ->route('admin.settings.landlords')
                ->withFlashMessage('Landlord deleted successfully!')
                ->withFlashType('success');
        } else {
            return redirect()
                ->route('admin.settings.landlords')
                ->withFlashMessage('Oops! Something went wrong')
                ->withFlashType('errors');
        }
    }

    public function searchData(Request $request)
    {
        $page['page_title'] = 'Manage Landlords';
        $page['page_parent'] = 'Home';
        $page['page_parent_link'] = route('admin.dashboard');
        $page['page_current'] = 'Landlords';

        $status = $request->status;
        $keywords = $request->keywords;

        $query = Landlord::with('profile')->orderBy('name', 'asc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%')
                ->orWhere('email', 'like', '%' . $keywords . '%')
                ->orWhereHas('profile', function ($q) use ($keywords) {
                    $q->where('phone_number', 'like', '%' . $keywords . '%');
                });
            });
        }

        $landlords = $query->orderBy('name', 'asc')->paginate(10);

        return view('admin.landlords.index', compact('page', 'status', 'landlords', 'keywords'));
    }

}