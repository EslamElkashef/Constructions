<?php

namespace App\Http\Controllers;

use App\Models\GeneralExpenseCategory;
use Illuminate\Http\Request;

class GeneralExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = GeneralExpenseCategory::latest()->paginate(20);

        return view('general-expense-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:general_expense_categories,name',
        ]);

        GeneralExpenseCategory::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Category added successfully');
    }

    public function edit(GeneralExpenseCategory $generalExpenseCategory)
    {
        return response()->json($generalExpenseCategory);
    }

    public function update(Request $request, GeneralExpenseCategory $generalExpenseCategory)
    {
        $request->validate([
            'name' => 'required|string|unique:general_expense_categories,name,'.$generalExpenseCategory->id,
        ]);

        $generalExpenseCategory->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Category updated successfully');
    }

    public function destroy(GeneralExpenseCategory $generalExpenseCategory)
    {
        // ⛔ منع الحذف لو مستخدم
        if ($generalExpenseCategory->expenses()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category, it is used in expenses.',
            ], 422);
        }

        $generalExpenseCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
