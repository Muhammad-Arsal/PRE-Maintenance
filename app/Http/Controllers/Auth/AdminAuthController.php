<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function __construct(){

    }

    public function login(){
        if(Auth::guard('admin')->check() )
        {
            return redirect()->route('admin.dashboard');
        }

        $page['page_title'] = 'Login';

        return view('admin.auth.login',compact('page'));

    }



    public function adminLogin(Request $request){

        $request->validate([
            'email' => ['required',
               function($attribute, $value, $fail){
                   $admin = Admin::where('email', $value)->first();
                   if(isset($admin)){
                       if($admin->deleted_at != null) {
                           $fail('Your account has been deactivated');
                       }
                   }
               }
           ],
            'password' => 'required',
        ]);
        $credentials = $request->only('email','password');

        $remember_me = $request->has('remember') ? true : false;

        if(Auth::guard('admin')->attempt($credentials, $remember_me)){
            $name = explode(' ',Auth::guard('admin')->user()->name );

            $welcome = 'Welcome '.$name[0].', great to see you again!';

            Admin::where('id',Auth::guard('admin')->user()->id)->update(['last_logged_in'=>now()]);
            return redirect()->route('admin.dashboard')->withSuccess($welcome);
        }

        return redirect()->route('admin.login')->with('error', 'Email or Password is incorrect!');
   }



    public function adminLogout() {
        $name = explode(' ',Auth::guard('admin')->user()->name );

        $message = "Good to see you again " . $name[0] . ", take care and see you soon";
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->withSuccess($message);

    }



    public function showLinkRequestForm(Request $request)

    {

         $page['page_title'] = 'Forgot Password';



        return view('admin.auth.forget-password',compact('page'));

    }



    public function forgetPassword(Request $request) {

        $request->validate(['email' => 'required|email|exists:admins']);



        $status = Password::broker('admins')->sendResetLink(

            $request->only('email')

        );





        return $status === Password::RESET_LINK_SENT

                    ? back()->with(['status' => __($status)])

                    : back()->withErrors(['email' => __($status)]);

    }

    public function showResetForm($token) {

        // return Hash::make($token);

        $email = request()->get('email');

        $check = \DB::table('admin_password_resets')->where('email', $email)->first();

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

        return view('admin.auth.reset-password', ['email' => $email, 'token' => $token,'page'=> $page]);

    }



    public function passwordUpdate(Request $request) {

        $request->validate([

            'token' => 'required',

            'email' => 'required|email',

            'password' => 'required|min:6|confirmed',

        ]);

        $status = Password::broker('admins')->reset(

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

                    ? redirect()->route('admin.login')->with('status', __($status))

                    : back()->withErrors(['email' => [__($status)]]);
    }
}
