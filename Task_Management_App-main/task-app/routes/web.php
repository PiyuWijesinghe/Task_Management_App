
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GoogleOauthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('force.logout');

Route::get('/dashboard', [TaskController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

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
    
    // Comment routes
    Route::post('/tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
    Route::delete('/comments/{comment}', [TaskController::class, 'deleteComment'])->name('comments.delete');
    
    // Attachment routes
    Route::post('/tasks/{task}/attachments', [TaskController::class, 'storeAttachmentWeb'])->name('tasks.attachments.store');
    Route::get('/tasks/{task}/attachments/{attachment}/download', [TaskController::class, 'downloadAttachment'])->middleware('secure.download')->name('tasks.attachments.download');
    Route::delete('/tasks/{task}/attachments/{attachment}', [TaskController::class, 'deleteAttachment'])->name('tasks.attachments.destroy');

    // Google OAuth
    Route::get('/google/redirect', [GoogleOauthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/google/callback', [GoogleOauthController::class, 'handleGoogleCallback'])->name('google.callback');
    
    // Report routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generateTaskReport'])->name('reports.generate');
    Route::get('/reports/export', [ReportController::class, 'exportTaskReport'])->name('reports.export');
    Route::get('/reports/filter-options', [ReportController::class, 'getFilterOptions'])->name('reports.filter-options');

    // Google Calendar test routes (temporary)
    Route::get('/google/calendar/list', function () {
        $user = auth()->user();
        $client = app(\App\Services\GoogleService::class)->getClientForUser($user);
        if (! $client) {
            return redirect()->route('google.redirect')->with('error', 'Connect Google first');
        }

        $svc = new \Google_Service_Calendar($client);
        $list = $svc->calendarList->listCalendarList();
        return response()->json($list->getItems());
    })->name('google.calendar.list');

    Route::post('/google/calendar/create', function (\Illuminate\Http\Request $request) {
        $user = auth()->user();
        $client = app(\App\Services\GoogleService::class)->getClientForUser($user);
        if (! $client) {
            return redirect()->route('google.redirect')->with('error', 'Connect Google first');
        }

        $svc = new \Google_Service_Calendar($client);
        $event = new \Google_Service_Calendar_Event([
            'summary' => $request->input('summary', 'Test Event'),
            'description' => $request->input('description', 'Created by test route'),
            'start' => ['dateTime' => \Carbon\Carbon::now()->addMinutes(5)->toRfc3339String()],
            'end' => ['dateTime' => \Carbon\Carbon::now()->addMinutes(35)->toRfc3339String()],
        ]);

        $created = $svc->events->insert('primary', $event);
        return response()->json($created);
    })->name('google.calendar.create');
});

require __DIR__.'/auth.php';
