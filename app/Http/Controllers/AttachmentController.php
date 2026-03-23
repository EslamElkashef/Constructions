<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    // رفع ملف
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // 5MB
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $task->attachments()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded successfully!');
    }

    // تحميل ملف
    public function download(TaskAttachment $attachment)
    {
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    // عرض ملف (Preview لو نوعه مناسب)
    public function view(TaskAttachment $attachment)
    {
        $file = Storage::disk('public')->path($attachment->file_path);

        return response()->file($file);
    }

    // حذف ملف
    public function destroy(TaskAttachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'File deleted successfully!');
    }
}
