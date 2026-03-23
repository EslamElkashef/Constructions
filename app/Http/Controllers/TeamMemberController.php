<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class TeamMemberController extends Controller
{
    /**
     * عرض صفحة الفريق
     */
    public function index()
    {
        $members = TeamMember::withCount(['project as projects_count' => function ($q) {
            $q->whereNotNull('id');
        }])
            ->withCount(['project as tasks_count' => function ($q) {
                $q->with('tasks');
            }])
            ->orderByDesc('favourite')
            ->get();

        return view('team.index', compact('members'));
    }

    /**
     * حفظ عضو جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'background' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $manager = new ImageManager(new Driver);

        if ($request->hasFile('background')) {
            $image = $manager->read($request->file('background'));

            // ✅ قص الصورة على أبعاد ثابتة تناسب الكارت (مثلاً 400x250)
            $image = $image->cover(400, 250);

            // حفظ الصورة في مجلد storage/public/backgrounds
            $path = 'backgrounds/'.uniqid().'.jpg';
            $image->toJpeg(90)->save(storage_path('app/public/'.$path));

            $validated['background'] = $path;
        }

        TeamMember::create($validated);

        return redirect()->back()->with('success', 'Team member added successfully!');
    }

    /**
     * عرض بيانات العضو للتعديل (AJAX)
     */
    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);

        return response()->json([
            'id' => $member->id,
            'name' => $member->name,
            'role' => $member->role,
            'project_id' => $member->project_id,
            'avatar_url' => $member->avatar ? asset('storage/'.$member->avatar) : asset('assets/images/users/user-dummy-img.jpg'),
        ]);
    }

    /**
     * حفظ تعديل العضو
     */
    public function update(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'background' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($member->avatar && Storage::disk('public')->exists($member->avatar)) {
                Storage::disk('public')->delete($member->avatar);
            }
            $member->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('background')) {
            if ($member->background && \Storage::disk('public')->exists($member->background)) {
                \Storage::disk('public')->delete($member->background);
            }

            $image = \Intervention\Image\Facades\Image::make($request->file('background'))
                ->fit(800, 400, function ($constraint) {
                    $constraint->upsize();
                })
                ->encode('jpg', 90);

            $path = 'backgrounds/'.uniqid().'.jpg';
            \Storage::disk('public')->put($path, (string) $image);
            $member->background = $path;
        }

        $member->update([
            'name' => $request->name,
            'role' => $request->role,
            'project_id' => $request->project_id,
            'avatar' => $member->avatar,
            'background' => $member->background,
        ]);

        return redirect()->back()->with('success', 'Team member updated successfully.');
    }

    /**
     * حذف العضو
     */
    public function destroy($id)
    {
        $member = TeamMember::findOrFail($id);

        if ($member->avatar && Storage::disk('public')->exists($member->avatar)) {
            Storage::disk('public')->delete($member->avatar);
        }

        if ($member->background && Storage::disk('public')->exists($member->background)) {
            Storage::disk('public')->delete($member->background);
        }

        $member->delete();

        return redirect()->back()->with('success', 'Member deleted successfully.');
    }

    /**
     * تبديل حالة المفضلة (AJAX)
     */
    public function toggleFavourite($id)
    {
        $member = TeamMember::findOrFail($id);
        $member->favourite = ! $member->favourite;
        $member->save();

        return response()->json([
            'success' => true,
            'favourite' => $member->favourite,
        ]);
    }
}
