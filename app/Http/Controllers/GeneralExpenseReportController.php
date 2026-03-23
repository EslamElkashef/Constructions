<?php

namespace App\Http\Controllers;

use App\Models\GeneralExpense;
use Illuminate\Http\Request;

class GeneralExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        // فلترة بالتواريخ لو موجودة
        $from = $request->from;
        $to = $request->to;

        $expenses = GeneralExpense::with('category')
            ->when($from, fn ($q) => $q->whereDate('expense_date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('expense_date', '<=', $to))
            ->get();

        // Group by category
        $report = $expenses->groupBy(fn ($expense) => $expense->category->name ?? 'غير محدد')
            ->map(fn ($group) => $group->sum('amount'));

        return view('general-expense-report.index', compact('report', 'from', 'to'));
    }
}
