<?php

namespace App\Http\Controllers;

use App\Models\GeneralRevenue;
use App\Models\Project;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneralRevenueController extends Controller
{
    public function index(Request $request)
    {
        $query = GeneralRevenue::with(['project', 'unit']);

        // 🔍 Search by title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }

        // 📂 Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 📅 Filter by month
        if ($request->filled('month')) {
            try {
                $date = Carbon::parse($request->month);
                $query->whereMonth('date', $date->month)
                    ->whereYear('date', $date->year);
            } catch (\Exception $e) {
                // Invalid date, skip
            }
        }

        // 📅 Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        // 🔃 Sorting
        switch ($request->sort) {
            case 'date_asc':
                $query->orderBy('date', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            default:
                $query->orderBy('date', 'desc');
                break;
        }

        $revenues = $query->paginate(15)->withQueryString();

        // ✅ Calculate KPIs
        $totalRevenues = GeneralRevenue::sum('amount');

        $thisMonthRevenues = GeneralRevenue::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $thisWeekRevenues = GeneralRevenue::whereBetween('date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->sum('amount');

        $todayRevenues = GeneralRevenue::whereDate('date', Carbon::today())
            ->sum('amount');

        // Active Categories
        $activeCategories = GeneralRevenue::distinct('category')->count('category');

        $projects = Project::orderBy('title')->get();
        $units = Unit::orderBy('id')->get();

        return view('general-revenues.index', compact(
            'revenues',
            'projects',
            'units',
            'totalRevenues',
            'thisMonthRevenues',
            'thisWeekRevenues',
            'todayRevenues',
            'activeCategories'
        ));
    }

    public function create()
    {
        $projects = Project::orderBy('title')->get();
        $units = Unit::orderBy('id')->get();

        return view('general-revenues.create', compact('projects', 'units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'received_from' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,wallet',
            'project_id' => 'nullable|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        GeneralRevenue::create($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Revenue added successfully',
            ], 201);
        }

        return redirect()->route('general-revenues.index')
            ->with('success', 'Revenue added successfully');
    }

    public function edit(GeneralRevenue $generalRevenue)
    {
        return response()->json([
            'success' => true,
            'data' => $generalRevenue,
        ]);
    }

    public function update(Request $request, GeneralRevenue $generalRevenue)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'received_from' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,wallet',
            'project_id' => 'nullable|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $generalRevenue->update($data);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Revenue updated successfully',
            ]);
        }

        return redirect()->route('general-revenues.index')
            ->with('success', 'Revenue updated successfully');
    }

    public function destroy($id)
    {
        try {
            $revenue = GeneralRevenue::findOrFail($id);
            $revenue->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Revenue deleted successfully',
                ]);
            }

            return redirect()->route('general-revenues.index')
                ->with('success', 'Revenue deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Revenue deletion error: '.$e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete revenue',
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete revenue');
        }
    }
}
