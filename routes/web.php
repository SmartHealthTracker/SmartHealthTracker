<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitTrackingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\UserControllerr;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('dashboard');
});
Route::resource('activities', ActivityController::class)
    ->names('activities')
    ->except('show');

Route::resource('activity-logs', ActivityLogController::class)
    ->names('activity_logs')
    ->except('show');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/pdf', [ReportController::class, 'downloadPDF'])->name('reports.pdf');
Route::get('/reports/predictions', [ReportController::class, 'predictions'])->name('reports.predictions');    



Route::resource('challenges', ChallengeController::class);
Route::resource('participations', ParticipationController::class);


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('resources', App\Http\Controllers\Admin\ResourceController::class);
    Route::resource('comments', App\Http\Controllers\Admin\CommentController::class);
});

// ---------------------- GOOGLE AUTH ----------------------
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');


// Route::get('/','DashboardController@index');

Route::group(['prefix' => 'basic-ui'], function(){
    Route::get('accordions', function () { return view('pages.basic-ui.accordions'); });
    Route::get('buttons', function () { return view('pages.basic-ui.buttons'); });
    Route::get('badges', function () { return view('pages.basic-ui.badges'); });
    Route::get('breadcrumbs', function () { return view('pages.basic-ui.breadcrumbs'); });
    Route::get('dropdowns', function () { return view('pages.basic-ui.dropdowns'); });
    Route::get('modals', function () { return view('pages.basic-ui.modals'); });
    Route::get('progress-bar', function () { return view('pages.basic-ui.progress-bar'); });
    Route::get('pagination', function () { return view('pages.basic-ui.pagination'); });
    Route::get('tabs', function () { return view('pages.basic-ui.tabs'); });
    Route::get('typography', function () { return view('pages.basic-ui.typography'); });
    Route::get('tooltips', function () { return view('pages.basic-ui.tooltips'); });
});

Route::group(['prefix' => 'advanced-ui'], function(){
    Route::get('dragula', function () { return view('pages.advanced-ui.dragula'); });
    Route::get('clipboard', function () { return view('pages.advanced-ui.clipboard'); });
    Route::get('context-menu', function () { return view('pages.advanced-ui.context-menu'); });
    Route::get('popups', function () { return view('pages.advanced-ui.popups'); });
    Route::get('sliders', function () { return view('pages.advanced-ui.sliders'); });
    Route::get('carousel', function () { return view('pages.advanced-ui.carousel'); });
    Route::get('loaders', function () { return view('pages.advanced-ui.loaders'); });
    Route::get('tree-view', function () { return view('pages.advanced-ui.tree-view'); });
});

Route::group(['prefix' => 'forms'], function(){
    Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
    Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
    Route::get('dropify', function () { return view('pages.forms.dropify'); });
    Route::get('form-validation', function () { return view('pages.forms.form-validation'); });
    Route::get('step-wizard', function () { return view('pages.forms.step-wizard'); });
    Route::get('wizard', function () { return view('pages.forms.wizard'); });
});

Route::group(['prefix' => 'editors'], function(){
    Route::get('text-editor', function () { return view('pages.editors.text-editor'); });
    Route::get('code-editor', function () { return view('pages.editors.code-editor'); });
});

Route::group(['prefix' => 'charts'], function(){
    Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
    Route::get('morris', function () { return view('pages.charts.morris'); });
    Route::get('flot', function () { return view('pages.charts.flot'); });
    Route::get('google-charts', function () { return view('pages.charts.google-charts'); });
    Route::get('sparklinejs', function () { return view('pages.charts.sparklinejs'); });
    Route::get('c3-charts', function () { return view('pages.charts.c3-charts'); });
    Route::get('chartist', function () { return view('pages.charts.chartist'); });
    Route::get('justgage', function () { return view('pages.charts.justgage'); });
});

Route::group(['prefix' => 'tables'], function(){
    Route::get('basic-table', function () { return view('pages.tables.basic-table'); });
    Route::get('data-table', function () { return view('pages.tables.data-table'); });
    Route::get('js-grid', function () { return view('pages.tables.js-grid'); });
    Route::get('sortable-table', function () { return view('pages.tables.sortable-table'); });
});

Route::get('notifications', function () {
    return view('pages.notifications.index');
});

Route::group(['prefix' => 'icons'], function(){
    Route::get('material', function () { return view('pages.icons.material'); });
    Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
    Route::get('font-awesome', function () { return view('pages.icons.font-awesome'); });
    Route::get('simple-line-icons', function () { return view('pages.icons.simple-line-icons'); });
    Route::get('themify', function () { return view('pages.icons.themify'); });
});

