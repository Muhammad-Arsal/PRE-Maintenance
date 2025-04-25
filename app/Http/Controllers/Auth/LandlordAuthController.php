<?php
namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Landlord;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class LandlordAuthController extends Controller
{
    public function __construct(){

    }

    public function login(){
        if(Auth::guard('landlord')->check() )
        {
            return redirect()->route('landlord.dashboard');
        }

        $page['page_title'] = 'Login';

        return view('landlord.auth.login',compact('page'));

    }



    public function landlordLogin(Request $request){

        $request->validate([
            'email' => ['required',
               function($attribute, $value, $fail){
                   $landlord = Landlord::where('email', $value)->first();
                   if(isset($landlord)){
                       if($landlord->deleted_at != null) {
                           $fail('Your account has been deactivated');
                       }
                   }
               }
           ],
            'password' => 'required',
        ]);

        $credentials = $request->only('email','password');

        $remember_me = $request->has('remember') ? true : false;

        if(Auth::guard('landlord')->attempt($credentials, $remember_me)){
            $name = explode(' ',Auth::guard('landlord')->user()->name );

            $welcome = 'Welcome '.$name[0].', great to see you again!';

            Landlord::where('id',Auth::guard('landlord')->user()->id)->update(['last_logged_in'=>now()]);
            
            return redirect()->route('landlord.settings.landlords.edit', auth('landlord')->id())->withSuccess($welcome);
        }

        return redirect()->route('landlord.login')->with('error', 'Email or Password is incorrect!');
   }



    public function landlordLogout() {
        $name = explode(' ',Auth::guard('landlord')->user()->name );

        $message = "Good to see you again " . $name[0] . ", take care and see you soon";
        Auth::guard('landlord')->logout();

        return redirect()->route('landlord.login')->withSuccess($message);

    }



    public function showLinkRequestForm(Request $request)

    {

         $page['page_title'] = 'Forgot Password';



        return view('landlord.auth.forget-password',compact('page'));

    }



    public function forgetPassword(Request $request) {

        $request->validate(['email' => 'required|email|exists:landlords']);



        $status = Password::broker('landlords')->sendResetLink(

            $request->only('email')

        );





        return $status === Password::RESET_LINK_SENT

                    ? back()->with(['status' => __($status)])

                    : back()->withErrors(['email' => __($status)]);

    }

    public function showResetForm($token) {

        // return Hash::make($token);

        $email = request()->get('email');

        $check = \DB::table('landlords_password_resets')->where('email', $email)->first();

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

        return view('landlord.auth.reset-password', ['email' => $email, 'token' => $token,'page'=> $page]);

    }



    public function passwordUpdate(Request $request) {

        $request->validate([

            'token' => 'required',

            'email' => 'required|email',

            'password' => 'required|min:6|confirmed',

        ]);

        $status = Password::broker('landlords')->reset(

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

                    ? redirect()->route('landlord.login')->with('status', __($status))

                    : back()->withErrors(['email' => [__($status)]]);
    }
}
?>