<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConsultationFormController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HealthServicesController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// URL::forceScheme('https');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	if(Auth::check()) {
		return redirect()->route('home');
	} else {
		return redirect()->route('login');
	}
    return view('welcome');
});

Auth::routes();


Route::group(['middleware' => ['schedule', 'onboard', 'auth']], function () {
	Route::resource('company', 'App\Http\Controllers\CompanyController');
	Route::resource('types', 'App\Http\Controllers\TypeController');
	Route::resource('employees', 'App\Http\Controllers\EmployeeController');
	Route::resource('forms', 'App\Http\Controllers\FormController');
	Route::resource('consultations', 'App\Http\Controllers\ConsultationController');
	Route::resource('consultations.prescriptions', 'App\Http\Controllers\ConsultationPrescriptionController');

	Route::put('employees/update-hours/{id}', [EmployeeController::class, 'resetHours'])->name('employees.reset-hours');

	Route::resource('services', 'App\Http\Controllers\HealthServicesController')->except([
		'edit', 'update'
	]);
	Route::resource('services.diary', 'App\Http\Controllers\DiaryController');
	Route::resource('services.prescriptions', 'App\Http\Controllers\PrescriptionController');
	Route::post('services/{id}/forms', [HealthServicesController::class, 'addNewFormToService'])->name('services.forms.create');

	Route::get('services/{id}/forms/{formId}', [HealthServicesController::class, 'showAnswerForm'])->name('services.forms.answer');
	Route::post('services/{id}/forms/{formId}', [HealthServicesController::class, 'storeResponse'])->name('services.forms.store');
	Route::get('services/{id}/forms/{formId}/response', [HealthServicesController::class, 'showResponse'])->name('services.forms.response');
	Route::delete('services/{id}/forms/{formId}', [HealthServicesController::class, 'deleteForm'])->name('services.forms.delete');
	Route::get('export-services', [HealthServicesController::class, 'exportAllBookings'])->name('services.export');
	Route::put('accept-service/{id}', [HealthServicesController::class, 'acceptBooking'])->name('services.accept-booking');
	
	Route::get('consultations/{consultationId}/forms/{formId}/{userId}', [ConsultationFormController::class, 'show'])->name('consultations.forms.show');
	Route::get('consultations/{consultationId}/forms/{formId}/{userId}/answer', [ConsultationFormController::class, 'edit'])->name('consultations.forms.edit');
	Route::post('consultations/{consultationId}/forms/{formId}/{userId}', [ConsultationFormController::class, 'store'])->name('consultations.forms.store');
	Route::get('export-consultations', [ConsultationController::class, 'export'])->name('consultations.export');

	Route::post('update-form/{serviceId}', ['as' => 'update-form', 'uses' => 'App\Http\Controllers\ServiceController@updateForms']);
	Route::post('submit-answer', ['as' => 'submit-answer', 'uses' => 'App\Http\Controllers\FormController@storeAnswer']);
	Route::get('answers/{formId}/{userId}', ['as' => 'view-answer', 'uses' => 'App\Http\Controllers\FormController@showAnswers']);
	Route::get('news', function () {
		return view('layouts.news');
	})->name('news');

	Route::get('notifications/forms', ['as' => 'doctors.forms.index', 'uses' => 'App\Http\Controllers\NotificationController@showForms']);
	Route::get('notifications', ['as' => 'working-hours-notification.index', 'uses' => 'App\Http\Controllers\NotificationController@index']);
	Route::post('notifications/{id}', ['as' => 'working-hours-notification.approve', 'uses' => 'App\Http\Controllers\NotificationController@approve']);
	Route::delete('notifications/{id}', ['as' => 'working-hours-notification.delete', 'uses' => 'App\Http\Controllers\NotificationController@delete']);
	Route::get('notifications/company', ['as' => 'company-notification.index', 'uses' => 'App\Http\Controllers\NotificationController@employeeCompanyIndex']);
	Route::post('notifications/company/{id}', ['as' => 'company-notification.approve', 'uses' => 'App\Http\Controllers\NotificationController@employeeCompanyApprove']);
	Route::delete('notifications/company/{id}', ['as' => 'company-notification.delete', 'uses' => 'App\Http\Controllers\NotificationController@employeeCompanyDelete']);

	Route::get('change-company', ['as' => 'change-company.index', 'uses' => 'App\Http\Controllers\NotificationController@companyIndex']);
	Route::post('change-company/{id}', ['as' => 'change-company.change', 'uses' => 'App\Http\Controllers\NotificationController@companyChange']);
});

Route::group(['middleware' => ['auth', 'onboard']], function () {
	Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::resource('marketplace', 'App\Http\Controllers\ItemController');
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::put('profile/email', ['as' => 'profile.email', 'uses' => 'App\Http\Controllers\ProfileController@email']);
	Route::put('profile/info', ['as' => 'profile.info', 'uses' => 'App\Http\Controllers\ProfileController@info']);
	Route::put('profile/company', ['as' => 'profile.company', 'uses' => 'App\Http\Controllers\ProfileController@company']);
	Route::post('update-hours', ['as' => 'profile.hours', 'uses' => 'App\Http\Controllers\ProfileController@hours']);
	Route::post('update-signature', ['as' => 'profile.signature', 'uses' => 'App\Http\Controllers\ProfileController@signature']);
});

Route::group(['middleware' => ['auth']], function () {
	Route::post('register-onboard', ['as' => 'register-onboard', 'uses' => 'App\Http\Controllers\UserController@onboard']);
});

Route::get('view-qr/{data}', ['as' => 'qr.view', 'uses' => 'App\Http\Controllers\QrCodeController@show']);
Route::get('verify-qr', ['as' => 'qr.verify', 'uses' => 'App\Http\Controllers\QrCodeController@index']);
Route::get('verify-qr/{id}', ['as' => 'qr.verify.fetch', 'uses' => 'App\Http\Controllers\QrCodeController@fetch']);
Route::get('services/{id}/forms/{formId}/export', [HealthServicesController::class, 'exportResponse'])->name('services.forms.export');