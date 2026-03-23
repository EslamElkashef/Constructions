<div class="container py-4">
    <div class="card p-4 shadow-sm">
        <h4 class="fw-bold text-primary mb-4">
            {{ $invoice ? 'Edit' : 'Create' }} Invoice
            @if ($unit)
                for Unit: {{ $unit->name }}
            @endif
        </h4>

        <form wire:submit.prevent="save" class="row g-3" x-data="{
            sellerPercent: @entangle('company_commission_seller_percent'),
            sellerValue: @entangle('company_commission_seller_value'),
            buyerPercent: @entangle('company_commission_buyer_percent'),
            buyerValue: @entangle('company_commission_buyer_value'),
            employeePercent: @entangle('employee_commission_percent'),
            employeeValue: @entangle('employee_commission_value')
        }">

            {{-- Buyer / Seller --}}
            <div class="col-md-6">
                <label class="form-label">Seller Name</label>
                <input type="text" wire:model.defer="seller_name" placeholder="Enter seller name" class="form-control">
                @error('seller_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Buyer Name</label>
                <input type="text" wire:model.defer="buyer_name" placeholder="Enter buyer name" class="form-control">
                @error('buyer_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Unit Price</label>
                <input type="number" wire:model.defer="unit_price" placeholder="Enter unit price" class="form-control">
                @error('unit_price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Price per Square Meter</label>
                <input type="number" wire:model.defer="meter_price" placeholder="Enter price per m²"
                    class="form-control">
            </div>

            {{-- Dates --}}
            <div class="col-md-6" wire:ignore>
                <label class="form-label">Offer Date</label>
                <input type="text" class="form-control flatpickr" placeholder="Select offer date"
                    data-field="offer_date" value="{{ $offer_date }}">
            </div>

            <div class="col-md-6" wire:ignore>
                <label class="form-label">Sale Date</label>
                <input type="text" class="form-control flatpickr" placeholder="Select sale date"
                    data-field="sale_date" value="{{ $sale_date }}">
            </div>

            <hr>

            {{-- Company Commission --}}
            <h5 class="fw-bold text-secondary mt-3">Company Commission 💼</h5>

            {{-- Seller Commission --}}
            <div class="mb-2 fw-bold">From Seller</div>
            <div class="col-md-6 d-flex gap-2">
                <input type="number" x-show="sellerValue === null || sellerValue === ''" x-model="sellerPercent"
                    placeholder="% commission" class="form-control flex-fill" step="0.01">
                <input type="number" x-show="sellerPercent === null || sellerPercent === ''" x-model="sellerValue"
                    placeholder="Fixed amount" class="form-control flex-fill" step="0.01">
            </div>

            {{-- Buyer Commission --}}
            <div class="mb-2 fw-bold mt-3">From Buyer</div>
            <div class="col-md-6 d-flex gap-2">
                <input type="number" x-show="buyerValue === null || buyerValue === ''" x-model="buyerPercent"
                    placeholder="% commission" class="form-control flex-fill" step="0.01">
                <input type="number" x-show="buyerPercent === null || buyerPercent === ''" x-model="buyerValue"
                    placeholder="Fixed amount" class="form-control flex-fill" step="0.01">
            </div>

            <hr>

            {{-- Employee Commission --}}
            <h5 class="fw-bold text-secondary mt-3">Employee Commission 👤</h5>
            <div class="mb-2 fw-bold">Employee</div>
            <div class="col-md-6 d-flex gap-2">
                <input type="number" x-show="employeeValue === null || employeeValue === ''" x-model="employeePercent"
                    placeholder="% commission" class="form-control flex-fill" step="0.01">
                <input type="number" x-show="employeePercent === null || employeePercent === ''"
                    x-model="employeeValue" placeholder="Fixed amount" class="form-control flex-fill" step="0.01">
            </div>

            <div class="col-12 mt-2">
                <label class="form-label">Commission Settlement</label>
                <select wire:model.defer="commission_settlement" class="form-select">
                    <option value="now">Now</option>
                    <option value="after_target">After Reaching Target</option>
                </select>
            </div>

            <div class="col-12 text-end mt-4">
                <button class="btn btn-success px-4 py-2">Save Invoice</button>
            </div>

        </form>
    </div>
</div>

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function initFlatpickrs() {
            if (typeof flatpickr === 'undefined') {
                console.warn('flatpickr not loaded yet.');
                return;
            }

            document.querySelectorAll('.flatpickr').forEach(function(el) {
                try {
                    if (el._flatpickr) {
                        el._flatpickr.destroy();
                    }

                    const field = el.dataset.field;
                    const initialValue = el.value; // احصل على القيمة الأولية من value attribute

                    flatpickr(el, {
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                        disableMobile: true,
                        maxDate: "today",
                        allowInput: true,
                        defaultDate: initialValue || null, // ضع القيمة الأولية
                        onChange: function(selectedDates, dateStr) {
                            if (!field) return;

                            try {
                                @this.set(field, dateStr);
                            } catch (e) {
                                console.warn('Could not set Livewire field', field);
                            }
                        }
                    });
                } catch (err) {
                    console.error('flatpickr init error for element', el, err);
                }
            });
        }

        document.addEventListener('livewire:load', function() {
            initFlatpickrs();

            if (window.Livewire && Livewire.hook) {
                Livewire.hook('message.processed', () => {
                    setTimeout(initFlatpickrs, 100);
                });
            }
        });

        window.addEventListener('load', initFlatpickrs);
    </script>
@endsection
