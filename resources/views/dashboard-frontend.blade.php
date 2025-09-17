<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Interactive Task Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="container mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Interactive Task Dashboard</h1>

    <!-- Notifications -->
    <div id="notifications" class="mb-6"></div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('all')">
            <span class="font-semibold">Total</span>
            <span id="total-tasks" class="text-2xl mt-1">0</span>
            <div class="w-full h-2 bg-blue-700 rounded mt-2"><div id="bar-total" class="h-2 bg-white rounded w-0"></div></div>
        </div>
        <div class="bg-yellow-400 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('pending')">
            <span class="font-semibold">Pending</span>
            <span id="pending-tasks" class="text-2xl mt-1">0</span>
            <div class="w-full h-2 bg-yellow-600 rounded mt-2"><div id="bar-pending" class="h-2 bg-white rounded w-0"></div></div>
        </div>
        <div class="bg-indigo-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('in_progress')">
            <span class="font-semibold">In Progress</span>
            <span id="inprogress-tasks" class="text-2xl mt-1">0</span>
            <div class="w-full h-2 bg-indigo-700 rounded mt-2"><div id="bar-inprogress" class="h-2 bg-white rounded w-0"></div></div>
        </div>
        <div class="bg-green-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('completed')">
            <span class="font-semibold">Completed</span>
            <span id="completed-tasks" class="text-2xl mt-1">0</span>
            <div class="w-full h-2 bg-green-700 rounded mt-2"><div id="bar-completed" class="h-2 bg-white rounded w-0"></div></div>
        </div>
        <div class="bg-red-500 text-white p-4 rounded shadow flex flex-col items-center cursor-pointer" onclick="filterTasks('high')">
            <span class="font-semibold">High Priority</span>
            <span id="high-priority" class="text-2xl mt-1">0</span>
            <div class="w-full h-2 bg-red-700 rounded mt-2"><div id="bar-high" class="h-2 bg-white rounded w-0"></div></div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow flex flex-col items-center">
            <h2 class="text-xl font-semibold mb-2">Task Status</h2>
            <div class="w-full max-w-sm">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow flex flex-col items-center">
            <h2 class="text-xl font-semibold mb-2">Priority Count</h2>
            <div class="w-full max-w-sm">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <h2 class="font-semibold mb-2">Pending</h2>
            <div id="pending-column" class="bg-gray-200 p-2 rounded min-h-[200px] space-y-2"></div>
        </div>
        <div>
            <h2 class="font-semibold mb-2">In Progress</h2>
            <div id="inprogress-column" class="bg-gray-200 p-2 rounded min-h-[200px] space-y-2"></div>
        </div>
    </div>

    <!-- Task Table -->
    <div class="bg-white p-4 rounded shadow mb-6 overflow-x-auto">
        <h2 class="text-xl font-semibold mb-2">All Tasks</h2>
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">Title</th>
                    <th class="p-2 text-left">Due Date</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Priority</th>
                    <th class="p-2 text-left">Assignees</th>
                </tr>
            </thead>
            <tbody id="task-table"></tbody>
        </table>
    </div>

</div>

<script>
let statusChart, priorityChart;
let allTasks = [];

function createTaskCard(task){
    const div = document.createElement("div");
    div.className = "bg-white p-3 rounded shadow cursor-move";
    div.draggable = true;
    div.dataset.taskId = task.id;
    div.innerHTML = `<strong>${task.title}</strong><br><small>Due: ${task.due_date}</small>`;
    if(task.priority==="high") div.classList.add("border-2","border-red-500");
    return div;
}

function enableDragDrop(){
    const columns = document.querySelectorAll("[id$='-column']");
    columns.forEach(col=>{
        col.addEventListener("dragover", e=>e.preventDefault());
        col.addEventListener("drop", e=>{
            const id = e.dataTransfer.getData("text");
            const card = document.querySelector(`[data-task-id='${id}']`);
            col.appendChild(card);
        });
    });
    const cards = document.querySelectorAll(".cursor-move");
    cards.forEach(card=>{
        card.addEventListener("dragstart", e=>e.dataTransfer.setData("text", card.dataset.taskId));
    });
}

