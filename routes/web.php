<?php

// routes/web.php

use App\Http\Controllers\AdminController;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DoctorMiddleware;

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


Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');//show admin dashboard
    Route::get('/admin/list-of-doctors', [AdminController::class, 'list_of_doctors'])->name('admin.list-doctors');//show list of doctors
    Route::get('/admin/doctors/create', [AdminController::class, 'createDoctor'])->name('admin.create-doctor');//show page that contain form to create doctor
    Route::post('/admin/doctors', [AdminController::class, 'add_doctor_info'])->name('admin.add-doctor');//post doctor information to database
    Route::get('/admin/services/create', [AdminController::class, 'createService'])->name('admin.add-service');
});

Route::middleware(['auth', DoctorMiddleware::class])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::get('/doctor/appointments', [DoctorController::class, 'appointment_table'])->name('doctor.appointments');
    Route::get('/doctor/schedules', [DoctorController::class, 'schedule'])->name('doctor.schedule');
    Route::get('/doctor/patients', [DoctorController::class, 'patients_table'])->name('doctor.patients');
    Route::get('/doctor/reports', [DoctorController::class, 'reports'])->name('doctor.reports');
});

// Specialty-based doctor search
Route::get('/doctors/specialty/{specialty}', [DoctorController::class, 'bySpecialty'])->name('doctors.specialty');

// Condition-based doctor search  
Route::get('/doctors/condition/{condition}', [DoctorController::class, 'byCondition'])->name('doctors.condition');

// Services pages
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

require __DIR__.'/auth.php';
