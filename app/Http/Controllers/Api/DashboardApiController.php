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

        $tasks = Task::with(['creator', 'assignees'])
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

        // Stats counts
        $pendingCount = $tasks->where('status', 'pending')->count();
        $inProgressCount = $tasks->where('status', 'in_progress')->count();
        $completedCount = $tasks->where('status', 'completed')->count();
        $highPriorityCount = $tasks->where('priority', 'high')->count();
        $mediumPriorityCount = $tasks->where('priority', 'medium')->count();
        $lowPriorityCount = $tasks->where('priority', 'low')->count();

        return response()->json([
            'tasks' => $tasks,
            'stats' => [
                'pending' => $pendingCount,
                'in_progress' => $inProgressCount,
                'completed' => $completedCount,
                'high_priority' => $highPriorityCount,
                'medium_priority' => $mediumPriorityCount,
                'low_priority' => $lowPriorityCount,
            ]
        ]);
    }
}
