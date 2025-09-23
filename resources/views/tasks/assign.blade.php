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
                <h2 class="text-xl font-semibold">My Tasks</h2>

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
                    <div x-show="open" 
                         @click.away="open = false"
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

            <div class="bg-white p-6 rounded-2xl shadow mb-6">
                <h2 class="text-2xl font-bold text-blue-800 mb-4">Assign Members to Tasks</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        @error('assigned_emails')
                            <div id="error-alert" 
                                class="flex items-center justify-between bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <span>{{ $message }}</span>
                                <button onclick="document.getElementById('error-alert').remove()" 
                                        class="text-red-700 font-bold text-lg ml-4">
                                    &times;
                                </button>
                            </div>
                        @enderror
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="p-3 border-b">Task</th>
                                <th class="p-3 border-b">Description</th>
                                <th class="p-3 border-b">Members</th>
                                <th class="p-3 border-b">Add Member</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b font-semibold">{{ $task->title }}</td>
                                <td class="p-3 border-b">{{ $task->description }}</td>
                                <td class="p-3 border-b space-y-1">
                                    @forelse($task->assignees as $member)
                                        <div class="flex items-center justify-between bg-blue-50 px-2 py-1 rounded">
                                            <span>{{ $member->name }} ({{ $member->email }})</span>
                                            <form method="POST" action="{{ route('tasks.removeMember', [$task, $member]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 font-bold">&times;</button>
                                            </form>
                                        </div>
                                    @empty
                                        <span class="text-gray-400">No members assigned</span>
                                    @endforelse
                                </td>

                                <td class="p-3 border-b">
                                    <form method="POST" action="{{ route('tasks.assign', $task) }}" class="flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="assigned_emails" 
                                               placeholder="email1@example.com,email2@example.com"  
                                               class="border rounded px-2 py-1 w-full">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Add</button>
                                    </form>
                                </td>
                            </tr>                           
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>






                
                    
                
            
        </main>
    </div>
</x-app-layout>
<script>
    // Delete confirmation
    document.querySelectorAll('.delete-task-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // prevent default submit
            Swal.fire({
                title: 'Are you sure?',
                text: "This task will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', // Tailwind red-600
                cancelButtonColor: '#6b7280', // Tailwind gray-500
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit the form
                }
            });
        });
    });

    // Mark completed confirmation
    document.querySelectorAll('.complete-task-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Mark as Completed?',
                text: "This will update the task status to completed.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // Tailwind green-600
                cancelButtonColor: '#6b7280', // Tailwind gray-500
                confirmButtonText: 'Yes, mark as completed',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Optional: success alert for session message
    @if(session('message'))
    Swal.fire({
        title: 'Success!',
        text: "{{ session('message') }}",
        icon: 'success',
        confirmButtonColor: '#3b82f6', // Tailwind blue-600
        confirmButtonText: 'OK'
    });
    @endif
</script>