function renderTasks(tasks){
    const statusMap = { "pending": "pending-column", "in progress": "inprogress-column" };

    // Clear and populate Kanban
    Object.keys(statusMap).forEach(status => {
        const col = document.getElementById(statusMap[status]);
        col.innerHTML = "";
        tasks.forEach(task => {
            if(task.status.toLowerCase() === status){
                col.appendChild(createTaskCard(task));
            }
        });
    });

    // Table shows all tasks including completed
    const tbody = document.getElementById("task-table");
    tbody.innerHTML = "";
    tasks.forEach(task => {
        const tr = document.createElement("tr");
        tr.className = "border-b";
        if(task.priority==="high") tr.classList.add("bg-red-50");
        tr.innerHTML = `
            <td class="p-2">${task.title}</td>
            <td class="p-2">${task.due_date}</td>
            <td class="p-2">${task.status}</td>
            <td class="p-2">${task.priority}</td>
            <td class="p-2">${task.assignees.map(a => a.name).join(", ")}</td>
        `;
        tbody.appendChild(tr);
    });
}

function filterTasks(type){
    let filtered = allTasks;
    if(type==="pending") filtered = allTasks.filter(t => t.status.toLowerCase() === "pending");
    if(type==="in_progress") filtered = allTasks.filter(t => t.status.toLowerCase() === "in progress");
    if(type==="completed") filtered = allTasks.filter(t => t.status.toLowerCase() === "completed");
    if(type==="high") filtered = allTasks.filter(t => t.priority==="high");
    renderTasks(filtered);
}



function updateBars(stats){
    const total = stats.total || 1;
    document.getElementById("bar-total").style.width = "100%";
    document.getElementById("bar-pending").style.width = `${(stats.pending/total)*100}%`;
    document.getElementById("bar-inprogress").style.width = `${(stats.in_progress/total)*100}%`;
    document.getElementById("bar-completed").style.width = `${(stats.completed/total)*100}%`;
    document.getElementById("bar-high").style.width = `${(stats.high_priority/total)*100}%`;
}

function filterTasks(type){
    let filtered = allTasks;
    if(type==="pending"||type==="in_progress"||type==="completed") filtered = allTasks.filter(t=>t.status===type);
    if(type==="high") filtered = allTasks.filter(t=>t.priority==="high");
    renderTasks(filtered);
}

async function fetchDashboardData(){
    try{
        const res = await fetch("/dashboard-data",{headers:{"Accept":"application/json"},credentials:"same-origin"});
        if(!res.ok) return;
        const data = await res.json();

        allTasks = data.tasks.data; // include completed tasks

        // Stats
        document.getElementById("total-tasks").textContent = data.stats.total;
        document.getElementById("pending-tasks").textContent = data.stats.pending;
        document.getElementById("inprogress-tasks").textContent = data.stats.in_progress;
        document.getElementById("completed-tasks").textContent = data.stats.completed;
        document.getElementById("high-priority").textContent = data.stats.high_priority;
        updateBars(data.stats);

        // Notifications
        const notificationsDiv = document.getElementById("notifications");
        notificationsDiv.innerHTML="";
        const today = new Date();
        allTasks.forEach(task=>{
            const due = new Date(task.due_date);
            const diffDays = Math.ceil((due-today)/(1000*60*60*24));
            if(diffDays<0){
                notificationsDiv.innerHTML+=`<div class="bg-red-100 text-red-800 p-2 rounded mb-2">⚠️ ${task.priority==="high"?"High Priority ":""}"${task.title}" is overdue!</div>`;
            } else if(diffDays<=3){
                notificationsDiv.innerHTML+=`<div class="bg-yellow-100 text-yellow-800 p-2 rounded mb-2">⏰ ${task.priority==="high"?"High Priority ":""}"${task.title}" is due in ${diffDays} day(s)</div>`;
            }
        });

        renderTasks(allTasks);
        enableDragDrop();

        // Charts
        const statusData=[data.stats.pending,data.stats.in_progress,data.stats.completed];
        const priorityData=[data.stats.high_priority,data.stats.medium_priority,data.stats.low_priority];

        if(statusChart) statusChart.destroy();
        if(priorityChart) priorityChart.destroy();

        const ctx1 = document.getElementById("statusChart").getContext("2d");
        statusChart = new Chart(ctx1,{
            type:'doughnut',
            data:{labels:["Pending","In Progress","Completed"],datasets:[{data:statusData,backgroundColor:["#facc15","#6366f1","#22c55e"]}]},
            options:{plugins:{legend:{position:'bottom'}}}
        });

        const ctx2 = document.getElementById("priorityChart").getContext("2d");
        priorityChart = new Chart(ctx2,{
            type:'bar',
            data:{labels:["High","Medium","Low"],datasets:[{label:"Tasks",data:priorityData,backgroundColor:["#ef4444","#6366f1","#10b981"]}]},
            options:{scales:{y:{beginAtZero:true,precision:0}}}
        });

    }catch(err){console.error(err);}
}

fetchDashboardData();
setInterval(fetchDashboardData,30000);
</script>

</body>
</html>
