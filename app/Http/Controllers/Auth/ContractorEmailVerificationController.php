<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContractorEmailVerification;
use App\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ContractorEmailVerificationController extends Controller
{
    public function verify(Request $request, $id, $token) {

        $page['title'] = 'Contractor New Password';

        if (!$request->hasValidSignature()) {
           return abort(404);
        }

        $check = ContractorEmailVerification::where('contractor_id', $id)->where('token', $token)->first();
        if(!$check) {
            return abort(404);
        }

        return view('contractor.auth.new-password', compact('page', 'id', 'token'));
    }

    public function store(Request $request, $id, $token) {


        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $verified = ContractorEmailVerification::where('contractor_id', $id)->where('token', $token)->first();

        if($verified == '') {
            return abort(404);
        }

        $contractor = Contractor::where('id', $id)->update([
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($request->password),
        ]);

        if($contractor) {
            $verificationDel = ContractorEmailVerification::where('contractor_id', $id)->where('token', $token)->delete();
            $contractor = Contractor::find($id);
        }

        if($contractor) {
            return redirect()->route('contractor.login')->with('status', 'Your Password has been successfully reset, You can now login');
        }
    }
}
