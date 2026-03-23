<?php

namespace App\Http\Controllers;

use App\Models\ActivityComment;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, ProjectActivity $activity)
    {
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $activity->comments()->create([
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        return back()->with('success', 'Reply added successfully!');
    }

    public function update(Request $request, ActivityComment $comment)
    {
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($data);

        return back()->with('success', 'Reply updated successfully!');
    }

    public function destroy(ActivityComment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Reply deleted successfully!');
    }
}
