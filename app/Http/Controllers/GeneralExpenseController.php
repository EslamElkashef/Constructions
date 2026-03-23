<?php

namespace App\Http\Controllers;

use App\Models\GeneralExpense;
use App\Models\GeneralExpenseCategory;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneralExpenseController extends Controller
{
    /**
     * Display a listing of general expenses with filters
     */
    public function index(Request $request)
    {
        $query = GeneralExpense::with(['category', 'project', 'user']);

        // 🔍 Search by title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }

        // 📂 Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 📅 Filter by month
        if ($request->filled('month')) {
            try {
                $date = Carbon::parse($request->month);
                $query->whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year);
            } catch (\Exception $e) {
                // Invalid date, skip
            }
        }

        // 📅 Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        // 🔃 Sorting
        switch ($request->sort) {
            case 'date_asc':
                $query->orderBy('expense_date', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            default:
                $query->orderBy('expense_date', 'desc');
                break;
        }

        $expenses = $query->paginate(15)->withQueryString();

        // ✅ Calculate KPIs
        $totalExpenses = GeneralExpense::sum('amount');

        $thisMonthExpenses = GeneralExpense::whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('amount');

        $thisWeekExpenses = GeneralExpense::whereBetween('expense_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->sum('amount');

        $todayExpenses = GeneralExpense::whereDate('expense_date', Carbon::today())
            ->sum('amount');

        // Active Categories (categories with expenses)
        $activeCategories = GeneralExpense::distinct('category_id')->count('category_id');

        $categories = GeneralExpenseCategory::orderBy('name')->get();
        $projects = Project::orderBy('title')->get();

        return view('general-expenses.index', compact(
            'expenses',
            'categories',
            'projects',
            'totalExpenses',
            'thisMonthExpenses',
            'thisWeekExpenses',
            'todayExpenses',
            'activeCategories'
        ));
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:general_expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,credit_card',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['created_by'] = auth()->id();

        $expense = GeneralExpense::create($data);

        // AJAX Response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense added successfully',
                'data' => $expense->load(['category', 'project', 'user']),
            ], 201);
        }

        // Regular Response
        return redirect()->route('general-expenses.index')
            ->with('success', 'Expense added successfully');
    }

    /**
     * Show the form for editing the specified expense (AJAX)
     */
    public function edit(GeneralExpense $generalExpense)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $generalExpense->id,
                'title' => $generalExpense->title,
                'category_id' => $generalExpense->category_id,
                'amount' => $generalExpense->amount,
                'expense_date' => $generalExpense->expense_date->format('Y-m-d'),
                'payment_method' => $generalExpense->payment_method,
                'project_id' => $generalExpense->project_id,
                'notes' => $generalExpense->notes,
            ],
        ]);
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, GeneralExpense $generalExpense)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:general_expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,credit_card',
            'project_id' => 'nullable|exists:projects,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $generalExpense->update($data);

        return redirect()->route('general-expenses.index')
            ->with('success', 'Expense updated successfully');
    }

    public function destroy($id)
    {
        try {
            $expense = GeneralExpense::findOrFail($id);
            $expense->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Expense deleted successfully',
                ]);
            }

            return redirect()->route('general-expenses.index')
                ->with('success', 'Expense deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Expense deletion error: '.$e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete expense',
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete expense');
        }
    }

    /**
     * Get expense statistics (optional)
     */
    public function statistics(Request $request)
    {
        $query = GeneralExpense::query();

        // Filter by date range if provided
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        $stats = [
            'total_expenses' => $query->sum('amount'),
            'expense_count' => $query->count(),
            'average_expense' => $query->avg('amount'),
            'by_category' => $query->with('category')
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->get(),
            'by_payment_method' => $query->selectRaw('payment_method, SUM(amount) as total')
                ->groupBy('payment_method')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
