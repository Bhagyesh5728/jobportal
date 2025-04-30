<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiteController;

Route::get('/', [SiteController::class, 'index'])->name('site.index');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth')->group(function () {
    Route::get('save-job', [SiteController::class, 'savedJob'])->name('save.jobs');
    Route::get('my-resume', [SiteController::class, 'myResume'])->name('my.resume');
    Route::post('/my-resume', [SiteController::class, 'uploadResume'])->name('resume.upload');
});