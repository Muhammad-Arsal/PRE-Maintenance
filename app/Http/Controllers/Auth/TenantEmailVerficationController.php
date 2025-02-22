<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TenantEmailVerfication;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class TenantEmailVerficationController extends Controller
{
    public function verify(Request $request, $id, $token) {

        $page['title'] = 'Tenant New Password';

        if (!$request->hasValidSignature()) {
           return abort(404);
        }

        $check = TenantEmailVerfication::where('tenant_id', $id)->where('token', $token)->first();
        if(!$check) {
            return abort(404);
        }

        return view('tenant.auth.new-password', compact('page', 'id', 'token'));
    }

    public function store(Request $request, $id, $token) {


        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $verified = TenantEmailVerfication::where('tenant_id', $id)->where('token', $token)->first();

        if($verified == '') {
            return abort(404);
        }

        $tenant = Tenant::where('id', $id)->update([
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($request->password),
        ]);

        if($tenant) {
            $verificationDel = TenantEmailVerfication::where('tenant_id', $id)->where('token', $token)->delete();
            $tenant = Tenant::find($id);
        }

        if($tenant) {
            return redirect()->route('tenant.login')->with('status', 'Your Password has been successfully reset, You can now login');
        }
    }
}
