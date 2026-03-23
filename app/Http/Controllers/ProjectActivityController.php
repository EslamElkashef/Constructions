<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;

class ProjectActivityController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'status' => 'required|string|in:In Progress,Completed',
        ]);

        $project->activities()->create([
            'user_id' => auth()->id(),
            'description' => $data['description'],
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Activity added!');
    }

    public function update(Request $request, $projectId, ProjectActivity $activity)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'status' => 'required|string|in:In Progress,Completed',
        ]);

        $activity->update($data);

        return back()->with('success', 'Activity updated successfully!');
    }

    public function storeComment(Request $request, ProjectActivity $activity)
    {
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $activity->comments()->create([
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function destroy($projectId, ProjectActivity $activity)
    {
        $activity->delete();

        return back()->with('success', 'Activity deleted successfully!');
    }
}
