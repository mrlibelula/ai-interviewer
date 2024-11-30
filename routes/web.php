<?php

use App\Livewire\Admin\Challenge;
use App\Livewire\Admin\Challenges;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Prompt;
use App\Livewire\Interview;
use App\Livewire\Landing;
use App\Livewire\Metrics;
use App\Livewire\MetricsDifficulty;
use App\Livewire\MetricsHintUsage;
use App\Livewire\Start;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


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
    return redirect()->route('login');
});

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
    $response = Http::get('https://iframes.libe.dev/editor.html');
    return response($response->body())->header('Content-Type', 'text/html');
})->name('embed-editor');