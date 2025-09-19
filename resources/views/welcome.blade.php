<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-100 text-gray-900">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-blue-900 text-white shadow">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold">Task Manager</h1>
                <nav class="space-x-4 flex items-center">
                    <a href="{{ url('/') }}" 
                    class="px-3 py-2 rounded-lg transition-colors {{ request()->is('/') ? 'bg-blue-700' : 'hover:bg-blue-600' }}">
                        Home
                    </a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                            class="px-3 py-2 rounded-lg transition-colors {{ request()->is('dashboard') ? 'bg-blue-700' : 'hover:bg-blue-600' }}">
                                Dashboard
                            </a>
                            <a href="{{ url('/dashboard-frontend') }}" 
                            class="px-3 py-2 rounded-lg transition-colors {{ request()->is('dashboard-frontend') ? 'bg-blue-700' : 'hover:bg-blue-600' }}">
                                Frontend Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded-lg hover:bg-blue-600">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                            class="px-3 py-2 rounded-lg transition-colors {{ request()->is('login') ? 'bg-blue-700' : 'hover:bg-blue-600' }}">
                                Login
                            </a>
                            <a href="{{ route('register') }}" 
                            class="px-3 py-2 rounded-lg transition-colors {{ request()->is('register') ? 'bg-blue-700' : 'hover:bg-blue-600' }}">
                                Register
                            </a>
                        @endauth
                    @endif
                </nav>

        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 h-[500px] flex items-center justify-center">
        <div class="text-center text-white max-w-2xl">
            <h2 class="text-5xl font-bold mb-4">Organize Your Work, Achieve More</h2>
            <p class="text-lg mb-6">Stay productive with Task Manager. Create, track, and complete tasks with ease.</p>
            <div class="space-x-4">
                @guest
                    <a href="{{ route('register') }}" class="px-5 py-3 bg-white text-blue-900 font-semibold rounded-xl hover:bg-gray-300">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="px-5 py-3 bg-white text-blue-900 font-semibold rounded-xl hover:bg-gray-300">
                        Login
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}" class="px-5 py-3 bg-white text-blue-900 font-semibold rounded-xl hover:bg-gray-300">
                        Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto py-16 px-6 grid md:grid-cols-3 gap-8 text-center">
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl font-bold mb-3">✅ Easy Task Management</h3>
            <p class="text-blue-900">Create, update, and track tasks with deadlines and status.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl font-bold mb-3">📊 Dashboard Overview</h3>
            <p class="text-blue-900">View tasks grouped by Pending, In Progress, and Completed.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl font-bold mb-3">🔒 Secure Authentication</h3>
            <p class="text-blue-900">Register and log in with Breeze authentication system.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white text-center py-6 mt-12">
        <p>&copy; {{ date('Y') }} Task Manager. All rights reserved.</p>
    </footer>

</body>
</html>
