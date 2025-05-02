<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\EmailController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('contacts', ContactController::class);

    Route::resource('templates', TemplateController::class);

    Route::get('emails/create',   [EmailController::class,'create'])->name('emails.create');
    Route::post('emails/send',    [EmailController::class,'send'])->name('emails.send');
    Route::get('emails/history',  [EmailController::class,'history'])->name('emails.history');
    Route::post('emails/bulk-send', [EmailController::class, 'bulkSend'])->name('emails.bulkSend');
    Route::post('emails/{email}/send-one', [EmailController::class, 'sendOne'])->name('emails.sendOne');
    Route::get('/emails/{email}/edit', [EmailController::class, 'edit'])->name('emails.edit');
    Route::put('/emails/{email}', [EmailController::class, 'update'])->name('emails.update');
});

require __DIR__.'/auth.php';
