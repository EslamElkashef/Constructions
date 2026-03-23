<?php

namespace App\Http\Controllers;

use App\Models\GeneralExpense;
use App\Models\GeneralExpenseCategory;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // عرض المصروفات
    public function index(Project $project)
    {
        $expenses = $project->generalExpenses()->with(['user', 'category'])->get();
        $total = $project->generalExpenses()->sum('amount');
        $users = User::orderBy('name')->get();
        $categories = GeneralExpenseCategory::orderBy('name')->get();

        return view('expenses.index', compact('project', 'expenses', 'total', 'users', 'categories'));
    }

    // تخزين مصروف جديد
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'category_id' => 'required|exists:general_expense_categories,id',
            'expense_date' => 'required|date',
        ]);

        $data['project_id'] = $project->id;
        $data['created_by'] = auth()->id();

        GeneralExpense::create($data);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Expense added successfully!');
    }

    // تعديل مصروف
    public function edit(Project $project, GeneralExpense $expense)
    {
        $users = User::orderBy('name')->get();
        $categories = GeneralExpenseCategory::orderBy('name')->get();

        return view('expenses.edit', compact('project', 'expense', 'users', 'categories'));
    }

    // تحديث مصروف
    public function update(Request $request, Project $project, GeneralExpense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'category_id' => 'required|exists:general_expense_categories,id',
            'expense_date' => 'required|date',
        ]);

        $expense->update($validated);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Project $project, GeneralExpense $expense)
    {
        $expense->delete();

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Expense deleted successfully!');
    }
}
