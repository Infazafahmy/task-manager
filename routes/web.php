<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Api\DashboardApiController;



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
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'markCompleted'])->name('tasks.complete');

    Route::get('/tasks/assign', [TaskController::class, 'assignPage'])->name('tasks.assignPage');
    Route::put('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');
    Route::delete('/tasks/{task}/member/{user}', [TaskController::class, 'removeMember'])->name('tasks.removeMember');

    Route::post('/tasks/{task}/postpone', [TaskController::class, 'postpone'])->name('tasks.postpone');
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');


});

require __DIR__.'/auth.php';

Route::middleware('auth')->get('/dashboard-data', [DashboardApiController::class, 'index']);

Route::get('/dashboard-frontend', function () {
    return view('dashboard-frontend'); 
})->middleware('auth');