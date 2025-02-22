<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TenantAuthController;
use App\Http\Controllers\Auth\TenantEmailVerficationController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ProfileController;


Route::get('/',  [TenantAuthController::class, 'login'])->name('tenant.login');
Route::post('/tenantlogin',[TenantAuthController::class, 'tenantLogin'])->name('tenantLogin');
Route::get('/forgetpassword',[TenantAuthController::class, 'showLinkRequestForm'])->name('tenant.showResetEmailForm');
Route::post('/forgetpassword',[TenantAuthController::class, 'forgetPassword'])->name('tenant.forgetSendEmail');
Route::get('/reset-password/{token}',[TenantAuthController::class, 'showResetForm'])->name('tenant.showResetForm');
Route::post('/reset-password',[TenantAuthController::class, 'passwordUpdate'])->name('tenant.password.update');

// Email Verification
Route::get('/email/verify/{id}/{token}', [TenantEmailVerficationController::class,'verify'])->name('tenant.email.verify')->middleware('signed');
Route::post('/email/verify/{id}/{token}',[TenantEmailVerficationController::class, 'store'])->name('tenant.email.store');


Route::middleware(['tenant', 'verified:tenant.login'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('tenant.dashboard');
    // Logout
    Route::get('/logout',[TenantAuthController::class, 'tenantLogout'])->name('tenant.logout');

    //Profile
    Route::get('/profile/edit',[ProfileController::class, 'showProfileForm'])->name('tenant.profile');
    Route::post('/profile/edit',[ProfileController::class, 'updateProfile'])->name('tenant.profile.update');
});

?>