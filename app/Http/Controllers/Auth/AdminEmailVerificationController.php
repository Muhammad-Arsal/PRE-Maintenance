<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminEmailVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminEmailVerificationController extends Controller
{
    public function verify(Request $request, $id, $token) {

        $page['title'] = 'Admin New Password';

        if (!$request->hasValidSignature()) {
           return abort(404);
        }

        $check = AdminEmailVerification::where('admin_id', $id)->where('token', $token)->first();
        if(!$check) {
            return abort(404);
        }

        return view('admin.auth.new-password', compact('page', 'id', 'token'));
    }

    public function store(Request $request, $id, $token) {


        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $verified = AdminEmailVerification::where('admin_id', $id)->where('token', $token)->first();

        if($verified == '') {
            return abort(404);
        }

        $admin = Admin::where('id', $id)->update([
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($request->password),
        ]);

        if($admin) {
            $verificationDel = AdminEmailVerification::where('admin_id', $id)->where('token', $token)->delete();
            $admin = Admin::find($id);
        }

        if($admin) {
            return redirect()->route('admin.login')->with('status', 'Your Password has been successfully reset, You can now login');
        }

    }
}
