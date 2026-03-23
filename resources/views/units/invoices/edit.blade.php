@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold text-primary mb-4">
            Edit Invoice for Unit: {{ $unit->name }}
        </h4>

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- استدعاء Livewire Component للتعديل --}}
        @livewire('unit-invoice', ['unitId' => $unit->id, 'invoiceId' => $invoice->id])
    </div>
@endsection
