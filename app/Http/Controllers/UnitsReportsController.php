<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UnitsReportsController extends Controller
{
    // الصفحة الرئيسية للتقارير
    public function index()
    {
        return view('units.reports.index');
    }

    // صفحات منفصلة لكل تقرير
    public function realEstatePage()
    {
        return view('units.reports.real_estate');
    }

    public function salesPage()
    {
        return view('units.reports.sales');
    }

    public function constructionPage()
    {
        return view('units.reports.construction');
    }

    public function financePage()
    {
        return view('units.reports.finance');
    }

    public function getKPIs()
    {
        $total_units = Unit::count();
        $available_units = Unit::where('status', 'available')->count();
        $sold_units = Unit::where('status', 'sold')->count();
        $reserved_units = Unit::where('status', 'reserved')->count();
        $total_projects = Project::count();
        $this_month_sales = Unit::where('status', 'sold')
            ->whereMonth('sold_at', Carbon::now()->month)
            ->whereYear('sold_at', Carbon::now()->year)
            ->count();

        return response()->json(compact('total_units', 'available_units', 'sold_units', 'reserved_units', 'total_projects', 'this_month_sales'));
    }

    public function getRealEstateData()
    {
        // آخر 12 شهر
        $months = [];
        $start = \Carbon\Carbon::now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->copy()->addMonths($i)->format('M Y');
        }

        // SOLD vs AVAILABLE
        $monthly_sold_units = [];
        $monthly_available_units = [];
        foreach ($months as $m) {
            $date = \Carbon\Carbon::createFromFormat('M Y', $m);

            $sold = \App\Models\Unit::where('status', 'sold')
                ->whereYear('sold_at', $date->year)
                ->whereMonth('sold_at', $date->month)
                ->count();
            $monthly_sold_units[] = $sold ?? 0;

            $available = \App\Models\Unit::where('status', 'available')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthly_available_units[] = $available ?? 0;
        }

        // UNIT TYPES PIE
        $unit_type_labels = \App\Models\UnitType::pluck('name')->toArray();
        $unit_type_counts = [];
        foreach ($unit_type_labels as $typeName) {
            $count = \App\Models\Unit::whereHas('type', function ($q) use ($typeName) {
                $q->where('name', $typeName);
            })->count();
            $unit_type_counts[] = $count ?? 0;
        }

        // COMPANY SHARE + SUCCESS RATE
        $monthly_company_share = [];
        $monthly_success_rate = [];
        foreach ($months as $m) {
            $date = \Carbon\Carbon::createFromFormat('M Y', $m);

            $total = \App\Models\Unit::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $company = \App\Models\Unit::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('company_share', '>', 0)
                ->count();

            $sold = \App\Models\Unit::where('status', 'sold')
                ->whereYear('sold_at', $date->year)
                ->whereMonth('sold_at', $date->month)
                ->count();

            $monthly_company_share[] = $company ?? 0;
            $monthly_success_rate[] = $total > 0 ? round(($sold / $total) * 100, 2) : 0;
        }

        // UNITS PER CITY
        $cities = \App\Models\Unit::select('city')->distinct()->pluck('city');
        $units_per_city = [];
        foreach ($cities as $city) {
            $cityMonthly = [];
            foreach ($months as $m) {
                $date = \Carbon\Carbon::createFromFormat('M Y', $m);
                $count = \App\Models\Unit::where('city', $city)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $cityMonthly[] = $count ?? 0;
            }
            // تأكد طول array = 12
            $cityMonthly = array_pad($cityMonthly, 12, 0);
            $units_per_city[] = ['name' => $city ?? 'Unknown', 'data' => $cityMonthly];
        }

        return response()->json([
            'months' => $months,
            'monthly_sold_units' => $monthly_sold_units,
            'monthly_available_units' => $monthly_available_units,
            'unit_type_labels' => $unit_type_labels,
            'unit_type_counts' => $unit_type_counts,
            'monthly_company_share' => $monthly_company_share,
            'monthly_success_rate' => $monthly_success_rate,
            'units_per_city' => $units_per_city,
        ]);
    }

    public function getSalesData()
    {
        $months = [];
        $start = now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $months[] = $start->copy()->addMonths($i)->format('M Y');
        }

        // 1️⃣ Units Sold by Type
        $unitTypes = \App\Models\UnitType::pluck('name');
        $unit_type_series = [];
        foreach ($unitTypes as $type) {
            $data = [];
            foreach ($months as $m) {
                $date = \Carbon\Carbon::createFromFormat('M Y', $m);
                $data[] = \App\Models\Unit::whereHas('type', fn ($q) => $q->where('name', $type))
                    ->where('status', 'sold')
                    ->whereYear('sold_at', $date->year)
                    ->whereMonth('sold_at', $date->month)
                    ->count();
            }
            $unit_type_series[] = ['name' => $type, 'data' => $data];
        }

        // 2️⃣ Salespersons Success Rate
        $employees = \App\Models\Employee::select('id', 'name')->get();
        $salespersons = [];
        $salespersons_success_values = [];

        foreach ($employees as $emp) {
            $salespersons[] = $emp->name;

            $total = \App\Models\Unit::where('employee_id', $emp->id)->count();

            $sold = \App\Models\Unit::where('employee_id', $emp->id)
                ->where('status', 'sold')
                ->count();

            $salespersons_success_values[] = $total > 0 ? round(($sold / $total) * 100, 2) : 0;
        }

        // 3️⃣ Top Cities
        $cities = \App\Models\Unit::select('city')->distinct()->pluck('city');
        $top_cities = [];
        $top_cities_values = [];
        foreach ($cities as $city) {
            $count = \App\Models\Unit::where('city', $city)->where('status', 'sold')->count();
            $top_cities[] = $city;
            $top_cities_values[] = $count;
        }

        // 4️⃣ Top Seller
        $maxIndex = array_search(max($salespersons_success_values), $salespersons_success_values);
        $top_seller = $employees[$maxIndex] ?? 'N/A';

        return view('units.reports.sales', [
            'months' => $months,
            'unit_type_series' => $unit_type_series,
            'salespersons' => $employees,
            'salespersons_success_values' => $salespersons_success_values,
            'top_cities' => $top_cities,
            'top_cities_values' => $top_cities_values,
            'top_seller' => $top_seller,
        ]);
    }

    public function getConstructionData(Request $request)
    {
        $query = Project::query();

        // فلاتر
        if ($request->filled('project_id')) {
            $query->where('id', $request->project_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $projects = $query->get();

        // Progress Chart
        $progress_labels = $projects->pluck('name');
        $progress_values = $projects->pluck('progress');

        // Budget vs Actual
        $budget_vs_actual_categories = $projects->pluck('name');
        $budget_vs_actual_series = [
            [
                'name' => 'Budget',
                'data' => $projects->pluck('budget')->map(fn ($v) => (float) $v),
            ],
            [
                'name' => 'Actual',
                'data' => $projects->pluck('actual_cost')->map(fn ($v) => (float) $v),
            ],
        ];

        // Workers
        $workers_series = $projects->pluck('workers')->map(fn ($v) => (int) $v);

        // Remaining Budget
        $remaining_budget_series = $projects->map(fn ($p) => (float) $p->budget - (float) $p->actual_cost
        );

        // Timeline (Gantt Chart)
        $timeline_data = $projects->map(fn ($p) => [
            'x' => $p->name,
            'y' => [
                strtotime($p->start_date) * 1000,
                strtotime($p->end_date) * 1000,
            ],
        ]);

        // Contractors Table
        $contractors = $projects->map(fn ($p) => [
            'name' => $p->contractor_name ?? '-',
            'project' => $p->name,
            'cost' => (float) ($p->contractor_cost ?? 0),
            'status' => $p->contractor_status ?? '-',
        ]);

        return response()->json(compact(
            'progress_labels',
            'progress_values',
            'budget_vs_actual_categories',
            'budget_vs_actual_series',
            'workers_series',
            'remaining_budget_series',
            'timeline_data',
            'contractors'
        ));
    }

    public function getFinanceData(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        // الشهور
        $months = collect(range(1, 12))
            ->map(fn ($m) => Carbon::create(null, $m, 1)->format('M'))
            ->toArray();

        // Revenue (dummy – استبدله ببيانات فعلية)
        $monthly_revenue_values = [12000, 15000, 13000, 17000, 16000, 14000, 18000, 19000, 20000, 21000, 22000, 23000];

        // Cash Flow (dummy)
        $cashflow_values = [10000, 13000, 12000, 16000, 15000, 13000, 17000, 18000, 19000, 20000, 21000, 22000];

        // Overdue Payments
        $overdue_customers = ['Ali', 'Sara', 'Omar'];
        $overdue_values = [2000, 1500, 3000];
        $overdue_details = [
            ['customer' => 'Ali',   'unit' => 'Unit 101', 'due_date' => '2025-10-01', 'days_late' => 15],
            ['customer' => 'Sara',  'unit' => 'Unit 203', 'due_date' => '2025-10-05', 'days_late' => 11],
            ['customer' => 'Omar',  'unit' => 'Unit 305', 'due_date' => '2025-09-28', 'days_late' => 18],
        ];

        // لو فيه فلاتر زمنية
        if ($start_date && $end_date) {
            // مثال: ممكن تعمل فلترة هنا لاحقًا
        }

        return response()->json(compact(
            'months',
            'monthly_revenue_values',
            'cashflow_values',
            'overdue_customers',
            'overdue_values',
            'overdue_details'
        ));
    }
}
