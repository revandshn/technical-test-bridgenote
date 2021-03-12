<?php

use App\Http\Controllers\WEB\Auth\AuthController;
use App\Http\Controllers\WEB\Dashboard\Administrator\HomeController as AdministratorHomeController;
use App\Http\Controllers\WEB\Dashboard\HomeController;
use App\Http\Controllers\WEB\Dashboard\UserController;
use App\Http\Middleware\Session;
use Illuminate\Support\Facades\Route;

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


Route::get('/login', [AuthController::class, 'index'])->name('auth.index');
Route::post('/login', [AuthController::class, 'signin'])->name('auth.login');
Route::get('/register', [AuthController::class, 'create'])->name('auth.create');
Route::post('/register', [AuthController::class, 'signup'])->name('auth.register');

// Dashboard
Route::middleware([Session::class])->group(function () {
    Route::get('signout', [AuthController::class, 'signout'])->name('auth.logout');


    Route::get('/profile', [UserController::class, 'profile'])->name('dashboard.my-profile');
    Route::get('/profile/edit/{user_id}', [UserController::class, 'edit'])->name('dashboard.edit-profile');
    Route::put('/profile/edit/{user_id}', [UserController::class, 'update'])->name('dashboard.update-profile');

    // Administrator

    // User
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.user-index');
    Route::get('admin/dashboard', [AdministratorHomeController::class, 'index'])->name('dashboard.admin-index');
    Route::get('admin/dashboard/users', [AdministratorHomeController::class, 'users'])->name('dashboard.admin-user');
    Route::get('admin/dashboard/users/{user_id}', [AdministratorHomeController::class, 'show'])->name('dashboard.admin-user-show');
    Route::get('admin/dashboard/users/add/{user_id}', [AdministratorHomeController::class, 'create'])->name('dashboard.admin-user-create');
    Route::post('admin/dashboard/users/add/{user_id}', [AdministratorHomeController::class, 'store'])->name('dashboard.admin-user-add');
    Route::get('admin/dashboard/users/edit/{user_id}', [AdministratorHomeController::class, 'edit'])->name('dashboard.admin-user-edit');
    Route::put('admin/dashboard/users/edit/{user_id}', [AdministratorHomeController::class, 'update'])->name('dashboard.admin-user-update');
});
