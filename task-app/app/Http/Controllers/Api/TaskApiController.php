<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskApiController extends Controller
{
    use AuthorizesRequests;

    private function normalizeStatus(?string $status): ?string
    {
        if (!$status) return null;
        $s = trim(strtolower(str_replace(['-', '_'], ' ', $status)));
        return match($s) {
            'pending' => 'Pending',
            'in progress', 'inprogress' => 'In Progress',
            'completed', 'complete' => 'Completed',
            default => $status,
        };
    }

    public function index(Request $request): JsonResponse
    {
        try {
            // Normalize status input (supports variations like in-progress)
            if ($request->filled('status')) {
                $request->merge(['status' => $this->normalizeStatus($request->get('status'))]);
            }

            $user = $request->user();

            // Validate query params (using names required by new spec: sort, order, search, status, priority, page, per_page)
            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|in:Pending,In Progress,Completed',
                'priority' => 'sometimes|in:High,Medium,Low',
                'search' => 'sometimes|string|max:255',
                'sort' => 'sometimes|string|in:id,title,status,priority,due_date,created_at,updated_at',
                'order' => 'sometimes|in:asc,desc',
                'per_page' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Task::query()
                ->where(function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id)
                             ->orWhere('assigned_user_id', $user->id)
                             ->orWhereHas('assignedUsers', function($q) use ($user) {
                                 $q->where('user_id', $user->id);
                             });
                })
                ->with(['assignedUsers', 'assignedUser', 'user']);

            // Filtering
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where('title', 'LIKE', "%{$search}%");
            }

            // Sorting (priority requires custom ordering)
            $sort = $request->get('sort', 'created_at');
            $order = $request->get('order', 'desc');
            if ($sort === 'priority') {
                $query->orderByRaw("CASE WHEN priority = 'High' THEN 1 WHEN priority = 'Medium' THEN 2 WHEN priority = 'Low' THEN 3 ELSE 4 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($sort, $order);
            }

            // Pagination (default 10 per page)
            $perPage = (int) $request->get('per_page', 10);
            $tasks = $query->paginate($perPage)->appends($request->query());

            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => [
                    'tasks' => $tasks->items(),
                    'pagination' => [
                        'current_page' => $tasks->currentPage(),
                        'last_page' => $tasks->lastPage(),
                        'per_page' => $tasks->perPage(),
                        'total' => $tasks->total(),
                        'from' => $tasks->firstItem(),
                        'to' => $tasks->lastItem(),
                        'has_more_pages' => $tasks->hasMorePages(),
                        'links' => [
                            'first' => $tasks->url(1),
                            'last' => $tasks->url($tasks->lastPage()),
                            'prev' => $tasks->previousPageUrl(),
                            'next' => $tasks->nextPageUrl(),
                        ]
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

    public function show(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();
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
                'assigned_to' => 'nullable|exists:users,id',
                'assigned_users' => 'nullable|array',
                'assigned_users.*' => 'exists:users,id',
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt,ppt,pptx',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $assignedUserId = $request->assigned_user_id ?? $request->assigned_to;
            
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'status' => $request->get('status', 'Pending'),
                'priority' => $request->get('priority', 'Medium'),
                'user_id' => $request->user()->id,
                'assigned_user_id' => $assignedUserId,
            ]);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $this->storeAttachment($task, $file);
                }
            }

            if ($request->has('assigned_users')) {
                $task->assignedUsers()->sync($request->assigned_users);
            }
            $task->load(['assignedUsers', 'assignedUser', 'user', 'attachments']);
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

    public function update(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();
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
                'title','description','due_date','priority','status','assigned_user_id'
            ]));
            if ($request->has('assigned_users')) {
                $task->assignedUsers()->sync($request->assigned_users);
            }
            $task->load(['assignedUsers','assignedUser','user']);
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

    public function destroy(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();
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

    public function markCompleted(Request $request, Task $task): JsonResponse
    {
        return $this->updateStatus($request->merge(['status' => 'Completed']), $task);
    }

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
            // Username is now the ONLY way to assign a user.
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|exists:users,username',
                'type' => 'sometimes|in:primary,additional',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            // Resolve target user by username only
            $targetUser = User::where('username', $request->get('username'))->first();
            if (!$targetUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Target user not found.'
                ], 404);
            }

            // Ensure target user is logged in (has at least one valid Sanctum token)
            $hasActiveToken = $targetUser->tokens()
                ->where(function($q){
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->exists();
            if (!$hasActiveToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'User must be logged in to be assigned.'
                ], 422);
            }

            $assignType = $request->get('type', 'additional');
            if ($assignType === 'primary') {
                $task->update(['assigned_user_id' => $targetUser->id]);
            } else {
                $task->assignedUsers()->syncWithoutDetaching([$targetUser->id]);
            }
            $task->load(['assignedUsers','assignedUser']);
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
            if ($task->assigned_user_id === $user->id) {
                $task->update(['assigned_user_id' => null]);
            }
            $task->assignedUsers()->detach($user->id);
            $task->load(['assignedUsers','assignedUser']);
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

    public function getAssignees(Task $task): JsonResponse
    {
        try {
            $assignees = collect();
            if ($task->assignedUser) {
                $assignees->push(['user' => $task->assignedUser,'type' => 'primary']);
            }
            foreach ($task->assignedUsers as $user) {
                $assignees->push(['user' => $user,'type' => 'additional']);
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

    public function dashboard(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $baseQuery = Task::query()->where(function($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id)
                         ->orWhere('assigned_user_id', $user->id)
                         ->orWhereHas('assignedUsers', function($q) use ($user) {
                             $q->where('user_id', $user->id);
                         });
            });
            $pending = (clone $baseQuery)->where('status','Pending')->count();
            $inProgress = (clone $baseQuery)->where('status','In Progress')->count();
            $completed = (clone $baseQuery)->where('status','Completed')->count();
            $overdue = (clone $baseQuery)->where('status','!=','Completed')->where('due_date','<', now()->toDateString())->count();
            $dueToday = (clone $baseQuery)->where('status','!=','Completed')->where('due_date', now()->toDateString())->count();
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

    public function overdue(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $tasks = Task::query()->where(function($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id)
                         ->orWhere('assigned_user_id', $user->id)
                         ->orWhereHas('assignedUsers', function($q) use ($user) {
                             $q->where('user_id', $user->id);
                         });
            })
            ->where('status','!=','Completed')
            ->where('due_date','<', now()->toDateString())
            ->with(['assignedUsers','assignedUser','user'])
            ->orderBy('due_date','asc')
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

    public function dueToday(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $tasks = Task::query()->where(function($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id)
                         ->orWhere('assigned_user_id', $user->id)
                         ->orWhereHas('assignedUsers', function($q) use ($user) {
                             $q->where('user_id', $user->id);
                         });
            })
            ->where('status','!=','Completed')
            ->where('due_date', now()->toDateString())
            ->with(['assignedUsers','assignedUser','user'])
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

    private function userHasAccessToTask(User $user, Task $task): bool
    {
        return $task->user_id === $user->id ||
               $task->assigned_user_id === $user->id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }

    private function userCanModifyTask(User $user, Task $task): bool
    {
        return $task->user_id === $user->id ||
               $task->assigned_user_id === $user->id ||
               $task->assignedUsers()->where('user_id', $user->id)->exists();
    }

    /**
     * Store a task attachment
     */
    private function storeAttachment(Task $task, $file)
    {
        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedName = Str::uuid() . '.' . $extension;
        
        // Store file in task attachments directory
        $filePath = $file->storeAs('task-attachments', $storedName, 'local');
        
        // Create attachment record
        return $task->attachments()->create([
            'original_name' => $originalName,
            'stored_name' => $storedName,
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $extension,
            'uploaded_by' => auth()->user()->id, // Use user()->id instead of id() to get primary key
        ]);
    }

    /**
     * Get task attachments
     */
    public function getAttachments(Request $request, Task $task): JsonResponse
    {
        try {
            // Use policy to authorize viewing attachments
            $this->authorize('viewAny', [TaskAttachment::class, $task]);
            
            $attachments = $task->attachments()->with('uploadedBy:id,name,email')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $attachments
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view attachments for this task'
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attachments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a new attachment to task
     */
    public function storeAttachmentApi(Request $request, Task $task): JsonResponse
    {
        try {
            $user = $request->user();
            \Log::info('Upload attempt', [
                'task_id' => $task->id,
                'user_id' => $user ? $user->id : 'null',
                'task_user_id' => $task->user_id,
                'assigned_user_id' => $task->assigned_user_id
            ]);
            
            // Use policy to authorize attachment creation
            $this->authorize('create', [TaskAttachment::class, $task]);
            
            $validator = Validator::make($request->all(), [
                'attachment' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx,txt,ppt,pptx',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('attachment');
            $attachment = $this->storeAttachment($task, $file);
            
            \Log::info('Attachment uploaded successfully', [
                'user_id' => $user->id,
                'task_id' => $task->id,
                'attachment_id' => $attachment->id,
                'file_name' => $attachment->original_name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'data' => $attachment
            ], 201);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('Upload unauthorized', [
                'user_id' => $user->id,
                'task_id' => $task->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to upload attachments to this task'
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download task attachment
     */
    public function downloadAttachment(Request $request, Task $task, TaskAttachment $attachment)
    {
        try {
            // First, verify the attachment belongs to the task
            if ($attachment->task_id !== $task->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attachment not found for this task'
                ], 404);
            }

            // Use policy to authorize download
            $this->authorize('download', $attachment);

            // Check if file exists on storage
            if (!Storage::exists($attachment->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on server'
                ], 404);
            }

            // Log the download for security audit (optional)
            \Log::info('Attachment downloaded', [
                'user_id' => $request->user()->id,
                'task_id' => $task->id,
                'attachment_id' => $attachment->id,
                'file_name' => $attachment->original_name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return Storage::download($attachment->file_path, $attachment->original_name);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to download this attachment'
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete task attachment
     */
    public function deleteAttachment(Request $request, Task $task, TaskAttachment $attachment): JsonResponse
    {
        try {
            // First, verify the attachment belongs to the task
            if ($attachment->task_id !== $task->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attachment not found for this task'
                ], 404);
            }

            // Use policy to authorize deletion
            $this->authorize('delete', $attachment);

            // Delete physical file
            if (Storage::exists($attachment->file_path)) {
                Storage::delete($attachment->file_path);
            }

            // Log the deletion for security audit
            \Log::info('Attachment deleted', [
                'user_id' => $request->user()->id,
                'task_id' => $task->id,
                'attachment_id' => $attachment->id,
                'file_name' => $attachment->original_name,
                'uploaded_by' => $attachment->uploaded_by
            ]);

            // Delete database record
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully!'
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this attachment'
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}