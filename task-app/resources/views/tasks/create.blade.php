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
                        <a href="{{ route('tasks.create') }}" class="bg-gradient-to-r from-green-500/20 to-teal-500/20 text-green-700 dark:text-green-200 group flex items-center px-4 py-3 text-sm font-medium rounded-xl border border-green-200/50 dark:border-green-500/30 shadow-sm">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="text-white h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="font-semibold">Create Task</span>
                            <div class="ml-auto w-2 h-2 bg-green-500 rounded-full"></div>
                        </a>

                        <!-- Assign User -->
                        <a href="{{ route('tasks.assign') }}" class="text-gray-700 dark:text-black-200 hover:bg-white/50 dark:hover:bg-gray-700/50 hover:scale-105 group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 backdrop-blur-sm">
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
            <!-- Header with Back Button -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-sm border-b border-white/20 dark:border-gray-700/20">
                <div class="max-w-4xl mx-auto px-4 py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('tasks.index') }}" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Tasks
                        </a>
                        <div class="text-gray-300 dark:text-gray-600">|</div>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create New Task</h1>
                    </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto py-8 px-4">
            <!-- Back to Dashboard -->
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create New Task</h1>
                
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- Title -->
                            <div class="space-y-2">
                                <label for="title" class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Task Title
                                </label>
                                <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus 
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-600/20 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-200"
                                    placeholder="Enter a descriptive title for your task...">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description" class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="4" 
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-600/20 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-all duration-200 resize-none"
                                    placeholder="Describe your task in detail...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="space-y-2">
                                <label for="due_date" class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Due Date
                                </label>
                                <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}"
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-600/20 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-500/50 transition-all duration-200"
                                    placeholder="Select a due date...">
                                @error('due_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                    <div class="w-4 h-4 mr-2 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    Status
                                </label>
                                <select id="status" name="status" 
                                    class="block w-full px-4 py-3 bg-emerald-50 dark:bg-emerald-900/30 backdrop-blur-sm border-2 border-emerald-300 dark:border-emerald-600 rounded-xl text-gray-900 dark:text-red focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 hover:shadow-xl hover:bg-emerald-100 dark:hover:bg-emerald-800/40 hover:scale-[1.02] appearance-none bg-no-repeat bg-right pr-10" style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 4 5%22><path fill=%22%23667eea%22 d=%22M2 0L0 2h4zm0 5L0 3h4z%22/></svg>'); background-size: 12px; background-position: calc(100% - 12px) center;">
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }} class="bg-red-50 text-gray-900">🔴 Pending</option>
                                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }} class="bg-yellow-50 text-gray-900">🟡 In Progress</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }} class="bg-green-50 text-gray-900">🟢 Completed</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="space-y-2">
                                <label for="priority" class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                    <div class="w-4 h-4 mr-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    Priority Level
                                </label>
                                <select id="priority" name="priority" 
                                    class="block w-full px-4 py-3 bg-orange-50 dark:bg-orange-900/30 backdrop-blur-sm border-2 border-orange-300 dark:border-orange-600 rounded-xl text-gray-900 dark:text-red-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 hover:shadow-xl hover:bg-orange-100 dark:hover:bg-orange-800/40 hover:scale-[1.02] appearance-none bg-no-repeat bg-right pr-10" style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 4 5%22><path fill=%22%23f97316%22 d=%22M2 0L0 2h4zm0 5L0 3h4z%22/></svg>'); background-size: 12px; background-position: calc(100% - 12px) center;">
                                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }} class="bg-red-50 text-gray-900">🔥 High Priority</option>
                                    <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }} class="bg-yellow-50 text-gray-900">⚡ Medium Priority</option>
                                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }} class="bg-green-50 text-gray-900">🟢 Low Priority</option>
                                </select>
                                @error('priority')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assign to Users -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                    <div class="w-4 h-4 mr-2 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    Assign to Users (Optional)
                                </label>
                                
                                <div class="bg-gradient-to-r from-rose-100 via-pink-100 to-violet-100 dark:from-rose-800/50 dark:via-pink-800/50 dark:to-violet-800/50 backdrop-blur-sm border-2 border-rose-400 dark:border-rose-500/60 rounded-xl transition-all duration-300 hover:shadow-xl hover:from-rose-200 hover:via-pink-200 hover:to-violet-200 dark:hover:from-rose-700/60 dark:hover:via-pink-700/60 dark:hover:to-violet-700/60">
                                    
                                    <!-- Dropdown header/trigger -->
                                    <button type="button" id="dropdown-trigger" class="w-full px-4 py-3 flex items-center justify-between hover:bg-white/10 dark:hover:bg-gray-800/10 rounded-t-xl transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">👥 Select Users to Assign</span>
                                            <span id="selected-count" class="ml-2 px-2 py-1 text-xs bg-rose-200 dark:bg-rose-700 text-rose-800 dark:text-rose-200 rounded-full hidden">0 selected</span>
                                        </div>
                                        <svg id="dropdown-arrow" class="w-5 h-5 text-gray-600 dark:text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown content -->
                                    <div id="user-dropdown-content" class="hidden border-t border-white/20 dark:border-gray-600/20">
                                        @if($users->count() > 0)
                                            <!-- Keep for myself option -->
                                            <div class="px-4 py-2 border-b border-white/10 dark:border-gray-600/10">
                                                <label class="flex items-center p-2 hover:bg-white/30 dark:hover:bg-gray-800/30 rounded-lg transition-colors cursor-pointer">
                                                    <input type="checkbox" 
                                                           id="keep_self" 
                                                           class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 dark:border-gray-600 rounded">
                                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">👤 Keep task for myself</span>
                                                </label>
                                            </div>
                                            
                                            <!-- Users list with scrolling -->
                                            <div class="px-4 py-2">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 px-2">Or assign to team members:</p>
                                                <div class="max-h-48 overflow-y-auto scrollbar-thin scrollbar-thumb-rose-300 dark:scrollbar-thumb-rose-600 scrollbar-track-transparent hover:scrollbar-thumb-rose-400 dark:hover:scrollbar-thumb-rose-500 space-y-1">
                                                    @foreach($users as $user)
                                                        @php
                                                            $oldAssignedUsers = old('assigned_users', []);
                                                        @endphp
                                                        <label class="flex items-center p-2 hover:bg-white/30 dark:hover:bg-gray-800/30 rounded-lg transition-colors cursor-pointer">
                                                            <input type="checkbox" 
                                                                   name="assigned_users[]" 
                                                                   value="{{ $user->id }}"
                                                                   id="user_{{ $user->id }}"
                                                                   {{ in_array($user->id, $oldAssignedUsers) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 dark:border-gray-600 rounded user-checkbox">
                                                            <div class="ml-3 flex-1">
                                                                <span class="text-sm font-medium text-gray-900 dark:text-white">🎯 {{ $user->name }}</span>
                                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Action buttons -->
                                            <div class="px-4 py-3 bg-white/20 dark:bg-gray-800/20 border-t border-white/20 dark:border-gray-600/20 flex space-x-2 rounded-b-xl">
                                                <button type="button" id="select-all-btn" class="flex-1 px-3 py-2 text-xs bg-rose-200 hover:bg-rose-300 dark:bg-rose-700 dark:hover:bg-rose-600 text-rose-800 dark:text-rose-200 rounded-lg transition-colors font-medium">
                                                    ✓ Select All
                                                </button>
                                                <button type="button" id="clear-all-btn" class="flex-1 px-3 py-2 text-xs bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors font-medium">
                                                    ✕ Clear All
                                                </button>
                                            </div>
                                        @else
                                            <div class="px-4 py-6">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">No other users available to assign</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @error('assigned_users')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @error('assigned_users.*')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between pt-6">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-purple-700 focus:from-blue-600 focus:to-purple-700 active:from-blue-700 active:to-purple-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownTrigger = document.getElementById('dropdown-trigger');
            const dropdownContent = document.getElementById('user-dropdown-content');
            const dropdownArrow = document.getElementById('dropdown-arrow');
            const selectedCount = document.getElementById('selected-count');
            const selfCheckbox = document.getElementById('keep_self');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const selectAllBtn = document.getElementById('select-all-btn');
            const clearAllBtn = document.getElementById('clear-all-btn');

            // Toggle dropdown function
            function toggleDropdown() {
                if (dropdownContent && dropdownArrow) {
                    if (dropdownContent.classList.contains('hidden')) {
                        dropdownContent.classList.remove('hidden');
                        dropdownArrow.classList.add('rotate-180');
                    } else {
                        dropdownContent.classList.add('hidden');
                        dropdownArrow.classList.remove('rotate-180');
                    }
                }
            }

            // Update selected count display
            function updateSelectedCount() {
                if (!selectedCount) return;
                
                const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
                let count = checkedUsers.length;
                
                if (selfCheckbox && selfCheckbox.checked) {
                    selectedCount.textContent = 'Assigned to me';
                    selectedCount.classList.remove('hidden');
                } else if (count > 0) {
                    selectedCount.textContent = `${count} user${count > 1 ? 's' : ''} selected`;
                    selectedCount.classList.remove('hidden');
                } else {
                    selectedCount.classList.add('hidden');
                }
            }

            // Handle self assignment toggle
            function handleSelfAssignment() {
                if (selfCheckbox && selfCheckbox.checked) {
                    userCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
                updateSelectedCount();
            }

            // Select all users
            function selectAllUsers() {
                if (selfCheckbox) selfCheckbox.checked = false;
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateSelectedCount();
            }

            // Clear all selections
            function clearAllUsers() {
                if (selfCheckbox) selfCheckbox.checked = false;
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedCount();
            }

            // Event listeners
            if (dropdownTrigger) {
                dropdownTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleDropdown();
                });
            }

            if (selfCheckbox) {
                selfCheckbox.addEventListener('change', handleSelfAssignment);
            }

            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked && selfCheckbox) {
                        selfCheckbox.checked = false;
                    }
                    updateSelectedCount();
                });
            });

            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', selectAllUsers);
            }

            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', clearAllUsers);
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdownContainer = dropdownTrigger ? dropdownTrigger.closest('.bg-gradient-to-r') : null;
                if (dropdownContainer && !dropdownContainer.contains(event.target)) {
                    if (dropdownContent) dropdownContent.classList.add('hidden');
                    if (dropdownArrow) dropdownArrow.classList.remove('rotate-180');
                }
            });

            // Initialize
            updateSelectedCount();
            if (dropdownContent) dropdownContent.classList.add('hidden');
        });
    </script>
</x-app-layout>
