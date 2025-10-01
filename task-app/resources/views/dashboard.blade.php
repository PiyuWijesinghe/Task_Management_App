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
            <nav class="mt-6">
                <div class="px-4">
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-blue-700 dark:text-blue-200 group flex items-center px-4 py-3 text-sm font-medium rounded-xl border border-blue-200/50 dark:border-blue-500/30 shadow-sm">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold">Dashboard</span>
                            <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></div>
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

                        <!-- Assign User -->
                        <a href="{{ route('tasks.assign') }}" class="text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-purple-500 group-hover:to-indigo-600 rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                                <svg class="text-gray-500 group-hover:text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            Assign User
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
            <!-- Welcome Section -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl mb-6 border border-white/20 dark:border-gray-700/20">
                <div class="relative p-8" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm"></div>
                    <div class="relative flex items-center justify-between text-white">
                        <div>
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 backdrop-blur-sm">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h1>
                                    <p class="text-blue-100 text-sm">Ready to manage your tasks and boost productivity?</p>
                                </div>
                            </div>

                        </div>
                        <div class="hidden lg:block">
                            <div class="relative float-animation">
                                <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm hover-lift">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center pulse-soft">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                @php
                    $user = Auth::user();
                    // Get both created and assigned tasks
                    $allUserTasksQuery = App\Models\Task::where('user_id', $user->id)
                        ->orWhere('assigned_user_id', $user->id);
                    $totalTasks = $allUserTasksQuery->count();
                    $pendingTasks = $allUserTasksQuery->where('status', 'Pending')->count();
                    $inProgressTasks = $allUserTasksQuery->where('status', 'In Progress')->count();
                    $completedTasks = $allUserTasksQuery->where('status', 'Completed')->count();
                @endphp
                
                <div class="group relative overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20 backdrop-blur-xl hover:scale-105 transition-all duration-300" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-2xl text-white bg-white/20 backdrop-blur-sm">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/80">Total Tasks</p>
                                    <p class="text-3xl font-bold text-white">{{ $totalTasks }}</p>
                                </div>
                            </div>
                            <div class="text-white/30">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20 backdrop-blur-xl hover:scale-105 transition-all duration-300" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-600/10 to-orange-600/10"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-2xl text-white bg-white/20 backdrop-blur-sm">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white">Pending</p>
                                    <p class="text-3xl font-bold text-white">{{ $pendingTasks }}</p>
                                </div>
                            </div>
                            <div class="text-white/30">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress Tasks Card -->
                <div class="group relative overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20 backdrop-blur-xl hover:scale-105 transition-all duration-300" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-600/10 to-orange-600/10"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-2xl text-white bg-white/20 backdrop-blur-sm">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/80">In Progress</p>
                                    <p class="text-3xl font-bold text-white">{{ $inProgressTasks }}</p>
                                </div>
                            </div>
                            <div class="text-white/30">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20 backdrop-blur-xl hover:scale-105 transition-all duration-300" style="background: linear-gradient(135deg, #2dd4bf 0%, #06b6d4 100%);">
                    <div class="absolute inset-0 bg-gradient-to-r from-teal-600/10 to-cyan-600/10"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-14 w-14 rounded-2xl text-white bg-white/20 backdrop-blur-sm">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white/80">Completed</p>
                                    <p class="text-3xl font-bold text-white">{{ $completedTasks }}</p>
                                </div>
                            </div>
                            <div class="text-white/30">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks & Quick Actions -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            Quick Actions
                        </h3>
                        <div class="space-y-4">
                            <a href="{{ route('tasks.create') }}" class="flex items-center p-3 text-white rounded-lg transition duration-200 hover:scale-105 transform" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold">Create New Task</h4>
                                    <p class="text-xs text-blue-100">Add a new task to your list</p>
                                </div>
                            </a>

                            <a href="{{ route('tasks.index') }}" class="flex items-center p-3 text-white rounded-lg transition duration-200 hover:scale-105 transform" style="background: linear-gradient(135deg, #8360c3 0%, #2ebf91 100%);">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold">View All Tasks</h4>
                                    <p class="text-xs text-purple-100">Manage and organize tasks</p>
                                </div>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 text-white rounded-lg transition duration-200 hover:scale-105 transform" style="background: linear-gradient(135deg, #FF4B2B 0%, #FF416C 100%);">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold">Profile Settings</h4>
                                    <p class="text-xs text-red-100">Update your information</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Tasks -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Recent Tasks
                        </h3>
                        <div class="space-y-3">
                            @php
                                // Get both created and assigned tasks for recent tasks
                                $currentUser = Auth::user();
                                $recentTasks = App\Models\Task::where('user_id', $currentUser->id)
                                    ->orWhere('assigned_user_id', $currentUser->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($recentTasks as $task)
                                <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl hover:bg-white/70 dark:hover:bg-gray-600/70 transition-all duration-300 border border-white/20 dark:border-gray-600/20 hover:scale-[1.02]">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3 {{ $task->status === 'Completed' ? 'bg-green-500' : ($task->status === 'In Progress' ? 'bg-yellow-500' : 'bg-gray-400') }}"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($task->title, 30) }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-md {{ $task->status === 'Completed' ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-white' : ($task->status === 'In Progress' ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' : 'bg-gradient-to-r from-orange-400 to-red-500 text-white') }}">
                                        {{ $task->status }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No tasks yet</p>
                                    <a href="{{ route('tasks.create') }}" class="mt-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-500">
                                        Create your first task
                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endforelse
                        </div>
                        
                        @if($recentTasks->count() > 0)
                            <div class="mt-4">
                                <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                    View all tasks
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- All Tasks Management -->
            <div class="mt-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2m0 0V9a2 2 0 012-2h14a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7v2a2 2 0 002 2h6a2 2 0 002-2V7"></path>
                                </svg>
                            </div>
                            All Tasks Management
                        </h3>
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Task
                        </a>
                    </div>

                    @php
                        // Get both created and assigned tasks for all tasks management
                        $currentUser = Auth::user();
                        $allUserTasks = App\Models\Task::where('user_id', $currentUser->id)
                            ->orWhere('assigned_user_id', $currentUser->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp

                    @if($allUserTasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($allUserTasks as $task)
                                <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/20 p-4 hover:bg-white/70 dark:hover:bg-gray-600/70 transition-all duration-300 hover:scale-[1.01] hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1">
                                            <div class="w-4 h-4 rounded-full mr-4 {{ $task->status === 'Completed' ? 'bg-green-500' : ($task->status === 'In Progress' ? 'bg-yellow-500' : 'bg-blue-500') }}"></div>
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $task->title }}</h4>
                                                        @if($task->description)
                                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ Str::limit($task->description, 100) }}</p>
                                                        @endif
                                                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <!-- Status Badge -->
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-md {{ $task->status === 'Completed' ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-white' : ($task->status === 'In Progress' ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white' : 'bg-gradient-to-r from-orange-400 to-red-500 text-white') }}">
                                                {{ $task->status }}
                                            </span>
                                            <!-- Edit Button (only for task creator) -->
                                            @can('update', $task)
                                            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            @endcan

                                            <!-- Mark as Completed Button (only show for non-completed tasks) -->
                                            @if($task->status !== 'Completed')
                                            <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline-block" onsubmit="return confirm('Mark this task as completed?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    ✓ Complete
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <!-- Delete Button (only for task creator) -->
                                            @can('delete', $task)
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2m0 0V9a2 2 0 012-2h14a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7v2a2 2 0 002 2h6a2 2 0 002-2V7"></path>
                                </svg>
                                View Detailed Task Management
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No tasks yet!</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first task to boost your productivity.</p>
                            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Your First Task
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Task Progress Chart -->
            <div class="mt-6 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        Task Progress Overview
                    </h3>
                    <div class="space-y-4">
                        @php
                            $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            $inProgressPercentage = $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0;
                        @endphp
                        
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                                <span>Overall Progress</span>
                                <span>{{ $completedTasks }}/{{ $totalTasks }} tasks ({{ $progressPercentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200/50 dark:bg-gray-700/50 rounded-full h-3 backdrop-blur-sm border border-white/20">
                                <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500 shadow-lg" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="p-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/20 hover:scale-105 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingTasks }}</p>
                                <p class="text-sm text-white font-medium">Pending</p>
                            </div>
                            <div class="p-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/20 hover:scale-105 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $inProgressTasks }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">In Progress</p>
                            </div>
                            <div class="p-4 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl border border-white/20 dark:border-gray-600/20 hover:scale-105 transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-teal-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $completedTasks }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
