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

	Route::put('employees/update-hours/{id}', [EmployeeController::class, 'resetHours'])->name('employees.reset-hours');

	Route::resource('services', 'App\Http\Controllers\HealthServicesController')->except([
		'edit', 'update'
	]);
	Route::resource('services.diary', 'App\Http\Controllers\DiaryController');
	Route::resource('services.prescriptions', 'App\Http\Controllers\PrescriptionController');
	Route::post('services/forms/{id}', [HealthServicesController::class, 'addNewFormToService'])->name('services.add-new-form');
	// Route::resource('services', 'App\Http\Controllers\ServiceController');
	// Route::get('services/{consultationId}/forms/{formId}/{userId}', [ConsultationFormController::class, 'show'])->name('services.forms.show');
	// Route::get('services/{consultationId}/forms/{formId}/{userId}/answer', [ConsultationFormController::class, 'edit'])->name('services.forms.edit');
	// Route::post('services/{consultationId}/forms/{formId}/{userId}', [ConsultationFormController::class, 'store'])->name('services.forms.store');
	// Route::get('export-services', [ServiceController::class, 'export'])->name('services.export');
	
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

	Route::get('notifications', ['as' => 'working-hours-notification.index', 'uses' => 'App\Http\Controllers\NotificationController@index']);
	Route::post('notifications/{id}', ['as' => 'working-hours-notification.approve', 'uses' => 'App\Http\Controllers\NotificationController@approve']);

	Route::get('change-company', ['as' => 'change-company.index', 'uses' => 'App\Http\Controllers\NotificationController@companyIndex']);
	Route::post('change-company/{id}', ['as' => 'change-company.change', 'uses' => 'App\Http\Controllers\NotificationController@companyChange']);
});

Route::group(['middleware' => ['auth', 'onboard']], function () {
	Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::resource('marketplace', 'App\Http\Controllers\ItemController');
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::post('update-hours', ['as' => 'profile.hours', 'uses' => 'App\Http\Controllers\ProfileController@hours']);
	Route::post('update-signature', ['as' => 'profile.signature', 'uses' => 'App\Http\Controllers\ProfileController@signature']);
});

Route::group(['middleware' => ['auth']], function () {
	Route::post('register-onboard', ['as' => 'register-onboard', 'uses' => 'App\Http\Controllers\UserController@onboard']);
});