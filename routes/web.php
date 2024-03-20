<?php

use App\Livewire\Admin\Challenge;
use App\Livewire\Admin\Challenges;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Prompt;
use App\Livewire\Interview;
use App\Livewire\Landing;
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
