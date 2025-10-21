<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TaskPro') }} - Task Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen" style="background: linear-gradient(135deg, #9e89da 0%, #8174c2 25%, #5d43b1 50%, #6189c1 75%, #467ca8 100%);">
    <!-- Navigation -->
    <nav class="backdrop-blur-xl border-b border-white/10 shadow-lg sticky top-0 z-50" style="background: rgba(75, 88, 230, 0.8);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #90b9de 0%, #4a649f 50%, #493ad6 100%);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <span class="ml-3 text-2xl font-bold" style="background: linear-gradient(135deg, #13064e 0%, #06040b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">TaskPro</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    @guest
                        <!-- Guest users - show Login and Register buttons -->
                        <a href="/login" class="inline-flex items-center px-4 py-2 text-gray-700 text-sm font-medium rounded-lg hover:bg-white/50 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        <a href="/register" class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-lg" style="background: linear-gradient(135deg, #3e0cd6 0%, #3b0998 100%);">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Register
                        </a>
                    @else
                        <!-- Authenticated users - show Dashboard link -->
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg hover:scale-105 transition-all duration-200 shadow-lg" style="background: linear-gradient(135deg, #4A9F95 0%, #2F5F85 100%);">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                            </svg>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-gray-700 text-sm font-medium rounded-lg hover:bg-red-50/50 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main>
        <div class="relative overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-10 left-10 w-72 h-72 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse" style="background: #DDF4E7;"></div>
                <div class="absolute top-0 right-4 w-72 h-72 rounded-full mix-blend-multiply filter blur-xl opacity-25 animate-pulse animation-delay-2000" style="background: #786adf;"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-4000" style="background: #9879d1;"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <!-- Main Heading -->
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        <span style="background: linear-gradient(135deg, #0e153e 0%, #310874 50%, #2d2ae2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 900;">
                            TaskPro
                        </span>
                        <br>
                        <span class="text-3xl md:text-5xl text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Task Management System</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl max-w-3xl mx-auto mb-10" style="color: rgba(255, 255, 255, 0.95); text-shadow: 0 1px 5px rgba(0,0,0,0.2); font-weight: 500; line-height: 1.7;">
                        Streamline your productivity with our powerful task management system. 
                        Organize, track, and complete your tasks efficiently with an intuitive interface designed for success.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 text-white text-lg font-bold rounded-xl hover:scale-105 transition-all duration-300 shadow-2xl" style="background: linear-gradient(135deg, #4A9F95 0%, #2F5F85 100%); box-shadow: 0 10px 30px rgba(74, 159, 149, 0.5);">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                                </svg>
                                Go to Dashboard
                            </a>
                        @else
                            
                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-white/90 text-lg font-semibold rounded-xl hover:bg-white transition-all duration-300 shadow-xl backdrop-blur-sm border border-white/30" style="color: #010304; background: linear-gradient(135deg, #4e3bda 0%, #2F5F85 100%); box-shadow: 0 8px 25px rgba(47, 95, 133, 0.3);">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
                    <!-- Feature 1 -->
                    <div class="bg-blue-50 backdrop-blur-xl rounded-2xl p-8 shadow-2xl border border-white/40 hover:scale-105 transition-all duration-300" style="box-shadow: 0 15px 35px rgba(92, 154, 204, 0.15);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg" style="background: linear-gradient(135deg, #5b53d6 0%, #7944be 100%);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4" style="color: #1e3a52;">Task Organization</h3>
                        <p style="color: #a9bbca; font-weight: 500; line-height: 1.6;">
                            Create, organize, and manage your tasks with intuitive categories and priority levels. Stay on top of your workflow effortlessly.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-blue-50 backdrop-blur-xl rounded-2xl p-8 shadow-2xl border border-white/40 hover:scale-105 transition-all duration-300" style="box-shadow: 0 15px 35px rgba(47, 95, 133, 0.15);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg" style="background: linear-gradient(135deg, #7d6fe6 0%, #302f85 100%);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4" style="color: #1e3a52;">Progress Tracking</h3>
                        <p style="color: #9db6ca; font-weight: 500; line-height: 1.6;">
                            Monitor your progress with visual indicators and status updates. Track pending, in-progress, and completed tasks at a glance.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-blue-50 backdrop-blur-xl rounded-2xl p-8 shadow-2xl border border-white/40 hover:scale-105 transition-all duration-300" style="box-shadow: 0 15px 35px rgba(47, 95, 133, 0.15);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg" style="background: linear-gradient(135deg, #2F5F85 0%, #4317be 100%);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4" style="color: #1e3a52;">Analytics & Insights</h3>
                        <p style="color: #aabece; font-weight: 500; line-height: 1.6;">
                            Get detailed analytics on your productivity patterns and task completion rates. Make informed decisions to optimize your workflow.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="backdrop-blur-xl border-t border-white/20 mt-20" style="background: linear-gradient(135deg, rgba(15, 20, 25, 0.8) 0%, rgba(47, 95, 133, 0.3) 50%, rgba(30, 58, 82, 0.6) 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #4A9F95 0%, #6CBB6F 100%);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-xl font-bold" style="background: linear-gradient(135deg, #6CBB6F 0%, #4A9F95 100%); background-clip: text; -webkit-background-clip: text; color: transparent; font-weight: 800;">TaskPro</span>
                </div>
                <p style="color: #a0a0a0; font-weight: 600;">© {{ date('Y') }} TaskPro. All rights reserved.</p>
                <p class="text-sm mt-2" style="color: #6CBB6F; font-weight: 500;">Streamline your productivity with our powerful task management system.</p>
            </div>
        </div>
    </footer>

    <style>
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</body>
</html>