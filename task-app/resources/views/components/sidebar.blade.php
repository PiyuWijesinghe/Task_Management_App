<!-- Sidebar Component -->
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
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-blue-700 dark:text-blue-200 border border-blue-200/50 dark:border-blue-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm shadow-sm">
                    <div class="w-8 h-8 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-purple-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-blue-500 group-hover:to-purple-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}">Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <div class="ml-auto w-2 h-2 bg-blue-500 rounded-full"></div>
                    @endif
                </a>

                <!-- Task Management -->
                <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.index') ? 'bg-gradient-to-r from-purple-500/20 to-indigo-500/20 text-purple-700 dark:text-purple-200 border border-purple-200/50 dark:border-purple-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <div class="w-8 h-8 {{ request()->routeIs('tasks.index') ? 'bg-gradient-to-r from-purple-500 to-indigo-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-purple-500 group-hover:to-indigo-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->routeIs('tasks.index') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    Task Management
                </a>

                <!-- Create Task -->
                <a href="{{ route('tasks.create') }}" class="{{ request()->routeIs('tasks.create') ? 'bg-gradient-to-r from-green-500/20 to-teal-500/20 text-green-700 dark:text-green-200 border border-green-200/50 dark:border-green-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <div class="w-8 h-8 {{ request()->routeIs('tasks.create') ? 'bg-gradient-to-r from-green-500 to-teal-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-green-500 group-hover:to-teal-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->routeIs('tasks.create') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    Create Task
                </a>

                <!-- Pending Tasks -->
                <a href="{{ route('tasks.index', ['status' => 'Pending']) }}" class="{{ request()->get('status') === 'Pending' ? 'bg-gradient-to-r from-orange-500/20 to-red-500/20 text-orange-700 dark:text-orange-200 border border-orange-200/50 dark:border-orange-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <div class="w-8 h-8 {{ request()->get('status') === 'Pending' ? 'bg-gradient-to-r from-orange-500 to-red-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-orange-500 group-hover:to-red-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->get('status') === 'Pending' ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Pending Tasks
                </a>

                <!-- In Progress Tasks -->
                <a href="{{ route('tasks.index', ['status' => 'In Progress']) }}" class="{{ request()->get('status') === 'In Progress' ? 'bg-gradient-to-r from-yellow-500/20 to-orange-500/20 text-yellow-700 dark:text-yellow-200 border border-yellow-200/50 dark:border-yellow-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <div class="w-8 h-8 {{ request()->get('status') === 'In Progress' ? 'bg-gradient-to-r from-yellow-500 to-orange-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-yellow-500 group-hover:to-orange-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->get('status') === 'In Progress' ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    In Progress Tasks
                </a>

                <!-- Completed Tasks -->
                <a href="{{ route('tasks.index', ['status' => 'Completed']) }}" class="{{ request()->get('status') === 'Completed' ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-700 dark:text-emerald-200 border border-emerald-200/50 dark:border-emerald-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <div class="w-8 h-8 {{ request()->get('status') === 'Completed' ? 'bg-gradient-to-r from-emerald-500 to-cyan-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-emerald-500 group-hover:to-cyan-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->get('status') === 'Completed' ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-indigo-500/20 to-purple-500/20 text-indigo-700 dark:text-indigo-200 border border-indigo-200/50 dark:border-indigo-500/30' : 'text-gray-700 dark:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
                    <div class="w-8 h-8 {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-indigo-500 to-purple-600' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gradient-to-r group-hover:from-indigo-500 group-hover:to-purple-600' }} rounded-lg flex items-center justify-center mr-3 transition-all duration-200">
                        <svg class="{{ request()->routeIs('profile.edit') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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