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
        
            <!-- Card Heading -->

            <div class="bg-white/70 p-6 rounded-2xl shadow space-y-4 mb-6">
                <h2 class="text-2xl font-bold mb-2 px-4 py-2 rounded text-blue-800 w-fit">
                    Add Task
                </h2>

                <!-- Add Task Form -->
                <form method="POST" action="{{ route('tasks.store') }}" class="space-y-3">
                    @csrf
                    <div class="grid md:grid-cols-4 gap-3">
                        <input name="title" class="border rounded-lg p-2" placeholder="Title" required>
                        <input name="due_date" type="date" class="border rounded-lg p-2" min="{{ now()->toDateString() }}" required>
                        <select name="status" class="border rounded-lg p-2">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <!-- Priority -->
                        <select name="priority" class="border rounded-lg p-2">
                            <option value="high">High</option>
                            <option value="medium" selected>Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    
                    <textarea name="description" class="border rounded-lg p-2 w-full" rows="2" placeholder="Description (optional)"></textarea>
                    <!-- Submit Button -->
                    <button class="w-full rounded-xl px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 transition">
                        Add Task
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow space-y-4">
                <!-- Heading + Search Form Container -->
                <div class="flex items-center justify-between mb-4 bg-white p-4 rounded-lg shadow">
                    <h2 class="text-2xl font-bold px-4 py-2 text-blue-800 ">
                        My Tasks
                    </h2>

                    <!-- Search Form Right Aligned -->
                    <form method="GET" action="{{ route('dashboard') }}" class="flex gap-3 items-center">
                        <input type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search tasks..."
                            class="border rounded-lg p-2 w-56" />

                        <!-- Status Filter -->
                        <select name="status" class="border rounded-lg p-2 w-40">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>

                        <!-- Priority Filter -->
                        <select name="priority" class="border rounded-lg p-2 w-40">
                            <option value="" {{ request('priority') == '' ? 'selected' : '' }}>All Priority</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 whitespace-nowrap">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Tasks Table -->
                <div class="bg-white p-6 rounded-2xl shadow overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="p-3 border-b">Title</th>
                                <th class="p-3 border-b">Description</th>
                                <th class="p-3 border-b">Due Date</th>
                                <th class="p-3 border-b">Status</th>
                                <th class="p-3 border-b">Created by</th>
                                <th class="p-3 border-b">Priority</th>
                                <th class="p-3 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $task->title }}</td>
                                <td class="p-3 border-b">{{ $task->description }}</td>
                                <td class="p-3 border-b">{{ $task->due_date }}</td>
                                
                                <td class="p-3 border-b">
                                    <span class="px-2 py-1 rounded-full text-sm
                                        {{ $task->status == 'pending' ? 'bg-blue-100 text-blue-700' : ($task->status == 'in_progress' ? 'bg-purple-100 text-purple-700' : 'bg-teal-100 text-teal-700') }}">
                                        {{ ucfirst(str_replace('_',' ',$task->status)) }}
                                    </span>
                                </td>
                                <td class="p-3 border-b">
                                    @if($task->user_id === auth()->id())
                                        Own
                                    @else
                                        {{ $task->creator->name }}
                                    @endif
                                </td>
                                <td class="p-3 border-b">
                                    <span class="px-2 py-1 rounded-full text-sm
                                        {{ $task->priority == 'high' ? 'bg-red-100 text-red-700' : 
                                        ($task->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="p-3 border-b flex gap-2">
                                    @if($task->user_id === auth()->id())
                                        <a href="{{ route('tasks.edit',$task) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white flex items-center justify-center px-2 py-1 rounded">Edit</a>
                                        <!-- Delete -->
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="delete-task-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white x-2 py-4 rounded">Delete</button>
                                        </form>

                                        <!-- Mark Completed -->
                                        @if($task->status !== 'completed') 
                                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="complete-task-form">
                                        @csrf @method('POST')
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded">
                                            Mark Completed
                                        </button>
                                        </form>
                                        @endif
                                    @endif
                                         @if($task->status !== 'completed')
                                            <!-- Postpone button opens modal -->
                                            <button type="button" 
                                                    onclick="document.getElementById('postpone-{{ $task->id }}').classList.remove('hidden')" 
                                                    class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded">
                                                Postpone
                                            </button>

                                            <!-- Modal -->
                                            <div id="postpone-{{ $task->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                                <div class="bg-white p-6 rounded-xl w-96">
                                                    <h2 class="text-lg font-bold mb-4">Postpone Task</h2>
                                                    <form action="{{ route('tasks.postpone', $task) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label class="block text-sm">New Due Date</label>
                                                            <input type="date" name="new_due_date" class="w-full border rounded p-2" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="block text-sm">Reason</label>
                                                            <textarea name="reason" class="w-full border rounded p-2" required></textarea>
                                                        </div>
                                                        <div class="flex justify-end gap-2">
                                                            <button type="button" onclick="document.getElementById('postpone-{{ $task->id }}').classList.add('hidden')" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                                                            <button type="submit" class="px-3 py-1 bg-orange-600 text-white rounded">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    

                                    <!-- View History Button -->
                                    <button type="button"
                                            onclick="document.getElementById('history-{{ $task->id }}').classList.remove('hidden')"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded">
                                        View History
                                    </button>
                                    <!-- History Modal -->
                                    <div id="history-{{ $task->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                        <div class="bg-white p-6 rounded-xl w-110 max-h-[70vh] overflow-auto">
                                            <h2 class="text-lg font-bold mb-4">Postpone History</h2>
                                            
                                            <!-- Scrollable List -->
                                            <ul class="list-disc ml-6 mt-2 max-h-[50vh] overflow-y-auto pr-2">
                                                @forelse($task->postpones as $p)
                                                    <li class="mb-2">
                                                        {{ $p->created_at->format('d M Y H:i') }} – 
                                                        <strong>
                                                            @if($p->user_id === auth()->id())
                                                                I am
                                                            @else
                                                                {{ $p->user->name }}
                                                            @endif
                                                        </strong> postponed to 
                                                        <span class="text-blue-600">{{ $p->new_due_date }}</span> 
                                                        (Reason: {{ $p->reason }})
                                                    </li>
                                                @empty
                                                    <li class="text-gray-500">No postpones recorded</li>
                                                @endforelse
                                            </ul>

                                            <div class="flex justify-end mt-4">
                                                <button type="button" 
                                                        onclick="document.getElementById('history-{{ $task->id }}').classList.add('hidden')"
                                                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Comments Button -->
                                    <button type="button"
                                            onclick="document.getElementById('comments-{{ $task->id }}').classList.remove('hidden')"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                                        Comments
                                    </button>

                                    <!-- Comments Modal -->
                                    <div id="comments-{{ $task->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                        <div class="bg-white p-6 rounded-xl w-2/3 max-h-[80vh] flex flex-col">
                                            <h2 class="text-lg font-bold mb-4">Comments for: {{ $task->title }}</h2>

                                            <!-- Comments List -->
                                            <div class="flex-1 overflow-y-auto space-y-2 mb-4">
                                                @forelse($task->comments as $comment)
                                                    <div class="p-2 border rounded bg-gray-50">
                                                        <p class="text-sm text-gray-800">{{ $comment->body }}</p>
                                                        <span class="text-xs text-gray-500">
                                                            – {{ $comment->user->name }} ({{ $comment->created_at->diffForHumans() }})
                                                        </span>
                                                    </div>
                                                @empty
                                                    <p class="text-gray-500 text-sm">No comments yet.</p>
                                                @endforelse
                                            </div>

                                            <!-- Add Comment Form -->
                                            <form action="{{ route('comments.store', $task) }}" method="POST" class="flex gap-2 mt-auto">
                                                @csrf
                                                <input type="text" name="body" placeholder="Write a comment..."
                                                    class="border rounded-lg p-2 flex-1" required>
                                                <button type="submit" 
                                                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                                    Add
                                                </button>
                                            </form>

                                            <!-- Close Button -->
                                            <div class="flex justify-end mt-2">
                                                <button type="button" 
                                                        onclick="document.getElementById('comments-{{ $task->id }}').classList.add('hidden')"
                                                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
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
