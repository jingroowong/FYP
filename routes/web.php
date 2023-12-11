<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TimeslotController;

Route::resource('HomeLogin','App\Http\Controllers\HomeController');



Auth::routes();
Route::redirect('/', '/HomePage');
//check message seens or not
Route::get('/check-messages/{userId}', [App\Http\Controllers\HomeController::class, 'checkMessages']);


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/HomeLogin', [App\Http\Controllers\HomeController::class, 'index'])->name('HomeLogin');

//Account Type Login
Route::post('/tenant/login', [App\Http\Controllers\HomeController::class, 'TenantLogin'])->name('tenant.login');
Route::post('/agent/login', [App\Http\Controllers\HomeController::class, 'AgentLogin'])->name('agent.login');
Route::post('/admin/login', [App\Http\Controllers\HomeController::class, 'AdminLogin'])->name('admin.login');

//Acount Type Logout
Route::get('/users/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('users.logout');

//Users Registration[ Agent & Tenant]
Route::get('/tenant/register', [App\Http\Controllers\UserRegistrationController::class, 'showTenantRegister'])->name('TenantRegister');
Route::post('/tenant/register.submit', [App\Http\Controllers\UserRegistrationController::class, 'storeTenant'])->name('tenant.register');
Route::get('/agent/register', [App\Http\Controllers\UserRegistrationController::class, 'showAgentRegister'])->name('AgentRegister');
Route::post('/agent/register.submit', [App\Http\Controllers\UserRegistrationController::class, 'storeAgent'])->name('agent.register');

//Users Forget Password and Reset Password
Route::post('/users/forget', [App\Http\Controllers\ForgetAndResetPasswordController::class, 'submitForgetPasswordForm'])->name('users.forget');
Route::get('/reset-password/{token}', [App\Http\Controllers\ForgetAndResetPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('/users/reset', [App\Http\Controllers\ForgetAndResetPasswordController::class, 'submitResetPasswordForm'])->name('users.reset');

//Tenant Account Management[Edit Profile & Set New Password & View My reviews]
Route::get('/tenant/account/{id}', [App\Http\Controllers\TenantAccountContoller::class, 'showMyAccount'])->name('MyTenantAccount');
Route::post('/tenant/photo', [App\Http\Controllers\TenantAccountContoller::class, 'uploadPhoto'])->name('UploadPhoto');
Route::post('/tenant/update/profile', [App\Http\Controllers\TenantAccountContoller::class, 'updateProfile'])->name('UpdateProfile');
Route::post('/tenant/update/password', [App\Http\Controllers\TenantAccountContoller::class, 'updatePassword'])->name('UpdatePassword');

//Agent or Admin Account Management[]
Route::get('/agent/account/{id}', [App\Http\Controllers\AgentController::class, 'showProfile'])->name('MyAgentAccount');
Route::post('/agent/photo', [App\Http\Controllers\AgentController::class, 'uploadAgentPhoto'])->name('UploadAgentPhoto');
Route::post('/agent/update/profile', [App\Http\Controllers\AgentController::class, 'updateAgentProfile'])->name('UpdateAgentProfile');
Route::get('/agent/change/password', [App\Http\Controllers\AgentController::class, 'showChangePasswordForm'])->name('ChangePassword');
Route::post('/agent/update/password', [App\Http\Controllers\AgentController::class, 'updateNewPassword'])->name('UpdateNewPassword');

//Tenant Main Home Page
Route::get('/HomePage', [App\Http\Controllers\HomeController::class, 'HomePage'])->name('HomePage');
Route::get('/search', [App\Http\Controllers\PropertyController::class, 'HomeSearch'])->name('home.search');
Route::get('/get-filters', [App\Http\Controllers\PropertyController::class,'getFilters'])->name('getFilters');
Route::get('/advanced-filters', [App\Http\Controllers\PropertyController::class,'AdvancedFilter'])->name('advanced.filter');
Route::get('/sort-properties', [App\Http\Controllers\PropertyController::class,'SortProperty'])->name('sort.properties');
Route::get('/map', [App\Http\Controllers\HomeController::class, 'map'])->name('map');

//Tenant Reviews [Add Reviews & View Reviews & Edit Reviews & Delete Reviews]
Route::get('/propertyDetails', [App\Http\Controllers\ReviewController::class, 'ViewPropertyDetails'])->name('PropertyDetails');
Route::post('/add/review', [App\Http\Controllers\ReviewController::class, 'AddReview'])->name('add_review');
Route::post('/reply/review', [App\Http\Controllers\ReviewController::class, 'ReplyReview'])->name('reply_review');
Route::post('/user-reviews', [App\Http\Controllers\ReviewController::class, 'GetUserReviews'])->name('user.reviews');
Route::post('/delete-reviews', [App\Http\Controllers\ReviewController::class, 'DeleteReviews'])->name('delete_review');
Route::post('/edit-reviews', [App\Http\Controllers\ReviewController::class, 'EditReviews'])->name('edit-review');


//Tenant [ Agent Lists ] 
Route::get('/agentLists', [App\Http\Controllers\AgentController::class, 'ViewAgentLists'])->name('AgentLists');
Route::get('/agents/search', [App\Http\Controllers\AgentController::class, 'SearchAgent'])->name('SearchAgent');
Route::get('/agent/{id}/agentDetail', [App\Http\Controllers\AgentController::class, 'AgentDetails'])->name('AgentDetails');

//Tenant [ Wish List]
Route::get('/api/{id}/wishLists', [App\Http\Controllers\WishListController::class, 'getWishlist']);
Route::get('/{id}/wishLists', [App\Http\Controllers\WishListController::class, 'ViewWishList'])->name('WishLists');
Route::post('/wishlist/delete-selected', [App\Http\Controllers\WishListController::class, 'deleteSelected'])->name('RemoveWishList');
Route::post('/wishlist/toggle', [App\Http\Controllers\WishListController::class, 'ToggleWishList'])->name('ToggleWishList');
Route::get('/wishilists/compare', [App\Http\Controllers\WishListController::class, 'ViewCompareList'])->name('ViewCompareList');

//Timeslot Route
Route::resource('timeslots', 'App\Http\Controllers\TimeslotController');
Route::get('/agent/timeslots', 'App\Http\Controllers\TimeslotController@index')->name('timeslots');
Route::get('/agent/calendar', 'App\Http\Controllers\TimeslotController@calendar')->name('timeslots.calendar');
Route::get('/agent/{timeslotID}/deleteTimeslot', 'App\Http\Controllers\TimeslotController@destroy')->name('timeslots.destroy');

//Appointment Route
Route::resource('appointments', 'App\Http\Controllers\AppointmentController');
Route::get('/appointments', 'App\Http\Controllers\AppointmentController@index')->name('appointments');
Route::get('agent/appointments', 'App\Http\Controllers\AppointmentController@agentIndex')->name('appointments.agentIndex');
Route::get('/appointments/create/{propertyID}', 'App\Http\Controllers\AppointmentController@create')->name('appointment.create');
Route::get('appointments/{appID}/cancel', 'App\Http\Controllers\AppointmentController@cancel')->name('appointments.cancel');
Route::get('appointments/{appID}/update', 'App\Http\Controllers\AppointmentController@update')->name('appointments.update');
Route::get('appointments/{appID}/updatebyAgent', 'App\Http\Controllers\AppointmentController@updateByAgent')->name('appointments.updateByAgent');
Route::get('appointments/{appID}/showAppointment', 'App\Http\Controllers\AppointmentController@showTenant')->name('appointments.showTenant');
Route::get('appointments/{appID}/editAppointment', 'App\Http\Controllers\AppointmentController@editTenant')->name('appointments.editTenant');
Route::get('appointments/{appID}/cancelAppointment', 'App\Http\Controllers\AppointmentController@agentCancel')->name('appointments.agentCancel');
Route::get('appointments/set-reminder/{appID}', 'App\Http\Controllers\AppointmentController@setReminder')->name('appointments.setReminder');

//Notification Route
Route::resource('notifications', 'App\Http\Controllers\NotificationController');
Route::get('/tenant/notifications', 'App\Http\Controllers\NotificationController@tenantIndex')->name('notifications.tenant');
Route::get('/agent/notifications', 'App\Http\Controllers\NotificationController@index')->name('notifications');
Route::get('/tenant/searchNotification', 'App\Http\Controllers\NotificationController@tenantSearch')->name('notifications.tenantSearch');
Route::get('/agent/searchNotification', 'App\Http\Controllers\NotificationController@agentSearch')->name('notifications.agentSearch');

Route::post('/notifications/mark-as-read', 'App\Http\Controllers\NotificationController@markAsRead')->name('notifications.markAsRead');
Route::post('/notifications/delete','App\Http\Controllers\NotificationController@delete')->name('notifications.delete');

//Property Route
Route::resource('properties', 'App\Http\Controllers\PropertyController');
Route::get('agent/properties', 'App\Http\Controllers\PropertyController@index')->name('properties');

Route::get('admin/properties', 'App\Http\Controllers\PropertyController@indexAll')->name('properties.all');
Route::get('/agent/createProperty', 'App\Http\Controllers\PropertyController@create')->name('createProperty');
Route::get('/agent/{propertyID}/show', 'App\Http\Controllers\PropertyController@showAgent')->name('properties.showAgent');
Route::get('/agent/{propertyID}/delete', 'App\Http\Controllers\PropertyController@destroy')->name('properties.destroy');
Route::post('/agent/{propertyID}/update', 'App\Http\Controllers\PropertyController@update')->name('properties.update');
Route::get('tenant/properties-list', 'App\Http\Controllers\PropertyController@propertyList')->name('propertyList');
Route::get('searchProperty', 'App\Http\Controllers\PropertyController@search')->name('properties.search');
Route::get('searchAllProperty', 'App\Http\Controllers\PropertyController@searchAll')->name('properties.searchAll');
Route::get('properties/{propertyID}/apply', 'App\Http\Controllers\PropertyController@apply')->name('properties.apply');
Route::get('properties/{propertyID}/submitApplication', 'App\Http\Controllers\PropertyController@submitApplication')->name('properties.submitApplication');
Route::get('properties/{propertyID}/approve', 'App\Http\Controllers\PropertyController@approve')->name('properties.approve');
Route::get('application', 'App\Http\Controllers\PropertyController@applicationIndex')->name('applicationIndex');
Route::get('properties/{propertyID}/reject', 'App\Http\Controllers\PropertyController@reject')->name('properties.reject');

//Refund Route
Route::resource('refunds', 'App\Http\Controllers\RefundController');
Route::get('/admin/refunds', 'App\Http\Controllers\RefundController@index')->name('refunds');
Route::get('refunds/{propertyRentalID}/create', 'App\Http\Controllers\RefundController@create')->name('refunds.create');
Route::post('refunds/approve', 'App\Http\Controllers\RefundController@approve')->name('refunds.approve');
Route::post('refunds/reject', 'App\Http\Controllers\RefundController@reject')->name('refunds.reject');

//Payment Route
Route::resource('payments', 'App\Http\Controllers\PaymentController');
Route::get('tenant/paymentHistory', 'App\Http\Controllers\PaymentController@index')->name('paymentHistory');
Route::get('payment/{propertyRentalID}/create', 'App\Http\Controllers\PaymentController@create')->name('payments.create');
Route::get('payment/{propertyRentalID}/store', 'App\Http\Controllers\PaymentController@store')->name('payments.store');
Route::get('payment/{propertyRentalID}/release', 'App\Http\Controllers\PaymentController@release')->name('payments.release');
Route::get('payment/{propertyRentalID}/receipt', 'App\Http\Controllers\PaymentController@paymentReceipt')->name('payments.paymentReceipt');
Route::get('payment/{propertyRentalID}/releaseAdmin', 'App\Http\Controllers\PaymentController@releaseAdmin')->name('payments.releaseAdmin');
Route::get('payment/{propertyRentalID}/receiptAdmin', 'App\Http\Controllers\PaymentController@paymentReceiptAdmin')->name('payments.paymentReceiptAdmin');

Route::get('/agent/wallet', 'App\Http\Controllers\WalletController@index')->name('agentWallet');
Route::post('/agent/wallet/payment', 'App\Http\Controllers\WalletController@payment')->name('posting.payment');
Route::get('/agent/wallet/make-payment', 'App\Http\Controllers\WalletController@walletPayment')->name('makePayment');
Route::post('/agent/wallet/withdraw', 'App\Http\Controllers\WalletController@withdraw')->name('withdraw');
Route::get('/agent/wallet/withdraw-money', 'App\Http\Controllers\WalletController@walletWithdraw')->name('withdrawMoney');
Route::get('/agent/wallet/topup', 'App\Http\Controllers\WalletController@topUp')->name('topUp');
Route::get('/agent/wallet/topup-money', 'App\Http\Controllers\WalletController@walletTopUp')->name('topUpMoney');
Route::get('/agent/wallet/pending-payment', 'App\Http\Controllers\WalletController@walletPending')->name('pendingPayment');
Route::get('/agent/request-payment/{propertyRentalID}/', 'App\Http\Controllers\WalletController@request')->name('agent.requestPayment');

Route::post('/tenant/session', 'App\Http\Controllers\StripeController@sessionTenant')->name('sessionTenant');
Route::get('/tenant/success', 'App\Http\Controllers\StripeController@successTenant')->name('successTenant');
Route::post('/session', 'App\Http\Controllers\StripeController@session')->name('session');
Route::get('/success', 'App\Http\Controllers\StripeController@success')->name('success');

//Report Route
Route::resource('reports', 'App\Http\Controllers\ReportController');
Route::get('/reports', 'App\Http\Controllers\ReportController@showReports')->name('reports');
Route::post('/reports/generateReport', 'App\Http\Controllers\ReportController@generateReport')->name('reports.generate');
Route::get('admin/indexAgent', 'App\Http\Controllers\ReportController@indexAgent')->name('indexAgent');
Route::get('admin/{agentID}/show','App\Http\Controllers\ReportController@showAgent')->name('showAgent');
Route::get('admin/searchAgent','App\Http\Controllers\ReportController@searchAgent')->name('searchAgent');
Route::get('admin/createAgent','App\Http\Controllers\ReportController@createAgent')->name('createAgent');
Route::get('admin/{agentID}/deactivate/{deactivationReason?}','App\Http\Controllers\ReportController@deleteAgent')->name('deleteAgent');
Route::get('admin/{agentID}/updateAgent','App\Http\Controllers\ReportController@updateAgent')->name('updateAgent');
Route::post('admin/update','App\Http\Controllers\ReportController@update')->name('agents.update');
Route::post('admin/register/agent', 'App\Http\Controllers\ReportController@storeAgent')->name('agent.registerByAdmin');
Route::get('/agent/{id}/admin/agentDetail', [App\Http\Controllers\AgentController::class, 'AgentDetailsAdmin'])->name('AgentDetailsAdmin');
//Footer Route
Route::get('rentspace/pricing', 'App\Http\Controllers\NotificationController@pricing')->name('pricing');
Route::get('rentspace/FAQ', 'App\Http\Controllers\NotificationController@faq')->name('faq');

Route::get('rentspace/aboutUs', 'App\Http\Controllers\NotificationController@about')->name('aboutUs');

Route::get('rentspace/feature', 'App\Http\Controllers\NotificationController@feature')->name('feature');

Route::get('rentspace/home', 'App\Http\Controllers\NotificationController@home')->name('home');


Route::get('/api/timeslots', [TimeslotController::class, 'getTimeslots']);

Route::get('/notifications/latest', 'App\Http\Controllers\NotificationController@getLatestNotifications')->name('notifications.latest');


//Search History
Route::get('/{id}/searchHistory', [App\Http\Controllers\TenantAccountContoller::class, 'viewSearchList'])->name('SearchHistory');
Route::post('/searchHistory/delete-selected', [App\Http\Controllers\TenantAccountContoller::class, 'RemoveSelected'])->name('RemoveHistory');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
