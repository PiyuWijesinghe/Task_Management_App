<x-app-layout>
    <div class="flex h-screen bg-transparent">
        <!-- Sidebar -->
        <div class="w-64 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-r border-white/20 dark:border-gray-700/20 shadow-2xl flex flex-col h-full overflow-hidden">
            <div class="p-6 border-b border-white/10 dark:border-gray-700/20 flex-shrink-0">
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

                        <!-- Postponed Tasks (Active) -->
                        <a href="{{ route('tasks.postponed') }}" class="bg-gradient-to-r from-orange-500/20 to-red-500/20 text-orange-700 dark:text-orange-200 group flex items-center px-4 py-3 text-sm font-medium rounded-xl border border-orange-200/50 dark:border-orange-500/30 shadow-sm">
                            <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold">Postponed Tasks</span>
                            <div class="ml-auto w-2 h-2 bg-orange-500 rounded-full"></div>
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

                <!-- User Section -->
                <div class="mt-8 pt-6 px-4 border-t border-white/10 dark:border-gray-700/20">
                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-indigo-500 group-hover:to-purple-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            Profile Settings
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-gray-700 dark:text-gray-200 hover:bg-red-50/50 dark:hover:bg-red-900/20 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-red-500 group-hover:to-pink-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                    <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- User Info -->
                <div class="mt-6 px-4">
                    <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-xl rounded-2xl p-4 border border-white/20 dark:border-gray-700/20 shadow-lg">
                        <div class="flex items-center">
                            <div class="relative">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                <div class="flex items-center mt-1">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-green-600 dark:text-green-400 font-medium">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto bg-transparent">
            <div class="p-6">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-2xl p-4 backdrop-blur-xl shadow-lg animate-bounce" id="success-message">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center pulse-soft">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-semibold text-green-800 dark:text-green-200 flex items-center">
                                    🎉 Success!
                                </p>
                                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                            </div>
                            <button onclick="document.getElementById('success-message').style.display='none'" class="ml-auto text-green-500 hover:text-green-700 dark:text-green-300 dark:hover:text-green-100 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <script>
                        // Auto-hide success message after 5 seconds
                        setTimeout(function() {
                            const successMessage = document.getElementById('success-message');
                            if (successMessage) {
                                successMessage.style.transform = 'translateY(-100%)';
                                successMessage.style.opacity = '0';
                                setTimeout(() => successMessage.style.display = 'none', 300);
                            }
                        }, 5000);
                    </script>
                @endif

                <!-- Header Section -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl mb-6 border border-white/20 dark:border-gray-700/20">
                    <div class="relative p-8" style="background: linear-gradient(135deg, #ff9a56 0%, #ff6b6b 100%);">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-600/20 to-red-600/20 backdrop-blur-sm"></div>
                        <div class="relative flex items-center justify-between text-white">
                            <div>
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 backdrop-blur-sm">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-3xl font-bold mb-1">📅 Postponed Tasks</h1>
                                        <p class="text-orange-100 text-sm">Tasks that have been rescheduled for later completion</p>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden lg:block">
                                <div class="relative float-animation">
                                    <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm hover-lift">
                                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center pulse-soft">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 9h12v8H4V9z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Postponed Tasks Content -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20">
                    <div class="p-6">
                        @php
                            // Get tasks with postponement history for current user
                            $currentUser = Auth::user();
                            $postponedTasks = App\Models\Task::whereHas('postponements')
                                ->where(function($query) use ($currentUser) {
                                    $query->where('user_id', $currentUser->id)
                                          ->orWhere('assigned_user_id', $currentUser->id);
                                })
                                ->with(['postponements' => function($query) {
                                    $query->orderBy('created_at', 'desc');
                                }, 'user', 'assignedUser'])
                                ->orderBy('updated_at', 'desc')
                                ->get();
                        @endphp

                        @if($postponedTasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($postponedTasks as $task)
                                    <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/20 p-4 hover:bg-white/70 dark:hover:bg-gray-600/70 transition-all duration-300 hover:scale-[1.01] hover:shadow-lg">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start flex-1">
                                                <div class="w-4 h-4 rounded-full mr-4 mt-1 {{ $task->status === 'Completed' ? 'bg-green-500' : ($task->status === 'In Progress' ? 'bg-yellow-500' : 'bg-orange-500') }}"></div>
                                                <div class="flex-1">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                                                            @if($task->description)
                                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($task->description, 150) }}</p>
                                                            @endif
                                                            
                                                            <!-- Task Info -->
                                                            <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                                                <span class="flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v5m0 0v5a2 2 0 002 2h4a2 2 0 002-2v-5m-6 0h6"></path>
                                                                    </svg>
                                                                    Created {{ $task->created_at->diffForHumans() }}
                                                                </span>
                                                                @if($task->assigned_user_id && $task->assigned_user_id !== Auth::id())
                                                                <span class="flex items-center text-purple-600 dark:text-purple-400">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                    </svg>
                                                                    Assigned to {{ $task->assignedUser->name }}
                                                                </span>
                                                                @elseif($task->user_id !== Auth::id())
                                                                <span class="flex items-center text-blue-600 dark:text-blue-400">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                    </svg>
                                                                    Assigned by {{ $task->user->name }}
                                                                </span>
                                                                @endif
                                                                @if($task->due_date)
                                                                <span class="flex items-center {{ $task->due_date->isPast() && $task->status !== 'Completed' ? 'text-red-600 dark:text-red-400 font-semibold' : ($task->due_date->isToday() && $task->status !== 'Completed' ? 'text-orange-600 dark:text-orange-400 font-semibold' : '') }}">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v5m0 0v5a2 2 0 002 2h4a2 2 0 002-2v-5m-6 0h6M9 11h6"></path>
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

                                                            <!-- Postponement History -->
                                                            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3 border border-orange-200 dark:border-orange-700">
                                                                <h5 class="text-sm font-semibold text-orange-800 dark:text-orange-200 mb-2 flex items-center">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    Postponement History ({{ $task->postponements->count() }} times)
                                                                </h5>
                                                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                                                    @foreach($task->postponements->take(3) as $postponement)
                                                                    <div class="text-xs text-orange-700 dark:text-orange-300 bg-white/30 dark:bg-gray-800/30 rounded p-2">
                                                                        <div class="flex items-center justify-between">
                                                                            <span class="font-medium">
                                                                                {{ $postponement->old_due_date ? $postponement->old_due_date->format('M d, Y') : 'No date' }} 
                                                                                → {{ $postponement->new_due_date->format('M d, Y') }}
                                                                            </span>
                                                                            <span class="text-xs opacity-70">{{ $postponement->created_at->diffForHumans() }}</span>
                                                                        </div>
                                                                        @if($postponement->reason)
                                                                        <p class="mt-1 text-xs opacity-80">💭 {{ $postponement->reason }}</p>
                                                                        @endif
                                                                        <p class="text-xs opacity-70">By {{ $postponement->postponedBy->name }}</p>
                                                                    </div>
                                                                    @endforeach
                                                                    @if($task->postponements->count() > 3)
                                                                    <p class="text-xs text-orange-600 dark:text-orange-400 text-center py-1">
                                                                        + {{ $task->postponements->count() - 3 }} more postponements
                                                                    </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <!-- Status Badge -->
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-md {{ $task->status === 'Completed' ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-white' : ($task->status === 'In Progress' ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' : 'bg-gradient-to-r from-orange-400 to-red-500 text-white') }}">
                                                    {{ $task->status }}
                                                </span>
                                                
                                                <!-- View Details Button -->
                                                <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Postponed Tasks</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">You haven't postponed any tasks yet. Great time management! 🎉</p>
                                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    View All Tasks
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>