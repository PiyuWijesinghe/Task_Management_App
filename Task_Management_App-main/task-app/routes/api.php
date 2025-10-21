<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\PostponementApiController;
use App\Http\Controllers\Api\DebugController;

Route::prefix('v1')->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthApiController::class, 'register']);
        Route::post('/login', [AuthApiController::class, 'login']);
        Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthApiController::class, 'resetPassword']);
    });

    // Protected API routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Authentication routes for authenticated users
        Route::prefix('auth')->group(function () {
            Route::get('/user', [AuthApiController::class, 'user']);
            Route::post('/logout', [AuthApiController::class, 'logout']);
            Route::post('/logout-all', [AuthApiController::class, 'logoutAll']);
            Route::patch('/update-profile', [AuthApiController::class, 'updateProfile']);
            Route::post('/change-password', [AuthApiController::class, 'changePassword']);
        });

        // User management routes
        Route::prefix('users')->group(function () {
            Route::get('/', [UserApiController::class, 'index']); // Get all users
            Route::get('/search', [UserApiController::class, 'search']); // Search users
            Route::get('/{user}', [UserApiController::class, 'show']); // Get specific user
            Route::put('/{user}', [UserApiController::class, 'update']); // Update user (admin only)
            Route::delete('/{user}', [UserApiController::class, 'destroy']); // Delete user (admin only)
        });

        // Task management routes
        Route::prefix('tasks')->group(function () {
            Route::get('/', [TaskApiController::class, 'index']); // Get user's tasks with filters
            Route::post('/', [TaskApiController::class, 'store']); // Create new task
            Route::get('/dashboard', [TaskApiController::class, 'dashboard']); // Dashboard data
            Route::get('/statistics', [TaskApiController::class, 'statistics']); // Task statistics
            Route::get('/overdue', [TaskApiController::class, 'overdue']); // Get overdue tasks
            Route::get('/due-today', [TaskApiController::class, 'dueToday']); // Get tasks due today
            Route::get('/postponed', [TaskApiController::class, 'postponed']); // Get postponed tasks
            
            // Specific task routes
            Route::get('/{task}', [TaskApiController::class, 'show']); // Get specific task
            Route::put('/{task}', [TaskApiController::class, 'update']); // Update task
            Route::delete('/{task}', [TaskApiController::class, 'destroy']); // Delete task
            
            // Task status management
            Route::patch('/{task}/status', [TaskApiController::class, 'updateStatus']); // Update task status
            Route::patch('/{task}/complete', [TaskApiController::class, 'markCompleted']); // Mark as completed
            Route::patch('/{task}/priority', [TaskApiController::class, 'updatePriority']); // Update priority
            
            // Task assignment routes
            Route::post('/{task}/assign', [TaskApiController::class, 'assignUser']); // Assign user to task
            Route::delete('/{task}/unassign/{user}', [TaskApiController::class, 'unassignUser']); // Unassign user
            Route::get('/{task}/assignees', [TaskApiController::class, 'getAssignees']); // Get task assignees
            
            // Task postponement routes
            Route::post('/{task}/postpone', [PostponementApiController::class, 'postpone']); // Postpone task
            Route::get('/{task}/postponements', [PostponementApiController::class, 'history']); // Postponement history
            
            // Task attachment routes
            Route::get('/{task}/attachments', [TaskApiController::class, 'getAttachments']); // Get task attachments
            Route::post('/{task}/attachments', [TaskApiController::class, 'storeAttachmentApi']); // Add attachment
            Route::get('/{task}/attachments/{attachment}/download', [TaskApiController::class, 'downloadAttachment'])->middleware('secure.download'); // Download attachment
            Route::delete('/{task}/attachments/{attachment}', [TaskApiController::class, 'deleteAttachment']); // Delete attachment
        });

        // Task comments routes
        Route::prefix('tasks/{task}/comments')->group(function () {
            Route::get('/', [CommentApiController::class, 'index']); // Get task comments
            Route::post('/', [CommentApiController::class, 'store']); // Add comment
            Route::put('/{comment}', [CommentApiController::class, 'update']); // Update comment (optional)
            Route::delete('/{comment}', [CommentApiController::class, 'destroy']); // Delete comment
        });

        // Standalone comments routes
        Route::prefix('comments')->group(function () {
            Route::get('/{comment}', [CommentApiController::class, 'show']); // Get specific comment
        });

        // Postponement management routes
        Route::prefix('postponements')->group(function () {
            Route::get('/', [PostponementApiController::class, 'index']); // Get user's postponements
            Route::get('/{postponement}', [PostponementApiController::class, 'show']); // Get specific postponement
        });

        // Debug routes
        Route::prefix('debug')->group(function () {
            Route::get('/auth', [DebugController::class, 'debugAuth']); // Debug auth state
            Route::post('/tasks/{task}/upload', [DebugController::class, 'debugUpload']); // Debug upload
        });

        // Bulk operations routes
        Route::prefix('bulk')->group(function () {
            Route::post('/tasks/complete', [TaskApiController::class, 'bulkComplete']); // Complete multiple tasks
            Route::post('/tasks/delete', [TaskApiController::class, 'bulkDelete']); // Delete multiple tasks
            Route::post('/tasks/assign', [TaskApiController::class, 'bulkAssign']); // Assign multiple tasks
            Route::post('/tasks/update-priority', [TaskApiController::class, 'bulkUpdatePriority']); // Update priority
        });

        // Search and filter routes
        Route::prefix('search')->group(function () {
            Route::get('/tasks', [TaskApiController::class, 'search']); // Search tasks
            Route::get('/users', [UserApiController::class, 'search']); // Search users
            Route::get('/comments', [CommentApiController::class, 'search']); // Search comments (optional)
        });

        // Report and analytics routes
        Route::prefix('reports')->group(function () {
            Route::get('/tasks/summary', [TaskApiController::class, 'taskSummary']); // Task summary report
            Route::get('/user/activity', [UserApiController::class, 'userActivity']); // User activity report
            Route::get('/productivity', [TaskApiController::class, 'productivityReport']); // Productivity metrics
        });
    });
});

// Fallback route for API
Route::fallback(function(){
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});