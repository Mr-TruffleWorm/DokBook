<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking-page', function () {
    return view('booking_page');
})->name('booking-page');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Specialty-based doctor search
Route::get('/doctors/specialty/{specialty}', [DoctorController::class, 'bySpecialty'])->name('doctors.specialty');

// Condition-based doctor search  
Route::get('/doctors/condition/{condition}', [DoctorController::class, 'byCondition'])->name('doctors.condition');

// Services pages
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

require __DIR__.'/auth.php';
