
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