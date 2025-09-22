<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Interactive Task Dashboard</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
            .animate-slide-in { animation: slideIn 0.3s ease-in-out; }
            @keyframes slideIn { 
                from { transform: translateY(-20px); opacity: 0; } 
                to { transform: translateY(0); opacity: 1; } 
            }
            #notifications { position: fixed; top: 20px; right: 20px; z-index: 1000; }
            #due-notifications { background: #fef2f2; padding: 10px; border: 1px solid #ef4444; border-radius: 8px; margin-bottom: 20px; }
        </style>
    </head>
    <body class="bg-gray-100">
<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="bg-blue-900 text-white flex flex-col transition-all duration-300"
            x-data="{ open: true }"
            :class="open ? 'w-64' : 'w-20'">
        <div class="flex flex-col items-center justify-center p-6 space-y-2" :class="!open && 'justify-center'">
            <div class="w-16 h-16">
                <img src="{{ asset('assets/images/task.png') }}" alt="Logo" class="w-full h-full rounded-full object-cover">
            </div>
            <span class="text-2xl font-bold whitespace-nowrap text-center flex-1">
                <span x-show="open">Task Manager</span>
                <span x-show="!open">TM</span>
            </span>
            <button @click="open = !open" 
                    class="focus:outline-none bg-blue-700 hover:bg-blue-600 text-white rounded-full px-2 py-1 transition">
                <span x-show="open" class="text-lg">⬅️</span>
                <span x-show="!open" class="text-lg">➡️</span>
            </button>
            <hr class="w-full border-t border-blue-100">
        </div>
        <nav class="flex flex-col px-1 py-4 space-y-2 text-left w-full">
            <a href="{{ url('/') }}" 
               class="flex items-center w-full px-4 py-2 rounded-lg transition {{ request()->is('/') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}"
               :class="!open && 'justify-center'">
                <i class="fa-solid fa-house text-xl"></i>
                <span x-show="open" class="mx-2 text-gray-400">|</span>
                <span x-show="open" class="truncate">Home</span>
            </a>
            <a href="{{ url('/dashboard') }}" 
               class="flex items-center w-full px-4 py-2 rounded-lg transition {{ request()->is('dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-700' }}"
               :class="!open && 'justify-center'">
                <i class="fa-solid fa-chart-line text-xl"></i>
                <span x-show="open" class="mx-2 text-gray-400">|</span>
                <span x-show="open" class="truncate">Dashboard</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                        :class="!open && 'justify-center'">
                    <i class="fa-solid fa-right-from-bracket text-xl"></i>
                    <span x-show="open" class="mx-2 text-gray-400">|</span>
                    <span x-show="open" class="truncate">Logout</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Dashboard Body -->
        <main class="p-6 overflow-y-auto">
            <div class="bg-blue-800 text-white shadow rounded-lg p-4 mb-6 flex justify-between items-center">
                <h2 class="text-xl font-semibold">Frontend-Dashboard</h2>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 focus:outline-none border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-800 hover:bg-gray-100 transition">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
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

            <!-- Due Soon/Overdue Notifications -->
            <div id="due-notifications" class="hidden">
                <div class="container mx-auto space-y-2">
                    <div id="due-notifications-list"></div>
                </div>
            </div>

            <!-- General Notifications -->
            <div id="notifications" class="mb-6"></div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('all')">
                    <span class="font-semibold">Total</span>
                    <span id="total-tasks" class="text-2xl mt-1">0</span>
                    <div class="w-full h-2 bg-blue-700 rounded mt-2"><div id="bar-total" class="h-2 bg-white rounded transition-all duration-500"></div></div>
                </div>
                <div class="bg-yellow-400 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('pending')">
                    <span class="font-semibold">Pending</span>
                    <span id="pending-tasks" class="text-2xl mt-1">0</span>
                    <div class="w-full h-2 bg-yellow-600 rounded mt-2"><div id="bar-pending" class="h-2 bg-white rounded transition-all duration-500"></div></div>
                </div>
                <div class="bg-indigo-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('in_progress')">
                    <span class="font-semibold">In Progress</span>
                    <span id="inprogress-tasks" class="text-2xl mt-1">0</span>
                    <div class="w-full h-2 bg-indigo-700 rounded mt-2"><div id="bar-inprogress" class="h-2 bg-white rounded transition-all duration-500"></div></div>
                </div>
                <div class="bg-green-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('completed')">
                    <span class="font-semibold">Completed</span>
                    <span id="completed-tasks" class="text-2xl mt-1">0</span>
                    <div class="w-full h-2 bg-green-700 rounded mt-2"><div id="bar-completed" class="h-2 bg-white rounded transition-all duration-500"></div></div>
                </div>
                <div class="bg-red-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('high')">
                    <span class="font-semibold">High Priority</span>
                    <span id="high-priority" class="text-2xl mt-1">0</span>
                    <div class="w-full h-2 bg-red-700 rounded mt-2"><div id="bar-high" class="h-2 bg-white rounded transition-all duration-500"></div></div>
                </div>
            </div>

            <!-- Search Bar and Filters -->
            <div class="bg-white shadow rounded-lg p-4 mb-6">
                <form id="searchForm" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" placeholder="Search by title or description..." class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <select id="statusFilter" class="w-full p-2 border rounded">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div>
                        <select id="priorityFilter" class="w-full p-2 border rounded">
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
                    <button type="button" id="resetFilters" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Reset</button>
                </form>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded shadow flex flex-col items-center">
                    <h2 class="text-2xl font-semibold mb-2">Task Status</h2>
                    <div class="w-full max-w-sm">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-4 rounded shadow flex flex-col items-center">
                    <h2 class="text-2xl font-semibold mb-2">Priority Count</h2>
                    <div class="w-full max-w-sm">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Kanban Board -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <h2 class="font-semibold mb-2">Pending</h2>
                    <div id="pending-column" class="bg-gray-200 p-2 rounded min-h-[200px] space-y-2"></div>
                </div>
                <div>
                    <h2 class="font-semibold mb-2">In Progress</h2>
                    <div id="inprogress-column" class="bg-gray-200 p-2 rounded min-h-[200px] space-y-2"></div>
                </div>
                <div>
                    <h2 class="font-semibold mb-2">Completed</h2>
                    <div id="completed-column" class="bg-gray-200 p-2 rounded min-h-[200px] space-y-2"></div>
                </div>
            </div>

            <!-- Button to Open Modal -->
            <button onclick="openTaskModal()" class="bg-green-500 text-white px-4 py-2 rounded mb-4 hover:bg-green-600">
                + Add Task
            </button>

            <!-- Task Modal -->
            <div id="taskModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
                    <h2 class="text-2xl font-bold mb-4" id="modalTitle">Add Task</h2>
                    <form id="taskForm" class="space-y-3">
                        <input type="hidden" id="taskId">
                        <div>
                            <input type="text" id="taskTitle" placeholder="Title" class="w-full p-2 border rounded" required>
                            <p class="text-red-500 text-sm hidden" id="taskTitleError">Title is required</p>
                        </div>
                        <div>
                            <input type="date" id="taskDue" class="w-full p-2 border rounded" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <p class="text-red-500 text-sm hidden" id="taskDueError">Due date must be after today</p>
                        </div>
                        <select id="taskStatus" class="w-full p-2 border rounded">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <select id="taskPriority" class="w-full p-2 border rounded">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                        <textarea id="taskDesc" class="w-full p-2 border rounded" placeholder="Description"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeTaskModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task Table -->
            <div class="bg-white p-4 rounded shadow mb-6 overflow-x-auto">
                <h2 class="text-xl font-semibold mb-2">All Tasks</h2>
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-2 text-left">Title</th>
                            <th class="p-2 text-left">Description</th>
                            <th class="p-2 text-left">Due Date</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Priority</th>
                            <th class="p-2 text-left">Assignees</th>
                            <th class="p-2 text-left">Created By</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="task-table"></tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Postpone Modal (Per Task) -->
