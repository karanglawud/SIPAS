<?php

use App\Http\Controllers\AboutPjController;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminToolController;
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
Route::get('/riwayat/cetak', [LoanController::class, 'printHistory'])->name('loans.history.print');
Route::post('/riwayat/{loan}/batalkan', [LoanController::class, 'cancel'])->name('loans.cancel');
Route::get('/tiket/{loan}/cetak', [LoanController::class, 'printTicket'])->name('loans.print');

// WhatsApp-style Notification Inbox
Route::get('/pesan', [MessageController::class, 'index'])->name('messages.index');

// Admin PJ Verification, Inventory & Messaging
Route::prefix('admin')->name('admin.')->group(function () {
    // Peminjaman Verification & Actions
    Route::get('/peminjaman', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::get('/peminjaman/cetak-rekap', [AdminLoanController::class, 'printReport'])->name('loans.report.print');
    Route::get('/peminjaman/{loan}/cetak', [AdminLoanController::class, 'printTicket'])->name('loans.print');
    Route::post('/peminjaman/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
    Route::post('/peminjaman/{loan}/handover', [AdminLoanController::class, 'handover'])->name('loans.handover');
    Route::post('/peminjaman/{loan}/reject', [AdminLoanController::class, 'reject'])->name('loans.reject');
    Route::post('/peminjaman/{loan}/complete', [AdminLoanController::class, 'complete'])->name('loans.complete');
    Route::post('/peminjaman/{loan}/pesan', [AdminLoanController::class, 'sendMessage'])->name('loans.sendMessage');

    // Tool & Inventory Management
    Route::get('/alat', [AdminToolController::class, 'index'])->name('tools.index');
    Route::get('/alat/tambah', [AdminToolController::class, 'create'])->name('tools.create');
    Route::post('/alat', [AdminToolController::class, 'store'])->name('tools.store');
    Route::get('/alat/{tool}/edit', [AdminToolController::class, 'edit'])->name('tools.edit');
    Route::put('/alat/{tool}', [AdminToolController::class, 'update'])->name('tools.update');
    Route::delete('/alat/{tool}', [AdminToolController::class, 'destroy'])->name('tools.destroy');
    Route::post('/alat/{tool}/unit', [AdminToolController::class, 'storeUnit'])->name('tools.units.store');
    Route::patch('/unit/{unit}', [AdminToolController::class, 'updateUnit'])->name('tools.units.update');
    Route::delete('/unit/{unit}', [AdminToolController::class, 'destroyUnit'])->name('tools.units.destroy');

    // Message Center (Admin to Users)
    Route::get('/pesan', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::post('/pesan', [AdminMessageController::class, 'store'])->name('messages.store');
});
