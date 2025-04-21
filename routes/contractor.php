<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ContractorAuthController;
use App\Http\Controllers\Auth\ContractorEmailVerificationController;
use App\Http\Controllers\Contractor\DashboardController;
use App\Http\Controllers\Contractor\ProfileController;
use App\Http\Controllers\Contractor\ContractorCorrespondenceController;

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

    // Contractors
    Route::get('/contractors/{id}/edit', [ProfileController::class, 'edit'])->name('contractor.settings.contractors.edit');
    Route::get('/contractors/{id}/edit/address', [ProfileController::class, 'editAddress'])->name('contractor.settings.contractors.edit.address');
    Route::put('/contractors/{id}/update', [ProfileController::class, 'update'])->name('contractor.settings.contractors.update');
    Route::put('/contractors/{id}/update/address', [ProfileController::class, 'updateAddress'])->name('contractor.settings.contractors.update.address');
    Route::post('contractors/{id}/destroy', [ProfileController::class, 'destroy'])->name('contractor.settings.contractors.destroy');
    Route::post('/contractors/{id}/delete', [ProfileController::class, 'delete'])->name('contractor.settings.contractors.delete');
    Route::get('/contractors/search', [ProfileController::class, 'searchData'])->name('contractor.settings.contractors.search');
    Route::get('contractors/{id}/view/jobs', [ProfileController::class, 'jobs'])->name('contractor.contractors.viewjobs');

    //Correspondence Routes
    Route::get('/profile/{id}/correspondence', [ContractorCorrespondenceController::class, 'index'])->name('contractor.contractors.correspondence');
    Route::get('/profile/{id}/correspondence/{parent_id}/view', [ContractorCorrespondenceController::class, 'showChild'])->name('contractor.contractors.correspondence.child');
    Route::post('/profile/{id}/correspondence/delete', [ContractorCorrespondenceController::class, 'delete'])->name('contractor.contractors.correspondence.delete');
    Route::post('/profile/{id}/correspondence/{parent_id}/move_file', [ContractorCorrespondenceController::class, 'moveFile'])->name('contractor.contractors.correspondence.moveFile');
    Route::post('/profile/{id}/correspondence/fileVault', [ContractorCorrespondenceController::class, 'fileVault'])->name('contractor.contractors.correspondence.fileVault');
    Route::post('/profile/{id}/correspondence/{parent_id}/new_folder', [ContractorCorrespondenceController::class, 'createFolder'])->name('contractor.contractors.correspondence.newFolder');
    Route::get('/profile/{id}/correspondence/{parent_id}/upload', [ContractorCorrespondenceController::class, 'showUploadFileForm'])->name('contractor.contractors.correspondence.uploadFilesForm');
    Route::post('/profile/{id}/correspondence/{parent_id}/upload', [ContractorCorrespondenceController::class, 'uploadFiles'])->name('contractor.contractors.correspondence.uploadFiles');
    Route::post('/profile/{id}/correspondence/ajax/addComment', [ContractorCorrespondenceController::class, 'saveComment'])->name('contractor.contractors.correspondence.ajax-add-comment');
    Route::post('/profile/{id}/correspondence/ajax/editComment', [ContractorCorrespondenceController::class, 'editComment'])->name('contractor.contractors.correspondence.ajax-edit-comment');
    Route::post('/profile/{id}/correspondence/file/description', [ContractorCorrespondenceController::class, 'add_edit_description'])->name('contractor.contractors.correspondence.add-edit-description');
    Route::post('/profile/{id}/correspondence/{parent_id}/new-call', [ContractorCorrespondenceController::class, 'newCall'])->name('contractor.contractors.correspondence.newCall');
    Route::post('/profile/{id}/correspondence/{parent_id}/new-meeting', [ContractorCorrespondenceController::class, 'storeMeeting'])->name('contractor.contractors.correspondence.newMeeting');
    Route::get('profile/{id}/correspondence/task', [ContractorCorrespondenceController::class, 'showTaskPage'])->name('contractor.suppliers.correspondence.task');
    Route::post('tasks/create/task/{id}', [ContractorCorrespondenceController::class, 'storeTask'])->name('contractor.tasks.cross.store');
});
?>