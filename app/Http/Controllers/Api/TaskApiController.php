<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    // 🔹 List tasks (with filters)
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $tasks = Task::with(['creator', 'assignees'])
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId) // tasks created by me
                      ->orWhereHas('assignees', function ($q) use ($userId) {
                          $q->where('users.id', $userId); // tasks assigned to me
                      });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('priority'), function($query) use ($request) {
                $query->where('priority', $request->priority);
            })
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return response()->json($tasks);
    }

    // 🔹 Create task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'priority'    => 'nullable|in:low,medium,high',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task'    => $task
        ], 201);
    }

    // 🔹 Update task
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'nullable|in:pending,in_progress,completed',
            'priority'    => 'nullable|in:low,medium,high',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully!',
            'task'    => $task
        ]);
    }

    // 🔹 Delete task
    public function destroy(Task $task, Request $request)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    // 🔹 Mark completed
    public function markCompleted(Task $task, Request $request)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->status = 'completed';
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task marked as completed',
            'task'    => $task
        ]);
    }

    // 🔹 Assign users by email
        public function assign(Request $request, Task $task)
    {
        $request->validate([
            'assigned_emails' => 'required|string',
        ]);

        // Split emails by comma
        $emails = array_map('trim', explode(',', $request->assigned_emails));

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $task->assignees()->syncWithoutDetaching($user->id);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Members assigned successfully.',
            'task'    => $task->load('assignees') // Include task with assigned users
        ]);
    }


    // 🔹 Postpone task
    public function postpone(Request $request, Task $task)
    {
        $request->validate([
            'reason'       => 'required|string|max:255',
            'new_due_date' => 'required|date|after:today',
        ]);

        $task->postpones()->create([
            'user_id'     => $request->user()->id,
            'reason'      => $request->reason,
            'new_due_date'=> $request->new_due_date,
        ]);

        $task->update([
            'due_date' => $request->new_due_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task postponed successfully.',
            'task'    => $task
        ]);
    }
}
