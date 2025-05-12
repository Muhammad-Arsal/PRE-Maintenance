<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('admin.**', function ($view) {
            $admin = auth()->guard('admin')->user();
            if ($admin) {
                $allNotifications = $admin->notifications()
                ->whereIn('data->notification_detail->type', ['invoice', 'job'])
                ->whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $allNotificationsCount = $admin->notifications()
                ->whereIn('data->notification_detail->type', ['invoice', 'job'])
                ->whereNull('read_at')
                ->count(); 

                $view->with(['allNotifications' => $allNotifications, 'allNotificationsCount' => $allNotificationsCount]);
            }
        });
        view()->composer('landlord.**', function ($view) {
            $landlord = auth()->guard('landlord')->user();
            if ($landlord) {
                $allNotifications = $landlord->notifications()->where('data->notification_detail->type', 'invoice')->whereNull('read_at')->orderBy('created_at', 'desc')->get();
                $allNotificationsCount = $landlord->notifications()->where('data->notification_detail->type', 'invoice')->whereNull('read_at')->count();

                $view->with(['allNotifications' => $allNotifications, 'allNotificationsCount' => $allNotificationsCount]);
            }
        });
        view()->composer('contractor.**', function ($view) {
            $contractor = auth()->guard('contractor')->user();
            if ($contractor) {
                $allNotifications = $contractor->notifications()
                ->whereIn('data->notification_detail->type', ['invoice', 'job'])
                ->whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $allNotificationsCount = $contractor->notifications()
                ->whereIn('data->notification_detail->type', ['invoice', 'job'])
                ->whereNull('read_at')
                ->count();            

                $view->with(['allNotifications' => $allNotifications, 'allNotificationsCount' => $allNotificationsCount]);
            }
        });
    }
}
