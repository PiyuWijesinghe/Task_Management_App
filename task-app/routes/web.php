
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('force.logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'markAsCompleted'])->name('tasks.complete');
    Route::get('/tasks-assign', [TaskController::class, 'assignPage'])->name('tasks.assign');
    Route::patch('/tasks/{task}/assign', [TaskController::class, 'assignUser'])->name('tasks.assign.update');
    Route::post('/tasks/{task}/postpone', [TaskController::class, 'postpone'])->name('tasks.postpone');
    Route::get('/tasks-postponed', [TaskController::class, 'postponed'])->name('tasks.postponed');
});

require __DIR__.'/auth.php';
