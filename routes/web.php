<?php

use App\Http\Controllers\Admin\AdUnitController;
use App\Http\Controllers\Admin\AdunitReportController;
use App\Http\Controllers\Admin\Index;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Route::get('/', function () {
//     return view('home');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/advertiser', [App\Http\Controllers\ContactController::class, 'advertiser'])->name('advertiser');
Auth::routes();
//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/adminlayout', function () {
    return view('layouts.admin');
});
Route::get('/dashboard', function () {
    return view('admin.index');
});
Route::post('contact',[ContactController::class,'contact'])->name('contact');

Route::post('advertiserSave',[ContactController::class,'advertiserSave'])->name('advertiserSave');


Route::prefix('admin')->group(function () {
    // Route::get('adunit', [AdUnitController::class, 'index'])->name('adunit');
    Route::get('adunit/{unit_id}/view', [AdUnitController::class, 'view'])->name('adunit.view');
        Route::get('users/processCheckboxes', [UserController::class, 'processCheckboxes'])->name('users.processCheckboxes');
            Route::get('graphDataDash', [Index::class, 'graphDataDash'])->name('graphDataDash');
                Route::get('allowDisallow/{id}', [UserController::class, 'allowDisallow'])->name('allowDisallow');
    Route::get('/updateRvenue', [AdunitReportController::class, 'updateRvenue'])->name('updateRvenue');

    Route::get('/updateStatus', [AdunitReportController::class, 'updateStatus'])->name('updateStatus');


});

Route::prefix('user')->group(function () {
    Route::get('dashboard', [AdUnitController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('dashboardData', [AdUnitController::class, 'dashboardData'])->name('dashboardData');
    Route::get('graphData', [AdUnitController::class, 'graphData'])->name('graphData');

    Route::patch('profile/{user}/passUpdate', [ProfileController::class, 'passUpdate'])->name('profile.passUpdate');
    Route::patch('profile/{user}/othersUpdate', [ProfileController::class, 'othersUpdate'])->name('profile.othersUpdate');
        Route::get('summary', [AdunitReportController::class, 'summaryUser'])->name('summary');

});


// Route::get('adunit', [AdUnitController::class, 'index'])->name('adunit');
Route::group([
    'middleware' => ['auth', 'role:SuperAdmin|Admin|Manager', 'permission:AdminPanel access'],
    'namespace'  => 'App\Http\Controllers\Admin',
    'prefix'     => 'admin',
    'as'         => 'admin.'
    ], function() {
    Route::get('/', [Index::class, 'index'])->name('dashboard');
    Route::get('/adunit', [AdUnitController::class, 'index'])->name('adunit');

    Route::resource('profile', 'ProfileController');
    Route::patch('profile/{user}/passUpdate', [ProfileController::class, 'passUpdate'])->name('profile.passUpdate');
    Route::patch('profile/{user}/othersUpdate', [ProfileController::class, 'othersUpdate'])->name('profile.othersUpdate');


    Route::resource('roles','RoleController');
    Route::resource('permissions','PermissionController');
    // Route::get('permission/index', [PermissionController::class, 'index'])->name('permission.index');

  Route::get('users/{unit_id}/editUser', [UserController::class, 'editUser'])->name('users.editUser');
  Route::get('users/{client_id}/view', [UserController::class, 'view'])->name('users.view');

    Route::post('users/{user}/updateUser', [UserController::class, 'updateUser'])->name('users.updateUser');


    Route::resource('users','UserController');
     Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{unitid}/deleteUnit', [UserController::class, 'deleteUnit'])->name('users.deleteUnit');

    Route::patch('users/{user}/passUpdate', [UserController::class, 'passUpdate'])->name('users.passUpdate');
    Route::patch('users/{user}/othersUpdate', [UserController::class, 'othersUpdate'])->name('users.othersUpdate');

    Route::get('/generate-report', [AdunitReportController::class, 'generateReport'])->name('generate-report');

    Route::get('summary', [AdunitReportController::class, 'summary'])->name('summary');


});


/* Email Verification Links */
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
