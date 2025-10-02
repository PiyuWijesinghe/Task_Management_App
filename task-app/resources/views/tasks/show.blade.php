<x-app-layout>
    <div class="flex h-screen b                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Priority</h3>
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $task->getPriorityBadgeClasses() }}">
                                    {!! $task->getPriorityIcon() !!}
                                    {{ $task->getPriorityText() }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Created By</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $task->user->name }}</p>nsparent">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto bg-transparent">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500/90 to-teal-500/90 backdrop-blur-xl text-white p-6 m-6 rounded-2xl shadow-2xl border border-white/20">
                <h2 class="font-bold text-2xl mb-2">Task Details</h2>
                <p class="text-green-100">View and manage your task information</p>
            </div>

            <div class="p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl overflow-hidden shadow-2xl rounded-2xl border border-white/20 dark:border-gray-700/20">
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
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Created By</h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $task->user->name }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Assigned To</h3>
                                <div class="text-gray-600 dark:text-gray-400">
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
                                        $isCurrentUserAssigned = $allAssignees->contains('id', auth()->id());
                                    @endphp
                                    
                                    @if($allAssignees->count() > 0)
                                        <div class="flex flex-wrap gap-2 items-center">
                                            @foreach($allAssignees as $assignee)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                    {{ $assignee->name }}
                                                    @if($assignee->id === auth()->id() && $task->user_id !== auth()->id())
                                                        <span class="ml-1 text-xs">(You - View Only)</span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span>Not assigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-4 pt-8 border-t border-gray-200 dark:border-gray-600">
                            @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Edit Task') }}
                            </a>
                            @endcan

                            @if($task->canBePostponedBy(auth()->user()) && $task->status !== 'Completed')
                            <button onclick="togglePostponeForm()" 
                                    class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded transition-colors duration-200">
                                Postpone
                            </button>
                            @endif
                            
                            @can('delete', $task)
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
                            @endcan
                            
                            <a href="{{ route('tasks.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('Back to Tasks') }}
                            </a>
                        </div>

                        <!-- Postpone Form (Hidden by default) -->
                        @if($task->canBePostponedBy(auth()->user()) && $task->status !== 'Completed')
                        <div id="postponeForm" class="hidden pt-8 border-t border-gray-200 dark:border-gray-600">
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 p-6 rounded-xl border border-purple-200 dark:border-purple-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Postpone Task
                                </h3>

                                @if(session('success'))
                                    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
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

                                @if(session('error'))
                                    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('tasks.postpone', $task) }}" method="POST" class="space-y-4">
                                    @csrf
                                    
                                    <div>
                                        <label for="new_due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            New Due Date *
                                        </label>
                                        <input type="date" 
                                               id="new_due_date" 
                                               name="new_due_date" 
                                               value="{{ old('new_due_date') }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               required
                                               class="block w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                        @error('new_due_date')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Reason (Optional)
                                        </label>
                                        <textarea id="reason" 
                                                  name="reason" 
                                                  rows="3" 
                                                  maxlength="500"
                                                  placeholder="Briefly explain why you're postponing this task..."
                                                  class="block w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <button type="submit" 
                                                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded transition-colors duration-200">
                                            Postpone
                                        </button>
                                        <button type="button" 
                                                onclick="togglePostponeForm()" 
                                                class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium rounded transition-colors duration-200">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        <!-- Postponement History -->
                        @if($task->postponements->count() > 0)
                        <div class="pt-8 border-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Postponement History
                            </h3>
                            
                            <div class="bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden shadow-sm">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Old Due Date
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    New Due Date
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Reason
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Postponed By
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach($task->postponements as $postponement)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $postponement->old_due_date ? $postponement->old_due_date->format('M d, Y') : 'No date set' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                                        {{ $postponement->new_due_date->format('M d, Y') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    <div class="max-w-xs">
                                                        {{ $postponement->reason ?: 'No reason provided' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $postponement->postponedBy->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $postponement->created_at->format('M d, Y g:i A') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

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
    </div>

    <script>
        function togglePostponeForm() {
            const form = document.getElementById('postponeForm');
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                // Scroll to form
                form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                form.classList.add('hidden');
            }
        }

        // Auto-show form if there are validation errors
        @if($errors->has('new_due_date') || $errors->has('reason'))
            document.addEventListener('DOMContentLoaded', function() {
                togglePostponeForm();
            });
        @endif
    </script>
</x-app-layout>
