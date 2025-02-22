<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ContractorMiddleware
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
        $user = Auth::guard('contractor')->user();


        if(!$user){
            return redirect()->route('contractor.login');
        }

          return $next($request);
    }
}
