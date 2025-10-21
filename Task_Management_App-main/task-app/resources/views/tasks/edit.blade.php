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

                            <!-- Assign to Users (multiple) -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Users</label>

                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                                    <!-- Keep for myself checkbox -->
                                    <label class="flex items-center p-3 rounded-lg border-2 border-transparent hover:border-rose-200 dark:hover:border-rose-600 cursor-pointer">
                                        <input type="checkbox" id="keep_self" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-blue-300 dark:border-blue-600 rounded">
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-sm font-semibold text-blue-900 dark:text-blue-200">👤 Keep task for myself</span>
                                                <span class="text-xs px-2 py-1 bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-200 rounded-full font-medium">Personal</span>
                                            </div>
                                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-medium">Assign this task to yourself only</div>
                                        </div>
                                    </label>

                                    <!-- Divider -->
                                    <div class="flex items-center py-2">
                                        <div class="flex-1 border-t border-gray-200 dark:border-gray-600"></div>
                                        <span class="px-3 text-xs text-gray-500 dark:text-gray-400 font-medium">OR ASSIGN TO OTHERS</span>
                                        <div class="flex-1 border-t border-gray-200 dark:border-gray-600"></div>
                                    </div>

                                    <!-- Other users -->
                                    @php $currentAssigned = $task->assignedUsers->pluck('id')->toArray(); @endphp
                                    @foreach($users as $user)
                                        @if($user->id != auth()->id())
                                            <label class="user-item other-user flex items-center p-4 hover:bg-white/60 dark:hover:bg-gray-800/60 rounded-xl transition-all duration-300 cursor-pointer border-2 border-transparent hover:border-rose-200 dark:hover:border-rose-600 shadow-sm hover:shadow-md" 
                                                   data-user-name="{{ strtolower($user->name) }}" 
                                                   data-user-email="{{ strtolower($user->email) }}"
                                                   data-user-username="{{ strtolower($user->username ?? '') }}">
                                                <input type="checkbox" 
                                                       name="assigned_users[]" 
                                                       value="{{ $user->id }}"
                                                       id="user_{{ $user->id }}"
                                                       {{ in_array($user->id, old('assigned_users', $currentAssigned)) ? 'checked' : '' }}
                                                       class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded user-checkbox transition-all duration-200">
                                                <div class="ml-4 flex-1">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">👤 {{ $user->username ?? $user->name }}</span>
                                                        @if($user->name && $user->username && $user->username !== $user->name)
                                                            <span class="text-xs px-2 py-1 bg-rose-100 dark:bg-rose-800 text-rose-600 dark:text-rose-300 rounded-full font-medium">{{ $user->name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach

                                    <!-- Select/Clear actions -->
                                    <div class="px-4 py-3 bg-white/20 dark:bg-gray-800/20 border-t border-white/20 dark:border-gray-600/20 flex space-x-2 rounded-b-xl">
                                        <button type="button" id="select-all-btn" class="flex-1 px-3 py-2 text-xs bg-rose-200 hover:bg-rose-300 dark:bg-rose-700 dark:hover:bg-rose-600 text-rose-800 dark:text-rose-200 rounded-lg transition-colors font-medium">✓ Select All</button>
                                        <button type="button" id="clear-all-btn" class="flex-1 px-3 py-2 text-xs bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition-colors font-medium">✕ Clear All</button>
                                    </div>
                                </div>

                                @error('assigned_users')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @error('assigned_users.*')
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
