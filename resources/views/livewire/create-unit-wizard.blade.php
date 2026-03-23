<div class="container py-4">
    <h3 class="fw-bold mb-4 text-primary">
        {{ $mode === 'edit' ? 'Edit Unit' : 'Add New Unit' }}
    </h3>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    {{-- Steps header --}}
    <div class="mb-4 d-flex align-items-center gap-3">
        <div class="badge {{ $currentStep >= 1 ? 'bg-primary' : 'bg-light text-dark' }}">1</div>
        <span class="{{ $currentStep >= 1 ? 'text-primary fw-semibold' : 'text-muted' }}">Basic Info</span>

        <div class="badge {{ $currentStep >= 2 ? 'bg-primary' : 'bg-light text-dark' }}">2</div>
        <span class="{{ $currentStep >= 2 ? 'text-primary fw-semibold' : 'text-muted' }}">Details</span>

        <div class="badge {{ $currentStep >= 3 ? 'bg-primary' : 'bg-light text-dark' }}">3</div>
        <span class="{{ $currentStep >= 3 ? 'text-primary fw-semibold' : 'text-muted' }}">Media</span>

        <div class="badge {{ $currentStep >= 4 ? 'bg-primary' : 'bg-light text-dark' }}">4</div>
        <span class="{{ $currentStep >= 4 ? 'text-primary fw-semibold' : 'text-muted' }}">Confirmation</span>
    </div>

    {{-- Step content --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            @if ($currentStep == 1)
                @include('units.components.step1', ['unitTypes' => $unitTypes])
            @elseif ($currentStep == 2)
                @include('units.components.step2', ['unitTypes' => $unitTypes])
            @elseif ($currentStep == 3)
                @include('units.components.step3', ['unitTypes' => $unitTypes])
            @elseif ($currentStep == 4)
                @include('units.components.step4', ['unitTypes' => $unitTypes])
            @endif
        </div>
    </div>

    {{-- Navigation --}}
    <div class="d-flex justify-content-between mt-3">
        @if ($currentStep > 1)
            <button class="btn btn-outline-secondary" wire:click="previousStep">
                <i class="bi bi-arrow-left"></i> Previous
            </button>
        @else
            <div></div>
        @endif

        @if ($currentStep < 4)
            <button class="btn btn-primary" wire:click="nextStep">
                Next <i class="bi bi-arrow-right"></i>
            </button>
        @endif
    </div>
</div>
