<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display all employees.
     */
    public function index(Request $request)
    {
        // جمع الفلتر من الريكوست (مثال بسيط)
        $query = Employee::query()->with('department', 'profile');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // ترتيب افتراضي أو حسب الطلب
        if ($request->filled('sort')) {
            $query->orderBy($request->sort);
        } else {
            $query->latest();
        }

        $employees = $query->paginate(12)->withQueryString();

        // جلب القوائم اللازمة للفلتر — من جدول departments
        // تأكد إن عندك موديل Department وإلا غيّر المصدر (مثال: distinct departments من employees).
        $departments = \App\Models\Department::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {

        try {
            $data = $request->validated();

            // Upload personal image if exists
            if ($request->hasFile('personal_image')) {
                $data['personal_image'] = $request->file('personal_image')
                    ->store('employees/personal_images', 'public');
            }

            // Upload national ID image if exists
            if ($request->hasFile('national_id_image')) {
                $data['national_id_image'] = $request->file('national_id_image')
                    ->store('employees/national_ids', 'public');
            }

            // Format dates
            $data['start_date'] = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : null;
            $data['birthday'] = $request->birthday ? date('Y-m-d', strtotime($request->birthday)) : null;

            // Create employee
            $employee = Employee::create($data);

            // Split full name safely
            $nameParts = explode(' ', trim($employee->name));
            $firstName = $nameParts[0] ?? '';
            $lastName = implode(' ', array_slice($nameParts, 1)) ?: '';

            // Create profile linked to this employee
            Profile::create([
                'employee_id' => $employee->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $employee->email,
                'phone' => $employee->phone,
            ]);

            return redirect()->route('employees.index')->with('success', 'Employee created successfully with profile.');
        } catch (Exception $e) {
            Log::error('Error creating employee: '.$e->getMessage());

            return back()->withInput()->withErrors([
                'general' => 'An error occurred while creating the employee.',
            ]);
        }
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $profile = $employee->profile;

        if (! $profile) {
            return redirect()->route('profiles.create')->with('employee_id', $employee->id);
        }

        return view('employees.show', compact('employee', 'profile'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(StoreEmployeeRequest $request, Employee $employee)
    {
        try {
            $data = $request->validated();

            // Update personal image if a new one is uploaded
            if ($request->hasFile('personal_image')) {
                if ($employee->personal_image) {
                    Storage::disk('public')->delete($employee->personal_image);
                }
                $data['personal_image'] = $request->file('personal_image')
                    ->store('employees/personal_images', 'public');
            }

            // Update national ID image if a new one is uploaded
            if ($request->hasFile('national_id_image')) {
                if ($employee->national_id_image) {
                    Storage::disk('public')->delete($employee->national_id_image);
                }
                $data['national_id_image'] = $request->file('national_id_image')
                    ->store('employees/national_ids', 'public');
            }

            // Format dates again (for safety)
            $data['start_date'] = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : null;
            $data['birthday'] = $request->birthday ? date('Y-m-d', strtotime($request->birthday)) : null;

            $employee->update($data);

            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating employee: '.$e->getMessage());

            return back()->withInput()->withErrors([
                'general' => 'An error occurred while updating employee data.',
            ]);
        }
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            // Delete images if they exist
            if ($employee->personal_image) {
                Storage::disk('public')->delete($employee->personal_image);
            }

            if ($employee->national_id_image) {
                Storage::disk('public')->delete($employee->national_id_image);
            }

            // Delete employee
            $employee->delete();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting employee: '.$e->getMessage());

            return back()->withErrors([
                'general' => 'An error occurred while deleting employee.',
            ]);
        }
    }

    /**
     * Toggle favorite status for an employee.
     */
    public function toggleFavourite(Employee $employee)
    {
        $employee->update(['favourite' => ! $employee->favourite]);

        return response()->json(['success' => true]);
    }
}