<template id="postpone-modal-template">
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl w-96 p-6">
            <h2 class="text-lg font-bold mb-4">Postpone Task</h2>
            <form class="space-y-3 postpone-form">
                <div>
                    <label class="block text-sm">New Due Date</label>
                    <input type="date" name="new_due_date" class="w-full border rounded p-2" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
                <div>
                    <label class="block text-sm">Reason</label>
                    <textarea name="reason" class="w-full border rounded p-2" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="cancel-postpone px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-3 py-1 bg-orange-600 text-white rounded hover:bg-orange-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<!-- Comments Modal (Per Task) -->
<template id="comments-modal-template">
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
            <h2 class="text-lg font-bold mb-4 comments-title">Comments</h2>
            <div class="flex-1 overflow-y-auto space-y-2 mb-4 comments-list"></div>
            <form class="comment-form flex gap-2 mt-auto">
                <input type="text" name="body" placeholder="Write a comment..." class="border rounded-lg p-2 flex-1" required>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add</button>
            </form>
            <div class="flex justify-end mt-2">
                <button type="button" class="close-comments px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</template>

<!-- History Modal (Per Task) -->
<template id="history-modal-template">
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-96 max-h-[70vh] overflow-auto">
            <h2 class="text-lg font-bold mb-4">Postpone History</h2>
            <ul class="list-disc ml-6 mt-2 max-h-[50vh] overflow-y-auto pr-2 history-list"></ul>
            <div class="flex justify-end mt-4">
                <button type="button" class="close-history px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
