<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ContractorAuthController;
use App\Http\Controllers\Auth\ContractorEmailVerificationController;
use App\Http\Controllers\Contractor\DashboardController;
use App\Http\Controllers\Contractor\ProfileController;

Route::get('/',  [ContractorAuthController::class, 'login'])->name('contractor.login');
Route::post('/contractorlogin',[ContractorAuthController::class, 'contractorLogin'])->name('contractorLogin');
Route::get('/forgetpassword',[ContractorAuthController::class, 'showLinkRequestForm'])->name('contractor.showResetEmailForm');
Route::post('/forgetpassword',[ContractorAuthController::class, 'forgetPassword'])->name('contractor.forgetSendEmail');
Route::get('/reset-password/{token}',[ContractorAuthController::class, 'showResetForm'])->name('contractor.showResetForm');
Route::post('/reset-password',[ContractorAuthController::class, 'passwordUpdate'])->name('contractor.password.update');

// Email Verification
Route::get('/email/verify/{id}/{token}', [ContractorEmailVerificationController::class,'verify'])->name('contractor.email.verify')->middleware('signed');
Route::post('/email/verify/{id}/{token}',[ContractorEmailVerificationController::class, 'store'])->name('contractor.email.store');


Route::middleware(['contractor', 'verified:contractor.login'])->group(function(){
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('contractor.dashboard');
    // Logout
    Route::get('/logout',[ContractorAuthController::class, 'contractorLogout'])->name('contractor.logout');

    //Profile
    Route::get('/profile/edit',[ProfileController::class, 'showProfileForm'])->name('contractor.profile');
    Route::post('/profile/edit',[ProfileController::class, 'updateProfile'])->name('contractor.profile.update');
});
?>