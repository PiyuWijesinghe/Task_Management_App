<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-green-500 to-teal-500 text-white p-6 rounded-lg shadow-lg">
            <h2 class="font-bold text-2xl mb-2">Task Details</h2>
            <p class="text-green-100">View and manage your task information</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="space-y-6">
                        <!-- Task Title -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $task->title }}</h1>
                        </div>

                        <!-- Task Description -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</h3>
                            <p class="text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $task->description ?: 'No description provided.' }}
                            </p>
                        </div>

                        <!-- Task Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Due Date</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date set' }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</h3>
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                    @if($task->status == 'Pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($task->status == 'In Progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @endif">
                                    {{ $task->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-4 pt-8 border-t border-gray-200 dark:border-gray-600">
                            <a href="{{ route('tasks.edit', $task) }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Edit Task') }}
                            </a>
                            
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block" 
                                  onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    {{ __('Delete Task') }}
                                </button>
                            </form>
                            
                            <a href="{{ route('tasks.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('Back to Tasks') }}
                            </a>
                        </div>

                        <!-- Task Metadata -->
                        <div class="text-sm text-gray-500 dark:text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <p>Created: {{ $task->created_at->format('M d, Y \a\t g:i A') }}</p>
                            @if($task->updated_at != $task->created_at)
                                <p>Last updated: {{ $task->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
