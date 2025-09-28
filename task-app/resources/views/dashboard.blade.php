<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-1000 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="overflow-hidden shadow-xl sm:rounded-lg mb-8" style="background: linear-gradient(135deg, #6e72db 0%, #51d8d8 100%);">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-blue-100">Ready to manage your tasks and boost productivity?</p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @php
                    $user = Auth::user();
                    $totalTasks = $user->tasks()->count();
                    $pendingTasks = $user->tasks()->where('status', 'Pending')->count();
                    $completedTasks = $user->tasks()->where('status', 'Completed')->count();
                @endphp
                
                <div class="overflow-hidden shadow-lg rounded-lg" style="background: linear-gradient(135deg, #6f84e2 0%, #ba9cd8 100%);">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white opacity-90">Total Tasks</p>
                                <p class="text-2xl font-semibold text-white">{{ $totalTasks }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden shadow-lg rounded-lg" style="background: linear-gradient(135deg, #9179d2 0%, #605df8 100%);">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md text-white" style="background: linear-gradient(135deg, #FF4B2B 0%, #FF416C 100%);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white opacity-90">Pending</p>
                                <p class="text-2xl font-semibold text-white">{{ $pendingTasks }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden shadow-lg rounded-lg" style="background: linear-gradient(135deg, #69c8e3 0%, #64c57b 100%);">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md text-white" style="background: linear-gradient(135deg, #2ebf91 0%, #8360c3 100%);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-white opacity-90">Completed</p>
                                <p class="text-2xl font-semibold text-white">{{ $completedTasks }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-500 dark:text-white mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('tasks.create') }}" class="group relative text-white p-6 rounded-lg transition duration-300 transform hover:scale-105" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);" onmouseover="this.style.background='linear-gradient(135deg, #00f2fe 0%, #4facfe 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-semibold">Create Task</h4>
                                    <p class="text-blue-400">Add a new task</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('tasks.index') }}" class="group relative text-white p-6 rounded-lg transition duration-300 transform hover:scale-105" style="background: linear-gradient(135deg, #8360c3 0%, #2ebf91 100%);" onmouseover="this.style.background='linear-gradient(135deg, #2ebf91 0%, #8360c3 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #8360c3 0%, #2ebf91 100%)'">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-semibold">View All Tasks</h4>
                                    <p class="text-purple-100">Manage your tasks</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="group relative text-white p-6 rounded-lg transition duration-300 transform hover:scale-105" style="background: linear-gradient(135deg, #c7798b 0%, #f39685 100%);" onmouseover="this.style.background='linear-gradient(135deg, #FF4B2B 0%, #FF416C 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%)'">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-semibold">Profile</h4>
                                    <p class="text-green-100">Edit your profile</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
