<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // 🔹 List tasks (with filters)
    public function index(Request $request)
    {
        $userId = auth()->id();

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
            ->when(request('priority'), function($query) {
                $query->where('priority', request('priority'));
            })
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    // 🔹 Create task
    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        auth()->user()->tasks()->create($request->all());

        return redirect()->route('tasks.index')->with('message','Task created successfully.');
    }

    // 🔹 Update task
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed'
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('message', 'Task updated successfully!');
    }

    // 🔹 Delete task
    public function destroy(Task $task)
    {
        $task->delete();

        if(request()->expectsJson()){
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
        }

        return redirect()->back()->with('message', 'Task deleted successfully');
    }

    // 🔹 Mark completed
    public function markCompleted(Task $task)
    {
        $task->status = 'completed';
        $task->save();

        if(request()->expectsJson()){
            return response()->json([
                'success' => true,
                'message' => 'Task marked as completed'
            ]);
        }

        return redirect()->back()->with('message', 'Task marked as completed');
    }


    public function assignPage()
    {
        $tasks = Task::with(['assignees', 'creator'])
        ->where('user_id', auth()->id())
        ->paginate(10);


        return view('tasks.assign', compact('tasks'));
    }

    // 🔹 Assign users by email
    public function assign(Request $request, Task $task)
    {
        $request->validate([
            'assigned_emails' => 'required|string',
        ]);

        $emails = array_map('trim', explode(',', $request->assigned_emails));

        $invalidEmails = [];
        $notRegistered = [];
        $assignedUsers = [];

        foreach ($emails as $email) 
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalidEmails[] = $email;
                continue;
            }

            $user = User::where('email', $email)->first();
            if (!$user) {
                $notRegistered[] = $email;
                continue;
            }

            $task->assignees()->syncWithoutDetaching($user->id);
            $assignedUsers[] = $email;
        }

        if ($invalidEmails || $notRegistered) {
            $messages = [];

            if ($invalidEmails) {
                $messages[] = 'Invalid emails: ' . implode(', ', $invalidEmails);
            }
            if ($notRegistered) {
                $messages[] = 'Not registered: ' . implode(', ', $notRegistered);
            }

            // ✅ Use Validator to return proper error messages
            return redirect()->back()->withErrors([
                'assigned_emails' => implode(' | ', $messages),
            ])->withInput();
        }

        return redirect()->back()->with('message', 'Members assigned: ' . implode(', ', $assignedUsers));
    }


    // 🔹 Remove an assigned member 
    public function removeMember(Task $task, User $user)
    {
        if ($task->user_id !== auth()->id()) abort(403);

        $task->assignees()->detach($user->id);

        return back()->with('message', 'Member removed successfully!');
    }

    // 🔹 Postpone task    
    public function postpone(Request $request, Task $task)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'new_due_date' => 'required|date|after:today',
        ]);

        // Save postpone record
        $task->postpones()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'new_due_date' => $request->new_due_date,
        ]);

        // Update task’s due date
        $task->update([
            'due_date' => $request->new_due_date,
        ]);

        return redirect()->back()->with('success', 'Task postponed successfully.');
    }






}
