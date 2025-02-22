<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandlordEmailVerification;
use App\Models\Landlord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class LandlordEmailVerficationController extends Controller
{
    public function verify(Request $request, $id, $token) {

        $page['title'] = 'landlord New Password';

        if (!$request->hasValidSignature()) {
           return abort(404);
        }

        $check = LandlordEmailVerification::where('landlord_id', $id)->where('token', $token)->first();
        if(!$check) {
            return abort(404);
        }

        return view('landlord.auth.new-password', compact('page', 'id', 'token'));
    }

    public function store(Request $request, $id, $token) {


        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $verified = LandlordEmailVerification::where('landlord_id', $id)->where('token', $token)->first();

        if($verified == '') {
            return abort(404);
        }

        $landlord = Landlord::where('id', $id)->update([
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($request->password),
        ]);

        if($landlord) {
            $verificationDel = LandlordEmailVerification::where('landlord_id', $id)->where('token', $token)->delete();
            $landlord = Landlord::find($id);
        }

        if($landlord) {
            return redirect()->route('landlord.login')->with('status', 'Your Password has been successfully reset, You can now login');
        }
    }
}
