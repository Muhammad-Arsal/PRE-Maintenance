<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\JobsController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaskTrayController;
use App\Http\Controllers\Admin\TenantsController; 
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LandlordsController;
use App\Http\Controllers\Admin\ContractorsController;
use App\Http\Controllers\Admin\TenantEventController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\PropertyEventController;
use App\Http\Controllers\Admin\ContractorTypeController;
use App\Http\Controllers\Admin\TenantCalendarController;
use App\Http\Controllers\Admin\PropertyCalendarController;
use App\Http\Controllers\Admin\TenantsCorrespondenceController;
use App\Http\Controllers\Auth\AdminEmailVerificationController;
use App\Http\Controllers\Admin\LandlordsCorrespondenceController;
use App\Http\Controllers\Admin\ProperptyCorrespondenceController;
use App\Http\Controllers\Admin\ContractorCorrespondenceController;



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
    Route::get('/tenants/{id}/edit/property', [TenantsController::class, 'editProperty'])->name('admin.settings.tenants.edit.property');
    Route::put('/tenants/{id}/update', [TenantsController::class, 'update'])->name('admin.settings.tenants.update');
    Route::put('/tenants/{id}/update/property', [TenantsController::class, 'storeProperty'])->name('admin.settings.tenants.update.property');
    Route::post('tenants/{id}/destroy', [TenantsController::class, 'destroy'])->name('admin.settings.tenants.destroy');
    Route::post('/tenants/{id}/delete', [TenantsController::class, 'delete'])->name('admin.settings.tenants.delete');
    Route::get('/tenants/search', [TenantsController::class, 'searchData'])->name('admin.settings.tenants.search');
    Route::get('tenants/{id}/show', [TenantsController::class,'show'])->name('admin.settings.tenants.show');
    Route::get('tenants/{id}/past_tenancy', [TenantsController::class, 'pastTenancy'])->name('admin.properties.past.tenancy');
    Route::get('tenants/{id}/jobs', [TenantsController::class, 'jobs'])->name('admin.tenants.jobs');
    
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
    Route::get('/landlords/{id}/invoices', [LandlordsController::class, 'invoices'])->name('admin.settings.landlords.invoices');
    Route::get('/landlords/{id}/quotes', [LandlordsController::class, 'jobs'])->name('admin.settings.landlords.jobs');
    Route::get('/landlords/{jobId}/quotes/{landlordId}', [LandlordsController::class, 'quotes'])->name('admin.settings.landlords.jobs.quotes');

    // Contractors
    Route::get('/contractors', [ContractorsController::class, 'index'])->name('admin.settings.contractors');
    Route::get('/contractors/create', [ContractorsController::class, 'create'])->name('admin.settings.contractors.create');
    Route::post('/contractors/store', [ContractorsController::class, 'store'])->name('admin.settings.contractors.store');
    Route::get('/contractors/{id}/edit', [ContractorsController::class, 'edit'])->name('admin.settings.contractors.edit');
    Route::get('/contractors/{id}/edit/address', [ContractorsController::class, 'editAddress'])->name('admin.settings.contractors.edit.address');
    Route::put('/contractors/{id}/update', [ContractorsController::class, 'update'])->name('admin.settings.contractors.update');
    Route::put('/contractors/{id}/update/address', [ContractorsController::class, 'updateAddress'])->name('admin.settings.contractors.update.address');
    Route::post('contractors/{id}/destroy', [ContractorsController::class, 'destroy'])->name('admin.settings.contractors.destroy');
    Route::post('/contractors/{id}/delete', [ContractorsController::class, 'delete'])->name('admin.settings.contractors.delete');
    Route::get('/contractors/search', [ContractorsController::class, 'searchData'])->name('admin.settings.contractors.search');
    Route::get('contractors/{id}/view/jobs', [ContractorsController::class, 'jobs'])->name('admin.contractors.viewjobs');
    Route::get('contractors/{id}/view/invoices', [ContractorsController::class, 'invoices'])->name('admin.contractors.invoices.index');
    Route::get('contractors/{jobId}/quote/{contractorId}', [ContractorsController::class, 'quote'])->name('admin.contractors.quote');

    //Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('admin.properties');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('admin.properties.create');
    Route::post('/properties/store', [PropertyController::class, 'store'])->name('admin.properties.store');
    Route::get('/properties/{id}/edit', [PropertyController::class, 'edit'])->name('admin.properties.edit');
    Route::get('/properties/{id}/edit/address', [PropertyController::class, 'editAddress'])->name('admin.properties.edit.address');
    Route::post('/properties/{id}/update', [PropertyController::class, 'update'])->name('admin.properties.update');
    Route::post('/properties/{id}/update/address', [PropertyController::class, 'updateAddress'])->name('admin.properties.update.address');
    Route::post('properties/{id}/destroy', [PropertyController::class, 'destroy'])->name('admin.properties.destroy');
    Route::get('properties/{id}/show', [PropertyController::class, 'show'])->name('admin.properties.show');
    Route::get('/properties/search', [PropertyController::class,'searchData'])->name('admin.properties.search');
    Route::get('properties/{id}/diary', [PropertyController::class, 'diary'])->name('admin.properties.diary');
    Route::put('/properties/diary/{id}', [PropertyController::class, 'updateDiary'])->name('admin.properties.diaryUpdate');
    Route::post('properties/{id}/editDiary', [PropertyController::class, 'diaryStore'])->name('admin.properties.diaryStore');
    Route::post('properties/{id}/deleteDiary', [PropertyController::class, 'diaryDelete'])->name('admin.properties.diaryDelete');
    Route::get('properties/{id}/view/jobs', [PropertyController::class, 'jobs'])->name('admin.properties.viewjobs');
    Route::get('properties/{id}/view/invoices', [PropertyController::class, 'invoices'])->name('admin.properties.invoices.index');
    Route::get('properties/{id}/past_tenant', [PropertyController::class, 'pastTenant'])->name('admin.properties.past.tenant');
    Route::get('properties/{id}/current_tenant', [PropertyController::class, 'currentTenant'])->name('admin.properties.current.tenant');
    Route::get('properties/{id}/view/jobs/quotes', [PropertyController::class, 'quotes'])->name('admin.properties.viewjobs.quotes');
    

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
    Route::get('/jobs/{id}/edit/contractor_list', [JobsController::class, 'editContractorList'])->name('admin.jobs.edit.contractorList');
    Route::post('/jobs/{id}/update', [JobsController::class, 'update'])->name('admin.jobs.update');
    Route::post('/jobs/{id}/update/contractor_list', [JobsController::class, 'updateContractorList'])->name('admin.jobs.update.contractor_list');
    Route::post('jobs/{id}/destroy', [JobsController::class, 'destroy'])->name('admin.jobs.destroy');
    Route::get('jobs/{id}/show', [JobsController::class,'show'])->name('admin.jobs.show');
    Route::get('/jobs/search', [JobsController::class,'searchData'])->name('admin.jobs.search');

    //invoices
    Route::get('accounts/invoices', [InvoicesController::class, 'index'])->name('admin.invoices');
    Route::get('accounts/invoices/create', [InvoicesController::class, 'create'])->name('admin.invoices.create');
    Route::post('accounts/invoices/store', [InvoicesController::class,'store'])->name('admin.invoices.store');
    Route::get('accounts/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('admin.invoices.edit');
    Route::get('accounts/invoices/{id}/update/status', [InvoicesController::class, 'editStatus'])->name('admin.invoices.edit.status');
    Route::post('accounts/invoices/{id}/update', [InvoicesController::class, 'update'])->name('admin.invoices.update');
    Route::post('accounts/invoices/{id}/save/status', [InvoicesController::class, 'storeStatus'])->name('admin.invoices.update.status');
    Route::delete('accounts/invoices/{id}/destroy', [InvoicesController::class, 'destroy'])->name('admin.invoices.destroy');
    Route::get('accounts/invoices/{id}/show', [InvoicesController::class,'show'])->name('admin.invoices.show');
    Route::get('accounts/invoices/search', [InvoicesController::class,'searchData'])->name('admin.invoices.search');

    //AJAX
    Route::get('admin/get-address-details', [InvoicesController::class, 'getAddressDetails'])->name('admin.get.address.details');

    //Landlord Correspondence Routes
    Route::get('/landlords/{id}/correspondence', [LandlordsCorrespondenceController::class, 'index'])->name('admin.landlords.correspondence');
    Route::get('/landlords/{id}/correspondence/{parent_id}/view', [LandlordsCorrespondenceController::class, 'showChild'])->name('admin.landlords.correspondence.child');
    Route::post('/landlords/{id}/correspondence/delete', [LandlordsCorrespondenceController::class, 'delete'])->name('admin.landlords.correspondence.delete');
    Route::post('/landlords/{id}/correspondence/{parent_id}/move_file', [LandlordsCorrespondenceController::class, 'moveFile'])->name('admin.landlords.correspondence.moveFile');
    Route::post('/landlords/{id}/correspondence/fileVault', [LandlordsCorrespondenceController::class, 'fileVault'])->name('admin.landlords.correspondence.fileVault');
    Route::post('/landlords/{id}/correspondence/{parent_id}/new_folder', [LandlordsCorrespondenceController::class, 'createFolder'])->name('admin.landlords.correspondence.newFolder');
    Route::get('/landlords/{id}/correspondence/{parent_id}/upload', [LandlordsCorrespondenceController::class, 'showUploadFileForm'])->name('admin.landlords.correspondence.uploadFilesForm');
    Route::post('/landlords/{id}/correspondence/{parent_id}/upload', [LandlordsCorrespondenceController::class, 'uploadFiles'])->name('admin.landlords.correspondence.uploadFiles');
    Route::post('/landlords/{id}/correspondence/ajax/addComment', [LandlordsCorrespondenceController::class, 'saveComment'])->name('admin.landlords.correspondence.ajax-add-comment');
    Route::post('/landlords/{id}/correspondence/ajax/editComment', [LandlordsCorrespondenceController::class, 'editComment'])->name('admin.landlords.correspondence.ajax-edit-comment');
    Route::post('/landlords/{id}/correspondence/file/description', [LandlordsCorrespondenceController::class, 'add_edit_description'])->name('admin.landlords.correspondence.add-edit-description');
    Route::post('/landlords/{id}/correspondence/{parent_id}/new-call', [LandlordsCorrespondenceController::class, 'newCall'])->name('admin.landlords.correspondence.newCall');
    Route::post('/landlords/{id}/correspondence/{parent_id}/new-meeting', [LandlordsCorrespondenceController::class, 'storeMeeting'])->name('admin.landlords.correspondence.newMeeting');
    Route::get('landlords/{id}/correspondence/task', [LandlordsCorrespondenceController::class, 'showTaskPage'])->name('admin.landlord.correspondence.task');
    Route::post('tasks/create/task', [LandlordsCorrespondenceController::class, 'storeTask'])->name('admin.landlord.tasks.cross.store');

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

    //Diary Calendar
    Route::get('/diary', [CalendarController::class, 'index'])->name('admin.diary');
    Route::post('/diary/save-calendar-state', [CalendarController::class, 'saveCalendarState'])->name('admin.saveCalendarState');
    
    //Diary Calendar Events
    Route::get('diary/event/add', [EventController::class, 'create'])->name('admin.diary.event.create');
    Route::post('diary/event/store', [EventController::class, 'store'])->name('admin.diary.event.store');
    Route::get('diary/event/{id}/edit', [EventController::class, 'edit'])->name('admin.diary.event.edit');
    Route::post('diary/event/{id}/update', [EventController::class, 'update'])->name('admin.diary.event.update');
    Route::post('diary/event/{id}/delete', [EventController::class, 'destroy'])->name('admin.diary.event.delete');
    Route::post('diary/event/{id}/deleteAllRecurrences', [EventController::class, 'deleteAllRecurrences'])->name('admin.diary.event.deleteAllRecurrences');
    Route::post('diary/event/{id}/file-delete', [EventController::class, 'fileDelete'])->name('admin.diary.event.fileDelete');

    //Diary Diary Meeting
    Route::get('diary/{id}/meeting/{type}', [CalendarController::class, 'editMeetingForm'])->name('admin.diary.meeting.editForm');
    Route::post('diary/{id}/meeting/{type}', [CalendarController::class, 'storeMeetingForm'])->name('admin.diary.meeting.update');


    //Tenant Correspondence Routes
    Route::get('/tenants/{id}/correspondence', [TenantsCorrespondenceController::class, 'index'])->name('admin.tenants.correspondence');
    Route::get('/tenants/{id}/correspondence/{parent_id}/view', [TenantsCorrespondenceController::class, 'showChild'])->name('admin.tenants.correspondence.child');
    Route::post('/tenants/{id}/correspondence/delete', [TenantsCorrespondenceController::class, 'delete'])->name('admin.tenants.correspondence.delete');
    Route::post('/tenants/{id}/correspondence/{parent_id}/move_file', [TenantsCorrespondenceController::class, 'moveFile'])->name('admin.tenants.correspondence.moveFile');
    Route::post('/tenants/{id}/correspondence/fileVault', [TenantsCorrespondenceController::class, 'fileVault'])->name('admin.tenants.correspondence.fileVault');
    Route::post('/tenants/{id}/correspondence/{parent_id}/new_folder', [TenantsCorrespondenceController::class, 'createFolder'])->name('admin.tenants.correspondence.newFolder');
    Route::get('/tenants/{id}/correspondence/{parent_id}/upload', [TenantsCorrespondenceController::class, 'showUploadFileForm'])->name('admin.tenants.correspondence.uploadFilesForm');
    Route::post('/tenants/{id}/correspondence/{parent_id}/upload', [TenantsCorrespondenceController::class, 'uploadFiles'])->name('admin.tenants.correspondence.uploadFiles');
    Route::post('/tenants/{id}/correspondence/ajax/addComment', [TenantsCorrespondenceController::class, 'saveComment'])->name('admin.tenants.correspondence.ajax-add-comment');
    Route::post('/tenants/{id}/correspondence/ajax/editComment', [TenantsCorrespondenceController::class, 'editComment'])->name('admin.tenants.correspondence.ajax-edit-comment');
    Route::post('/tenants/{id}/correspondence/file/description', [TenantsCorrespondenceController::class, 'add_edit_description'])->name('admin.tenants.correspondence.add-edit-description');
    Route::post('/tenants/{id}/correspondence/{parent_id}/new-call', [TenantsCorrespondenceController::class, 'newCall'])->name('admin.tenants.correspondence.newCall');
    Route::post('/tenants/{id}/correspondence/{parent_id}/new-meeting', [TenantsCorrespondenceController::class, 'storeMeeting'])->name('admin.tenants.correspondence.newMeeting');
    Route::get('/tenants/{id}/correspondence/task', [TenantsCorrespondenceController::class, 'showTaskPage'])->name('admin.suppliers.correspondence.task');
    Route::post('/tenants/tasks/create/task', [TenantsCorrespondenceController::class, 'storeTask'])->name('admin.tasks.cross.store');

    //Properties Correspondence Routes
    Route::get('properties/{id}/correspondence', [ProperptyCorrespondenceController::class, 'index'])->name('admin.propertys.correspondence');
    Route::get('properties/{id}/correspondence/{parent_id}/view', [ProperptyCorrespondenceController::class, 'showChild'])->name('admin.propertys.correspondence.child');
    Route::post('properties/{id}/correspondence/delete', [ProperptyCorrespondenceController::class, 'delete'])->name('admin.propertys.correspondence.delete');
    Route::post('properties/{id}/correspondence/{parent_id}/move_file', [ProperptyCorrespondenceController::class, 'moveFile'])->name('admin.propertys.correspondence.moveFile');
    Route::post('properties/{id}/correspondence/fileVault', [ProperptyCorrespondenceController::class, 'fileVault'])->name('admin.propertys.correspondence.fileVault');
    Route::post('properties/{id}/correspondence/{parent_id}/new_folder', [ProperptyCorrespondenceController::class, 'createFolder'])->name('admin.propertys.correspondence.newFolder');
    Route::get('properties/{id}/correspondence/{parent_id}/upload', [ProperptyCorrespondenceController::class, 'showUploadFileForm'])->name('admin.propertys.correspondence.uploadFilesForm');
    Route::post('properties/{id}/correspondence/{parent_id}/upload', [ProperptyCorrespondenceController::class, 'uploadFiles'])->name('admin.propertys.correspondence.uploadFiles');
    Route::post('properties/{id}/correspondence/ajax/addComment', [ProperptyCorrespondenceController::class, 'saveComment'])->name('admin.propertys.correspondence.ajax-add-comment');
    Route::post('properties/{id}/correspondence/ajax/editComment', [ProperptyCorrespondenceController::class, 'editComment'])->name('admin.propertys.correspondence.ajax-edit-comment');
    Route::post('properties/{id}/correspondence/file/description', [ProperptyCorrespondenceController::class, 'add_edit_description'])->name('admin.propertys.correspondence.add-edit-description');
    Route::post('properties/{id}/correspondence/{parent_id}/new-call', [ProperptyCorrespondenceController::class, 'newCall'])->name('admin.propertys.correspondence.newCall');
    Route::post('properties/{id}/correspondence/{parent_id}/new-meeting', [ProperptyCorrespondenceController::class, 'storeMeeting'])->name('admin.propertys.correspondence.newMeeting');
    Route::get('properties/{id}/correspondence/task', [ProperptyCorrespondenceController::class, 'showTaskPage'])->name('admin.propertys.correspondence.task');
    Route::post('properties/tasks/create/task', [ProperptyCorrespondenceController::class, 'storeTask'])->name('admin.propertys.tasks.cross.store');

    //Contractor Correspondence Routes
    Route::get('contractors/{id}/correspondence', [ContractorCorrespondenceController::class, 'index'])->name('admin.contractors.correspondence');
    Route::get('contractors/{id}/correspondence/{parent_id}/view', [ContractorCorrespondenceController::class, 'showChild'])->name('admin.contractors.correspondence.child');
    Route::post('contractors/{id}/correspondence/delete', [ContractorCorrespondenceController::class, 'delete'])->name('admin.contractors.correspondence.delete');
    Route::post('contractors/{id}/correspondence/{parent_id}/move_file', [ContractorCorrespondenceController::class, 'moveFile'])->name('admin.contractors.correspondence.moveFile');
    Route::post('contractors/{id}/correspondence/fileVault', [ContractorCorrespondenceController::class, 'fileVault'])->name('admin.contractors.correspondence.fileVault');
    Route::post('contractors/{id}/correspondence/{parent_id}/new_folder', [ContractorCorrespondenceController::class, 'createFolder'])->name('admin.contractors.correspondence.newFolder');
    Route::get('contractors/{id}/correspondence/{parent_id}/upload', [ContractorCorrespondenceController::class, 'showUploadFileForm'])->name('admin.contractors.correspondence.uploadFilesForm');
    Route::post('contractors/{id}/correspondence/{parent_id}/upload', [ContractorCorrespondenceController::class, 'uploadFiles'])->name('admin.contractors.correspondence.uploadFiles');
    Route::post('contractors/{id}/correspondence/ajax/addComment', [ContractorCorrespondenceController::class, 'saveComment'])->name('admin.contractors.correspondence.ajax-add-comment');
    Route::post('contractors/{id}/correspondence/ajax/editComment', [ContractorCorrespondenceController::class, 'editComment'])->name('admin.contractors.correspondence.ajax-edit-comment');
    Route::post('contractors/{id}/correspondence/file/description', [ContractorCorrespondenceController::class, 'add_edit_description'])->name('admin.contractors.correspondence.add-edit-description');
    Route::post('contractors/{id}/correspondence/{parent_id}/new-call', [ContractorCorrespondenceController::class, 'newCall'])->name('admin.contractors.correspondence.newCall');
    Route::post('contractors/{id}/correspondence/{parent_id}/new-meeting', [ContractorCorrespondenceController::class, 'storeMeeting'])->name('admin.contractors.correspondence.newMeeting');
    Route::get('contractors/{id}/correspondence/task', [ContractorCorrespondenceController::class, 'showTaskPage'])->name('admin.contractors.correspondence.task');
    Route::post('contractors/tasks/create/task', [ContractorCorrespondenceController::class, 'storeTask'])->name('admin.contractors.tasks.cross.store');


    // Contractor Types
    Route::get('settings/contractorType', [ContractorTypeController::class, 'index'])->name('admin.settings.contractorType');
    Route::get('settings/contractorType/create', [ContractorTypeController::class, 'create'])->name('admin.settings.contractorType.create');
    Route::post('settings/contractorType/store', [ContractorTypeController::class,'store'])->name('admin.settings.contractorType.store');
    Route::get('settings/contractorType/{id}/edit', [ContractorTypeController::class, 'edit'])->name('admin.settings.contractorType.edit');
    Route::post('settings/contractorType/{id}/update', [ContractorTypeController::class, 'update'])->name('admin.settings.contractorType.update');
    Route::post('settings/contractorType/{id}/destroy', [ContractorTypeController::class, 'destroy'])->name('admin.settings.contractorType.destroy');

    //pdf generate 
    Route::get('admin/invoices/generatePDF/{id}', [InvoicesController::class, 'generatePDF'])->name('admin.invoices.generatePDF');

    //Tenant Calendar Routes
    Route::get('/tenants/{id}/calendar', [TenantCalendarController::class, 'index'])->name('admin.tenants.calendar');
    Route::post('tenants/diary/save-calendar-state', [TenantCalendarController::class, 'saveCalendarState'])->name('admin.tenants.saveCalendarState');
    Route::get('tenants/diary/event/{id}/add', [TenantEventController::class, 'create'])->name('admin.tenants.diary.event.create');
    Route::post('tenants/diary/event/{id}/store', [TenantEventController::class, 'store'])->name('admin.tenants.diary.event.store');
    Route::get('tenants/diary/event/{id}/edit/{tenant_id}', [TenantEventController::class, 'edit'])->name('admin.tenants.diary.event.edit');
    Route::post('tenants/diary/event/{id}/update/{tenant_id}', [TenantEventController::class, 'update'])->name('admin.tenants.diary.event.update');
    Route::post('tenants/diary/event/{id}/delete{tenant_id}', [TenantEventController::class, 'destroy'])->name('admin.tenants.diary.event.delete');
    Route::post('tenants/diary/event/{id}/deleteAllRecurrences/{tenant_id}', [TenantEventController::class, 'deleteAllRecurrences'])->name('admin.tenants.diary.event.deleteAllRecurrences');
    Route::post('tenants/diary/event/{id}/file-delete/{tenant_id}', [TenantEventController::class, 'fileDelete'])->name('admin.tenants.diary.event.fileDelete');

    //Property Calendar Routes
    Route::get('/properties/{id}/calendar', [PropertyCalendarController::class, 'index'])->name('admin.properties.calendar');
    Route::post('properties/diary/save-calendar-state', [PropertyCalendarController::class, 'saveCalendarState'])->name('admin.properties.saveCalendarState');
    Route::get('properties/diary/event/{id}/add', [PropertyEventController::class, 'create'])->name('admin.properties.diary.event.create');
    Route::post('properties/diary/event/{id}/store', [PropertyEventController::class, 'store'])->name('admin.properties.diary.event.store');
    Route::get('properties/diary/event/{id}/edit/{property_id}', [PropertyEventController::class, 'edit'])->name('admin.properties.diary.event.edit');
    Route::post('properties/diary/event/{id}/update/{property_id}', [PropertyEventController::class, 'update'])->name('admin.properties.diary.event.update'); 
    Route::post('properties/diary/event/{id}/delete{property_id}', [PropertyEventController::class, 'destroy'])->name('admin.properties.diary.event.delete');
    Route::post('properties/diary/event/{id}/deleteAllRecurrences/{property_id}', [PropertyEventController::class, 'deleteAllRecurrences'])->name('admin.properties.diary.event.deleteAllRecurrences');
    Route::post('properties/diary/event/{id}/file-delete/{property_id}', [PropertyEventController::class, 'fileDelete'])->name('admin.properties.diary.event.fileDelete');
});


?>