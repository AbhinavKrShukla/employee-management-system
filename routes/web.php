<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\HasPermission;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/employee', 'admin.create');

Route::middleware(['auth', HasPermission::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::resource('departments', DepartmentController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('permissions', PermissionController::class);
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
