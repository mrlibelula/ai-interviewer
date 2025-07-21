<?php

use App\Http\Controllers\LoginController;
use App\Livewire\Admin\Challenge;
use App\Livewire\Admin\Challenges;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Prompt;
use App\Livewire\Interview;
use App\Livewire\Landing;
use App\Livewire\Metrics;
use App\Livewire\MetricsAttempts;
use App\Livewire\MetricsComparison;
use App\Livewire\MetricsDifficulty;
use App\Livewire\MetricsHintUsage;
use App\Livewire\MetricsLeaderboard;
use App\Livewire\MetricsTimeBased;
use App\Livewire\MetricsTopic;
use App\Livewire\Start;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// oauth2
Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/redirect', [LoginController::class, 'handleGoogleCallback'])->name('login.google.callback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/landing', Landing::class)->name('landing');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/interview', Interview::class)->name('interview');
    Route::get('/interview/start/{enc_selected_difficulty}/{enc_selected_topic_id}/{enc_challenge_id?}/{challenge_slug?}', Start::class)->name('interview-start');
    Route::get('/metrics', Metrics::class)->name('metrics');
    Route::get('/metrics/difficulty', MetricsDifficulty::class)->name('metrics-difficulty');
    Route::get('/metrics/hint-usage', MetricsHintUsage::class)->name('metrics-hint-usage');
    Route::get('/metrics/topic', MetricsTopic::class)->name('metrics-topic');
    Route::get('/metrics/attempts', MetricsAttempts::class)->name('metrics-attempts');
    Route::get('/metrics/time-based', MetricsTimeBased::class)->name('metrics-time-based');
    Route::get('/metrics/leaderboard', MetricsLeaderboard::class)->name('metrics-leaderboard');
    Route::get('/metrics/comparison', MetricsComparison::class)->name('metrics-comparison');
});

Route::middleware([
    'auth:sanctum',
    'role:admin|recruiter',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin', Dashboard::class)->name('admin-dashboard');
    Route::get('/admin/prompt', Prompt::class)->name('admin-prompt');
    Route::get('/admin/challenges', Challenges::class)->name('admin-challenges');
    Route::get('/admin/challenge', Challenge::class)->name('admin-challenge');
});

Route::get('/embed-editor', function () {
    $editor = Storage::get('code-editor/editor.html');
    return response($editor)->header('Content-Type', 'text/html');
})->name('embed-editor');