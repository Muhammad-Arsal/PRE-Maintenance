<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AdminEmailVerificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\TenantsController; 
use App\Http\Controllers\Admin\LandlordsController;
use App\Http\Controllers\Admin\ContractorsController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\JobsController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\LandlordsCorrespondenceController;
use App\Http\Controllers\Admin\TaskTrayController;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\CalendarController;


Route::get('/',  [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('/adminlogin',[AdminAuthController::class, 'adminLogin'])->name('adminLogin');
Route::get('/forgetpassword',[AdminAuthController::class, 'showLinkRequestForm'])->name('admin.showResetEmailForm');
Route::post('/forgetpassword',[AdminAuthController::class, 'forgetPassword'])->name('admin.forgetSendEmail');
Route::get('/reset-password/{token}',[AdminAuthController::class, 'showResetForm'])->name('admin.showResetForm');
Route::post('/reset-password',[AdminAuthController::class, 'passwordUpdate'])->name('admin.password.update');

// Email Verification
Route::get('/email/verify/{id}/{token}', [AdminEmailVerificationController::class,'verify'])->name('admin.email.verify')->middleware('signed');
Route::post('/email/verify/{id}/{token}',[AdminEmailVerificationController::class, 'store'])->name('admin.email.store');

Route::middleware(['admin', 'verified:admin.login'])->group(function(){

    Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');
    // Logout
    Route::get('/logout',[AdminAuthController::class, 'adminLogout'])->name('admin.logout');
    
    // Profile
    Route::get('/profile/edit',[ProfileController::class, 'showProfileForm'])->name('admin.profile');
    Route::post('/profile/edit',[ProfileController::class, 'updateProfile'])->name('admin.profile.update');

    // Email Template
    Route::get('/settings/emailTemplate',[EmailTemplateController::class, 'index'])->name('admin.emailTemplate.index');
    Route::get('/settings/emailTemplate/add',[EmailTemplateController::class, 'add'])->name('admin.emailTemplate.add');
    Route::post('/settings/emailTemplate/add',[EmailTemplateController::class, 'save'])->name('admin.emailTemplate.save');
    Route::get('/settings/emailTemplate/{id}/edit',[EmailTemplateController::class, 'edit'])->name('admin.emailTemplate.edit');
    Route::post('/settings/emailTemplate/{id}/update',[EmailTemplateController::class, 'update'])->name('admin.emailTemplate.update');
    Route::post('/settings/emailTemplate/{id}',[EmailTemplateController::class, 'destroy'])->name('admin.emailTemplate.delete');
    Route::get('/settings/emailTemplate/{id}',[EmailTemplateController::class, 'show'])->name('admin.emailTemplate.show');
    Route::get('/settings/emailTemplate/manage/search',[EmailTemplateController::class, 'searchData'])->name('admin.emailTemplate.searchData');
    Route::get('/settings/emailTemplate/manage/reset',[EmailTemplateController::class, 'resetData'])->name('admin.emailTemplate.resetData');

    // Theme Options
    Route::get('/settings/themeOptions',[SettingsController::class, 'themeOptions'])->name('admin.settings.themeOptions');
    Route::post('/settings/themeOptions',[SettingsController::class, 'setThemeOptions'])->name('admin.save.themeOptions');

    // Admins
    Route::get('settings/admins', [AdminsController::class, 'index'])->name('admin.settings.admins');
    Route::get('settings/admins/create', [AdminsController::class, 'create'])->name('admin.settings.admins.create');
    Route::post('settings/admins/store', [AdminsController::class, 'store'])->name('admin.settings.admins.store');
    Route::get('settings/admins/{id}/edit', [AdminsController::class, 'edit'])->name('admin.settings.admins.edit');
    Route::put('settings/admins/{id}/update', [AdminsController::class, 'update'])->name('admin.settings.admins.update');
    Route::post('settings/admins/{id}/destroy', [AdminsController::class, 'destroy'])->name('admin.settings.admins.destroy');
    Route::post('settings/admins/{id}/delete', [AdminsController::class, 'delete'])->name('admin.settings.admins.delete');
    Route::get('/settings/admins/search', [AdminsController::class, 'searchData'])->name('admin.settings.admins.search');

    // Tenants
    Route::get('/tenants', [TenantsController::class, 'index'])->name('admin.settings.tenants');
    Route::get('/tenants/create', [TenantsController::class, 'create'])->name('admin.settings.tenants.create');
    Route::post('/tenants/store', [TenantsController::class, 'store'])->name('admin.settings.tenants.store');
    Route::get('/tenants/{id}/edit', [TenantsController::class, 'edit'])->name('admin.settings.tenants.edit');
    Route::put('/tenants/{id}/update', [TenantsController::class, 'update'])->name('admin.settings.tenants.update');
    Route::post('tenants/{id}/destroy', [TenantsController::class, 'destroy'])->name('admin.settings.tenants.destroy');
    Route::post('/tenants/{id}/delete', [TenantsController::class, 'delete'])->name('admin.settings.tenants.delete');
    Route::get('/tenants/search', [TenantsController::class, 'searchData'])->name('admin.settings.tenants.search');
    Route::get('tenants/{id}/show', [TenantsController::class,'show'])->name('admin.settings.tenants.show');
    
    // Landlords
    Route::get('/landlords', [LandlordsController::class, 'index'])->name('admin.settings.landlords');
    Route::get('/landlords/create', [LandlordsController::class, 'create'])->name('admin.settings.landlords.create');
    Route::post('/landlords/store', [LandlordsController::class, 'store'])->name('admin.settings.landlords.store');
    Route::get('/landlords/{id}/edit', [LandlordsController::class, 'edit'])->name('admin.settings.landlords.edit');
    Route::get('/landlords/{id}/address', [LandlordsController::class, 'address'])->name('admin.settings.landlord.address');
    Route::post('/landlords/{id}/address/store', [LandlordsController::class, 'updateAddress'])->name('admin.settings.landlord.address.store');
    Route::get('/landlords/{id}/bank_details', [LandlordsController::class, 'bankDetails'])->name('admin.settings.landlord.bank');
    Route::post('/landlords/{id}/bank_details/store', [LandlordsController::class, 'updateBankDetails'])->name('admin.settings.landlord.bank.store');
    Route::get('/landlords/{id}/properties', [LandlordsController::class, 'properties'])->name('admin.settings.landlord.properties');
    Route::put('/landlords/{id}/update', [LandlordsController::class, 'update'])->name('admin.settings.landlords.update');
    Route::post('landlords/{id}/destroy', [LandlordsController::class, 'destroy'])->name('admin.settings.landlords.destroy');
    Route::post('/landlords/{id}/delete', [LandlordsController::class, 'delete'])->name('admin.settings.landlords.delete');
    Route::get('/landlords/search', [LandlordsController::class, 'searchData'])->name('admin.settings.landlords.search');
    Route::get('landlords/{id}/show', [LandlordsController::class, 'show'])->name('admin.settings.landlords.show');

    // Contractors
    Route::get('/contractors', [ContractorsController::class, 'index'])->name('admin.settings.contractors');
    Route::get('/contractors/create', [ContractorsController::class, 'create'])->name('admin.settings.contractors.create');
    Route::post('/contractors/store', [ContractorsController::class, 'store'])->name('admin.settings.contractors.store');
    Route::get('/contractors/{id}/edit', [ContractorsController::class, 'edit'])->name('admin.settings.contractors.edit');
    Route::put('/contractors/{id}/update', [ContractorsController::class, 'update'])->name('admin.settings.contractors.update');
    Route::post('contractors/{id}/destroy', [ContractorsController::class, 'destroy'])->name('admin.settings.contractors.destroy');
    Route::post('/contractors/{id}/delete', [ContractorsController::class, 'delete'])->name('admin.settings.contractors.delete');
    Route::get('/contractors/search', [ContractorsController::class, 'searchData'])->name('admin.settings.contractors.search');
    Route::get('contractors/{id}/view/jobs', [ContractorsController::class, 'jobs'])->name('admin.contractors.viewjobs');

    //Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('admin.properties');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('admin.properties.create');
    Route::post('/properties/store', [PropertyController::class, 'store'])->name('admin.properties.store');
    Route::get('/properties/{id}/edit', [PropertyController::class, 'edit'])->name('admin.properties.edit');
    Route::post('/properties/{id}/update', [PropertyController::class, 'update'])->name('admin.properties.update');
    Route::post('properties/{id}/destroy', [PropertyController::class, 'destroy'])->name('admin.properties.destroy');
    Route::get('properties/{id}/show', [PropertyController::class, 'show'])->name('admin.properties.show');
    Route::get('/properties/search', [PropertyController::class,'searchData'])->name('admin.properties.search');
    Route::get('properties/{id}/diary', [PropertyController::class, 'diary'])->name('admin.properties.diary');
    Route::post('properties/{id}/editDiary', [PropertyController::class, 'diaryStore'])->name('admin.properties.diaryStore');
    Route::post('properties/{id}/deleteDiary', [PropertyController::class, 'diaryDelete'])->name('admin.properties.diaryDelete');
    Route::get('properties/{id}/view/jobs', [PropertyController::class, 'jobs'])->name('admin.properties.viewjobs');

    // Property Types
    Route::get('settings/propertyType', [PropertyTypeController::class, 'index'])->name('admin.settings.propertyType');
    Route::get('settings/propertyType/create', [PropertyTypeController::class, 'create'])->name('admin.settings.propertyType.create');
    Route::post('settings/propertyType/store', [PropertyTypeController::class,'store'])->name('admin.settings.propertyType.store');
    Route::get('settings/propertyType/{id}/edit', [PropertyTypeController::class, 'edit'])->name('admin.settings.propertyType.edit');
    Route::post('settings/propertyType/{id}/update', [PropertyTypeController::class, 'update'])->name('admin.settings.propertyType.update');
    Route::post('settings/propertyType/{id}/destroy', [PropertyTypeController::class, 'destroy'])->name('admin.settings.propertyType.destroy');

    //General Settings
    Route::get('settings/general/create', [SettingsController::class, 'create'])->name('admin.settings.general.create');
    Route::post('settings/general/store', [SettingsController::class,'store'])->name('admin.settings.general.store');

    //Jobs
    Route::get('/jobs', [JobsController::class, 'index'])->name('admin.jobs');
    Route::get('/jobs/create', [JobsController::class, 'create'])->name('admin.jobs.create');
    Route::get('/jobs/custom/{id}/create', [JobsController::class, 'customCreate'])->name('admin.jobs.custom.create');
    Route::post('/jobs/store', [JobsController::class,'store'])->name('admin.jobs.store');
    Route::get('/jobs/{id}/edit', [JobsController::class, 'edit'])->name('admin.jobs.edit');
    Route::post('/jobs/{id}/update', [JobsController::class, 'update'])->name('admin.jobs.update');
    Route::post('jobs/{id}/destroy', [JobsController::class, 'destroy'])->name('admin.jobs.destroy');
    Route::get('jobs/{id}/show', [JobsController::class,'show'])->name('admin.jobs.show');
    Route::get('/jobs/search', [JobsController::class,'searchData'])->name('admin.jobs.search');

    //invoices
    Route::get('accounts/invoices', [InvoicesController::class, 'index'])->name('admin.invoices');
    Route::get('accounts/invoices/create', [InvoicesController::class, 'create'])->name('admin.invoices.create');
    Route::post('accounts/invoices/store', [InvoicesController::class,'store'])->name('admin.invoices.store');
    Route::get('accounts/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('admin.invoices.edit');
    Route::post('accounts/invoices/{id}/update', [InvoicesController::class, 'update'])->name('admin.invoices.update');
    Route::delete('accounts/invoices/{id}/destroy', [InvoicesController::class, 'destroy'])->name('admin.invoices.destroy');
    Route::get('accounts/invoices/{id}/show', [InvoicesController::class,'show'])->name('admin.invoices.show');
    Route::get('accounts/invoices/search', [InvoicesController::class,'searchData'])->name('admin.invoices.search');

    //AJAX
    Route::get('/get-address-details', [InvoicesController::class, 'getAddressDetails'])->name('get.address.details');

    //Landlord Correspondence Routes
    Route::get('/landlord/{id}/correspondence', [LandlordsCorrespondenceController::class, 'index'])->name('admin.landlords.correspondence');
    Route::get('/landlord/{id}/correspondence/{parent_id}/view', [LandlordsCorrespondenceController::class, 'showChild'])->name('admin.landlords.correspondence.child');
    Route::post('/landlord/{id}/correspondence/delete', [LandlordsCorrespondenceController::class, 'delete'])->name('admin.landlords.correspondence.delete');
    Route::post('/landlord/{id}/correspondence/{parent_id}/move_file', [LandlordsCorrespondenceController::class, 'moveFile'])->name('admin.landlords.correspondence.moveFile');
    Route::post('/landlord/{id}/correspondence/fileVault', [LandlordsCorrespondenceController::class, 'fileVault'])->name('admin.landlords.correspondence.fileVault');
    Route::post('/landlord/{id}/correspondence/{parent_id}/new_folder', [LandlordsCorrespondenceController::class, 'createFolder'])->name('admin.landlords.correspondence.newFolder');
    Route::get('/landlord/{id}/correspondence/{parent_id}/upload', [LandlordsCorrespondenceController::class, 'showUploadFileForm'])->name('admin.landlords.correspondence.uploadFilesForm');
    Route::post('/landlord/{id}/correspondence/{parent_id}/upload', [LandlordsCorrespondenceController::class, 'uploadFiles'])->name('admin.landlords.correspondence.uploadFiles');
    Route::post('/landlord/{id}/correspondence/ajax/addComment', [LandlordsCorrespondenceController::class, 'saveComment'])->name('admin.landlords.correspondence.ajax-add-comment');
    Route::post('/landlord/{id}/correspondence/ajax/editComment', [LandlordsCorrespondenceController::class, 'editComment'])->name('admin.landlords.correspondence.ajax-edit-comment');
    Route::post('/landlord/{id}/correspondence/file/description', [LandlordsCorrespondenceController::class, 'add_edit_description'])->name('admin.landlords.correspondence.add-edit-description');
    Route::post('/landlord/{id}/correspondence/{parent_id}/new-call', [LandlordsCorrespondenceController::class, 'newCall'])->name('admin.landlords.correspondence.newCall');
    Route::post('/landlord/{id}/correspondence/{parent_id}/new-meeting', [LandlordsCorrespondenceController::class, 'storeMeeting'])->name('admin.landlords.correspondence.newMeeting');
    Route::get('landlord/{id}/correspondence/task', [LandlordsCorrespondenceController::class, 'showTaskPage'])->name('admin.suppliers.correspondence.task');
    Route::post('tasks/create/task', [LandlordsCorrespondenceController::class, 'storeTask'])->name('admin.tasks.cross.store');

    //Task Tray
    Route::get('settings/task-tray', [TaskTrayController::class, 'index'])->name('admin.settings.taskTray');
    Route::get('settings/task-tray/create', [TaskTrayController::class, 'create'])->name('admin.settings.taskTray.create');
    Route::post('settings/task-tray/store', [TaskTrayController::class, 'store'])->name('admin.settings.taskTray.store');
    Route::get('settings/task-tray/{id}/edit', [TaskTrayController::class, 'edit'])->name('admin.settings.taskTray.edit');
    Route::post('settings/task-tray/{id}/update', [TaskTrayController::class, 'update'])->name('admin.settings.taskTray.update');
    Route::post('settings/task-tray/{id}/delete', [TaskTrayController::class, 'delete'])->name('admin.settings.taskTray.delete');

    //Tasks
    Route::get('/tasks', [TasksController::class, 'index'])->name('admin.tasks');
    Route::get('tasks/create', [TasksController::class, 'create'])->name('admin.tasks.create');
    Route::post('tasks/create', [TasksController::class, 'store'])->name('admin.tasks.store');
    Route::get('tasks/{id}/edit', [TasksController::class, 'edit'])->name('admin.tasks.edit');
    Route::post('tasks/{id}/update', [TasksController::class, 'update'])->name('admin.tasks.update');
    Route::post('tasks/{id}/delete', [TasksController::class, 'destroy'])->name('admin.tasks.delete');
    Route::post('tasks/{id}/status', [TasksController::class, 'changeStatus'])->name('admin.tasks.changeStatus');
    Route::get('/tasks/searchData', [TasksController::class, 'searchTasks'])->name('admin.tasks.searchData');
    Route::get('/tasks/exportCSV', [TasksController::class, 'exportCSV'])->name('admin.tasks.export');
    Route::delete('/tasks/{taskId}/file', [TasksController::class, 'deleteFile'])->name('admin.task.deleteFile');


    // Event Type
    Route::get('settings/event/type', [EventController::class, 'manageType'])->name('admin.settings.event-type');
    Route::get('settings/event/type/create', [EventController::class, 'createType'])->name('admin.settings.event-type.create');
    Route::post('settings/event/type/store', [EventController::class, 'storeType'])->name('admin.settings.event-type.store');
    Route::get('settings/event/type/{id}/edit', [EventController::class, 'editType'])->name('admin.settings.event-type.edit');
    Route::post('settings/event/type/{id}/update', [EventController::class, 'updateType'])->name('admin.settings.event-type.update');
    Route::post('settings/event/type/{id}/destroy', [EventController::class, 'destroyType'])->name('admin.settings.event-type.destroy');

    //Calendar
    Route::get('/diary', [CalendarController::class, 'index'])->name('admin.diary');
    Route::post('/diary/save-calendar-state', [CalendarController::class, 'saveCalendarState'])->name('admin.saveCalendarState');
    
    //Calendar Events
    Route::get('diary/event/add', [EventController::class, 'create'])->name('admin.diary.event.create');
    Route::post('diary/event/store', [EventController::class, 'store'])->name('admin.diary.event.store');
    Route::get('diary/event/{id}/edit', [EventController::class, 'edit'])->name('admin.diary.event.edit');
    Route::post('diary/event/{id}/update', [EventController::class, 'update'])->name('admin.diary.event.update');
    Route::post('diary/event/{id}/delete', [EventController::class, 'destroy'])->name('admin.diary.event.delete');
    Route::post('diary/event/{id}/deleteAllRecurrences', [EventController::class, 'deleteAllRecurrences'])->name('admin.diary.event.deleteAllRecurrences');
    Route::post('diary/event/{id}/file-delete', [EventController::class, 'fileDelete'])->name('admin.diary.event.fileDelete');

    //Diary Meeting
    Route::get('diary/{id}/meeting/{type}', [CalendarController::class, 'editMeetingForm'])->name('admin.diary.meeting.editForm');
    Route::post('diary/{id}/meeting/{type}', [CalendarController::class, 'storeMeetingForm'])->name('admin.diary.meeting.update');


});


?>