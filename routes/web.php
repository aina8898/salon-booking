<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ServiceController;


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
    return view('welcome');
});

Route::resource('appointments', AppointmentController::class);

Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])
    ->name('appointments.calendar');

Route::get('/staff', [StaffController::class, 'index'])
    ->name('staff.index');

Route::resource('services', ServiceController::class);