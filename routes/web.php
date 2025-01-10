<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/employee', 'admin.create');

Route::resource('departments', DepartmentController::class);
Route::resource('roles', RoleController::class);
