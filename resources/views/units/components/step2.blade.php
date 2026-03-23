<div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
    <div class="card-header bg-gradient bg-info text-white py-3 d-flex align-items-center">
        <i class="bi bi-ui-checks-grid fs-5 me-2 text-warning"></i>
        <h5 class="mb-0 fw-bold">Step 2: Unit Details</h5>
    </div>

    <div class="card-body bg-light">
        @if (empty($typeFields))
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <span class="fw-semibold">{{ __('messages.select_unit_type_first') }}</span>
            </div>
        @else
            <div class="row g-4">
                {{-- الحقول العادية --}}
                @foreach ($typeFields as $field)
                    @if (!($field['type'] === 'checkbox' && Str::startsWith($field['label'], 'بها')))
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-input-cursor-text text-info me-1"></i>
                                {{ $field['label'] ?? ucfirst($field['name']) }}
                            </label>

                            {{-- Input / Number --}}
                            @if (in_array($field['type'], ['text', 'number']))
                                <input type="{{ $field['type'] }}" id="{{ $field['name'] }}"
                                    wire:model.live.debounce.500ms="details.{{ $field['name'] }}"
                                    class="form-control form-control-lg
                                        @if ($errors->has('details.' . $field['name']) && empty($details[$field['name']])) is-invalid shake
                                        @elseif(!empty($details[$field['name']])) is-valid @endif"
                                    placeholder="{{ __('messages.enter_field', ['field' => $field['label'] ?? $field['name']]) }}">
                            @endif

                            {{-- Textarea --}}
                            @if ($field['type'] === 'textarea')
                                <textarea id="{{ $field['name'] }}" wire:model.live.debounce.500ms="details.{{ $field['name'] }}" rows="2"
                                    class="form-control form-control-lg
                                        @if ($errors->has('details.' . $field['name']) && empty($details[$field['name']])) is-invalid shake
                                        @elseif(!empty($details[$field['name']])) is-valid @endif"
                                    placeholder="{{ __('messages.enter_field', ['field' => $field['label'] ?? $field['name']]) }}"></textarea>
                            @endif

                            {{-- Select --}}
                            @if ($field['type'] === 'select')
                                <select id="{{ $field['name'] }}"
                                    wire:model.live.debounce.500ms="details.{{ $field['name'] }}"
                                    class="form-select form-select-lg
                                        @if ($errors->has('details.' . $field['name']) && empty($details[$field['name']])) is-invalid shake
                                        @elseif(!empty($details[$field['name']])) is-valid @endif">
                                    <option value="">
                                        {{ __('messages.select_field', ['field' => $field['label'] ?? $field['name']]) }}
                                    </option>
                                    @foreach ($field['options'] ?? [] as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            @endif

                            {{-- Error message --}}
                            @error('details.' . $field['name'])
                                <small class="invalid-feedback d-block mt-1 fw-semibold">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- الحقول اللي فيها "بها ..." في آخر النموذج --}}
            <div class="mt-4">
                <div class="card bg-white border-0 shadow-sm p-3">
                    <h6 class="fw-bold text-secondary mb-3">
                        <i class="bi bi-building-check text-info me-2"></i>
                        {{ __('messages.features') }}
                    </h6>
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        @foreach ($typeFields as $field)
                            @if ($field['type'] === 'checkbox' && Str::startsWith($field['label'], 'بها'))
                                <div class="form-check">
                                    <input type="checkbox" id="{{ $field['name'] }}"
                                        wire:model.live="details.{{ $field['name'] }}"
                                        class="form-check-input
                                            @if ($errors->has('details.' . $field['name'])) is-invalid shake
                                            @elseif(!empty($details[$field['name']])) is-valid @endif">
                                    <label class="form-check-label fw-semibold text-secondary"
                                        for="{{ $field['name'] }}">
                                        {{ $field['label'] }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Custom CSS --}}
<style>
    .form-control.is-valid,
    .form-select.is-valid,
    .form-check-input.is-valid {
        border-color: #28a745 !important;
        box-shadow: 0 0 6px rgba(40, 167, 69, 0.25);
        transition: all 0.3s ease-in-out;
    }

    .form-control.is-invalid,
    .form-select.is-invalid,
    .form-check-input.is-invalid {
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
</style>
