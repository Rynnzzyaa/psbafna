<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Admin\Monitoring as AdminMonitoring;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\CustomerRegistrationForm;
use App\Livewire\CustomerStatusTracker;
use App\Livewire\DatabaseChecker;
use App\Livewire\Referrer\Dashboard as ReferrerDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Form Pendaftaran Pelanggan Berlangganan WiFi (Bisa dengan ?ref=KODE_REFERRAL)
Route::get('/', CustomerRegistrationForm::class)->name('home');
Route::get('/daftar', CustomerRegistrationForm::class)->name('register.wifi');

// Lacak Status Pemasangan Pelanggan
Route::get('/lacak-status', CustomerStatusTracker::class)->name('tracking');

// Database Checker (Bawaan Project)
Route::get('/db-check', DatabaseChecker::class)->name('db.check');

/*
|--------------------------------------------------------------------------
| Guest Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Logout & Demo Switcher
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

/*
|--------------------------------------------------------------------------
| Pembawa Pelanggan (User / Referrer) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:referrer'])->group(function (): void {
    Route::get('/dashboard', ReferrerDashboard::class)->name('referrer.dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin (Pemegang Semua Monitoring) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function (): void {
    Route::get('/monitoring', AdminMonitoring::class)->name('admin.monitoring');
});
