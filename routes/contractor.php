<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ContractorAuthController;
use App\Http\Controllers\Auth\ContractorEmailVerificationController;
use App\Http\Controllers\Contractor\DashboardController;
use App\Http\Controllers\Contractor\ProfileController;
use App\Http\Controllers\Contractor\ContractorCorrespondenceController;
use App\Http\Controllers\Contractor\InvoicesController;

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
    Route::get('/overview/{id}/edit', [ProfileController::class, 'edit'])->name('contractor.settings.contractors.edit');
    Route::get('/address/{id}/edit', [ProfileController::class, 'editAddress'])->name('contractor.settings.contractors.edit.address');
    Route::put('/contractors/{id}/update', [ProfileController::class, 'update'])->name('contractor.settings.contractors.update');
    Route::put('/contractors/{id}/update/address', [ProfileController::class, 'updateAddress'])->name('contractor.settings.contractors.update.address');
    Route::post('contractors/{id}/destroy', [ProfileController::class, 'destroy'])->name('contractor.settings.contractors.destroy');
    Route::post('/contractors/{id}/delete', [ProfileController::class, 'delete'])->name('contractor.settings.contractors.delete');
    Route::get('/contractors/search', [ProfileController::class, 'searchData'])->name('contractor.settings.contractors.search');
    Route::get('jobs/{id}/view', [ProfileController::class, 'jobs'])->name('contractor.contractors.viewjobs');
    Route::get('jobs/{id}/edit/detail/{jobId}', [ProfileController::class, 'editJob'])->name('contractor.contractors.editJob.details');
    Route::post('jobs/{id}/update/detail', [ProfileController::class, 'updateContractorTasks'])->name('contractor.contractors.updateJob.details');

    //Correspondence Routes
    Route::get('/correspondence/{id}/edit', [ContractorCorrespondenceController::class, 'index'])->name('contractor.contractors.correspondence');
    Route::get('/correspondence/{id}/edit/{parent_id}/view', [ContractorCorrespondenceController::class, 'showChild'])->name('contractor.contractors.correspondence.child');
    Route::post('/correspondence/{id}/edit/delete', [ContractorCorrespondenceController::class, 'delete'])->name('contractor.contractors.correspondence.delete');
    Route::post('/correspondence/{id}/edit/{parent_id}/move_file', [ContractorCorrespondenceController::class, 'moveFile'])->name('contractor.contractors.correspondence.moveFile');
    Route::post('/correspondence/{id}/edit/fileVault', [ContractorCorrespondenceController::class, 'fileVault'])->name('contractor.contractors.correspondence.fileVault');
    Route::post('/correspondence/{id}/edit/{parent_id}/new_folder', [ContractorCorrespondenceController::class, 'createFolder'])->name('contractor.contractors.correspondence.newFolder');
    Route::get('/correspondence/{id}/edit/{parent_id}/upload', [ContractorCorrespondenceController::class, 'showUploadFileForm'])->name('contractor.contractors.correspondence.uploadFilesForm');
    Route::post('/correspondence/{id}/edit/{parent_id}/upload', [ContractorCorrespondenceController::class, 'uploadFiles'])->name('contractor.contractors.correspondence.uploadFiles');
    Route::post('/correspondence/{id}/edit/ajax/addComment', [ContractorCorrespondenceController::class, 'saveComment'])->name('contractor.contractors.correspondence.ajax-add-comment');
    Route::post('/correspondence/{id}/edit/ajax/editComment', [ContractorCorrespondenceController::class, 'editComment'])->name('contractor.contractors.correspondence.ajax-edit-comment');
    Route::post('/correspondence/{id}/edit/file/description', [ContractorCorrespondenceController::class, 'add_edit_description'])->name('contractor.contractors.correspondence.add-edit-description');
    Route::post('/correspondence/{id}/edit/{parent_id}/new-call', [ContractorCorrespondenceController::class, 'newCall'])->name('contractor.contractors.correspondence.newCall');
    Route::post('/correspondence/{id}/edit/{parent_id}/new-meeting', [ContractorCorrespondenceController::class, 'storeMeeting'])->name('contractor.contractors.correspondence.newMeeting');
    Route::get('correspondence/{id}/edit/task', [ContractorCorrespondenceController::class, 'showTaskPage'])->name('contractor.suppliers.correspondence.task');
    Route::post('tasks/create/task/{id}', [ContractorCorrespondenceController::class, 'storeTask'])->name('contractor.tasks.cross.store');



    //invoices
    Route::get('account/invoices', [InvoicesController::class, 'index'])->name('contractor.invoices');
    Route::get('account/invoices/create', [InvoicesController::class, 'create'])->name('contractor.invoices.create');
    Route::post('account/invoices/store', [InvoicesController::class,'store'])->name('contractor.invoices.store');
    Route::get('account/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('contractor.invoices.edit');
    Route::post('account/invoices/{id}/update', [InvoicesController::class, 'update'])->name('contractor.invoices.update');
    Route::delete('account/invoices/{id}/destroy', [InvoicesController::class, 'destroy'])->name('contractor.invoices.destroy');
    Route::get('account/invoices/{id}/show', [InvoicesController::class,'show'])->name('contractor.invoices.show');
    Route::get('account/invoices/search', [InvoicesController::class,'searchData'])->name('contractor.invoices.search');
    //AJAX
    Route::get('/get-address-details', [InvoicesController::class, 'getAddressDetails'])->name('get.address.details');
});
?>