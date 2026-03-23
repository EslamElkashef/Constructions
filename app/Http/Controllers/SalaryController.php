<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::with('employee')->orderByDesc('created_at');

        // 🔍 بحث باسم الموظف
        if ($request->filled('employee')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->employee.'%');
            });
        }

        // 📅 فلتر بالشهر
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // 💰 فلتر بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $salaries = $query->paginate(12)->appends($request->query());
        $employees = Employee::orderBy('name')->get();

        return view('salaries.index', compact('salaries', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:2100',
            'basic_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'allowance_reason' => 'nullable|string|max:255',
            'deductions' => 'nullable|numeric',
            'deduction_reason' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',

        ]);

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = ($data['basic_salary'] + $data['allowances']) - $data['deductions'];

        Salary::create($data);

        return redirect()->route('salaries.index')->with('success', 'Salary added successfully!');
    }

    // edit (عرض بيانات مرتب في صفحة أو modal - لو تستخدم صفحة منفصلة)
    public function edit(Salary $salary)
    {
        $employees = Employee::orderBy('name')->get();

        return view('salaries.partials.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:2100',
            'basic_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'allowance_reason' => 'nullable|string|max:255',
            'deductions' => 'nullable|numeric',
            'deduction_reason' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',

        ]);

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = ($data['basic_salary'] + $data['allowances']) - $data['deductions'];

        $salary->update($data);

        return redirect()->route('salaries.index')->with('success', 'Salary updated successfully!');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Salary deleted successfully!');
    }

    public function show(Salary $salary)
    {
        return view('salaries.show', compact('salary'));
    }

    public function generate()
    {
        $month = date('F');
        $year = date('Y');

        // هات الموظفين النشطين فقط
        $activeEmployees = \App\Models\Employee::where('status', 'active')->get();
        $createdCount = 0;

        foreach ($activeEmployees as $employee) {
            // تأكد إن المرتب مش موجود مسبقًا لهذا الشهر
            $exists = \App\Models\Salary::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if (! $exists) {
                // 👇 هنا بنحمي لو الراتب مش موجود
                $basicSalary = $employee->salary ?? 0;

                \App\Models\Salary::create([
                    'employee_id' => $employee->id,
                    'basic_salary' => $basicSalary,
                    'allowances' => 0,
                    'deductions' => 0,
                    'net_salary' => $basicSalary,
                    'month' => $month,
                    'year' => $year,
                    'status' => 'Pending',
                    'payment_date' => null,
                ]);

                $createdCount++;
            }
        }

        return redirect()->route('salaries.index')
            ->with('success', "$createdCount salaries generated for $month $year.");
    }
}
