<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Postponement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PostponementApiController extends Controller
{
    /**
     * Get postponements for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'task_id' => 'sometimes|exists:tasks,id',
                'limit' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Postponement::query()
                ->with(['task:id,title,status', 'postponedBy:id,name,username'])
                ->whereHas('task', function($q) use ($user) {
                    // Only get postponements for tasks the user has access to
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

            $limit = $request->get('limit', 20);
            $postponements = $query->orderBy('created_at', 'desc')
                                  ->paginate($limit);

            return response()->json([
                'success' => true,
                'data' => [
                    'postponements' => $postponements->items(),
                    'pagination' => [
                        'current_page' => $postponements->currentPage(),
                        'last_page' => $postponements->lastPage(),
                        'per_page' => $postponements->perPage(),
                        'total' => $postponements->total(),
                        'from' => $postponements->firstItem(),
                        'to' => $postponements->lastItem(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch postponements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Postpone a task
     */
    public function postpone(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if user can postpone this task
            if (!$task->canBePostponedBy($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to postpone this task'
                ], 403);
            }

            // Check if task is already completed
            if ($task->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot postpone a completed task'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'new_due_date' => 'required|date|after:today',
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create postponement record
            $postponement = Postponement::create([
                'task_id' => $task->id,
                'old_due_date' => $task->due_date,
                'new_due_date' => $request->new_due_date,
                'reason' => $request->reason,
                'postponed_by' => $user->id,
            ]);

            // Update task's due date
            $task->update([
                'due_date' => $request->new_due_date,
            ]);

            $postponement->load(['task:id,title,status', 'postponedBy:id,name,username']);

            return response()->json([
                'success' => true,
                'message' => 'Task postponed successfully',
                'data' => [
                    'postponement' => $postponement,
                    'task' => $task->fresh(['assignedUsers', 'assignedUser', 'user'])
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task postponement failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get postponement history for a specific task
     */
    public function history(Request $request, Task $task): JsonResponse
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
                'limit' => 'sometimes|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $limit = $request->get('limit', 20);

            $postponements = $task->postponements()
                                 ->with(['postponedBy:id,name,username'])
                                 ->orderBy('created_at', 'desc')
                                 ->limit($limit)
                                 ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'task' => [
                        'id' => $task->id,
                        'title' => $task->title,
                        'current_due_date' => $task->due_date,
                        'status' => $task->status,
                    ],
                    'postponements' => $postponements
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch postponement history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific postponement
     */
    public function show(Request $request, Postponement $postponement): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if user has access to the task this postponement belongs to
            if (!$this->userHasAccessToTask($user, $postponement->task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied to this postponement'
                ], 403);
            }

            $postponement->load(['task:id,title,status', 'postponedBy:id,name,username']);

            return response()->json([
                'success' => true,
                'data' => ['postponement' => $postponement]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch postponement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get postponement statistics for the authenticated user
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'period' => 'sometimes|in:week,month,quarter,year',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $period = $request->get('period', 'month');

            // Calculate date range based on period
            switch ($period) {
                case 'week':
                    $startDate = now()->startOfWeek();
                    break;
                case 'quarter':
                    $startDate = now()->startOfQuarter();
                    break;
                case 'year':
                    $startDate = now()->startOfYear();
                    break;
                default:
                    $startDate = now()->startOfMonth();
            }

            $baseQuery = Postponement::query()
                ->whereHas('task', function($q) use ($user) {
                    $q->where(function($subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id)
                                 ->orWhere('assigned_user_id', $user->id)
                                 ->orWhereHas('assignedUsers', function($assignedQuery) use ($user) {
                                     $assignedQuery->where('user_id', $user->id);
                                 });
                    });
                });

            $totalPostponements = (clone $baseQuery)->count();
            $periodPostponements = (clone $baseQuery)
                ->where('created_at', '>=', $startDate)
                ->count();
            
            $userPostponements = (clone $baseQuery)
                ->where('postponed_by', $user->id)
                ->count();

            $mostPostponedTasks = (clone $baseQuery)
                ->with(['task:id,title'])
                ->selectRaw('task_id, COUNT(*) as postponement_count')
                ->groupBy('task_id')
                ->orderBy('postponement_count', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        'total_postponements' => $totalPostponements,
                        'period_postponements' => $periodPostponements,
                        'user_postponements' => $userPostponements,
                        'period' => $period,
                        'most_postponed_tasks' => $mostPostponedTasks,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch postponement statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent postponements
     */
    public function recent(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

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

            $limit = $request->get('limit', 10);
            $days = $request->get('days', 7);

            $postponements = Postponement::query()
                ->with(['task:id,title,status', 'postponedBy:id,name,username'])
                ->where('created_at', '>=', now()->subDays($days))
                ->whereHas('task', function($q) use ($user) {
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
                'data' => ['postponements' => $postponements]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent postponements',
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