<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%")
                    ->orWhereHas('type', function ($t) use ($request) {
                        $t->where('name', 'like', "%{$request->search}%")
                            ->orWhere('name_ar', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('unit_type_id', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('deleted')) {
            $query->onlyTrashed();
        }

        $units = $query->orderByDesc('is_favorite')
            ->latest()
            ->paginate(12);

        $unitTypes = UnitType::all();

        return view('units.index', compact('units', 'unitTypes'));
    }

    public function show(Unit $unit)
    {
        $unit->load(['details', 'media', 'type', 'employee']);

        return view('units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = Unit::with(['type', 'details', 'media'])->findOrFail($id);
        $unitTypes = UnitType::all();

        return view('units.edit', compact('unit', 'unitTypes'));
    }

    public function restore($id)
    {
        $unit = Unit::withTrashed()->findOrFail($id);
        $unit->restore();

        return redirect()->route('units.index')->with('message', 'Unit restored successfully.');
    }

    public function forceDelete($id)
    {
        $unit = Unit::withTrashed()->findOrFail($id);
        $unit->forceDelete();

        return redirect()->route('units.index')->with('message', 'Unit permanently deleted.');
    }

    public function toggleFavorite($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $unit->is_favorite = ! $unit->is_favorite;
        $unit->save();

        return response()->json(['status' => 'success', 'is_favorite' => $unit->is_favorite]);
    }
}
