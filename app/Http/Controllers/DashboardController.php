<?php

namespace App\Http\Controllers;

use App\Models\Task; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $tasks = Task::with(['creator', 'assignees'])
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId) // tasks I created
                      ->orWhereHas('assignees', function ($q) use ($userId) {
                          $q->where('users.id', $userId); // tasks assigned to me
                      });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
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

        // Stats counts (created by me + assigned to me)
        $pendingCount = Task::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })->where('status', 'pending')->count();

        $inProgressCount = Task::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })->where('status', 'in_progress')->count();

        $completedCount = Task::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })->where('status', 'completed')->count();


        $highPriorityCount = Task::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })->where('priority', 'high')->count();
        $mediumPriorityCount = Task::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })->where('priority', 'medium')->count();

        $lowPriorityCount = Task::where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignees', fn($q) => $q->where('users.id', $userId));
            })->where('priority', 'low')->count();


        return view('dashboard', compact(
            'pendingCount', 'inProgressCount', 'completedCount',
            'highPriorityCount', 'mediumPriorityCount', 'lowPriorityCount',
            'tasks'
        ));
    }


}
