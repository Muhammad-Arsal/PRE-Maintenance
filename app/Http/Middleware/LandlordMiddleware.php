<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LandlordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){

        // return redirect()->route('admin.login');
        $user = Auth::guard('landlord')->user();

        if(!$user){
            return redirect()->route('landlord.login');
        }

          return $next($request);
    }
}
