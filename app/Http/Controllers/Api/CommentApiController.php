<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment; 
use App\Models\Task;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    // 🔹 Add comment to task
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = $task->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => $comment
        ], 201);
    }
}
