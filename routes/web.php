<?php

// routes/web.php
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\AdminController;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DoctorMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Middleware\PreventBackHistory;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::get('/booking-page', function () {
    return view('booking_page');
})->name('booking-page');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');//show admin dashboard
    Route::get('/admin/list-of-doctors', [AdminController::class, 'list_of_doctors'])->name('admin.list-doctors');//show list of doctors
    Route::get('/admin/doctors/create', [AdminController::class, 'createDoctor'])->name('admin.create-doctor');//show page that contain form to create doctor
    Route::post('/admin/add-doctor', [AdminController::class, 'add_doctor_info'])->name('admin.add-doctor');//post doctor information to database
    Route::get('/doctors/{id}/edit', [AdminController::class, 'editDoctor'])->name('admin.edit-doctor');
    Route::put('/doctors/{id}', [AdminController::class, 'updateDoctor'])->name('admin.update-doctor');
    Route::delete('/doctors/{id}', [AdminController::class, 'deleteDoctor'])->name('admin.delete-doctor');
    Route::get('/admin/services/create', [AdminController::class, 'createService'])->name('admin.add-service');
});

Route::middleware(['auth', DoctorMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'doctor_dashboard'])->name('doctor.dashboard');
    Route::get('/doctor/schedule', [DoctorController::class, 'appointment_table'])->name('doctor.schedule');
    Route::get('/doctor/schedule/create', [DoctorController::class, 'create'])->name('doctor.schedule.create');
    Route::post('/doctor/schedule', [DoctorController::class, 'set_schedule'])->name('doctor.schedule.store');

    Route::post('/doctor/booking/{id}/confirm', [BookingController::class, 'confirmBooking'])->name('doctor.booking.confirm');
    Route::post('/doctor/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('doctor.booking.cancel');
    Route::post('/doctor/booking/{id}/complete', [BookingController::class, 'completeBooking'])->name('doctor.booking.complete');
    
    Route::get('/doctor/schedule/{schedule}/edit', [DoctorController::class, 'edit'])->name('doctor.schedule.edit');
    Route::put('/doctor/schedule/{schedule}', [DoctorController::class, 'update'])->name('doctor.schedule.update');
    Route::delete('/doctor/schedule/{schedule}', [DoctorController::class, 'destroy'])->name('doctor.schedule.destroy');
    Route::patch('/doctor/schedule/{schedule}/toggle', [DoctorController::class, 'toggle'])->name('doctor.schedule.toggle');
    Route::get('/doctor/patients', [DoctorController::class, 'patients_table'])->name('doctor.patients');
    Route::get('/doctor/reports', [DoctorController::class, 'reports'])->name('doctor.reports');
});



Route::get('/booking-page/{doctor}', [BookingController::class, 'showBookingPage'])->name('booking-page');
Route::post('/book-appointment/{doctorId}', [BookingController::class, 'bookAppointment'])->name('book.appointment');
Route::get('/book/success/{appointment}', [BookingController::class, 'bookingSuccess'])->name('booking.success');

// routes/web.php
Route::post('/send-verification-code', [BookingController::class, 'sendVerificationCode'])->name('send.verification.code');
Route::post('/verify-email-code', [BookingController::class, 'verifyEmailCode'])->name('verify.email.code');



// Specialty-based doctor search
Route::get('/doctors/specialty/{specialty}', [DoctorController::class, 'bySpecialty'])->name('doctors.specialty');

// Condition-based doctor search  
Route::get('/doctors/condition/{condition}', [DoctorController::class, 'byCondition'])->name('doctors.condition');

// Services pages
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

require __DIR__.'/auth.php';