</template>

<!-- Assign Members Modal -->
<template id="assign-members-modal-template">
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md">
            <h2 class="text-lg font-bold mb-4 assign-title">Assign Members</h2>
            <form class="assign-form space-y-3">
                <div>
                    <label class="block text-sm">Emails (comma-separated)</label>
                    <input type="text" name="emails" placeholder="user1@example.com,user2@example.com" class="w-full border rounded p-2" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="cancel-assign px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="assign-members-btn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Assign</button>
                    <button type="button" class="remove-members-btn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Remove</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
let allTasks = [];
let statusChart, priorityChart;
let notifiedTasks = new Set();

function showNotification(message, isError = false) {
    console.log('Showing notification:', message);
    const container = document.getElementById('notifications');
    const div = document.createElement('div');
    div.textContent = message;
    div.className = `px-4 py-2 rounded shadow-lg text-white ${isError ? 'bg-red-500' : 'bg-green-500'} animate-slide-in`;
    container.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

function showDueNotification(task) {
    const container = document.getElementById('due-notifications');
    const list = document.getElementById('due-notifications-list');
    const today = new Date();
    const dueDate = new Date(task.due_date + 'T23:59:59');
    const diffDays = (dueDate - today) / (1000 * 60 * 60 * 24);
    let message = '';
    let bgClass = '';

    if (diffDays < 0) {
        message = `⚠️ Task "${task.title}" is overdue (Due: ${task.due_date})`;
        bgClass = 'bg-red-100 text-red-800';
    } else if (diffDays <= 2) {
        message = `⚠️ Task "${task.title}" is due soon on ${task.due_date}`;
        bgClass = 'bg-yellow-100 text-yellow-800';
    } else {
        return;
    }

    if (!notifiedTasks.has(task.id)) {
        const div = document.createElement('div');
        div.id = `due-notification-${task.id}`;
        div.className = `px-4 py-2 rounded shadow mb-2 ${bgClass}`;
        div.textContent = message;
        list.appendChild(div);
        container.classList.remove('hidden');
        notifiedTasks.add(task.id);
    }
}

function clearDueNotifications() {
    const list = document.getElementById('due-notifications-list');
    list.innerHTML = '';
    document.getElementById('due-notifications').classList.add('hidden');
    notifiedTasks.clear();
}

function openTaskModal(task = null) {
    const modal = document.getElementById('taskModal');
    modal.classList.remove('hidden');
    if (task) {
        document.getElementById('modalTitle').textContent = 'Edit Task';
        document.getElementById('taskId').value = task.id;
        document.getElementById('taskTitle').value = task.title;
        document.getElementById('taskDue').value = task.due_date;
        document.getElementById('taskStatus').value = task.status;
        document.getElementById('taskPriority').value = task.priority;
        document.getElementById('taskDesc').value = task.description ?? '';
    } else {
        document.getElementById('modalTitle').textContent = 'Add Task';
        document.getElementById('taskForm').reset();
        document.getElementById('taskId').value = '';
    }
}

function closeTaskModal() {
    document.getElementById('taskModal').classList.add('hidden');
}

document.getElementById('taskForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const taskTitle = document.getElementById('taskTitle');
    const taskDue = document.getElementById('taskDue');
    const taskTitleError = document.getElementById('taskTitleError');
    const taskDueError = document.getElementById('taskDueError');

    taskTitleError.classList.add('hidden');
    taskDueError.classList.add('hidden');

    let hasError = false;
    if (!taskTitle.value) {
        taskTitleError.classList.remove('hidden');
        hasError = true;
    }
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const selectedDate = new Date(taskDue.value);
    if (!taskDue.value || selectedDate <= today) {
        taskDueError.classList.remove('hidden');
        hasError = true;
    }
    if (hasError) return;

    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

    const id = document.getElementById('taskId').value;
    const payload = {
        title: taskTitle.value,
        due_date: taskDue.value,
        status: document.getElementById('taskStatus').value,
        priority: document.getElementById('taskPriority').value,
        description: document.getElementById('taskDesc').value
    };

    try {
        let url = '/api/tasks';
        let method = 'POST';
        if (id) {
            url = `/api/tasks/${id}`;
            method = 'PUT';
        }

        console.log('Sending request:', { url, method, payload });
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include',
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        console.log('Response:', data);
        if (!res.ok) throw new Error(data.message || 'Failed to save task');

        showNotification(data.message || 'Task saved successfully');
        closeTaskModal();
        clearDueNotifications();
        fetchDashboardData();
        fetchTasks();
    } catch (err) {
        console.error('Error:', err);
        showNotification(err.message, true);
    }
});

