<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Experience;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * عرض كل البروفايلات
     */
    public function index()
    {
        $profiles = Profile::with('employee')
            ->orderByDesc('favourite')
            ->latest()
            ->paginate(10);

        return view('profile.index', compact('profiles'));
    }

    /**
     * إنشاء بروفايل جديد
     */
    public function create(Request $request)
    {
        $employee = null;

        if ($request->has('employee_id')) {
            $employee = Employee::find($request->employee_id);
        }

        return view('profile.create', compact('employee'));
    }

    /**
     * حفظ بروفايل جديد
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'designation' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'status' => 'nullable|in:pending,active,terminated,resigned',
            'status_reason' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('profiles/avatars', 'public');
        }

        Profile::create($data);

        return redirect()->route('profiles.index')->with('success', 'Profile created successfully!');
    }

    /**
     * عرض بروفايل محدد
     */
    public function show(Profile $profile)
    {
        $employee = $profile->employee;

        return view('profile.show', compact('profile', 'employee'));
    }

    /**
     * صفحة التعديل
     */
    public function edit(Profile $profile)
    {
        $employee = $profile->employee;
        $experiences = $profile->experiences;

        return view('profile.edit', compact('profile', 'employee', 'experiences'));
    }

    /**
     * تحديث البروفايل
     */
    public function update(Request $request, Profile $profile)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'designation' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'status' => 'nullable|in:pending,active,terminated,resigned',
            'status_reason' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('profiles/avatars', 'public');
        }

        $profile->update($data);

        // تحديث الخبرات لو موجودة
        if ($request->has('job_title')) {
            foreach ($request->job_title as $index => $title) {
                $exp = $profile->experiences[$index] ?? new Experience;
                $exp->profile_id = $profile->id;
                $exp->job_title = $title;
                $exp->company_name = $request->company_name[$index];
                $exp->from_year = $request->from_year[$index];
                $exp->to_year = $request->to_year[$index];
                $exp->job_description = $request->job_description[$index];
                $exp->save();
            }
        }

        return redirect()->route('profiles.show', $profile->id)->with('success', 'Profile updated successfully!');
    }

    /**
     * حذف البروفايل
     */
    public function destroy(Profile $profile)
    {
        if ($profile->avatar) {
            Storage::disk('public')->delete($profile->avatar);
        }

        $profile->delete();

        return redirect()->route('profiles.index')->with('success', 'Profile deleted successfully.');
    }

    /**
     * تفعيل/إلغاء المفضلة
     */
    public function toggleFavourite($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->favourite = ! $profile->favourite;
        $profile->save();

        return response()->json([
            'success' => true,
            'favourite' => $profile->favourite,
        ]);
    }
}