Route::group(['prefix' => 'maps'], function(){
    Route::get('vector-map', function () { return view('pages.maps.vector-map'); });
    Route::get('mapael', function () { return view('pages.maps.mapael'); });
    Route::get('google-maps', function () { return view('pages.maps.google-maps'); });
});

Route::group(['prefix' => 'user-pages'], function() {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('deleteusers', [UserControllerr::class, 'index'])->name('users.index');
    Route::delete('deleteusers/{user}', [UserControllerr::class, 'destroy'])->name('users.destroy');
    Route::post('deleteusers/delete-all', [UserControllerr::class, 'deleteAll'])->name('users.deleteAll');
    Route::patch('toggle-block/{user}', [UserControllerr::class, 'toggleBlock'])->name('users.toggleBlock');

    Route::get('login-2', function () { return view('pages.user-pages.login-2'); });
    Route::get('multi-step-login', function () { return view('pages.user-pages.multi-step-login'); });
    Route::get('register-2', function () { return view('pages.user-pages.register-2'); });
    Route::get('lock-screen', function () { return view('pages.user-pages.lock-screen'); });

    Route::get('register', [RegisterController::class, 'showRegistrationForm']);
    Route::post('register', [RegisterController::class, 'register'])->name('register');

     // 🔹 Ajouter ces routes pour Forgot Password
    Route::get('password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

Route::group(['prefix' => 'error-pages'], function(){
    Route::get('error-404', function () { return view('pages.error-pages.error-404'); });
    Route::get('error-500', function () { return view('pages.error-pages.error-500'); });
});

Route::group(['prefix' => 'general-pages'], function(){
    Route::get('blank-page', function () { return view('pages.general-pages.blank-page'); });
    Route::get('landing-page', function () { return view('pages.general-pages.landing-page'); });
    Route::get('profile', function () { return view('pages.general-pages.profile'); });
    Route::get('email-templates', function () { return view('pages.general-pages.email-templates'); });
    Route::get('faq', function () { return view('pages.general-pages.faq'); });
    Route::get('faq-2', function () { return view('pages.general-pages.faq-2'); });
    Route::get('news-grid', function () { return view('pages.general-pages.news-grid'); });
    Route::get('timeline', function () { return view('pages.general-pages.timeline'); });
    Route::get('search-results', function () { return view('pages.general-pages.search-results'); });
    Route::get('portfolio', function () { return view('pages.general-pages.portfolio'); });
    Route::get('user-listing', function () { return view('pages.general-pages.user-listing'); });
});

Route::group(['prefix' => 'ecommerce'], function(){
    Route::get('invoice', function () { return view('pages.ecommerce.invoice'); });
    Route::get('invoice-2', function () { return view('pages.ecommerce.invoice-2'); });
    Route::get('pricing', function () { return view('pages.ecommerce.pricing'); });
    Route::get('product-catalogue', function () { return view('pages.ecommerce.product-catalogue'); });
    Route::get('project-list', function () { return view('pages.ecommerce.project-list'); });
    Route::get('orders', function () { return view('pages.ecommerce.orders'); });
});

// For Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
// Habits Routes
Route::resource('habits', HabitController::class);
Route::post('/habits/{habit}/start', [HabitController::class, 'start'])->name('habits.start');

Route::post('/habit-trackings/{tracking}/update', [HabitTrackingController::class, 'updateProgress'])->name('habit.updateProgress');
Route::post('/habit-trackings/{tracking}/finish', [HabitTrackingController::class, 'finish'])->name('habit.finish');

Route::post('/nutrition', [NutritionController::class, 'getNutrition'])->name('nutrition.get');

// routes/web.php

Route::prefix('health')->middleware(['auth'])->group(function () {
    // Page principale du Health Tracker (affiche le formulaire et l'historique)
    Route::get('/', [HealthController::class, 'index'])->name('health.index');

    // Page des logs (pour route('health.logs'))
    Route::get('/logs', [HealthController::class, 'logs'])->name('health.logs');

    // CRUD HealthTracker (ajout d'un log)
    Route::post('/', [HealthController::class, 'store'])->name('health.store');

    // Suppression d'un log
    Route::delete('/{healthLog}', [HealthController::class, 'destroy'])->name('health.destroy');
});


// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error-pages.error-404');
})->where('page','.*');
Route::any('/{page?}',function(){
    return View::make('pages.error-pages.error-404');
})->where('page','.*');
