<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('appointments.index')
        : redirect()->route('login');
});

Route::resource('appointments', AppointmentController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])
    ->name('appointments.calendar');

Route::get('/staff', [StaffController::class, 'index'])
    ->name('staff.index');

Route::resource('services', ServiceController::class);

Route::get('/mypage', [AppointmentController::class, 'mypage'])
    ->middleware('auth')
    ->name('appointments.mypage');

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::get('/appointments/{id}/edit', [AdminAppointmentController::class, 'edit'])
            ->name('appointments.edit');

        Route::put('/appointments/{id}', [AdminAppointmentController::class, 'update'])
            ->name('appointments.update');

        Route::delete('/appointments/{id}', [AdminAppointmentController::class, 'destroy'])
            ->name('appointments.destroy');
    });
