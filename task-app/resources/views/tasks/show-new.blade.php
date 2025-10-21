<x-app-layout>
    <div class="flex h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-blue-900/20 dark:to-indigo-900/20">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden">
            <div class="h-full overflow-y-auto p-8">
                <div class="max-w-6xl mx-auto">
                    <!-- Header with Back Button -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-4 mb-4">
                            <a href="{{ route('tasks.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white/60 dark:bg-gray-700/60 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Tasks
                            </a>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $task->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Task Details and Management</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Task Information -->
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-8">
                                <div class="space-y-6">
                                    <!-- Description -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Description</h3>
                                        <div class="bg-gray-50/80 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200/50 dark:border-gray-600/50">
                                            <p class="text-gray-800 dark:text-gray-200 leading-relaxed">
                                                {{ $task->description ?: 'No description provided.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Task Details Grid -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Status -->
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</h3>
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium
                                                @if($task->status === 'Completed')
                                                    bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                @elseif($task->status === 'In Progress')
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                                                @else
                                                    bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400
                                                @endif
                                                border border-current/20">
                                                @if($task->status === 'Completed')
                                                    ✅
                                                @elseif($task->status === 'In Progress')
                                                    🔄
                                                @else
                                                    ⏳
                                                @endif
                                                {{ $task->status }}
                                            </span>
                                        </div>

                                        <!-- Priority -->
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Priority</h3>
                                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium
                                                @if($task->priority === 'High')
                                                    bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                                @elseif($task->priority === 'Medium')
                                                    bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                                @else
                                                    bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                @endif
                                                border border-current/20">
                                                @if($task->priority === 'High')
                                                    🔥
                                                @elseif($task->priority === 'Medium')
                                                    ⚡
                                                @else
                                                    🟢
                                                @endif
                                                {{ $task->priority }}
                                            </span>
                                        </div>

                                        <!-- Due Date -->
                                        @if($task->due_date)
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Due Date</h3>
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-gray-800 dark:text-gray-200">
                                                    {{ $task->due_date->format('M d, Y') }}
                                                </span>
                                                @if($task->due_date->isPast() && $task->status !== 'Completed')
                                                    <span class="text-red-500 text-sm font-medium">(Overdue)</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Created By -->
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Created By</h3>
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($task->user->name, 0, 2)) }}
                                                </div>
                                                <span class="text-gray-800 dark:text-gray-200">{{ $task->user->name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assigned Users -->
                                    @if($task->assignedUsers->count() > 0 || $task->assignedUser)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Assigned To</h3>
                                        <div class="flex flex-wrap gap-3">
                                            @if($task->assignedUser)
                                                <div class="flex items-center space-x-2 bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-lg border border-blue-200/50 dark:border-blue-600/30">
                                                    <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($task->assignedUser->name, 0, 2)) }}
                                                    </div>
                                                    <span class="text-blue-800 dark:text-blue-300 text-sm">{{ $task->assignedUser->name }}</span>
                                                </div>
                                            @endif
                                            @foreach($task->assignedUsers as $user)
                                                <div class="flex items-center space-x-2 bg-purple-50 dark:bg-purple-900/20 px-3 py-2 rounded-lg border border-purple-200/50 dark:border-purple-600/30">
                                                    <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                    <span class="text-purple-800 dark:text-purple-300 text-sm">{{ $user->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Attachments Section -->
                            @include('tasks.partials.attachments')

                            <!-- Comments Section -->
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-8">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    Comments ({{ $task->comments->count() }})
                                </h3>

                                <!-- Add Comment Form -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 mb-6 border border-blue-200/50 dark:border-blue-600/30">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Add a Comment</h4>
                                    <form action="{{ route('tasks.comments.store', $task) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <textarea 
                                                name="comment" 
                                                rows="3" 
                                                class="w-full px-4 py-3 bg-white/60 dark:bg-gray-700/60 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                                placeholder="Share your thoughts, updates, or questions about this task..."
                                                required>{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add Comment
                                        </button>
                                    </form>
                                </div>

                                <!-- Comments List -->
                                @if($task->comments->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($task->comments as $comment)
                                            <div class="bg-white/60 dark:bg-gray-700/60 rounded-xl p-6 border border-gray-200/50 dark:border-gray-600/50 hover:bg-white/80 dark:hover:bg-gray-600/80 transition-all duration-200">
                                                <div class="flex items-start space-x-4">
                                                    <!-- User Avatar -->
                                                    <div class="flex-shrink-0">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Comment Content -->
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h5 class="font-semibold text-gray-900 dark:text-white">{{ $comment->user->name }}</h5>
                                                            @if($comment->user->username && $comment->user->username !== $comment->user->name)
                                                                <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300 rounded-full font-medium">{{ $comment->user->username }}</span>
                                                            @endif
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $comment->comment }}</p>
                                                    </div>
                                                    
                                                    <!-- Delete Button -->
                                                    @if($comment->user_id === auth()->id() || $task->user_id === auth()->id())
                                                        <div class="flex-shrink-0">
                                                            <form action="{{ route('comments.delete', $comment) }}" method="POST" 
                                                                  onsubmit="return confirm('Are you sure you want to delete this comment?');"
                                                                  class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No comments yet</h4>
                                        <p class="text-gray-500 dark:text-gray-400">Be the first to add a comment to this task!</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Quick Actions -->
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                                <div class="space-y-3">
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}" 
                                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg hover:scale-105">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit Task
                                        </a>
                                    @endcan

                                    @if($task->status !== 'Completed')
                                        <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg hover:scale-105">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Mark as Completed
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Task Metadata -->
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/20 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Task Information</h3>
                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Created:</span>
                                        <span class="text-gray-800 dark:text-gray-200">{{ $task->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($task->updated_at != $task->created_at)
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Updated:</span>
                                            <span class="text-gray-800 dark:text-gray-200">{{ $task->updated_at->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Comments:</span>
                                        <span class="text-gray-800 dark:text-gray-200">{{ $task->comments->count() }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Attachments:</span>
                                        <span class="text-gray-800 dark:text-gray-200">{{ $task->attachments->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>