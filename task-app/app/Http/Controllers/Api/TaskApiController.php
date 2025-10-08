<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\TaskIndexRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskApiController extends Controller
{
    use AuthorizesRequests;

    /**
     * Normalize incoming status value to canonical form.
     * Accepts variations like pending, PENDING, in_progress, in-progress, completed etc.
     */
    private function normalizeStatus(?string $status): ?string
    {
        if (!$status) return null;
        $s = trim(strtolower(str_replace(['-', '_'], ' ', $status)));
        return match($s) {
            'pending' => 'Pending',
            'in progress', 'inprogress' => 'In Progress',
            'completed', 'complete' => 'Completed',
            default => $status, // Return original; validator will catch invalid
        };
    }

    /**
     * Get tasks with filtering and sorting
     */
    public function index(TaskIndexRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            if (isset($validated['status'])) {
                $validated['status'] = $this->normalizeStatus($validated['status']);
            }

            $user = $request->user();

            // Base query for user's tasks
            $query = Task::query()
                ->where(function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                             ->orWhere('assigned_user_id', $user->id)
                             ->orWhereHas('assignedUsers', function($q) use ($user) {
                                 $q->where('user_id', $user->id);
                             });
                })
                ->with(['assignedUsers', 'assignedUser', 'user']);

            // Filters
            if (!empty($validated['status'] ?? null)) {
                $query->where('status', $validated['status']);
            }
            if (!empty($validated['priority'] ?? null)) {
                $query->where('priority', $validated['priority']);
            }
            if (!empty($validated['assigned_user_id'] ?? null)) {
                $query->where(function($q) use ($validated) {
                    $q->where('assigned_user_id', $validated['assigned_user_id'])
                      ->orWhereHas('assignedUsers', function($aq) use ($validated) {
                          $aq->where('user_id', $validated['assigned_user_id']);
                      });
                });
            }
            if (!empty($validated['creator_id'] ?? null)) {
                $query->where('user_id', $validated['creator_id']);
            }
            if (!empty($validated['due_from'] ?? null)) {
                $query->whereDate('due_date', '>=', $validated['due_from']);
            }
            if (!empty($validated['due_to'] ?? null)) {
                $query->whereDate('due_date', '<=', $validated['due_to']);
            }
            if (!empty($validated['created_from'] ?? null)) {
                $query->whereDate('created_at', '>=', $validated['created_from']);
            }
            if (!empty($validated['created_to'] ?? null)) {
                $query->whereDate('created_at', '<=', $validated['created_to']);
            }
            if (!empty($validated['q'] ?? null)) {
                $qTerm = $validated['q'];
                $query->where(function($sub) use ($qTerm) {
                    $sub->where('title', 'LIKE', "%{$qTerm}%")
                        ->orWhere('description', 'LIKE', "%{$qTerm}%");
                });
            }

            // Sorting
            $sortBy = $validated['sort_by'] ?? 'due_date';
            $sortDir = $validated['sort_dir'] ?? 'asc';

            if ($sortBy === 'priority' || $sortBy === 'priority_order') {
                $query->orderByRaw("CASE 
                    WHEN priority = 'High' THEN 1 
                    WHEN priority = 'Medium' THEN 2 
                    WHEN priority = 'Low' THEN 3 
                    ELSE 4 END " . ($sortDir === 'desc' ? 'DESC' : 'ASC'));
            } else if ($sortBy === 'status') {
                $query->orderByRaw("CASE 
                    WHEN status = 'Pending' THEN 1 
                    WHEN status = 'In Progress' THEN 2 
                    WHEN status = 'Completed' THEN 3 
                    ELSE 4 END " . ($sortDir === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($sortBy, $sortDir);
            }

            // Secondary ordering for consistency
            if ($sortBy !== 'created_at') {
                $query->orderBy('created_at', 'desc');
            }

            $perPage = $validated['per_page'] ?? 15;
            $page = $validated['page'] ?? null; // paginator will read current page from request if null
            $tasks = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'tasks' => $tasks->items(),
                    'meta' => [
                        'current_page' => $tasks->currentPage(),
                        'last_page' => $tasks->lastPage(),
                        'per_page' => $tasks->perPage(),
                        'total' => $tasks->total(),
                        'from' => $tasks->firstItem(),
                        'to' => $tasks->lastItem(),
                        'sort_by' => $sortBy,
                        'sort_dir' => $sortDir,
                        'filters' => array_filter([
                            'status' => $validated['status'] ?? null,
                            'priority' => $validated['priority'] ?? null,
                            'assigned_user_id' => $validated['assigned_user_id'] ?? null,
                            'creator_id' => $validated['creator_id'] ?? null,
                            'due_from' => $validated['due_from'] ?? null,
                            'due_to' => $validated['due_to'] ?? null,
                            'created_from' => $validated['created_from'] ?? null,
                            'created_to' => $validated['created_to'] ?? null,
                            'q' => $validated['q'] ?? null,
                        ])
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new task
     */
    public function store(Request $request): JsonResponse
    {
        try {
            if ($request->has('status')) {
                $request->merge(['status' => $this->normalizeStatus($request->get('status'))]);
            }
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date|after_or_equal:today',
                'status' => 'sometimes|in:Pending,In Progress,Completed',
                'priority' => 'sometimes|in:High,Medium,Low',
                'assigned_user_id' => 'nullable|exists:users,id',
                'assigned_users' => 'nullable|array',
                'assigned_users.*' => 'exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'status' => $request->get('status', 'Pending'),
                'priority' => $request->get('priority', 'Medium'),
                'user_id' => $request->user()->id,
                'assigned_user_id' => $request->assigned_user_id,
            ]);

            // Assign multiple users if provided
            if ($request->has('assigned_users')) {
                $task->assignedUsers()->sync($request->assigned_users);
            }

            $task->load(['assignedUsers', 'assignedUser', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => ['task' => $task]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific task
     */
    public function show(Request $request, Task $task): JsonResponse
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

            $task->load(['assignedUsers', 'assignedUser', 'user', 'comments.user', 'postponements.postponedBy']);

            return response()->json([
                'success' => true,
                'data' => ['task' => $task]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update task
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            // Check if user can update this task
            if (!$this->userCanModifyTask($user, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this task'
                ], 403);
            }

            if ($request->has('status')) {
                $request->merge(['status' => $this->normalizeStatus($request->get('status'))]);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|nullable|string',
                'due_date' => 'sometimes|nullable|date|after_or_equal:today',
                'priority' => 'sometimes|in:High,Medium,Low',
                'status' => 'sometimes|in:Pending,In Progress,Completed',
                'assigned_user_id' => 'sometimes|nullable|exists:users,id',
                'assigned_users' => 'sometimes|nullable|array',
                'assigned_users.*' => 'exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task->update($request->only([
                'title', 'description', 'due_date', 'priority', 'status', 'assigned_user_id'
            ]));

            // Update assigned users if provided
            if ($request->has('assigned_users')) {
                $task->assignedUsers()->sync($request->assigned_users);
            }

            $task->load(['assignedUsers', 'assignedUser', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => ['task' => $task]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete task
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            // Only creator can delete task
            if ($task->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete this task'
                ], 403);
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update task status
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$this->userCanModifyTask($user, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this task status'
                ], 403);
            }

            if ($request->has('status')) {
                $request->merge(['status' => $this->normalizeStatus($request->get('status'))]);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Pending,In Progress,Completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'data' => ['task' => $task]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark task as completed
     */
    public function markCompleted(Request $request, Task $task): JsonResponse
    {
        return $this->updateStatus($request->merge(['status' => 'Completed']), $task);
    }

    /**
     * Update task priority
     */
    public function updatePriority(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$this->userCanModifyTask($user, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this task priority'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'priority' => 'required|in:High,Medium,Low',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task->update(['priority' => $request->priority]);

            return response()->json([
                'success' => true,
                'message' => 'Task priority updated successfully',
                'data' => ['task' => $task]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Priority update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign user to task
     */
    public function assignUser(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$this->userCanModifyTask($user, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to assign users to this task'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'type' => 'sometimes|in:primary,additional',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $assignType = $request->get('type', 'additional');

            if ($assignType === 'primary') {
                $task->update(['assigned_user_id' => $request->user_id]);
            } else {
                $task->assignedUsers()->syncWithoutDetaching([$request->user_id]);
            }

            $task->load(['assignedUsers', 'assignedUser']);

            return response()->json([
                'success' => true,
                'message' => 'User assigned to task successfully',
                'data' => ['task' => $task]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User assignment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unassign user from task
     */
    public function unassignUser(Request $request, Task $task, User $user): JsonResponse
    {
        try {
            $currentUser = $request->user();

            if (!$this->userCanModifyTask($currentUser, $task)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to unassign users from this task'
                ], 403);
            }

            // Remove from primary assignment
            if ($task->assigned_user_id === $user->id) {
                $task->update(['assigned_user_id' => null]);
            }

            // Remove from additional assignments
            $task->assignedUsers()->detach($user->id);

            $task->load(['assignedUsers', 'assignedUser']);

            return response()->json([
                'success' => true,
                'message' => 'User unassigned from task successfully',
                'data' => ['task' => $task]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User unassignment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get task assignees
     */
    public function getAssignees(Task $task): JsonResponse
    {
        try {
            $assignees = collect();

            // Add primary assignee
            if ($task->assignedUser) {
                $assignees->push([
                    'user' => $task->assignedUser,
                    'type' => 'primary'
                ]);
            }

            // Add additional assignees
            foreach ($task->assignedUsers as $user) {
                $assignees->push([
                    'user' => $user,
                    'type' => 'additional'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => ['assignees' => $assignees]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assignees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard data
     */
    public function dashboard(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $baseQuery = Task::query()
                ->where(function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                             ->orWhere('assigned_user_id', $user->id)
                             ->orWhereHas('assignedUsers', function($q) use ($user) {
                                 $q->where('user_id', $user->id);
                             });
                });

            $pending = (clone $baseQuery)->where('status', 'Pending')->count();
            $inProgress = (clone $baseQuery)->where('status', 'In Progress')->count();
            $completed = (clone $baseQuery)->where('status', 'Completed')->count();
            $overdue = (clone $baseQuery)
                ->where('status', '!=', 'Completed')
                ->where('due_date', '<', now()->toDateString())
                ->count();
            $dueToday = (clone $baseQuery)
                ->where('status', '!=', 'Completed')
                ->where('due_date', now()->toDateString())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        'pending' => $pending,
                        'in_progress' => $inProgress,
                        'completed' => $completed,
                        'overdue' => $overdue,
                        'due_today' => $dueToday,
                        'total' => $pending + $inProgress + $completed,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get overdue tasks
     */
    public function overdue(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $tasks = Task::query()
                ->where(function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                             ->orWhere('assigned_user_id', $user->id)
                             ->orWhereHas('assignedUsers', function($q) use ($user) {
                                 $q->where('user_id', $user->id);
                             });
                })
                ->where('status', '!=', 'Completed')
                ->where('due_date', '<', now()->toDateString())
                ->with(['assignedUsers', 'assignedUser', 'user'])
                ->orderBy('due_date', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => ['tasks' => $tasks]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch overdue tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tasks due today
     */
    public function dueToday(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $tasks = Task::query()
                ->where(function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                             ->orWhere('assigned_user_id', $user->id)
                             ->orWhereHas('assignedUsers', function($q) use ($user) {
                                 $q->where('user_id', $user->id);
                             });
                })
                ->where('status', '!=', 'Completed')
                ->where('due_date', now()->toDateString())
                ->with(['assignedUsers', 'assignedUser', 'user'])
                ->orderBy('priority')
                ->get();

            return response()->json([
                'success' => true,
                'data' => ['tasks' => $tasks]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks due today',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search tasks
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
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
            $limit = $request->get('limit', 10);

            $tasks = Task::query()
                ->where(function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                             ->orWhere('assigned_user_id', $user->id)
                             ->orWhereHas('assignedUsers', function($q) use ($user) {
                                 $q->where('user_id', $user->id);
                             });
                })
                ->where(function($query) use ($searchQuery) {
                    $query->where('title', 'LIKE', "%{$searchQuery}%")
                          ->orWhere('description', 'LIKE', "%{$searchQuery}%");
                })
                ->with(['assignedUsers', 'assignedUser', 'user'])
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => ['tasks' => $tasks]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to check if user has access to task
     */
    private function userHasAccessToTask(User $user, Task $task): bool
    {
        return $task->user_id === $user->id ||
               $task->assigned_user_id === $user->id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }

    /**
     * Helper method to check if user can modify task
     */
    private function userCanModifyTask(User $user, Task $task): bool
    {
        return $task->user_id === $user->id ||
               $task->assigned_user_id === $user->id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }
}