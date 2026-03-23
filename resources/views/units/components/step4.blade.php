<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-header bg-gradient bg-primary text-white py-4 d-flex align-items-center">
        <i class="bi bi-check2-circle fs-3 me-2 text-warning"></i>
        <h4 class="mb-0 fw-bold">{{ __('Step 4: Review & Confirmation') }}</h4>
    </div>

    <div class="card-body bg-light p-4">
        {{-- Review alert --}}
        <div class="alert alert-info border-0 shadow-sm mb-4 rounded-3">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>{{ __('Please review the following details before saving.') }}</strong>
        </div>

        {{-- Basic Information --}}
        <div class="mb-5">
            <h5 class="fw-bold text-primary mb-3">
                <i class="bi bi-person-vcard me-2"></i> {{ __('Basic Information') }}
            </h5>

            <div class="row g-3">
                {{-- Unit Name --}}
                <div class="col-md-6">
                    <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
                        <div class="small text-muted">{{ __('Unit Name') }}</div>
                        <div class="fw-semibold fs-6">{{ $name ?? '-' }}</div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="col-md-6">
                    <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
                        <div class="small text-muted">{{ __('Address') }}</div>
                        <div class="fw-semibold fs-6">{{ $address ?? '-' }}</div>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="col-md-6">
                    <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
                        <div class="small text-muted">{{ __('Phone Number') }}</div>
                        <div class="fw-semibold fs-6">{{ $phone ?? '-' }}</div>
                    </div>
                </div>

                {{-- Unit Type --}}
                <div class="col-md-6">
                    <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
                        <div class="small text-muted">{{ __('Unit Type') }}</div>
                        <div class="fw-semibold fs-6">
                            {{ \App\Models\UnitType::find($unit_type_id)?->name ?? '-' }}
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-md-6">
                    <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
                        <div class="small text-muted">{{ __('Status') }}</div>
                        <div class="fw-semibold fs-6">
                            @switch($status ?? 'available')
                                @case('available')
                                    <span class="badge bg-success">{{ __('Available') }}</span>
                                @break

                                @case('reserved')
                                    <span class="badge bg-warning text-dark">{{ __('Reserved') }}</span>
                                @break

                                @case('sold')
                                    <span class="badge bg-danger">{{ __('Sold') }}</span>
                                @break

                                @default
                                    <span class="badge bg-secondary">{{ __('Unknown') }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Unit Details --}}
        <div class="mb-5">
            <h5 class="fw-bold text-success mb-3">
                <i class="bi bi-building-gear me-2"></i> {{ __('Unit Details') }}
            </h5>

            @php
                $orderedFields = [
                    'total_area' => __('Total Area'),
                    'garden_area' => __('Garden Area'),
                    'price_per_meter' => __('Price per Meter'),
                    'total_price' => __('Total Price'),
                    'floors' => __('Floors'),
                    'rooms' => __('Rooms'),
                    'bathrooms' => __('Bathrooms'),
                    'kitchens' => __('Kitchens'),
                    'license_status' => __('License Status'),
                    'finishing' => __('Finishing'),
                    'has_pool' => __('Has Swimming Pool'),
                    'has_garage' => __('Has Garage'),
                    'has_guard_room' => __('Has Guard Room'),
                    'has_roof' => __('Has Roof'),
                ];
            @endphp

            <div class="bg-white rounded-3 p-4 shadow-sm border-start border-4 border-success">
                <div class="row g-3">
                    @foreach ($orderedFields as $key => $label)
                        @if (isset($details[$key]))
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light-subtle d-flex align-items-center shadow-sm">
                                    <i class="bi bi-dot fs-4 text-success me-2"></i>
                                    <div>
                                        <div class="small text-muted">{{ $label }}</div>
                                        <div class="fw-semibold">
                                            {{ is_bool($details[$key]) ? ($details[$key] ? __('Yes') : __('No')) : $details[$key] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Media --}}
        <div class="mb-4">
            <h5 class="fw-bold text-danger mb-3">
                <i class="bi bi-camera-reels me-2"></i> {{ __('Uploaded Media') }}
            </h5>

            @if (!empty($images))
                <div class="mb-4">
                    <div class="fw-semibold mb-2 text-secondary">
                        <i class="bi bi-image me-1"></i> {{ __('Images') }}
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($images as $img)
                            <div class="rounded overflow-hidden shadow-sm border position-relative"
                                style="width: 140px;">
                                <img src="{{ $img->temporaryUrl() }}" class="img-fluid rounded" alt="Image Preview">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($videos))
                <div>
                    <div class="fw-semibold mb-2 text-secondary">
                        <i class="bi bi-play-btn me-1"></i> {{ __('Videos') }}
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($videos as $v)
                            <div class="rounded overflow-hidden shadow-sm border" style="width: 240px;">
                                <video width="240" controls class="rounded">
                                    <source src="{{ $v->temporaryUrl() }}" type="{{ $v->getMimeType() }}">
                                </video>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Navigation Buttons --}}
        <div class="d-flex justify-content-between align-items-center mt-5">
            <button class="btn btn-outline-secondary px-4 py-2 fw-semibold rounded-3 shadow-sm"
                wire:click="previousStep">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
            </button>
            <button class="btn btn-success px-4 py-2 fw-semibold rounded-3 shadow-sm" wire:click="submit">
                <i class="bi bi-check2-circle me-1"></i> {{ __('Confirm & Save') }}
            </button>
        </div>
    </div>
</div>
