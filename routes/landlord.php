<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LandlordAuthController;
use App\Http\Controllers\Auth\LandlordEmailVerficationController;
use App\Http\Controllers\Landlord\DashboardController;
use App\Http\Controllers\Landlord\ProfileController;

Route::get('/',  [LandlordAuthController::class, 'login'])->name('landlord.login');
Route::post('/landlordlogin',[LandlordAuthController::class, 'landlordLogin'])->name('landlordLogin');
Route::get('/forgetpassword',[LandlordAuthController::class, 'showLinkRequestForm'])->name('landlord.showResetEmailForm');
Route::post('/forgetpassword',[LandlordAuthController::class, 'forgetPassword'])->name('landlord.forgetSendEmail');
Route::get('/reset-password/{token}',[LandlordAuthController::class, 'showResetForm'])->name('landlord.showResetForm');
Route::post('/reset-password',[LandlordAuthController::class, 'passwordUpdate'])->name('landlord.password.update');

// Email Verification
Route::get('/email/verify/{id}/{token}', [LandlordEmailVerficationController::class,'verify'])->name('landlord.email.verify')->middleware('signed');
Route::post('/email/verify/{id}/{token}',[LandlordEmailVerficationController::class, 'store'])->name('landlord.email.store');


Route::middleware(['landlord', 'verified:landlord.login'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('landlord.dashboard');
    // Logout
    Route::get('/logout',[LandlordAuthController::class, 'landlordLogout'])->name('landlord.logout');

    //Profile
    Route::get('/profile/edit',[ProfileController::class, 'showProfileForm'])->name('landlord.profile');
    Route::post('/profile/edit',[ProfileController::class, 'updateProfile'])->name('landlord.profile.update');
});
?>