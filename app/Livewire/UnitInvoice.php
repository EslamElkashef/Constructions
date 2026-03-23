<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\Unit;
use Livewire\Component;

class UnitInvoice extends Component
{
    public Unit $unit;

    public $invoice;

    public $buyer_name;

    public $seller_name;

    public $unit_price;

    public $meter_price;

    public $offer_date;

    public $sale_date;

    public $company_commission_seller_percent = null;

    public $company_commission_seller_value = null;

    public $company_commission_buyer_percent = null;

    public $company_commission_buyer_value = null;

    public $employee_id;

    public $employee_commission_value = null;

    public $employee_commission_percent = null;

    public $commission_settlement = 'after_target';

    public function mount(int $unitId, $invoiceId = null)
    {
        $this->unit = Unit::findOrFail($unitId);
        $this->employee_id = auth()->id();

        if ($invoiceId) {
            $this->invoice = Invoice::findOrFail($invoiceId);

            $this->buyer_name = $this->invoice->buyer_name;
            $this->seller_name = $this->invoice->seller_name;
            $this->unit_price = $this->invoice->unit_price;
            $this->meter_price = $this->invoice->meter_price;
            $this->offer_date = $this->invoice->offer_date;
            $this->sale_date = $this->invoice->sale_date;

            // ===== Seller Commission =====
            if ($this->invoice->company_commission_from_seller && $this->unit_price > 0) {
                $percent = round(($this->invoice->company_commission_from_seller / $this->unit_price) * 100, 2);
                $this->company_commission_seller_percent = $percent;
                $this->company_commission_seller_value = $this->invoice->company_commission_from_seller != ($this->unit_price * $percent / 100) ? $this->invoice->company_commission_from_seller : null;
            }

            // ===== Buyer Commission =====
            if ($this->invoice->company_commission_from_buyer && $this->unit_price > 0) {
                $percent = round(($this->invoice->company_commission_from_buyer / $this->unit_price) * 100, 2);
                $this->company_commission_buyer_percent = $percent;
                $this->company_commission_buyer_value = $this->invoice->company_commission_from_buyer != ($this->unit_price * $percent / 100) ? $this->invoice->company_commission_from_buyer : null;
            }

            // ===== Employee Commission =====
            if ($this->invoice->employee_commission_value && $this->unit_price > 0) {
                $percent = round(($this->invoice->employee_commission_value / $this->unit_price) * 100, 2);
                $this->employee_commission_percent = $percent;
                $this->employee_commission_value = $this->invoice->employee_commission_value != ($this->unit_price * $percent / 100) ? $this->invoice->employee_commission_value : null;
            } else {
                $this->employee_commission_percent = $this->invoice->employee_commission_percent;
                $this->employee_commission_value = null;
            }

            $this->commission_settlement = $this->invoice->commission_settlement;
        }
    }

    private function validateData()
    {
        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'seller_name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'meter_price' => 'nullable|numeric|min:0',
            'offer_date' => 'nullable|date',
            'sale_date' => 'nullable|date',
            'company_commission_seller_value' => 'nullable|numeric|min:0',
            'company_commission_buyer_value' => 'nullable|numeric|min:0',
            'employee_commission_value' => 'nullable|numeric|min:0',
            'employee_commission_percent' => 'nullable|numeric|min:0|max:100',
            'commission_settlement' => 'required|in:now,after_target',
        ]);
    }

    public function save()
    {
        $this->validateData();

        $company_seller_commission = $this->company_commission_seller_value ??
            ($this->unit_price * ($this->company_commission_seller_percent ?? 0) / 100);

        $company_buyer_commission = $this->company_commission_buyer_value ??
            ($this->unit_price * ($this->company_commission_buyer_percent ?? 0) / 100);

        $employee_commission = $this->employee_commission_value ??
            ($this->unit_price * ($this->employee_commission_percent ?? 0) / 100);

        if ($this->invoice) {
            $this->invoice->update([
                'buyer_name' => $this->buyer_name,
                'seller_name' => $this->seller_name,
                'unit_price' => $this->unit_price,
                'meter_price' => $this->meter_price,
                'offer_date' => $this->offer_date,
                'sale_date' => $this->sale_date,
                'company_commission_from_seller' => $company_seller_commission,
                'company_commission_from_buyer' => $company_buyer_commission,
                'employee_commission_value' => $employee_commission,
                'employee_commission_percent' => $this->employee_commission_percent,
                'commission_settlement' => $this->commission_settlement,
            ]);

            session()->flash('success', 'Invoice updated successfully ✅');
        } else {
            Invoice::create([
                'unit_id' => $this->unit->id,
                'employee_id' => $this->employee_id,
                'buyer_name' => $this->buyer_name,
                'seller_name' => $this->seller_name,
                'unit_price' => $this->unit_price,
                'meter_price' => $this->meter_price,
                'offer_date' => $this->offer_date,
                'sale_date' => $this->sale_date,
                'company_commission_from_seller' => $company_seller_commission,
                'company_commission_from_buyer' => $company_buyer_commission,
                'employee_commission_value' => $employee_commission,
                'employee_commission_percent' => $this->employee_commission_percent,
                'commission_settlement' => $this->commission_settlement,
            ]);

            session()->flash('success', 'Invoice created successfully ✅');
        }

        return redirect()->route('invoices.index', ['unitId' => $this->unit->id]);
    }

    public function updated($field)
    {
        $pairs = [
            'company_commission_seller_percent' => 'company_commission_seller_value',
            'company_commission_seller_value' => 'company_commission_seller_percent',
            'company_commission_buyer_percent' => 'company_commission_buyer_value',
            'company_commission_buyer_value' => 'company_commission_buyer_percent',
            'employee_commission_percent' => 'employee_commission_value',
            'employee_commission_value' => 'employee_commission_percent',
        ];

        if (isset($pairs[$field]) && $this->{$field}) {
            $this->{$pairs[$field]} = null;
        }
    }

    public function render()
    {
        return view('livewire.unit-invoice')->layout('layouts.master');
    }
}
