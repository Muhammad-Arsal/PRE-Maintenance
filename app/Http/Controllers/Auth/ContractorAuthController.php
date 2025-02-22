<?php
namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contractor;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ContractorAuthController extends Controller
{
    public function __construct(){

    }

    public function login(){
        if(Auth::guard('contractor')->check() )
        {
            return redirect()->route('contractor.dashboard');
        }

        $page['page_title'] = 'Login';

        return view('contractor.auth.login',compact('page'));

    }



    public function contractorLogin(Request $request){

        $request->validate([
            'email' => ['required',
               function($attribute, $value, $fail){
                   $contractor = Contractor::where('email', $value)->first();
                   if(isset($contractor)){
                       if($contractor->deleted_at != null) {
                           $fail('Your account has been deactivated');
                       }
                   }
               }
           ],
            'password' => 'required',
        ]);

        $credentials = $request->only('email','password');

        $remember_me = $request->has('remember') ? true : false;

        if(Auth::guard('contractor')->attempt($credentials, $remember_me)){
            $name = explode(' ',Auth::guard('contractor')->user()->name );

            $welcome = 'Welcome '.$name[0].', great to see you again!';

            Contractor::where('id',Auth::guard('contractor')->user()->id)->update(['last_logged_in'=>now()]);
            
            return redirect()->route('contractor.dashboard')->withSuccess($welcome);
        }

        return redirect()->route('contractor.login')->with('error', 'Email or Password is incorrect!');
   }



    public function contractorLogout() {
        $name = explode(' ',Auth::guard('contractor')->user()->name );

        $message = "Good to see you again " . $name[0] . ", take care and see you soon";
        Auth::guard('contractor')->logout();

        return redirect()->route('contractor.login')->withSuccess($message);

    }



    public function showLinkRequestForm(Request $request)

    {

         $page['page_title'] = 'Forgot Password';



        return view('contractor.auth.forget-password',compact('page'));

    }



    public function forgetPassword(Request $request) {
        $request->validate(['email' => 'required|email|exists:contractors']);



        $status = Password::broker('contractors')->sendResetLink(

            $request->only('email')

        );





        return $status === Password::RESET_LINK_SENT

                    ? back()->with(['status' => __($status)])

                    : back()->withErrors(['email' => __($status)]);

    }

    public function showResetForm($token) {

        // return Hash::make($token);

        $email = request()->get('email');

        $check = \DB::table('contractors_password_resets')->where('email', $email)->first();

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

        return view('contractor.auth.reset-password', ['email' => $email, 'token' => $token,'page'=> $page]);

    }



    public function passwordUpdate(Request $request) {
        $request->validate([

            'token' => 'required',

            'email' => 'required|email',

            'password' => 'required|min:6|confirmed',

        ]);

        $status = Password::broker('contractors')->reset(

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

                    ? redirect()->route('contractor.login')->with('status', __($status))

                    : back()->withErrors(['email' => [__($status)]]);
    }
}
?>