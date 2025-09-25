<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB max
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('attachments', time() . '_' . $fileName, 'public');

        Attachment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'filename' => $fileName,
            'path' => $filePath,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('message', 'File uploaded successfully!');
    }

    public function download(Attachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->filename);
    }
    public function destroy(Attachment $attachment)
    {
        // Optional: Add authorization to ensure only authorized users can delete
        if ($attachment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the file from storage
        if (Storage::disk('public')->exists($attachment->path)) {
            Storage::disk('public')->delete($attachment->path);
        }

        // Delete the attachment record from the database
        $attachment->delete();

        return back()->with('message', 'File deleted successfully!');
    }
}