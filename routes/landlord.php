<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landlord\ProfileController;
use App\Http\Controllers\Auth\LandlordAuthController;
use App\Http\Controllers\Landlord\InvoicesController;
use App\Http\Controllers\Landlord\DashboardController;
use App\Http\Controllers\Landlord\LandlordCalendarController;
use App\Http\Controllers\Auth\LandlordEmailVerficationController;
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
    Route::get('overview/{id}/edit', [ProfileController::class, 'edit'])->name('landlord.settings.landlords.edit');
    Route::get('address/{id}/edit', [ProfileController::class, 'address'])->name('landlord.settings.landlord.address');
    Route::post('profile/{id}/address/store', [ProfileController::class, 'updateAddress'])->name('landlord.settings.landlord.address.store');
    Route::get('bank_details/{id}/edit', [ProfileController::class, 'bankDetails'])->name('landlord.settings.landlord.bank');
    Route::post('profile/{id}/bank_details/store', [ProfileController::class, 'updateBankDetails'])->name('landlord.settings.landlord.bank.store');
    Route::get('properties/{id}/edit', [ProfileController::class, 'properties'])->name('landlord.settings.landlord.properties');
    Route::put('profile/{id}/update', [ProfileController::class, 'update'])->name('landlord.settings.landlords.update');
    Route::post('profile/{id}/destroy', [ProfileController::class, 'destroy'])->name('landlord.settings.landlords.destroy');
    Route::post('profile/{id}/delete', [ProfileController::class, 'delete'])->name('landlord.settings.landlords.delete');
    Route::get('profile/search', [ProfileController::class, 'searchData'])->name('landlord.settings.landlords.search');
    Route::get('profile/{id}/show', [ProfileController::class, 'show'])->name('landlord.settings.landlords.show');

    //Landlord Correspondence Routes
    Route::get('/correspondence/{id}/edit', [LandlordCorrespondenceController::class, 'index'])->name('landlord.landlords.correspondence');
    Route::get('/correspondence/{id}/edit/{parent_id}/view', [LandlordCorrespondenceController::class, 'showChild'])->name('landlord.landlords.correspondence.child');
    Route::post('/correspondence/{id}/edit/delete', [LandlordCorrespondenceController::class, 'delete'])->name('landlord.landlords.correspondence.delete');
    Route::post('/correspondence/{id}/edit/{parent_id}/move_file', [LandlordCorrespondenceController::class, 'moveFile'])->name('landlord.landlords.correspondence.moveFile');
    Route::post('/correspondence/{id}/edit/fileVault', [LandlordCorrespondenceController::class, 'fileVault'])->name('landlord.landlords.correspondence.fileVault');
    Route::post('/correspondence/{id}/edit/{parent_id}/new_folder', [LandlordCorrespondenceController::class, 'createFolder'])->name('landlord.landlords.correspondence.newFolder');
    Route::get('/correspondence/{id}/edit/{parent_id}/upload', [LandlordCorrespondenceController::class, 'showUploadFileForm'])->name('landlord.landlords.correspondence.uploadFilesForm');
    Route::post('/correspondence/{id}/edit/{parent_id}/upload', [LandlordCorrespondenceController::class, 'uploadFiles'])->name('landlord.landlords.correspondence.uploadFiles');
    Route::post('/correspondence/{id}/edit/ajax/addComment', [LandlordCorrespondenceController::class, 'saveComment'])->name('landlord.landlords.correspondence.ajax-add-comment');
    Route::post('/correspondence/{id}/edit/ajax/editComment', [LandlordCorrespondenceController::class, 'editComment'])->name('landlord.landlords.correspondence.ajax-edit-comment');
    Route::post('/correspondence/{id}/edit/file/description', [LandlordCorrespondenceController::class, 'add_edit_description'])->name('landlord.landlords.correspondence.add-edit-description');
    Route::post('/correspondence/{id}/edit/{parent_id}/new-call', [LandlordCorrespondenceController::class, 'newCall'])->name('landlord.landlords.correspondence.newCall');
    Route::post('/correspondence/{id}/edit/{parent_id}/new-meeting', [LandlordCorrespondenceController::class, 'storeMeeting'])->name('landlord.landlords.correspondence.newMeeting');
    Route::get('correspondence/{id}/edit/task', [LandlordCorrespondenceController::class, 'showTaskPage'])->name('landlord.suppliers.correspondence.task');
    Route::post('tasks/create/task/{id}', [LandlordCorrespondenceController::class, 'storeTask'])->name('landlord.tasks.cross.store');

     //invoices
     Route::get('account/invoices', [InvoicesController::class, 'index'])->name('landlord.invoices');
     Route::get('account/invoices/create', [InvoicesController::class, 'create'])->name('landlord.invoices.create');
     Route::post('account/invoices/store', [InvoicesController::class,'store'])->name('landlord.invoices.store');
     Route::get('account/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('landlord.invoices.edit');
     Route::post('account/invoices/{id}/update', [InvoicesController::class, 'update'])->name('landlord.invoices.update');
     Route::delete('account/invoices/{id}/destroy', [InvoicesController::class, 'destroy'])->name('landlord.invoices.destroy');
     Route::get('account/invoices/{id}/show', [InvoicesController::class,'show'])->name('landlord.invoices.show');
     Route::get('account/invoices/search', [InvoicesController::class,'searchData'])->name('landlord.invoices.search');
     //AJAX
     Route::get('/get-address-details', [InvoicesController::class, 'getAddressDetails'])->name('get.address.details');

    //pdf generate 
    Route::get('landlord/invoices/generatePDF/{id}', [InvoicesController::class, 'generatePDF'])->name('landlord.invoices.generatePDF');

    //landlord calendar Routes
    Route::get('/diary/{id}/calendar', [LandlordCalendarController::class, 'index'])->name('landlord.calendar');
    Route::post('diary/save-calendar-state', [LandlordCalendarController::class, 'saveCalendarState'])->name('landlord.saveCalendarState');    
    Route::get('diary/event/{id}/edit/{landlord_id}', [LandlordCalendarController::class, 'edit'])->name('landlord.diary.event.edit');
});
?>