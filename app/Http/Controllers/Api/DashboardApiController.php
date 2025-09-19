<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Fetch paginated tasks for the user
        $tasks = Task::with(['creator', 'postpones.user', 'comments.user', 'assignees'])
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })
            ->when($request->filled('search'), fn($query) =>
                $query->where('title', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%")
            )
            ->when($request->filled('status'), fn($query) =>
                $query->where('status', $request->status)
            )
            ->when($request->filled('priority'), fn($query) =>
                $query->where('priority', $request->priority)
            )
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        // Fetch all tasks (without pagination) for stats
        $allTasks = Task::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
        })->get();

        // Stats counts
        $pendingCount = $allTasks->where('status', 'pending')->count();
        $inProgressCount = $allTasks->where('status', 'in_progress')->count();
        $completedCount = $allTasks->where('status', 'completed')->count();
        $highPriorityCount = $allTasks->where('priority', 'high')->count();
        $mediumPriorityCount = $allTasks->where('priority', 'medium')->count();
        $lowPriorityCount = $allTasks->where('priority', 'low')->count();
        $totalCount = $allTasks->count();

        // High-priority tasks due soon (next 5)
        $highPriorityTasks = $allTasks->where('priority', 'high')
                                      ->sortBy('due_date')
                                      ->take(5)
                                      ->map(fn($t) => [
                                          'id' => $t->id,
                                          'title' => $t->title,
                                          'due_date' => $t->due_date,
                                      ]);

        return response()->json([
            'tasks' => $tasks,
            'stats' => [
                'total' => $totalCount,
                'pending' => $pendingCount,
                'in_progress' => $inProgressCount,
                'completed' => $completedCount,
                'high_priority' => $highPriorityCount,
                'medium_priority' => $mediumPriorityCount,
                'low_priority' => $lowPriorityCount,
            ],
            'high_priority_tasks' => $highPriorityTasks
        ]);
    }
}
