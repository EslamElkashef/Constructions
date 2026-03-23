<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Unit;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Index فواتير وحدة معينة
    public function index($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $invoices = $unit->invoices()->with('employee')->latest()->paginate(15);

        return view('units.invoices.index', compact('unit', 'invoices'));
    }

    // صفحة إنشاء فاتورة جديدة
    public function create($unitId)
    {
        $unit = Unit::findOrFail($unitId);

        return view('units.invoices.create', compact('unit'));
    }

    // حفظ فاتورة جديدة
    public function store(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'seller_name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'meter_price' => 'nullable|numeric|min:0',
            'offer_date' => 'nullable|date',
            'sale_date' => 'nullable|date',
        ]);

        Invoice::create([
            'unit_id' => $unit->id,
            'employee_id' => auth()->id(),
            'buyer_name' => $request->buyer_name,
            'seller_name' => $request->seller_name,
            'unit_price' => $request->unit_price,
            'meter_price' => $request->meter_price,
            'offer_date' => $request->offer_date,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('invoices.index', ['unitId' => $unit->id])
            ->with('success', 'Invoice created successfully!');
    }

    // صفحة تعديل فاتورة
    public function edit($unitId, $invoiceId)
    {
        $unit = Unit::findOrFail($unitId);
        $invoice = Invoice::findOrFail($invoiceId);

        return view('units.invoices.edit', compact('unit', 'invoice'));
    }

    // تحديث فاتورة موجودة
    public function update(Request $request, $unitId, $invoiceId)
    {
        $unit = Unit::findOrFail($unitId);
        $invoice = Invoice::findOrFail($invoiceId);

        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'seller_name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'meter_price' => 'nullable|numeric|min:0',
            'offer_date' => 'nullable|date',
            'sale_date' => 'nullable|date',
        ]);

        $invoice->update($request->only([
            'buyer_name', 'seller_name', 'unit_price', 'meter_price', 'offer_date', 'sale_date',
        ]));

        return redirect()->route('invoices.index', ['unitId' => $unit->id])
            ->with('success', 'Invoice updated successfully!');
    }

    // حذف فاتورة
    public function destroy($unitId, $invoiceId)
    {
        $invoice = Invoice::where('unit_id', $unitId)->findOrFail($invoiceId);
        $invoice->delete();

        return response()->json(['success' => true]);
    }
}
