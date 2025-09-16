<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Dashboard</title>
    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Task Dashboard</h1>

        <!-- Stats container -->
        <div id="stats" class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
            <!-- Dynamic content will go here -->
        </div>

        <!-- High priority tasks -->
        <div>
            <h2 class="text-2xl font-semibold mb-4">High Priority Tasks Due Soon</h2>
            <ul id="high-priority-tasks" class="space-y-2">
                <!-- Dynamic content will go here -->
            </ul>
        </div>
    </div>

    <!-- Script for fetching API data -->
    <script>
        async function fetchDashboard() {
            try {
                const res = await fetch('/api/dashboard'); // Laravel API endpoint
                const data = await res.json();

                // Stats section
                const statsDiv = document.getElementById('stats');
                statsDiv.innerHTML = `
                    <div class="bg-blue-500 text-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-2">Total Tasks</h3>
                        <p class="text-3xl font-bold">${data.total}</p>
                    </div>
                    <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-2">Pending</h3>
                        <p class="text-3xl font-bold">${data.pending}</p>
                    </div>
                    <div class="bg-green-500 text-white p-6 rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-2">Completed</h3>
                        <p class="text-3xl font-bold">${data.completed}</p>
                    </div>
                `;

                // High-priority tasks
                const highPriorityList = document.getElementById('high-priority-tasks');
                highPriorityList.innerHTML = data.high_priority.map(task => `
                    <li class="p-4 bg-white rounded shadow flex justify-between">
                        <span>${task.title}</span>
                        <span class="text-red-600">${task.due_date}</span>
                    </li>
                `).join('');

            } catch (error) {
                console.error('Error fetching dashboard:', error);
            }
        }

        fetchDashboard();
    </script>
</body>
</html>
