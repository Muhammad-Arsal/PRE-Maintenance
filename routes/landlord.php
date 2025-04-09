<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LandlordAuthController;
use App\Http\Controllers\Auth\LandlordEmailVerficationController;
use App\Http\Controllers\Landlord\DashboardController;
use App\Http\Controllers\Landlord\ProfileController;
use App\Http\Controllers\Landlord\LandlordCorrespondenceController;

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

    // Landlords
    Route::get('profile/{id}/edit', [ProfileController::class, 'edit'])->name('landlord.settings.landlords.edit');
    Route::get('profile/{id}/address', [ProfileController::class, 'address'])->name('landlord.settings.landlord.address');
    Route::post('profile/{id}/address/store', [ProfileController::class, 'updateAddress'])->name('landlord.settings.landlord.address.store');
    Route::get('profile/{id}/bank_details', [ProfileController::class, 'bankDetails'])->name('landlord.settings.landlord.bank');
    Route::post('profile/{id}/bank_details/store', [ProfileController::class, 'updateBankDetails'])->name('landlord.settings.landlord.bank.store');
    Route::get('profile/{id}/properties', [ProfileController::class, 'properties'])->name('landlord.settings.landlord.properties');
    Route::put('profile/{id}/update', [ProfileController::class, 'update'])->name('landlord.settings.landlords.update');
    Route::post('profile/{id}/destroy', [ProfileController::class, 'destroy'])->name('landlord.settings.landlords.destroy');
    Route::post('profile/{id}/delete', [ProfileController::class, 'delete'])->name('landlord.settings.landlords.delete');
    Route::get('profile/search', [ProfileController::class, 'searchData'])->name('landlord.settings.landlords.search');
    Route::get('profile/{id}/show', [ProfileController::class, 'show'])->name('landlord.settings.landlords.show');

    //Landlord Correspondence Routes
    Route::get('/profile/{id}/correspondence', [LandlordCorrespondenceController::class, 'index'])->name('landlord.landlords.correspondence');
    Route::get('/profile/{id}/correspondence/{parent_id}/view', [LandlordCorrespondenceController::class, 'showChild'])->name('landlord.landlords.correspondence.child');
    Route::post('/profile/{id}/correspondence/delete', [LandlordCorrespondenceController::class, 'delete'])->name('landlord.landlords.correspondence.delete');
    Route::post('/profile/{id}/correspondence/{parent_id}/move_file', [LandlordCorrespondenceController::class, 'moveFile'])->name('landlord.landlords.correspondence.moveFile');
    Route::post('/profile/{id}/correspondence/fileVault', [LandlordCorrespondenceController::class, 'fileVault'])->name('landlord.landlords.correspondence.fileVault');
    Route::post('/profile/{id}/correspondence/{parent_id}/new_folder', [LandlordCorrespondenceController::class, 'createFolder'])->name('landlord.landlords.correspondence.newFolder');
    Route::get('/profile/{id}/correspondence/{parent_id}/upload', [LandlordCorrespondenceController::class, 'showUploadFileForm'])->name('landlord.landlords.correspondence.uploadFilesForm');
    Route::post('/profile/{id}/correspondence/{parent_id}/upload', [LandlordCorrespondenceController::class, 'uploadFiles'])->name('landlord.landlords.correspondence.uploadFiles');
    Route::post('/profile/{id}/correspondence/ajax/addComment', [LandlordCorrespondenceController::class, 'saveComment'])->name('landlord.landlords.correspondence.ajax-add-comment');
    Route::post('/profile/{id}/correspondence/ajax/editComment', [LandlordCorrespondenceController::class, 'editComment'])->name('landlord.landlords.correspondence.ajax-edit-comment');
    Route::post('/profile/{id}/correspondence/file/description', [LandlordCorrespondenceController::class, 'add_edit_description'])->name('landlord.landlords.correspondence.add-edit-description');
    Route::post('/profile/{id}/correspondence/{parent_id}/new-call', [LandlordCorrespondenceController::class, 'newCall'])->name('landlord.landlords.correspondence.newCall');
    Route::post('/profile/{id}/correspondence/{parent_id}/new-meeting', [LandlordCorrespondenceController::class, 'storeMeeting'])->name('landlord.landlords.correspondence.newMeeting');
    Route::get('profile/{id}/correspondence/task', [LandlordCorrespondenceController::class, 'showTaskPage'])->name('landlord.suppliers.correspondence.task');
    Route::post('tasks/create/task', [LandlordCorrespondenceController::class, 'storeTask'])->name('landlord.tasks.cross.store');
});
?>