<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ProfileController;
use Inertia\Inertia;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/events', [FrontController::class, 'allEvents'])->name('events.all');
Route::get('/event/{id}', [FrontController::class, 'detail'])->name('event.detail');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/payment/{id}/process', [CheckoutController::class, 'processPayment'])->name('checkout.processPayment');
    Route::post('/payment/{id}/cancel', [CheckoutController::class, 'cancelPayment'])->name('checkout.cancelPayment');
    
    Route::get('/tiket-saya', [\App\Http\Controllers\FrontController::class, 'tiketSaya'])->name('tiket.saya');
    Route::get('/tiket-saya/download-pdf', [\App\Http\Controllers\FrontController::class, 'downloadPdf'])->name('tiket.download_pdf');
});

Route::post('/api/verify-tiket', [CheckInController::class, 'verify'])->name('api.verify.tiket');

require __DIR__.'/auth.php';