function editTask(id) {
    const task = allTasks.find(t => t.id === id);
    openTaskModal(task);
}

async function markCompleted(id) {
    const confirmResult = await Swal.fire({
        title: 'Mark as Completed?',
        text: "This will update the task status to completed.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, mark completed'
    });

    if (!confirmResult.isConfirmed) return;

    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    const res = await fetch(`/api/tasks/${id}/complete`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include'
    });

    const data = await res.json();
    console.log('Complete response:', data);
    if (!res.ok) {
        return showNotification(data.message || 'Failed to mark completed', true);
    }

    showNotification('Task marked as completed');
    clearDueNotifications();
    fetchDashboardData();
    fetchTasks();
}

async function postponeTask(id) {
    const modalTemplate = document.getElementById('postpone-modal-template');
    const modal = modalTemplate.content.cloneNode(true).children[0];
    modal.id = `postpone-${id}`;
    document.body.appendChild(modal);
    modal.classList.remove('hidden');

    const form = modal.querySelector('.postpone-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const newDueDate = form.querySelector('[name="new_due_date"]').value;
        const reason = form.querySelector('[name="reason"]').value;

        if (!newDueDate || !reason) {
            showNotification('New due date and reason are required', true);
            return;
        }

        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch(`/api/tasks/${id}/postpone`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include',
            body: JSON.stringify({ new_due_date: newDueDate, reason })
        });

        const data = await res.json();
        console.log('Postpone response:', data);
        if (!res.ok) {
            return showNotification(data.message || 'Failed to postpone task', true);
        }

        showNotification('Task postponed');
        modal.classList.add('hidden');
        modal.remove();
        clearDueNotifications();
        fetchDashboardData();
        fetchTasks();
    });

    modal.querySelector('.cancel-postpone').addEventListener('click', () => {
        console.log('Cancel postpone clicked for task ' + id);
        modal.classList.add('hidden');
        modal.remove();
    });
}

