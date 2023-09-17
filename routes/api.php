<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
// Dashboard Controller
    Route::prefix("dashboard")->group(function () {
        Route::get("tasks", [DashboardController::class, "tasks"]);
        Route::get("users", [DashboardController::class, "users"]);
    });

    Route::apiResources([
        "users" => UserController::class,
        "status" => StatusController::class,
        "tasks" => TaskController::class,
        "user-tasks" => UserTaskController::class,
    ]);
});
