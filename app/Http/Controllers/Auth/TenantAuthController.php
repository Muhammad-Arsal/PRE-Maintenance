<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class TenantAuthController extends Controller
{
    public function __construct(){

    }

    public function login(){
        if(Auth::guard('tenant')->check() )
        {
            return redirect()->route('tenant.dashboard');
        }

        $page['page_title'] = 'Login';

        return view('tenant.auth.login',compact('page'));

    }



    public function tenantLogin(Request $request){

        $request->validate([
            'email' => ['required',
               function($attribute, $value, $fail){
                   $tenant = Tenant::where('email', $value)->first();
                   if(isset($tenant)){
                       if($tenant->deleted_at != null) {
                           $fail('Your account has been deactivated');
                       }
                   }
               }
           ],
            'password' => 'required',
        ]);
        $credentials = $request->only('email','password');

        $remember_me = $request->has('remember') ? true : false;

        if(Auth::guard('tenant')->attempt($credentials, $remember_me)){
            $name = explode(' ',Auth::guard('tenant')->user()->name );

            $welcome = 'Welcome '.$name[0].', great to see you again!';

            Tenant::where('id',Auth::guard('tenant')->user()->id)->update(['last_logged_in'=>now()]);
            return redirect()->route('tenant.dashboard')->withSuccess($welcome);
        }

        return redirect()->route('tenant.login')->with('error', 'Email or Password is incorrect!');
   }



    public function tenantLogout() {
        $name = explode(' ',Auth::guard('tenant')->user()->name );

        $message = "Good to see you again " . $name[0] . ", take care and see you soon";
        Auth::guard('tenant')->logout();

        return redirect()->route('tenant.login')->withSuccess($message);

    }



    public function showLinkRequestForm(Request $request)

    {

         $page['page_title'] = 'Forgot Password';



        return view('tenant.auth.forget-password',compact('page'));

    }



    public function forgetPassword(Request $request) {

        $request->validate(['email' => 'required|email|exists:tenants']);



        $status = Password::broker('tenants')->sendResetLink(

            $request->only('email')

        );





        return $status === Password::RESET_LINK_SENT

                    ? back()->with(['status' => __($status)])

                    : back()->withErrors(['email' => __($status)]);

    }

    public function showResetForm($token) {

        // return Hash::make($token);

        $email = request()->get('email');

        $check = \DB::table('tenants_password_resets')->where('email', $email)->first();

        if(!$check) {

            abort(404);

        }



        if($check) {

            $date = date(strtotime($check->created_at));

            if(time() - $date > 60 * 60) {

                 abort(404);

            }

        }

        $page['page_title'] = 'Reset Password';

        return view('tenant.auth.reset-password', ['email' => $email, 'token' => $token,'page'=> $page]);

    }



    public function passwordUpdate(Request $request) {

        $request->validate([

            'token' => 'required',

            'email' => 'required|email',

            'password' => 'required|min:6|confirmed',

        ]);

        $status = Password::broker('tenants')->reset(

            $request->only('email', 'password', 'password_confirmation', 'token'),

            function ($user, $password) {

                $user->forceFill([

                    'password' => Hash::make($password)

                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }

        );



        return $status === Password::PASSWORD_RESET

                    ? redirect()->route('tenant.login')->with('status', __($status))

                    : back()->withErrors(['email' => [__($status)]]);
    }
}