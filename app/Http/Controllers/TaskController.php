<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create($projectId = null)
    {
        $project = null;

        if ($projectId) {
            $project = Project::findOrFail($projectId);
        }

        $users = User::all();
        $projects = Project::all(); // هنا ضيف المشاريع

        return view('tasks.create', compact('project', 'users', 'projects'));
    }

    public function index(Request $request)
    {
        // نبدأ الاستعلام بالـ relationships المطلوبة
        $query = Task::with(['assignedUsers', 'project'])->orderBy('due_date', 'asc');

        // 🔍 البحث بالكلمة
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('client_name', 'like', "%{$q}%")
                    ->orWhereHas('assignedUsers', function ($userSub) use ($q) {
                        $userSub->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('project', function ($projSub) use ($q) {
                        $projSub->where('title', 'like', "%{$q}%");
                    });
            });
        }

        // 🏷️ فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📅 فلترة بتاريخ الإنشاء أو الـ due_date
        if ($request->filled('date_range')) {
            // لازم يكون الفيلد بالشكل "2025-10-01 to 2025-10-10"
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $start = $dates[0];
                $end = $dates[1];
                $query->whereBetween('due_date', [$start, $end]);
            }
        }

        // ⏳ تنفيذ الاستعلام
        $tasks = $query->paginate(10)->withQueryString();

        // 📊 الإحصائيات
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'Pending')->count(),
            'completed' => Task::where('status', 'Completed')->count(),
            'deleted' => Task::onlyTrashed()->count(),
        ];

        // بيانات المستخدمين والمشاريع لاستخدامها في create/edit
        $users = User::all();
        $projects = Project::all();

        return view('tasks.index', compact('tasks', 'users', 'stats', 'projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
            'priority' => 'required|string',
            'assignedTo' => 'nullable|array',
            'assignedTo.*' => 'exists:users,id',
        ]);

        // إنشاء التاسك
        $task = Task::create([
            'project_id' => $data['project_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'client_name' => $data['client_name'],
            'due_date' => $data['due_date'] ?? now(),
            'status' => $data['status'],
            'priority' => $data['priority'],
        ]);

        // ربط المستخدمين المعينين (إن وجدوا)
        if (! empty($data['assignedTo'])) {
            $task->assignedUsers()->sync($data['assignedTo']);
        }

        return redirect()->back()->with('success', 'Task added successfully!');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignedUsers', 'attachments', 'comments']);
        $users = User::all();

        return view('tasks.show', compact('task', 'users'));
    }

    public function edit(Task $task)
    {
        $users = User::all();
        $projects = Project::all();

        return view('tasks.edit', compact('task', 'users', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'assignedTo' => 'nullable|array',
            'assignedTo.*' => 'exists:users,id',
        ]);

        // لوج للديباج (شوف storage/logs/laravel.log)
        \Log::info('Task update called', ['id' => $task->id, 'input' => $request->all()]);

        // احتفظ بالقيم اللي تخص العلاقة ثم شيلها من المصفوفة قبل التحديث
        $assigned = $validated['assignedTo'] ?? null;
        unset($validated['assignedTo']);

        // حدّد الحقول صراحة لتفادي أي مشاكل مع mass-assignment
        $task->project_id = $validated['project_id'] ?? $task->project_id;
        $task->title = $validated['title'];
        $task->description = $validated['description'] ?? $task->description;
        $task->client_name = $validated['client_name'] ?? $task->client_name;
        $task->due_date = $validated['due_date'] ?? $task->due_date;
        $task->status = $validated['status'] ?? $task->status;
        $task->priority = $validated['priority'] ?? $task->priority;
        $task->save();

        // مزامنة المستخدمين المكلفين
        if (is_array($assigned)) {
            $task->assignedUsers()->sync($assigned);
        } else {
            // لو المفتاح مش موجود => فصل الجميع (اختياري حسب رغبتك)
            $task->assignedUsers()->detach();
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function assignUser(Task $task, User $user)
    {
        $task->assignedUsers()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'User assigned successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*' => 'exists:tasks,id',
        ]);

        Task::whereIn('id', $request->tasks)->delete(); // Soft delete أو delete مباشرة

        return redirect()->back()->with('success', 'Selected tasks deleted successfully.');
    }
}
