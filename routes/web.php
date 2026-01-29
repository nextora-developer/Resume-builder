<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ResumeController;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/{slug}', [TemplateController::class, 'preview'])->name('templates.preview');

// Resume (public create/edit/preview allowed)
Route::get('/resume/create', [ResumeController::class, 'create'])->name('resume.create');
Route::get('/resume/{resume:uuid}/edit', [ResumeController::class, 'edit'])->name('resume.edit');
Route::put('/resume/{resume:uuid}', [ResumeController::class, 'update'])->name('resume.update');
Route::get('/resume/{resume:uuid}/preview', [ResumeController::class, 'preview'])->name('resume.preview');

// Auth-only
Route::middleware('auth')->group(function () {
    Route::get('/resumes', [ResumeController::class, 'index'])->name('resumes.index'); // My Resumes
    Route::post('/resume/{resume:uuid}/claim', [ResumeController::class, 'claim'])->name('resume.claim');
    Route::get('/resume/{resume:uuid}/download', [ResumeController::class, 'download'])->name('resume.download');
    Route::delete('/resume/{resume:uuid}', [ResumeController::class, 'destroy'])->name('resume.destroy');
});



// =====================
// Admin Auth
// =====================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__ . '/auth.php';
