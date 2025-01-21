<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\HasPermission;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/employee', 'admin.create');

Route::middleware(['auth', HasPermission::class])->group(function () {
    Route::get('/', function () {
        $user = User::find(auth()->user()->id);
        return view('welcome', compact('user'));
    });
    Route::resource('departments', DepartmentController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('leaves', LeaveController::class);
    Route::post('accept-reject-leave/{id}', [LeaveController::class, 'acceptRejectLeave'])->name('accept-reject-leave');
    Route::resource('notices', NoticeController::class);
    Route::get('mails', [MailController::class, 'create'])->name('mails.create');
    Route::post('mails', [MailController::class, 'store'])->name('mails.store');

});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
