<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\DashboardApiController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Core CRUD
    Route::get('/tasks', [TaskApiController::class, 'index']);
    Route::post('/tasks', [TaskApiController::class, 'store']);
    Route::put('/tasks/{task}', [TaskApiController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskApiController::class, 'destroy']);
    Route::post('/tasks/{task}/complete', [TaskApiController::class, 'markCompleted']);
    
    // Extra features
    Route::post('/tasks/{task}/assign', [TaskApiController::class, 'assign']);
    Route::post('/tasks/{task}/remove-members', [TaskApiController::class, 'removeAssignMember']);
    Route::post('/tasks/{task}/postpone', [TaskApiController::class, 'postpone']);

    Route::post('/tasks/{task}/comments', [CommentApiController::class, 'store']);
   
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/dashboard-data', [DashboardApiController::class, 'index']);

Route::middleware(['auth:sanctum', 'auth'])->get('/user', function (Request $request) {
    return $request->user();
});
