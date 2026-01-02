<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SkillController; // For manual skill management
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public Redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// 2. SkillPulse Core Features (Protected by Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    
 
    Route::get('/dashboard', [ResumeController::class, 'showUploadForm'])->name('dashboard');
    Route::get('/upload', [ResumeController::class, 'showUploadForm'])->name('upload');
    
    // AI Analysis Action
    Route::post('/analyze', [ResumeController::class, 'analyze'])->name('analyze');
    
    // --- MANUAL SKILLS MANAGEMENT ---
    
    Route::post('/skills/add', [SkillController::class, 'add'])->name('skills.add');
    Route::post('/skills/remove', [SkillController::class, 'remove'])->name('skills.remove');
});

// 3. Laravel Breeze Profile Management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';