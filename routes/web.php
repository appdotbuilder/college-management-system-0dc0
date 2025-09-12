<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student Management (Admin/Super Admin only)
    Route::resource('students', StudentController::class);
    
    // Program Management (Admin/Super Admin only)
    Route::resource('programs', ProgramController::class);
    
    // Fee Type Management (Admin/Super Admin only)
    Route::resource('fee-types', FeeTypeController::class);
    
    // Invoice Management (Staff and above)
    Route::resource('invoices', InvoiceController::class);
    
    // Payment Management (All authenticated users)
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';