@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold text-primary mb-4">
            Create Invoice {{ $unit ? 'for Unit: ' . $unit->name : '' }}
        </h4>

        {{-- استدعاء الـ Livewire Component --}}
        @livewire('unit-invoice', ['unitId' => $unit->id])
    </div>
@endsection
