<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CommentApiController extends Controller
{
    /**
     * Get comments for a specific task
     */
    public function index(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if user has access to this task
            if (!$this->userHasAccessToTask($user, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied to this task'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'limit' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1',
                'order' => 'sometimes|in:asc,desc',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $limit = $request->get('limit', 20);
            $order = $request->get('order', 'asc');

            $comments = $task->comments()
                           ->with(['user:id,name,username'])
                           ->orderBy('created_at', $order)
                           ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => [
                    'comments' => $comments->items(),
                    'pagination' => [
                        'current_page' => $comments->currentPage(),
                        'last_page' => $comments->lastPage(),
                        'per_page' => $comments->perPage(),
                        'total' => $comments->total(),
                        'from' => $comments->firstItem(),
                        'to' => $comments->lastItem(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a comment to a task
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if user has access to this task
            if (!$this->userHasAccessToTask($user, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied to this task'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'comment' => 'required|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comment = TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'comment' => $request->comment,
            ]);

            $comment->load('user:id,name,username');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => ['comment' => $comment]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific comment
     */
    public function show(TaskComment $comment): JsonResponse
    {
        try {
            $comment->load(['user:id,name,username', 'task:id,title']);

            return response()->json([
                'success' => true,
                'data' => ['comment' => $comment]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a comment (optional feature)
     */
    public function update(Request $request, Task $task, TaskComment $comment): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if comment belongs to this task
            if ($comment->task_id !== $task->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment does not belong to this task'
                ], 400);
            }

            // Check if user owns this comment
            if ($comment->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only edit your own comments'
                ], 403);
            }

            // Check if comment is not too old (e.g., can only edit within 24 hours)
            if ($comment->created_at->diffInHours(now()) > 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comments can only be edited within 24 hours of creation'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'comment' => 'required|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comment->update([
                'comment' => $request->comment,
            ]);

            $comment->load('user:id,name,username');

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'data' => ['comment' => $comment]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(Request $request, Task $task, TaskComment $comment): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if comment belongs to this task
            if ($comment->task_id !== $task->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment does not belong to this task'
                ], 400);
            }

            // Check if user owns this comment or is the task creator
            if ($comment->user_id !== $user->id && $task->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own comments or comments on your tasks'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search comments (optional feature)
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
                'task_id' => 'sometimes|exists:tasks,id',
                'limit' => 'sometimes|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $searchQuery = $request->query;
            $limit = $request->get('limit', 20);

            $query = TaskComment::query()
                ->with(['user:id,name,username', 'task:id,title'])
                ->where('comment', 'LIKE', "%{$searchQuery}%")
                ->whereHas('task', function($q) use ($user) {
                    // Only search in tasks the user has access to
                    $q->where(function($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id)
                                 ->orWhere('assigned_user_id', $user->id)
                                 ->orWhereHas('assignedUsers', function($assignedQuery) use ($user) {
                                     $assignedQuery->where('user_id', $user->id);
                                 });
                    });
                });

            // Filter by specific task if provided
            if ($request->has('task_id')) {
                $query->where('task_id', $request->task_id);
            }

            $comments = $query->orderBy('created_at', 'desc')
                            ->limit($limit)
                            ->get();

            return response()->json([
                'success' => true,
                'data' => ['comments' => $comments]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Comment search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent comments for user's tasks
     */
    public function recent(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'sometimes|integer|min:1|max:50',
                'days' => 'sometimes|integer|min:1|max:30',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $limit = $request->get('limit', 10);
            $days = $request->get('days', 7);

            $comments = TaskComment::query()
                ->with(['user:id,name,username', 'task:id,title'])
                ->where('created_at', '>=', now()->subDays($days))
                ->whereHas('task', function($q) use ($user) {
                    // Only get comments from tasks the user has access to
                    $q->where(function($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id)
                                 ->orWhere('assigned_user_id', $user->id)
                                 ->orWhereHas('assignedUsers', function($assignedQuery) use ($user) {
                                     $assignedQuery->where('user_id', $user->id);
                                 });
                    });
                })
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => ['comments' => $comments]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to check if user has access to task
     */
    private function userHasAccessToTask($user, Task $task): bool
    {
        return $task->user_id === $user->id ||
               $task->assigned_user_id === $user->id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }
}