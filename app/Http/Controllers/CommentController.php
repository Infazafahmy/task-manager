<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\Comment; 

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('message', 'Comment added successfully!');
    }

}
