<x-app-layout>
    <div class="flex h-screen bg-transparent">
        <!-- Sidebar -->
        <div class="w-64 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-r border-white/20 dark:border-gray-700/20 shadow-2xl">
            <div class="p-6 border-b border-white/10 dark:border-gray-700/20">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">TaskFlow</h3>
                        <div class="flex items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent font-semibold">Professional</span>
                            <div class="w-2 h-2 bg-green-500 rounded-full ml-2 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent hover:scrollbar-thumb-gray-400 dark:hover:scrollbar-thumb-gray-500">
                <div class="px-4 pb-6">
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-blue-500 group-hover:to-purple-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                                </svg>
                            </div>
                            Dashboard
                        </a>

                        <!-- Task Management -->
                        <a href="{{ route('tasks.index') }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-purple-500 group-hover:to-indigo-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            Task Management
                        </a>

                        <!-- Create Task -->
                        <a href="{{ route('tasks.create') }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-green-500 group-hover:to-teal-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            Create Task
                        </a>

                        <!-- Assign User (Active) -->
                        <a href="{{ route('tasks.assign') }}" class="bg-gradient-to-r from-purple-500/20 to-indigo-500/20 text-purple-700 dark:text-purple-200 group flex items-center px-4 py-3 text-sm font-medium rounded-xl border border-purple-200/50 dark:border-purple-500/30 shadow-sm">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold">Assign User</span>
                            <div class="ml-auto w-2 h-2 bg-purple-500 rounded-full"></div>
                        </a>

                        <!-- Pending Tasks -->
                        <a href="{{ route('tasks.index', ['status' => 'Pending']) }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-orange-500 group-hover:to-red-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Pending Tasks
                        </a>

                        <!-- In Progress Tasks -->
                        <a href="{{ route('tasks.index', ['status' => 'In Progress']) }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-yellow-500 group-hover:to-orange-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            In Progress Tasks
                        </a>

                        <!-- Completed Tasks -->
                        <a href="{{ route('tasks.index', ['status' => 'Completed']) }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-emerald-500 group-hover:to-cyan-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Completed Tasks
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto bg-transparent">
            <!-- Header -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-sm border-b border-white/20 dark:border-gray-700/20">
                <div class="max-w-7xl mx-auto px-4 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                        <div class="text-gray-300 dark:text-gray-600">|</div>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Assign Users to Tasks</h1>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto py-8 px-4">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Your Created Tasks</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Assign your tasks to team members or manage existing assignments</p>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $tasks->count() }} {{ $tasks->count() === 1 ? 'task' : 'tasks' }}
                            </div>
                        </div>
                    </div>

                    <!-- Tasks List -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($tasks as $task)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h3>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $task->status === 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : ($task->status === 'In Progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400') }}">
                                            {{ $task->status }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $task->getPriorityBadgeClasses() }}">
                                            {!! $task->getPriorityIcon() !!}
                                            {{ $task->getPriorityText() }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Created {{ $task->created_at->diffForHumans() }}
                                        </span>
                                        
                                        @if($task->due_date)
                                        <span class="flex items-center {{ $task->due_date->isPast() && $task->status !== 'Completed' ? 'text-red-600 dark:text-red-400 font-semibold' : ($task->due_date->isToday() && $task->status !== 'Completed' ? 'text-orange-600 dark:text-orange-400 font-semibold' : '') }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            @if($task->due_date->isPast() && $task->status !== 'Completed')
                                                ⚠️ Overdue: {{ $task->due_date->format('M d, Y') }}
                                            @elseif($task->due_date->isToday() && $task->status !== 'Completed')
                                                🕐 Due Today: {{ $task->due_date->format('M d, Y') }}
                                            @else
                                                Due {{ $task->due_date->format('M d, Y') }}
                                            @endif
                                        </span>
                                        @endif
                                    </div>
                                    
                                    @php
                                        $allAssignees = collect();
                                        
                                        // Add single assigned user if exists
                                        if($task->assigned_user_id && $task->assignedUser) {
                                            $allAssignees->push($task->assignedUser);
                                        }
                                        
                                        // Add multiple assigned users
                                        if($task->assignedUsers && $task->assignedUsers->count() > 0) {
                                            $allAssignees = $allAssignees->merge($task->assignedUsers);
                                        }
                                        
                                        // Remove duplicates based on ID
                                        $allAssignees = $allAssignees->unique('id');
                                    @endphp
                                    
                                    @if($allAssignees->count() > 0)
                                    <div class="mt-3 text-sm text-blue-600 dark:text-blue-400">
                                        <div class="flex items-start">
                                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <div>
                                                <span class="font-medium">Currently assigned to:</span>
                                                <div class="mt-1 flex flex-wrap gap-2">
                                                    @foreach($allAssignees as $assignee)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                            {{ $assignee->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Assignment Form -->
                                <div class="ml-6 min-w-0 flex-shrink-0">
                                    <form action="{{ route('tasks.assign.update', $task) }}" method="POST" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Assign to Users:
                                            </label>
                                            
                                            <div class="max-h-40 overflow-y-auto space-y-2 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-600 p-3">
                                                @php
                                                    // Get currently assigned user IDs
                                                    $currentAssignees = collect();
                                                    if($task->assigned_user_id) {
                                                        $currentAssignees->push($task->assigned_user_id);
                                                    }
                                                    if($task->assignedUsers && $task->assignedUsers->count() > 0) {
                                                        $currentAssignees = $currentAssignees->merge($task->assignedUsers->pluck('id'));
                                                    }
                                                    $currentAssignees = $currentAssignees->unique()->toArray();
                                                @endphp
                                                
                                                @foreach($users as $user)
                                                    <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded">
                                                        <input type="checkbox" 
                                                               name="assigned_users[]" 
                                                               value="{{ $user->id }}"
                                                               {{ in_array($user->id, $currentAssignees) ? 'checked' : '' }}
                                                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600 rounded">
                                                        <div class="ml-3 flex-1">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                                
                                                @if($users->isEmpty())
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No other users available</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white text-sm font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                Update Assignments
                                            </button>
                                            
                                            @php
                                                $hasAssignees = !empty($currentAssignees);
                                            @endphp
                                            
                                            @if($hasAssignees)
                                            <button type="button" 
                                                    onclick="clearAllAssignments(this.form)"
                                                    class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                Clear All
                                            </button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tasks found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't created any tasks yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-teal-600 text-white text-sm font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create your first task
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearAllAssignments(form) {
            // Uncheck all checkboxes
            const checkboxes = form.querySelectorAll('input[name="assigned_users[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Submit the form
            form.submit();
        }
    </script>
</x-app-layout>