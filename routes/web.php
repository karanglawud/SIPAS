<?php

use App\Http\Controllers\AboutPjController;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// Public & Information Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/tentang-pj', [AboutPjController::class, 'index'])->name('about.pj');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Peminjaman Flow
Route::get('/pinjam/{tool?}', [LoanController::class, 'create'])->name('loans.create');
Route::post('/pinjam', [LoanController::class, 'store'])->name('loans.store');
Route::get('/riwayat', [LoanController::class, 'index'])->name('loans.index');
Route::get('/tiket/{loan}/cetak', [LoanController::class, 'printTicket'])->name('loans.print');

// WhatsApp-style Notification Inbox
Route::get('/pesan', [MessageController::class, 'index'])->name('messages.index');

// Admin PJ Verification & Processing
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/peminjaman', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::post('/peminjaman/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
    Route::post('/peminjaman/{loan}/reject', [AdminLoanController::class, 'reject'])->name('loans.reject');
    Route::post('/peminjaman/{loan}/complete', [AdminLoanController::class, 'complete'])->name('loans.complete');
});
