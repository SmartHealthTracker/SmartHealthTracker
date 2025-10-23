<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitTrackingController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\UserControllerr;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ChallengeParticipationController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HabitSaifController;
// -------------------- DASHBOARD --------------------
Route::get('/', function () {
    return view('dashboard');
});


// -------------------- CHALLENGE Mahmoud PARTICIPATION --------------------
Route::get('/cha-parti-dashboard', [ChallengeParticipationController::class, 'index'])
    ->name('cha-parti-dashboard');

// Export PDF (must be before single challenge route)
Route::get('/challenges/export-pdf', [ChallengeParticipationController::class, 'exportPdf'])
    ->name('challenges.exportPdf');

// Resource routes for challenges
Route::resource('challenges', ChallengeController::class);

// Single Challenge view (after export-pdf)
Route::get('/challenges/{challenge}', [ChallengeController::class, 'show'])
    ->name('challenges.show');
// -------------------- CHALLENGE Mahmoud PARTICIPATION --------------------


// -------------------- ACTIVITIES --------------------
Route::resource('activities', ActivityController::class)
    ->names('activities')
    ->except('show');

Route::resource('activity-logs', ActivityLogController::class)
    ->names('activity_logs')
    ->except('show');

// -------------------- PARTICIPATIONS Mahmoud Controller --------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/participations', [ParticipationController::class, 'index'])->name('participations.index');
    Route::post('/participations', [ParticipationController::class, 'store'])->name('participations.store');
    Route::put('/participations/{participation}', [ParticipationController::class, 'update'])->name('participations.update');
    Route::delete('/participations/{participation}', [ParticipationController::class, 'destroy'])->name('participations.destroy');
// -------------------- PARTICIPATIONS Mahmoud Controller --------------------

    // Admin users
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
});

// -------------------- ADMIN RESOURCES --------------------
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('resources', App\Http\Controllers\Admin\ResourceController::class);
    Route::resource('comments', App\Http\Controllers\Admin\CommentController::class);
});

// -------------------- GOOGLE AUTH --------------------
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// -------------------- CHATBOT Mah --------------------
Route::post('/chatbot/reply', [ChatbotController::class, 'reply'])->name('chatbot.reply');
// -------------------- CHATBOT Mah --------------------


// -------------------- HABITS --------------------
Route::resource('habits', HabitController::class);
Route::post('/habits/{habit}/start', [HabitController::class, 'start'])->name('habits.start');
Route::post('/habit-trackings/{tracking}/update', [HabitTrackingController::class, 'updateProgress'])->name('habit.updateProgress');
Route::post('/habit-trackings/{tracking}/finish', [HabitTrackingController::class, 'finish'])->name('habit.finish');

// -------------------- NUTRITION --------------------
Route::post('/nutrition', [NutritionController::class, 'getNutrition'])->name('nutrition.get');

// -------------------- HEALTH --------------------
Route::prefix('health')->middleware(['auth'])->group(function () {
    Route::get('/', [HealthController::class, 'index'])->name('health.index');
    Route::get('/logs', [HealthController::class, 'logs'])->name('health.logs');
    Route::post('/', [HealthController::class, 'store'])->name('health.store');
    Route::delete('/{healthLog}', [HealthController::class, 'destroy'])->name('health.destroy');
});

// -------------------- USER PAGES --------------------
Route::group(['prefix' => 'user-pages'], function() {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('deleteusers', [UserControllerr::class, 'index'])->name('users.index');
    Route::delete('deleteusers/{user}', [UserControllerr::class, 'destroy'])->name('users.destroy');
    Route::post('deleteusers/delete-all', [UserControllerr::class, 'deleteAll'])->name('users.deleteAll');
    Route::patch('toggle-block/{user}', [UserControllerr::class, 'toggleBlock'])->name('users.toggleBlock');

    Route::get('register', [RegisterController::class, 'showRegistrationForm']);
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    // Forgot Password
    Route::get('password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// -------------------- HABIT SAIF CONTROLLER --------------------


// Routes pour HabitLog
// routes/web.php
Route::resource('habit-logs', HabitLogController::class);
Route::resource('habitssaif', HabitSaifController::class)->parameters([
    'habitssaif' => 'habit'
]);
// Individual routes - use {habit} parameter
Route::get('habitssaif/{habit}/edit', [HabitSaifController::class, 'edit'])->name('habitssaif.edit');
Route::get('habitssaif/{habit}/download-report', [HabitSaifController::class, 'downloadReport'])->name('habitssaif.downloadReport');
Route::get('/api/gemini-advice/{habit}', [HabitSaifController::class, 'fetchGeminiAdvice'])->name('api.gemini-advice');
// -------------------- HABIT SAIF CONTROLLER --------------------


// -------------------- CLEAR CACHE --------------------
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// -------------------- FALLBACK 404 --------------------
Route::fallback(function () {
    return view('pages.error-pages.error-404');
});