async function showComments(id) {
    const task = allTasks.find(t => t.id === id);
    if (!task) return;

    const modalTemplate = document.getElementById('comments-modal-template');
    const modal = modalTemplate.content.cloneNode(true).children[0];
    modal.id = `comments-${id}`;
    document.body.appendChild(modal);
    modal.classList.remove('hidden');

    const commentsList = modal.querySelector('.comments-list');
    const updateCommentsList = () => {
        commentsList.innerHTML = task.comments.length
            ? task.comments.map(c => `
                <div class="p-2 border rounded bg-gray-50">
                    <p class="text-sm text-gray-800">${c.body}</p>
                    <span class="text-xs text-gray-500">– ${c.user?.name || 'Unknown'} (${new Date(c.created_at).toLocaleString()})</span>
                </div>
            `).join('')
            : '<p class="text-gray-500 text-sm">No comments yet.</p>';
    };
    updateCommentsList();

    const form = modal.querySelector('.comment-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const body = form.querySelector('[name="body"]').value;
        if (!body) {
            showNotification('Comment cannot be empty', true);
            return;
        }

        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch(`/api/tasks/${id}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include',
            body: JSON.stringify({ body })
        });

        const data = await res.json();
        console.log('Comment response:', data);
        if (!res.ok) {
            return showNotification(data.message || 'Failed to add comment', true);
        }

        showNotification('Comment added successfully');
        form.reset();
        await fetchDashboardData();
        const updatedTask = allTasks.find(t => t.id === id);
        if (updatedTask) {
            task.comments = updatedTask.comments;
            updateCommentsList();
        }
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.remove();
        }, 1000);
    });

    modal.querySelector('.close-comments').addEventListener('click', () => {
        console.log('Close comments clicked for task ' + id);
        modal.classList.add('hidden');
        modal.remove();
    });
}

async function showHistory(id) {
    const task = allTasks.find(t => t.id === id);
    if (!task) return;

    const modalTemplate = document.getElementById('history-modal-template');
    const modal = modalTemplate.content.cloneNode(true).children[0];
    modal.id = `history-${id}`;
    document.body.appendChild(modal);
    modal.classList.remove('hidden');

    const historyList = modal.querySelector('.history-list');
    historyList.innerHTML = task.postpones.length
        ? task.postpones.map(p => `
            <li class="mb-2">
                ${new Date(p.created_at).toLocaleString()} – 
                <strong>${p.user?.name || 'Unknown'}</strong> postponed to 
                <span class="text-blue-600">${p.new_due_date}</span> 
                (Reason: ${p.reason})
            </li>
        `).join('')
        : '<li class="text-gray-500">No postpones recorded</li>';

    modal.querySelector('.close-history').addEventListener('click', () => {
        console.log('Close history clicked for task ' + id);
        modal.classList.add('hidden');
        modal.remove();
    });
}

async function assignMembers(id) {
    const task = allTasks.find(t => t.id === id);
    if (!task) return;

    const modalTemplate = document.getElementById('assign-members-modal-template');
    const modal = modalTemplate.content.cloneNode(true).children[0];
    modal.id = `assign-members-${id}`;
    document.body.appendChild(modal);
    modal.classList.remove('hidden');

    modal.querySelector('.assign-title').textContent = `Assign Members to: ${task.title}`;

    const form = modal.querySelector('.assign-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const emails = form.querySelector('[name="emails"]').value;
        if (!emails) {
            showNotification('Please enter at least one email', true);
            return;
        }

        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch(`/api/tasks/${id}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include',
            body: JSON.stringify({ emails })
        });

        const data = await res.json();
        console.log('Assign members response:', data);
        if (!res.ok) {
            let errorMessage = data.message || 'Failed to assign members';
            if (data.errors) {
                if (data.errors.invalid_emails) {
                    errorMessage += `: Invalid emails - ${data.errors.invalid_emails.join(', ')}`;
                }
                if (data.errors.not_registered) {
                    errorMessage += `: Not registered - ${data.errors.not_registered.join(', ')}`;
                }
                if (data.errors.already_assigned) {
                    errorMessage += `: Already assigned - ${data.errors.already_assigned.join(', ')}`;
                }
            }
            return showNotification(errorMessage, true);
        }

        showNotification(data.message || 'Members assigned successfully');
        form.reset();
        clearDueNotifications();
        await fetchDashboardData();
        fetchTasks();
        modal.classList.add('hidden');
        modal.remove();
    });

    modal.querySelector('.remove-members-btn').addEventListener('click', async () => {
        const emails = form.querySelector('[name="emails"]').value;
        if (!emails) {
            showNotification('Please enter at least one email to remove', true);
            return;
        }

        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch(`/api/tasks/${id}/remove-members`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include',
            body: JSON.stringify({ emails })
        });

        const data = await res.json();
        console.log('Remove members response:', data);
        if (!res.ok) {
            return showNotification(data.message || 'Failed to remove members', true);
        }

        let message = 'Members removed successfully';
        if (data.removed.length) {
            message += `: ${data.removed.join(', ')}`;
        }
        if (data.not_assigned.length) {
            message += `. Not assigned: ${data.not_assigned.join(', ')}`;
        }
        if (data.not_found.length) {
            message += `. Not found: ${data.not_found.join(', ')}`;
        }
        showNotification(message);
        form.reset();
        clearDueNotifications();
        await fetchDashboardData();
        fetchTasks();
        modal.classList.add('hidden');
        modal.remove();
    });

    modal.querySelector('.cancel-assign').addEventListener('click', () => {
        console.log('Cancel assign clicked for task ' + id);
        modal.classList.add('hidden');
        modal.remove();
    });
}

