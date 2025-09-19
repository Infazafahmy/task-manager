<x-app-layout>
    <div class="flex h-screen bg-blue-100">

        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white flex flex-col transition-all duration-300"
            x-data="{ open: true }"
            :class="open ? 'w-64' : 'w-20'">


            <!-- Sidebar Header -->
            <div class="flex flex-col items-center justify-center p-6 space-y-2 ":class="!open && 'justify-center'">

                <!-- Logo -->
                <div class="w-16 h-16">
                    <img src="{{ asset('assets/images/task.png') }}" alt="Logo" class="w-full h-full rounded-full object-cover">
                </div>
            
                <span class="text-2xl font-bold whitespace-nowrap text-center flex-1">
                    <span x-show="open">Task Manager</span>
                    <span x-show="!open">TM</span>
                </span>

                <!-- Collapse Button -->
                <button @click="open = !open" 
                        class="focus:outline-none bg-blue-700 hover:bg-blue-600 text-white rounded-full px-2 py-1 transition">
                    <span x-show="open" class="text-lg">⬅️</span>
                    <span x-show="!open" class="text-lg">➡️</span>
                </button>

                <!-- Horizontal Line -->
                <hr class="w-full border-t border-blue-100 ">


                


            <!-- Navigation Links -->
            <nav class="flex flex-col px-1 py-4 space-y-2 text-left space-y-2 w-full">

                <!-- Home -->
                <a href="{{ url('/') }}" 
                   class="flex items-center w-full px-4 py-2 rounded-lg transition
                   {{ request()->is('/') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}":class="!open && 'justify-center'">
                    <i class="fa-solid fa-house text-xl"></i>
                    <span x-show="open" class="mx-2 text-gray-400">|</span>
                    <span x-show="open" class="truncate">Home</span>
                </a>

                <!-- Dashboard -->
                <a href="{{ url('/dashboard') }}" 
                   class="flex items-center w-full px-4 py-2 rounded-lg transition
                   {{ request()->is('dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}":class="!open && 'justify-center'">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                    <span x-show="open" class="mx-2 text-gray-400">|</span>
                    <span x-show="open" class="truncate" >Dashboard</span>
                </a>

                <!-- Frontend Dashboard -->
                <a href="{{ url('/dashboard-frontend') }}" 
                class="flex items-center w-full px-4 py-2 rounded-lg transition {{ request()->is('dashboard-frontend') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}"
                :class="!open && 'justify-center'">
                    <i class="fa-solid fa-tachometer-alt text-xl"></i>
                    <span x-show="open" class="mx-2 text-gray-400">|</span>
                    <span x-show="open" class="truncate">Frontend Dashboard</span>
                </a>

                <!-- My Tasks -->
                <a href="{{ route('tasks.index') }}" 
                   class="flex items-center w-full px-4 py-2 rounded-lg transition
                   {{ request()->routeIs('tasks.index') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}":class="!open && 'justify-center'">
                    <i class="fa-solid fa-list-check text-xl"></i>
                    <span x-show="open" class="mx-2 text-gray-400">|</span>
                    <span x-show="open" class="truncate">My Tasks</span>
                </a>

                <!-- Assign User -->
                <a href="{{ route('tasks.assignPage') }}"
                class="flex items-center w-full px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('tasks.assignPage') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}":class="!open && 'justify-center'">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                    <span x-show="open" class="mx-2 text-gray-400">|</span>
                    <span x-show="open" class="truncate">Assign User</span>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-blue-700 transition":class="!open && 'justify-center'">
                        <i class="fa-solid fa-right-from-bracket text-xl"></i>
                        <span x-show="open" class="mx-2 text-gray-400">|</span>
                        <span x-show="open" class="truncate">Logout</span>
                    </button>
                </form>

            </nav>
               
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto">

            <!-- Header -->
            <div class="bg-blue-800 text-white shadow rounded-lg p-4 mb-6 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Dashboard</h2>

                <!-- Breeze Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                        class="flex items-center space-x-2 focus:outline-none 
                                border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-800 hover:bg-gray-100 transition">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>


                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
   
            <!-- Edit Task Form -->
            <div class="flex flex-col items-center p-6">
                <form method="POST" action="{{ route('tasks.update', $task) }}" 
                      class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-3xl space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Heading -->
                    <h2 class="text-2xl font-bold bg-blue-50 border-l-4 border-blue-600 px-4 py-2 rounded text-blue-800 shadow-sm text-center">
                        ✏️ Edit Task
                    </h2>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
                        <input id="title" name="title" 
                               class="mt-1 border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500" 
                               value="{{ $task->title }}" placeholder="Enter task title" required>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input id="due_date" name="due_date" type="date"
                               class="mt-1 border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500" 
                               value="{{ $task->due_date }}"
                               min="{{ now()->toDateString() }}" required>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" 
                                class="mt-1 border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $task->status=='pending'?'selected':'' }}>Pending</option>
                            <option value="in_progress" {{ $task->status=='in_progress'?'selected':'' }}>In Progress</option>
                            <option value="completed" {{ $task->status=='completed'?'selected':'' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select id="priority" name="priority" 
                                class="mt-1 border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500">
                            <option value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ old('priority', $task->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500"
                                  placeholder="Enter task description">{{ $task->description }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow">
                            💾 Update Task
                        </button>
                        <a href="{{ route('tasks.index') }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center transition shadow">
                            ← Back
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-app-layout>





