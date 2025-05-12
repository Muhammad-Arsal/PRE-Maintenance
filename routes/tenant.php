<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Auth\TenantAuthController;
use App\Http\Controllers\Tenant\InvoicesController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Auth\TenantEmailVerficationController;
use App\Http\Controllers\Tenant\TenantCorrespondenceController;



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

    // Tenants
    Route::get('overview/{id}/edit', [ProfileController::class, 'edit'])->name('tenant.settings.tenants.edit');
    Route::get('property/{id}/edit', [ProfileController::class, 'editProperty'])->name('tenant.settings.tenants.edit.property');
    Route::put('profiles/{id}/update', [ProfileController::class, 'update'])->name('tenant.settings.tenants.update');
    Route::put('profiles/{id}/update/property', [ProfileController::class, 'storeProperty'])->name('tenant.settings.tenants.update.property');
    Route::post('profiles/{id}/destroy', [ProfileController::class, 'destroy'])->name('tenant.settings.tenants.destroy');
    Route::post('profiles/{id}/delete', [ProfileController::class, 'delete'])->name('tenant.settings.tenants.delete');
    Route::get('profiles/search', [ProfileController::class, 'searchData'])->name('tenant.settings.tenants.search');
    Route::get('profiles/{id}/show', [ProfileController::class,'show'])->name('tenant.settings.tenants.show');

    //profile Correspondence Routes
    Route::get('correspondence/{id}/edit', [TenantCorrespondenceController::class, 'index'])->name('tenant.tenants.correspondence');
    Route::get('correspondence/{id}/edit/{parent_id}/view', [TenantCorrespondenceController::class, 'showChild'])->name('tenant.tenants.correspondence.child');
    Route::post('correspondence/{id}/edit/delete', [TenantCorrespondenceController::class, 'delete'])->name('tenant.tenants.correspondence.delete');
    Route::post('correspondence/{id}/edit/{parent_id}/move_file', [TenantCorrespondenceController::class, 'moveFile'])->name('tenant.tenants.correspondence.moveFile');
    Route::post('correspondence/{id}/edit/fileVault', [TenantCorrespondenceController::class, 'fileVault'])->name('tenant.tenants.correspondence.fileVault');
    Route::post('correspondence/{id}/edit/{parent_id}/new_folder', [TenantCorrespondenceController::class, 'createFolder'])->name('tenant.tenants.correspondence.newFolder');
    Route::get('correspondence/{id}/edit/{parent_id}/upload', [TenantCorrespondenceController::class, 'showUploadFileForm'])->name('tenant.tenants.correspondence.uploadFilesForm');
    Route::post('correspondence/{id}/edit/{parent_id}/upload', [TenantCorrespondenceController::class, 'uploadFiles'])->name('tenant.tenants.correspondence.uploadFiles');
    Route::post('correspondence/{id}/edit/ajax/addComment', [TenantCorrespondenceController::class, 'saveComment'])->name('tenant.tenants.correspondence.ajax-add-comment');
    Route::post('correspondence/{id}/edit/ajax/editComment', [TenantCorrespondenceController::class, 'editComment'])->name('tenant.tenants.correspondence.ajax-edit-comment');
    Route::post('correspondence/{id}/edit/file/description', [TenantCorrespondenceController::class, 'add_edit_description'])->name('tenant.tenants.correspondence.add-edit-description');
    Route::post('correspondence/{id}/edit/{parent_id}/new-call', [TenantCorrespondenceController::class, 'newCall'])->name('tenant.tenants.correspondence.newCall');
    Route::post('correspondence/{id}/edit/{parent_id}/new-meeting', [TenantCorrespondenceController::class, 'storeMeeting'])->name('tenant.tenants.correspondence.newMeeting');
    Route::get('correspondence/{id}/edit/task', [TenantCorrespondenceController::class, 'showTaskPage'])->name('tenant.suppliers.correspondence.task');
    Route::post('correspondence/tasks/create/task/{id}', [TenantCorrespondenceController::class, 'storeTask'])->name('tenant.tasks.cross.store');


    //invoices
    Route::get('account/invoices', [InvoicesController::class, 'index'])->name('tenant.invoices');
    Route::get('account/invoices/create', [InvoicesController::class, 'create'])->name('tenant.invoices.create');
    Route::post('account/invoices/store', [InvoicesController::class,'store'])->name('tenant.invoices.store');
    Route::get('account/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('tenant.invoices.edit');
    Route::post('account/invoices/{id}/update', [InvoicesController::class, 'update'])->name('tenant.invoices.update');
    Route::delete('account/invoices/{id}/destroy', [InvoicesController::class, 'destroy'])->name('tenant.invoices.destroy');
    Route::get('account/invoices/{id}/show', [InvoicesController::class,'show'])->name('tenant.invoices.show');
    Route::get('account/invoices/search', [InvoicesController::class,'searchData'])->name('tenant.invoices.search');

});

?>