async function deleteTask(id) {
    const confirmResult = await Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    });

    if (!confirmResult.isConfirmed) return;

    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    console.log('Deleting task:', id);
    const res = await fetch(`/api/tasks/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include'
    });

    const data = await res.json();
    console.log('Delete response:', data);
    if (!res.ok) {
        return showNotification(data.message || 'Failed to delete', true);
    }

    showNotification('Task deleted');
    clearDueNotifications();
    fetchDashboardData();
    fetchTasks();
}

function renderTasks(tasks) {
    const tableBody = document.getElementById('task-table');
    const pendingCol = document.getElementById('pending-column');
    const inprogressCol = document.getElementById('inprogress-column');
    const completedCol = document.getElementById('completed-column');

    tableBody.innerHTML = '';
    pendingCol.innerHTML = '';
    inprogressCol.innerHTML = '';
    completedCol.innerHTML = '';

    tasks.forEach(task => {

        let priorityClass = '';
            if (task.priority === 'high') {
                priorityClass = 'bg-red-50 ';
            } else if (task.priority === 'medium') {
                priorityClass = 'bg-blue-50';
            } else if (task.priority === 'low') {
                priorityClass = 'bg-white-50 ';
            }
            
        const tr = document.createElement('tr');
        tr.dataset.id = task.id;
        tr.className = `border-b ${priorityClass}`;        
        tr.innerHTML = `
            <td class="p-2">${task.title}</td>
            <td class="p-2">${task.description ?? '-'}</td>
            <td class="p-2">${task.due_date ?? '-'}</td>
            <td class="p-2"><span class="px-2 py-1 rounded-full text-sm ${
                task.status == 'pending' ? 'bg-blue-100 text-blue-700' :
                task.status == 'in_progress' ? 'bg-purple-100 text-purple-700' :
                'bg-teal-100 text-teal-700'
            }">${task.status.replace('_', ' ')}</span></td>
            <td class="p-2"><span class="px-2 py-1 rounded-full text-sm ${
                task.priority == 'high' ? 'bg-red-100 text-red-700' :
                task.priority == 'medium' ? 'bg-yellow-100 text-yellow-700' :
                'bg-green-100 text-green-700'
            }">${task.priority}</span></td>
            <td class="p-2">${task.assignees?.map(a => a.name).join(', ') || '-'}</td>
            <td class="p-2">${task.user_id === {{ Auth::id() }} ? 'Own' : task.creator?.name || 'Unknown'}</td>
            <td class="p-2 flex gap-2 flex-wrap">
                ${task.user_id === {{ Auth::id() }} ? `
                    <button class="edit-btn px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</button>
                    <button class="delete-btn px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                    ${task.status !== 'completed' ? `
                        <button class="complete-btn px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">Mark Completed</button>
                        <button class="postpone-btn px-2 py-1 bg-orange-500 text-white rounded hover:bg-orange-600">Postpone</button>
                    ` : ''}
                    <button class="assign-btn px-2 py-1 bg-purple-500 text-white rounded hover:bg-purple-600">Assign Members</button>
                ` : ''}
                <button class="comments-btn px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Comments</button>
                <button class="history-btn px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">Postpone History</button>
            </td>
        `;
        tableBody.appendChild(tr);


        const card = document.createElement('div');
        card.className = `bg-white p-2 rounded shadow ${priorityClass}`;
        card.innerHTML = `<strong>${task.title}</strong><br><small>Due: ${task.due_date ?? '-'}</small>`;
        if (task.status === 'pending') pendingCol.appendChild(card);
        else if (task.status === 'in_progress') inprogressCol.appendChild(card);
        else if (task.status === 'completed') completedCol.appendChild(card);

        if (task.due_date && task.status !== 'completed') {
            showDueNotification(task);
        }
    });

    updateCharts(tasks);
}

function filterTasks(filter) {
    document.getElementById('statusFilter').value = filter === 'high' ? '' : filter;
    document.getElementById('priorityFilter').value = filter === 'high' ? 'high' : '';
    document.getElementById('searchInput').value = '';
    fetchTasks();
}

async function fetchTasks() {
    try {
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const priority = document.getElementById('priorityFilter').value;
        const params = new URLSearchParams({ search, status, priority }).toString();
        const res = await fetch(`/api/tasks?${params}`, {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            credentials: 'include'
        });
        if (!res.ok) throw new Error('Failed to fetch tasks');

        const data = await res.json();
        console.log('Tasks response:', data);
        allTasks = data.data ?? data;
        renderTasks(allTasks);
    } catch (err) {
        console.error('Fetch tasks error:', err);
        showNotification(err.message, true);
    }
}

document.getElementById('searchForm').addEventListener('submit', (e) => {
    e.preventDefault();
    fetchTasks();
});

document.getElementById('resetFilters').addEventListener('click', () => {
    document.getElementById('searchForm').reset();
    fetchTasks();
});

document.getElementById('task-table').addEventListener('click', (e) => {
    const row = e.target.closest('tr');
    if (!row) return;

    const taskId = parseInt(row.dataset.id);
    if (e.target.classList.contains('edit-btn')) {
        editTask(taskId);
    }
    if (e.target.classList.contains('delete-btn')) {
        deleteTask(taskId);
    }
    if (e.target.classList.contains('complete-btn')) {
        markCompleted(taskId);
    }
    if (e.target.classList.contains('postpone-btn')) {
        postponeTask(taskId);
    }
    if (e.target.classList.contains('comments-btn')) {
        showComments(taskId);
    }
    if (e.target.classList.contains('history-btn')) {
        showHistory(taskId);
    }
    if (e.target.classList.contains('assign-btn')) {
        assignMembers(taskId);
    }
});

function updateCharts(tasks) {
    const statusCounts = { pending: 0, in_progress: 0, completed: 0 };
    const priorityCounts = { low: 0, medium: 0, high: 0 };

    tasks.forEach(t => {
        statusCounts[t.status] = (statusCounts[t.status] || 0) + 1;
        priorityCounts[t.priority] = (priorityCounts[t.priority] || 0) + 1;
    });

    if (statusChart) statusChart.destroy();
    if (priorityChart) priorityChart.destroy();

    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],
            datasets: [{ 
                data: [statusCounts.pending, statusCounts.in_progress, statusCounts.completed],
                backgroundColor: ['#facc15', '#6366f1', '#22c55e']
            }]
        }
    });

    const ctxPriority = document.getElementById('priorityChart').getContext('2d');
    priorityChart = new Chart(ctxPriority, {
        type: 'bar',
        data: {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{ 
                data: [priorityCounts.low, priorityCounts.medium, priorityCounts.high],
                backgroundColor: ['#64748b', '#8b5cf6', '#ef4444']
            }]
        },
        options: { indexAxis: 'y' }
    });
}

async function fetchDashboardData() {
    try {
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch('/api/dashboard-data', {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            credentials: 'include'
        });
        if (!res.ok) throw new Error('Unauthenticated');

        const data = await res.json();
        allTasks = data.tasks.data ?? data.tasks;

        const total = data.stats.total || 0;
        document.getElementById('total-tasks').textContent = total;
        document.getElementById('pending-tasks').textContent = data.stats.pending || 0;
        document.getElementById('inprogress-tasks').textContent = data.stats.in_progress || 0;
        document.getElementById('completed-tasks').textContent = data.stats.completed || 0;
        document.getElementById('high-priority').textContent = data.stats.high_priority || 0;

        document.getElementById('bar-total').style.width = total > 0 ? '100%' : '0%';
        document.getElementById('bar-pending').style.width = total > 0 ? `${(data.stats.pending / total * 100).toFixed(2)}%` : '0%';
        document.getElementById('bar-inprogress').style.width = total > 0 ? `${(data.stats.in_progress / total * 100).toFixed(2)}%` : '0%';
        document.getElementById('bar-completed').style.width = total > 0 ? `${(data.stats.completed / total * 100).toFixed(2)}%` : '0%';
        document.getElementById('bar-high').style.width = total > 0 ? `${(data.stats.high_priority / total * 100).toFixed(2)}%` : '0%';

        // Update charts with all tasks
        updateCharts(allTasks);
    } catch (err) {
        console.error('Fetch dashboard error:', err);
        showNotification(err.message, true);
    }
}

async function checkAuth() {
    try {
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch('/api/user', {
            headers: { 'Accept': 'application/json' },
            credentials: 'include'
        });
        const data = await res.json();
        console.log('Authenticated user:', data);
    } catch (err) {
        console.error('Auth check failed:', err);
    }
}

async function logout() {
    try {
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
        const res = await fetch('/api/logout', {
            method: 'POST',
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include'
        });
        if (!res.ok) throw new Error('Logout failed');
        showNotification('Logged out successfully');
        window.location.href = '/login';
    } catch (err) {
        console.error('Logout error:', err);
        showNotification(err.message, true);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('taskModal').classList.add('hidden');
    fetchDashboardData();
    fetchTasks();
    setInterval(fetchDashboardData, 30000);
    checkAuth();
});
</script>
</body>
</html>