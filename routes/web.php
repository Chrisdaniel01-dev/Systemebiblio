<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\AbonneController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ ROUTES PUBLIQUES (sans authentification)
Route::resource('categories', CategorieController::class);
Route::resource('livres', LivreController::class); // ← DÉPLACÉ HORS DU GROUPE AUTH
Route::resource('abonnes', AbonneController::class); // ← DÉPLACÉ HORS DU GROUPE AUTH
Route::resource('emprunts', EmpruntController::class); // ← EMPRUNTS AUSSI PUBLIC
Route::patch('/emprunts/{emprunt}/return', [EmpruntController::class, 'return'])->name('emprunts.return');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Statistics route
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

// Make dashboard the homepage if logged in
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : view('welcome');
});

// Logs Routes
Route::resource('logs', LogController::class);

// Web authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'webLogin']);
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

// ✅ ROUTES PROTÉGÉES (uniquement avec authentification)
Route::middleware('auth')->group(function () {
    // Seulement les routes qui nécessitent vraiment l'auth
    // Emprunts déplacé vers les routes publiques

    // Activity Logs Routes
    Route::middleware('auth')->prefix('admin')->name('activity-logs.')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::get('/activity-logs/{activityLog}/details', [ActivityLogController::class, 'details'])->name('details');
        Route::get('/activity-logs-export', [ActivityLogController::class, 'export'])->name('export');
        Route::delete('/activity-logs-bulk-delete', [ActivityLogController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/activity-logs-statistics', [ActivityLogController::class, 'statistics'])->name('statistics');
        Route::get('/activity-logs-cleanup', [ActivityLogController::class, 'cleanup'])->name('cleanup');
        Route::get('/activity-logs/user/{user}', [ActivityLogController::class, 'getByUser'])->name('by-user');
        Route::get('/activity-logs/action/{actionType}', [ActivityLogController::class, 'getByActionType'])->name('by-action-type');
        Route::get('/activity-logs/model/{modelType}/{modelId?}', [ActivityLogController::class, 'getByModel'])->name('by-model');
        Route::get('/activity-logs-realtime-count', [ActivityLogController::class, 'getRealTimeCount'])->name('realtime-count');
        Route::get('/activity-logs-recent/{limit?}', [ActivityLogController::class, 'getRecentActivities'])->name('recent');
    });
});

// Additional routes
Route::post('/toggle-dark-mode', function () {
    $darkMode = session('dark_mode', false);
    session(['dark_mode' => !$darkMode]);
    return response()->json(['success' => true]);
})->name('toggle-dark-mode');