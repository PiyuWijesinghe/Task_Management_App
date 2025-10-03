<x-app-layout>
    <div class="flex h-screen bg-transparent">
        <!-- Sidebar -->
        <x-sidebar />

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
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Task</h1>
                    </div>
                </div>
            </div>

            <!-- Content Container -->
            <div class="max-w-4xl mx-auto py-8 px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <!-- Title -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Task Title
                                </label>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $task->title) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Enter task title..."
                                       required>
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Description
                                </label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                                          placeholder="Describe your task...">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="space-y-2">
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Due Date
                                </label>
                                <input type="date" 
                                       id="due_date" 
                                       name="due_date" 
                                       value="{{ old('due_date', $task->due_date) }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('due_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Status
                                </label>
                                <select id="status" 
                                        name="status"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="Pending" {{ old('status', $task->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ old('status', $task->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status', $task->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div class="space-y-2">
                                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Priority
                                </label>
                                <select id="priority" 
                                        name="priority"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="Low" {{ old('priority', $task->priority) == 'Low' ? 'selected' : '' }}>🟢 Low Priority</option>
                                    <option value="Medium" {{ old('priority', $task->priority) == 'Medium' ? 'selected' : '' }}>🟡 Medium Priority</option>
                                    <option value="High" {{ old('priority', $task->priority) == 'High' ? 'selected' : '' }}>🔴 High Priority</option>
                                </select>
                                @error('priority')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assign to User -->
                            <div class="space-y-2">
                                <label for="assigned_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Assign to User (Optional)
                                </label>
                                
                                <!-- Custom Searchable Dropdown -->
                                <div class="relative">
                                    <!-- Hidden Input for Form Submission -->
                                    <input type="hidden" id="assigned_user_id" name="assigned_user_id" value="{{ old('assigned_user_id', $task->assigned_user_id) }}">
                                    
                                    <!-- Search Input -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               id="userSearchInput" 
                                               class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                                               placeholder="Search users or keep for myself..."
                                               autocomplete="off"
                                               onclick="toggleUserDropdown()"
                                               onkeyup="filterEditUsers(this)"
                                               value="{{ old('assigned_user_id', $task->assigned_user_id) ? ($users->where('id', old('assigned_user_id', $task->assigned_user_id))->first()->name ?? 'Keep task for myself') : 'Keep task for myself' }}">
                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                            <button type="button" 
                                                    id="clearUserBtn" 
                                                    onclick="clearSelectedUser()"
                                                    class="mr-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 {{ old('assigned_user_id', $task->assigned_user_id) ? '' : 'hidden' }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <button type="button" 
                                                    onclick="toggleUserDropdown()" 
                                                    class="pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                                <svg class="h-5 w-5 transform transition-transform duration-200" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Dropdown List -->
                                    <div id="userDropdown" class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden custom-edit-scrollbar">
                                        <!-- Keep for myself option -->
                                        <div class="user-option px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-200 dark:border-gray-600 transition-colors duration-200" 
                                             onclick="selectUser('', 'Keep task for myself')"
                                             data-user-name="keep task for myself" 
                                             data-user-username="myself">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    ME
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Keep task for myself</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">No assignment needed</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- User options -->
                                        @foreach($users as $user)
                                            <div class="user-option px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors duration-200" 
                                                 onclick="selectUser('{{ $user->id }}', '{{ $user->name }}')"
                                                 data-user-name="{{ strtolower($user->name) }}" 
                                                 data-user-username="{{ strtolower($user->username ?? '') }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">👤 {{ $user->username ?? $user->name }}</div>
                                                        @if($user->name && $user->username && $user->username !== $user->name)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->name }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <!-- No Results Message -->
                                        <div id="noEditResults" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 hidden">
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <div class="text-sm">No users found matching your search</div>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('assigned_user_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center justify-end space-x-4 pt-6">
                                <a href="{{ route('tasks.index') }}" 
                                   class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    Update Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isDropdownOpen = false;

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            if (isDropdownOpen) {
                dropdown.classList.add('hidden');
                arrow.classList.remove('rotate-180');
                isDropdownOpen = false;
            } else {
                dropdown.classList.remove('hidden');
                arrow.classList.add('rotate-180');
                isDropdownOpen = true;
                
                // Show all options when opening
                showAllUserOptions();
            }
        }

        function selectUser(userId, userName) {
            const hiddenInput = document.getElementById('assigned_user_id');
            const searchInput = document.getElementById('userSearchInput');
            const clearBtn = document.getElementById('clearUserBtn');
            
            // Update hidden input value
            hiddenInput.value = userId;
            
            // Update search input display
            searchInput.value = userName;
            
            // Show/hide clear button
            if (userId) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
            
            // Close dropdown
            toggleUserDropdown();
        }

        function clearSelectedUser() {
            selectUser('', 'Keep task for myself');
        }

        function filterEditUsers(input) {
            const searchTerm = input.value.toLowerCase().trim();
            const dropdown = document.getElementById('userDropdown');
            const userOptions = dropdown.querySelectorAll('.user-option');
            const noResults = document.getElementById('noEditResults');
            
            let visibleCount = 0;
            
            // Open dropdown if not already open
            if (!isDropdownOpen) {
                toggleUserDropdown();
            }
            
            // Filter user options
            userOptions.forEach(option => {
                const userName = option.dataset.userName || '';
                const userUsername = option.dataset.userUsername || '';
                
                if (userName.includes(searchTerm) || userUsername.includes(searchTerm)) {
                    option.style.display = 'block';
                    visibleCount++;
                    
                    // Highlight matching text
                    highlightEditSearchTerm(option, searchTerm);
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0 && searchTerm.length > 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        function highlightEditSearchTerm(option, searchTerm) {
            if (searchTerm.length === 0) return;
            
            const textElements = option.querySelectorAll('.text-sm');
            textElements.forEach(element => {
                const originalText = element.textContent;
                if (originalText) {
                    const regex = new RegExp(`(${searchTerm})`, 'gi');
                    const highlightedText = originalText.replace(regex, '<mark class="bg-yellow-200 dark:bg-yellow-800 px-1 rounded">$1</mark>');
                    if (highlightedText !== originalText) {
                        element.innerHTML = highlightedText;
                    }
                }
            });
        }

        function showAllUserOptions() {
            const dropdown = document.getElementById('userDropdown');
            const userOptions = dropdown.querySelectorAll('.user-option');
            const noResults = document.getElementById('noEditResults');
            
            // Show all options
            userOptions.forEach(option => {
                option.style.display = 'block';
                
                // Remove highlights
                const textElements = option.querySelectorAll('.text-sm');
                textElements.forEach(element => {
                    element.innerHTML = element.textContent;
                });
            });
            
            // Hide no results
            noResults.classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const searchInput = document.getElementById('userSearchInput');
            
            if (!dropdown.contains(event.target) && !searchInput.contains(event.target)) {
                if (isDropdownOpen) {
                    toggleUserDropdown();
                }
            }
        });

        // Keyboard navigation
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('userSearchInput');
            
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (isDropdownOpen) {
                        toggleUserDropdown();
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    
                    // Select first visible option
                    const dropdown = document.getElementById('userDropdown');
                    const visibleOptions = Array.from(dropdown.querySelectorAll('.user-option'))
                        .filter(option => option.style.display !== 'none');
                    
                    if (visibleOptions.length > 0) {
                        visibleOptions[0].click();
                    }
                }
            });
        });
    </script>

    <style>
        /* Custom Scrollbar for Edit User Dropdown */
        .custom-edit-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #3b82f6 #f1f5f9;
        }

        .custom-edit-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-edit-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 12px;
            margin: 6px;
            border: 1px solid #e2e8f0;
        }

        .custom-edit-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
            border-radius: 12px;
            border: 2px solid #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        }

        .custom-edit-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2563eb, #7c3aed, #db2777);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            border-color: #e2e8f0;
        }

        .custom-edit-scrollbar::-webkit-scrollbar-thumb:active {
            background: linear-gradient(135deg, #1d4ed8, #6d28d9, #be185d);
            transform: scale(0.95);
        }

        /* Dark mode scrollbar */
        .dark .custom-edit-scrollbar {
            scrollbar-color: #6366f1 #374151;
        }

        .dark .custom-edit-scrollbar::-webkit-scrollbar-track {
            background: #374151;
            border-color: #4b5563;
        }

        .dark .custom-edit-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #f472b6);
            border-color: #374151;
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
        }

        .dark .custom-edit-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed, #ec4899);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.5);
            border-color: #4b5563;
        }

        .dark .custom-edit-scrollbar::-webkit-scrollbar-thumb:active {
            background: linear-gradient(135deg, #4338ca, #6d28d9, #be185d);
        }

        /* Enhanced dropdown animations */
        #userDropdown {
            animation: fadeInDown 0.2s ease-out;
            transform-origin: top;
        }

        #userDropdown.hidden {
            animation: fadeOutUp 0.15s ease-in;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
        }

        /* User option hover effects */
        .user-option {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .user-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .user-option:hover::before {
            left: 100%;
        }

        .user-option:hover {
            transform: translateX(4px);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05));
        }

        .dark .user-option:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        }
    </style>
</x-app-layout>
