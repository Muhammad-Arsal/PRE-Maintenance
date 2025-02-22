<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */

     public function boot()
     {
         $this->configureRateLimiting();
     
         $this->routes(function () {
             // API routes
             Route::middleware('api')
                 ->prefix('api')
                 ->group(base_path('routes/api.php'));
     
             // Web routes
             Route::middleware('web')
                 ->group(base_path('routes/web.php'));
     
             // Admin routes
             Route::prefix('admin')
                 ->middleware('web')
                 ->namespace($this->namespace . '\Admin')
                 ->group(base_path('routes/admin.php'));
     
             // Tenant routes
             Route::prefix('tenant')
                 ->middleware('web')
                 ->namespace($this->namespace . '\Tenant')
                 ->group(base_path('routes/tenant.php'));
     
             // Landlord routes
             Route::prefix('landlord')
                 ->middleware('web')
                 ->namespace($this->namespace . '\Landlord')
                 ->group(base_path('routes/landlord.php'));
     
             // Contractor routes
             Route::prefix('contractor')
                 ->middleware('web')
                 ->namespace($this->namespace . '\Contractor')
                 ->group(base_path('routes/contractor.php'));
         });
     }     

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
