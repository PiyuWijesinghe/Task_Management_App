<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    /**
     * Get all users (with pagination)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1',
                'search' => 'sometimes|string|min:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = User::query();

            // Apply search if provided
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                });
            }

            $limit = $request->get('limit', 15);
            $users = $query->select(['id', 'name', 'username', 'email', 'created_at'])
                          ->orderBy('name')
                          ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => [
                    'users' => $users->items(),
                    'pagination' => [
                        'current_page' => $users->currentPage(),
                        'last_page' => $users->lastPage(),
                        'per_page' => $users->perPage(),
                        'total' => $users->total(),
                        'from' => $users->firstItem(),
                        'to' => $users->lastItem(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific user
     */
    public function show(User $user): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search users
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
                'limit' => 'sometimes|integer|min:1|max:50',
                'exclude_current' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $searchQuery = $request->query;
            $limit = $request->get('limit', 10);
            $excludeCurrent = $request->get('exclude_current', false);

            $query = User::query();

            // Exclude current user if requested
            if ($excludeCurrent) {
                $query->where('id', '!=', $request->user()->id);
            }

            $users = $query->where(function($q) use ($searchQuery) {
                        $q->where('name', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('username', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('email', 'LIKE', "%{$searchQuery}%");
                    })
                    ->select(['id', 'name', 'username', 'email'])
                    ->limit($limit)
                    ->orderBy('name')
                    ->get();

            return response()->json([
                'success' => true,
                'data' => ['users' => $users]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user (admin functionality - can be extended)
     */
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            // Basic permission check - only user can update their own profile
            // You can extend this for admin roles
            if ($request->user()->id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this user'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update($request->only(['name', 'username', 'email']));

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'updated_at' => $user->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user (admin functionality)
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        try {
            // Basic permission check - prevent self-deletion
            if ($request->user()->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 400);
            }

            // Additional admin permission checks can be added here
            // For now, only allow users to delete their own account through profile
            return response()->json([
                'success' => false,
                'message' => 'User deletion not allowed through this endpoint'
            ], 403);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user activity/statistics
     */
    public function userActivity(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Get user's task statistics
            $createdTasks = $user->tasks()->count();
            $assignedTasks = $user->assignedTasks()->count();
            $completedTasks = $user->tasks()->where('status', 'Completed')->count() +
                             $user->assignedTasks()->where('status', 'Completed')->count();
            
            $pendingTasks = $user->tasks()->where('status', 'Pending')->count() +
                           $user->assignedTasks()->where('status', 'Pending')->count();
            
            $inProgressTasks = $user->tasks()->where('status', 'In Progress')->count() +
                              $user->assignedTasks()->where('status', 'In Progress')->count();

            // Get overdue tasks
            $overdueTasks = $user->tasks()
                                ->where('status', '!=', 'Completed')
                                ->where('due_date', '<', now()->toDateString())
                                ->count() +
                           $user->assignedTasks()
                                ->where('status', '!=', 'Completed')
                                ->where('due_date', '<', now()->toDateString())
                                ->count();

            // Get recent activity (last 7 days)
            $recentTasks = $user->tasks()
                               ->where('created_at', '>=', now()->subDays(7))
                               ->count();

            $recentComments = $user->hasMany(\App\Models\TaskComment::class)
                                  ->where('created_at', '>=', now()->subDays(7))
                                  ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_activity' => [
                        'tasks_created' => $createdTasks,
                        'tasks_assigned' => $assignedTasks,
                        'tasks_completed' => $completedTasks,
                        'tasks_pending' => $pendingTasks,
                        'tasks_in_progress' => $inProgressTasks,
                        'tasks_overdue' => $overdueTasks,
                        'recent_tasks_created' => $recentTasks,
                        'recent_comments' => $recentComments,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users available for task assignment
     */
    public function getAssignableUsers(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'sometimes|string|min:2',
                'limit' => 'sometimes|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = User::query();
            $limit = $request->get('limit', 20);

            // Apply search if provided
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('username', 'LIKE', "%{$searchTerm}%");
                });
            }

            $users = $query->select(['id', 'name', 'username'])
                          ->orderBy('name')
                          ->limit($limit)
                          ->get();

            return response()->json([
                'success' => true,
                'data' => ['users' => $users]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assignable users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}