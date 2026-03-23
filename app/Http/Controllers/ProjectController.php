<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\GeneralExpenseCategory;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        switch ($request->filter) {
            case 'today':
                $query->whereDate('updated_at', today());
                break;
            case 'yesterday':
                $query->whereDate('updated_at', today()->subDay());
                break;
            case 'last7':
                $query->whereDate('updated_at', '>=', today()->subDays(7));
                break;
            default:
                break;
        }

        $projects = $query->latest('updated_at')->paginate(12)->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(ProjectRequest $request)
    {
        $project = new Project($request->validated()); // كل الحقول المسموح بها
        $project->user_id = auth()->id();

        // حفظ الصورة المصغرة
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/project_thumbnails', $filename);
            $project->thumbnail = 'project_thumbnails/'.$filename;
        }

        // حفظ الملفات المرفقة
        if ($request->hasFile('attached_files')) {
            $files = [];
            foreach ($request->file('attached_files') as $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $file->storeAs('public/project_files', $filename);
                $files[] = [
                    'path' => 'project_files/'.$filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $project->attached_files = $files;
        }

        $project->save();

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load([
            'users',
            'activities.user',
            'teamMembers',
            'generalExpenses.user',
            'generalExpenses.category',
            'tasks.assignedUsers',
        ]);

        $users = User::orderBy('name')->get();

        // ✅ أضف هذا السطر لجلب جميع التصنيفات
        $categories = GeneralExpenseCategory::all();

        return view('projects.show', compact('project', 'users', 'categories'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        // ---------- Thumbnail ----------
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        } else {
            unset($data['thumbnail']); // لا تحدثها إذا مفيش تغيير
        }

        // ---------- Attached files ----------
        if ($request->hasFile('attached_files')) {
            $files = is_array($project->attached_files) ? $project->attached_files : [];
            foreach ($request->file('attached_files') as $f) {
                $files[] = [
                    'path' => $f->store('projects/files', 'public'),
                    'original_name' => $f->getClientOriginalName(),
                    'size' => $f->getSize(),
                ];
            }
            $data['attached_files'] = $files;
        } else {
            unset($data['attached_files']); // خليها زي ما هي لو مفيش تغيير
        }

        // ---------- Budget ----------
        $data['budget'] = $request->has('budget') ? $request->input('budget') : $project->budget;

        // ----------- Actual update -----------
        $project->update($data);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        if ($project->attached_files) {
            foreach ($project->attached_files as $p) {
                Storage::disk('public')->delete($p['path']); // حذف حسب المفتاح الصحيح
            }
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully');
    }

    public function toggleFavourite(Project $project)
    {
        $project->favourite = ! $project->favourite;
        $project->save();

        return back()->with('success', 'Favourite status updated');
    }

    public function addMember(Request $request, Project $project)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $project->teamMembers()->syncWithoutDetaching($request->user_id);

        return back()->with('success', 'Member invited!');
    }

    public function uploadFiles(Request $request, Project $project)
    {
        $request->validate([
            'attached_files.*' => 'file|max:5120',
        ]);

        $files = is_array($project->attached_files) ? $project->attached_files : [];

        foreach ($request->file('attached_files', []) as $f) {
            $files[] = [
                'path' => $f->store('projects/files', 'public'),
                'original_name' => $f->getClientOriginalName(),
                'size' => $f->getSize(),
            ];
        }

        $project->update(['attached_files' => $files]);

        return back()->with('success', 'Files uploaded successfully!');
    }

    public function deleteFile(Project $project, $fileIndex)
    {
        $files = $project->attached_files ?? [];

        if (isset($files[$fileIndex])) {
            $file = $files[$fileIndex];

            // لو الملف متخزن كـ string
            if (is_string($file)) {
                Storage::disk('public')->delete($file);
            }

            // لو الملف متخزن كـ array
            if (is_array($file) && isset($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }

            // شيل الملف من القائمة
            unset($files[$fileIndex]);

            // أعد ترتيب الاندكس
            $project->update([
                'attached_files' => array_values($files),
            ]);
        }

        return back()->with('success', 'File deleted successfully!');
    }
}
