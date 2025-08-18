<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){
        // Ensure Gate & Auth resolve the admin guard for this request
        Auth::shouldUse('admin');
        $user = Auth::guard('admin')->user();


        if(!$user){
            return redirect()->route('admin.login');
        }

          return $next($request);
    }
}
