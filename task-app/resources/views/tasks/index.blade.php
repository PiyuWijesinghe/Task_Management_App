<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Task Management</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Organize your tasks by status and stay productive</p>
                        </div>
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Task
                        </a>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Pending Tasks -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-lg border border-yellow-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full mr-3"></div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Pending</h2>
                                <span class="ml-auto bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 px-2 py-1 rounded-full text-sm font-medium">{{ count($pending) }}</span>
                            </div>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($pending as $task)
                                    <div class="bg-white dark:bg-gray-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 border-l-4 border-yellow-400 group">
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-blue-600 transition duration-200">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($task->description, 60) ?: 'No description' }}</p>
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d') : 'No due date' }}
                                                </div>
                                                <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-full transition duration-200">
                                                    View
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No pending tasks</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- In Progress Tasks -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-lg border border-blue-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">In Progress</h2>
                                <span class="ml-auto bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-sm font-medium">{{ count($inProgress) }}</span>
                            </div>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($inProgress as $task)
                                    <div class="bg-white dark:bg-gray-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 border-l-4 border-blue-400 group">
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-blue-600 transition duration-200">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ Str::limit($task->description, 60) ?: 'No description' }}</p>
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d') : 'No due date' }}
                                                </div>
                                                <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-full transition duration-200">
                                                    View
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No tasks in progress</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Completed Tasks -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-lg border border-green-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Completed</h2>
                                <span class="ml-auto bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 px-2 py-1 rounded-full text-sm font-medium">{{ count($completed) }}</span>
                            </div>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($completed as $task)
                                    <div class="bg-white dark:bg-gray-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 border-l-4 border-green-400 group opacity-75">
                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2 group-hover:text-green-600 transition duration-200 line-through">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-through">{{ Str::limit($task->description, 60) ?: 'No description' }}</p>
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Completed
                                                </div>
                                                <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-full transition duration-200">
                                                    View
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No completed tasks yet</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
