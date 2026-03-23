<div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-gradient bg-primary text-white py-3 d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-2 fs-5 text-warning"></i>
        <h5 class="mb-0 fw-bold">Step 1: Basic Information</h5>
    </div>

    <div class="card-body bg-light">
        <div class="row g-4">

            {{-- Unit Name --}}
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-tag-fill text-primary me-1"></i> Unit Name
                </label>
                <input type="text" id="name" wire:model.live.debounce.500ms="name"
                    class="form-control form-control-lg
                        @if ($errors->has('name') && empty($name)) is-invalid shake
                        @elseif(strlen($name ?? '') > 0) is-valid @endif"
                    placeholder="Enter unit name">
                @if ($errors->has('name') && empty($name))
                    <small class="invalid-feedback d-block mt-1">{{ $errors->first('name') }}</small>
                @endif
            </div>

            {{-- Address --}}
            <div class="col-md-6">
                <label for="address" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-geo-alt-fill text-primary me-1"></i> Address
                </label>
                <input type="text" id="address" wire:model.live.debounce.500ms="address"
                    class="form-control form-control-lg
                        @if ($errors->has('address') && empty($address)) is-invalid shake
                        @elseif(strlen($address ?? '') > 0) is-valid @endif"
                    placeholder="Enter address">
                @if ($errors->has('address') && empty($address))
                    <small class="invalid-feedback d-block mt-1">{{ $errors->first('address') }}</small>
                @endif
            </div>

            {{-- Phone --}}
            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-telephone-fill text-primary me-1"></i> Phone
                </label>
                <input type="text" id="phone" wire:model.live.debounce.500ms="phone"
                    class="form-control form-control-lg
                        @if ($errors->has('phone') && empty($phone)) is-invalid shake
                        @elseif(strlen($phone ?? '') > 0) is-valid @endif"
                    placeholder="Enter phone number">
                @if ($errors->has('phone') && empty($phone))
                    <small class="invalid-feedback d-block mt-1">{{ $errors->first('phone') }}</small>
                @endif
            </div>

            {{-- Unit Type --}}
            <div class="col-md-6">
                <label for="unit_type_id" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-house-fill text-primary me-1"></i> Unit Type
                </label>
                <select id="unit_type_id" wire:model.live.debounce.500ms="unit_type_id"
                    class="form-select form-select-lg
                        @if ($errors->has('unit_type_id') && empty($unit_type_id)) is-invalid shake
                        @elseif(!empty($unit_type_id)) is-valid @endif">
                    <option value="">Select Type</option>
                    @foreach ($unitTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('unit_type_id') && empty($unit_type_id))
                    <small class="invalid-feedback d-block mt-1">{{ $errors->first('unit_type_id') }}</small>
                @endif
            </div>

            {{-- Employee Name --}}
            <div class="col-md-6">
                <label for="employee" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-fill text-primary me-1"></i> Employee
                </label>
                <input type="text" id="employee" wire:model="employee_name" class="form-control form-control-lg"
                    readonly>
            </div>

            {{-- City --}}
            <div class="col-md-6">
                <label for="city" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-geo-alt text-primary me-1"></i> City
                </label>
                <select id="city" wire:model.live.debounce.500ms="city"
                    class="form-select form-select-lg
                @if ($errors->has('city') && empty($city)) is-invalid shake
                @elseif(!empty($city)) is-valid @endif">
                    <option value="">Select City</option>
                    <option value="bdr">BDR</option>
                    <option value="zagazig">Zagazig</option>
                    <option value="Fifth Settlement">Fifth Settlement</option>
                    <option value="shrooq">Shrooq</option>
                    <option value="6 october">6 October</option>
                    <option value="shaikh zayed">Shaikh Zayed</option>
                    <option value="maadi">Maadi</option>
                    <option value="First Settlement">First Settlement</option>
                </select>
                @if ($errors->has('city') && empty($city))
                    <small class="invalid-feedback d-block mt-1">{{ $errors->first('city') }}</small>
                @endif
            </div>
            {{-- Status --}}
            <div class="col-md-6">
                <label for="status" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-activity text-primary me-1"></i> Status
                </label>
                <select id="status" wire:model="status"
                    class="form-select form-select-lg w-100
                    @if ($errors->has('status') && empty($status)) is-invalid shake
                    @elseif(!empty($status)) is-valid @endif">
                    <option value="">Select Status</option>
                    <option value="available">🟢 Available</option>
                    <option value="reserved">🟠 Reserved</option>
                    <option value="sold">🔴 Sold</option>
                </select>
                @if ($errors->has('status') && empty($status))
                    <small class="invalid-feedback d-block mt-1">{{ $errors->first('status') }}</small>
                @endif
            </div>

            {{-- Sold At (يظهر فقط لو Status = sold) --}}
            @if ($status === 'sold')
                <div class="col-md-6">
                    <label for="sold_at" class="form-label fw-semibold text-secondary">
                        <i class="bi bi-calendar-check text-primary me-1"></i> Sold At
                    </label>
                    <input type="date" id="sold_at" wire:model.live.debounce.500ms="sold_at"
                        class="form-control form-control-lg
                            @if ($errors->has('sold_at') && empty($sold_at)) is-invalid shake
                            @elseif(!empty($sold_at)) is-valid @endif">
                    @if ($errors->has('sold_at') && empty($sold_at))
                        <small class="invalid-feedback d-block mt-1">{{ $errors->first('sold_at') }}</small>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Custom CSS (نفس الموجود سابقاً) --}}
<style>
    .form-control-lg,
    .form-select-lg {
        height: 3.5rem !important;
        padding: 0.75rem 1rem !important;
        font-size: 1rem !important;
        border-radius: 0.6rem !important;
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: #28a745 !important;
        box-shadow: 0 0 6px rgba(40, 167, 69, 0.25);
        transition: all 0.3s ease-in-out;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 6px rgba(220, 53, 69, 0.25);
        transition: all 0.3s ease-in-out;
    }

    .shake {
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-4px);
        }

        50% {
            transform: translateX(4px);
        }

        75% {
            transform: translateX(-4px);
        }
    }

    .invalid-feedback {
        font-weight: 500;
        font-size: 0.85rem;
        color: #dc3545;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-3px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #status {
        height: 50px;
        font-size: 1.1rem;
        padding-left: 0.75rem;
        text-align: left;
        display: inline-block;
        width: 100%;
    }
</style>
