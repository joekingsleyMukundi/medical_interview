<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/users', function () {
    return Inertia::render('Users');
})->middleware(['auth', 'verified'])->name('users');

/*
 * Task */
Route::prefix("tasks")->middleware(["auth", "verified"])->group(function () {
    Route::get("", fn() => Inertia::render('Task/Tasks'))->middleware(['auth', 'verified'])->name('tasks');
    Route::get("/create", fn() => Inertia::render('Task/TaskCreate'))->middleware(['auth', 'verified'])->name('task.create');
    Route::get("/{id}", fn() => Inertia::render('Task/Task'))->middleware(['auth', 'verified'])->name('task.show');
    Route::get("/{id}/edit", fn() => Inertia::render('Task/TaskEdit'))->middleware(['auth', 'verified'])->name('task.edit');
});

require __DIR__ . '/auth.php';
